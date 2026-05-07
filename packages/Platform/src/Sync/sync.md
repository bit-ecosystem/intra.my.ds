```bash

packages/faros/api-pipeline/

```



# =========================================



# composer.json



# =========================================



```json

{
 "name": "faros/api-pipeline",
 "description": "Config-driven API ingestion & nested mapping pipeline",
 "type": "library",
 "autoload": {
   "psr-4": {
     "Faros\\ApiPipeline\\": "src/"
   }
 },
 "extra": {
   "laravel": {
     "providers": [
       "Faros\\ApiPipeline\\ApiPipelineServiceProvider"
     ]
   }
 }

}

```



# =========================================



# Service Provider



# =========================================



```php

<?php



namespace Faros\ApiPipeline;



use Illuminate\Support\ServiceProvider;

use Faros\ApiPipeline\Models\ApiData;

use Faros\ApiPipeline\Observers\ApiDataObserver;



class ApiPipelineServiceProvider extends ServiceProvider

{
   public function register()
   {
       $this->mergeConfigFrom(__DIR__.'/config/api-pipeline.php', 'api-pipeline');
   }


   public function boot()
   {
       $this->publishes([
           __DIR__.'/config/api-pipeline.php' => config_path('api-pipeline.php'),
       ], 'config');


       $this->publishes([
           __DIR__.'/../database/migrations/' => database_path('migrations'),
       ], 'migrations');


       ApiData::observe(ApiDataObserver::class);
   }

}

```



# =========================================



# config/api-pipeline.php



# =========================================



```php

<?php



return [
   'queue' => true,

];

```



# =========================================



# MIGRATIONS



# =========================================



## create_api_configs_table.php



```php

Schema::create('api_configs', function (Blueprint $table) {
   $table->id();
   $table->string('name');


   $table->string('source_type'); // rest | database
   $table->json('source_config');


   $table->string('data_path')->nullable();
   $table->json('mapping');


   $table->boolean('active')->default(true);
   $table->timestamps();

});

```



## create_api_data_table.php



```php

Schema::create('api_data', function (Blueprint $table) {
   $table->id();


   $table->foreignId('api_config_id')->constrained();


   $table->string('fingerprint')->nullable()->index();
   $table->json('payload');


   $table->string('status')->default('pending');
   $table->timestamp('processed_at')->nullable();
   $table->text('error')->nullable();


   $table->timestamps();

});

```



# =========================================



# MODELS



# =========================================



## ApiConfig.php



```php

<?php



namespace Faros\ApiPipeline\Models;



use Illuminate\Database\Eloquent\Model;



class ApiConfig extends Model

{
   protected $fillable = [
       'name','source_type','source_config',
       'data_path','mapping','active'
   ];


   protected $casts = [
       'source_config' => 'array',
       'mapping' => 'array',
       'active' => 'boolean'
   ];


   public function data()
   {
       return $this->hasMany(ApiData::class);
   }

}

```



## ApiData.php



```php

<?php



namespace Faros\ApiPipeline\Models;



use Illuminate\Database\Eloquent\Model;



class ApiData extends Model

{
   protected $fillable = [
       'api_config_id','payload','fingerprint',
       'status','processed_at','error'
   ];


   protected $casts = [
       'payload' => 'array',
       'processed_at' => 'datetime'
   ];


   public function config()
   {
       return $this->belongsTo(ApiConfig::class);
   }


   public function markProcessed()
   {
       $this->update([
           'status' => 'processed',
           'processed_at' => now(),
           'error' => null
       ]);
   }


   public function markFailed($e)
   {
       $this->update([
           'status' => 'failed',
           'error' => $e
       ]);
   }

}

```



# =========================================



# SUPPORT



# =========================================



## Fingerprint.php



```php

class Fingerprint {
   public static function make(array $payload): string {
       return sha1(json_encode($payload));
   }

}

```



# =========================================



# FETCHERS



# =========================================



## Fetcher.php



```php

interface Fetcher {
   public function fetch(array $config): array;

}

```



## RestFetcher.php



```php

use Illuminate\Support\Facades\Http;



class RestFetcher implements Fetcher {
   public function fetch(array $config): array {
       return Http::withHeaders($config['headers'] ?? [])
           ->get($config['url'], $config['query'] ?? [])
           ->json();
   }

}

```



## DatabaseFetcher.php



```php

use Illuminate\Support\Facades\DB;



class DatabaseFetcher implements Fetcher {
   public function fetch(array $config): array {
       return DB::connection($config['connection'])
           ->select($config['query'], $config['bindings'] ?? []);
   }

}

```



# =========================================



# INGESTION



# =========================================



## IngestionService.php



```php

use Faros\ApiPipeline\Models\ApiConfig;

use Faros\ApiPipeline\Models\ApiData;



class IngestionService

{
   public function ingest(ApiConfig $config)
   {
       $fetcher = match ($config->source_type) {
           'rest' => new RestFetcher(),
           'database' => new DatabaseFetcher(),
       };


       $data = $fetcher->fetch($config->source_config);


       $items = data_get($data, $config->data_path ?? 'data', $data);


       foreach ($items as $item) {
           $fp = Fingerprint::make((array)$item);


           ApiData::firstOrCreate(
               [
                   'api_config_id' => $config->id,
                   'fingerprint' => $fp
               ],
               [
                   'payload' => (array)$item
               ]
           );
       }
   }

}

```



# =========================================



# OBSERVER + JOB



# =========================================



## ApiDataObserver.php



```php

class ApiDataObserver

{
   public function created(ApiData $data)
   {
       ProcessApiDataJob::dispatch($data);
   }

}

```



## ProcessApiDataJob.php



```php

use Illuminate\Contracts\Queue\ShouldQueue;



class ProcessApiDataJob implements ShouldQueue

{
   public function __construct(public ApiData $data) {}


   public function handle()
   {
       app(ProcessingEngine::class)->process($this->data);
   }

}

```



# =========================================



# PROCESSING



# =========================================



## ProcessingEngine.php



```php

class ProcessingEngine

{
   public function process(ApiData $data)
   {
       try {
           app(MappingEngine::class)->run($data);
           $data->markProcessed();
       } catch (\Throwable $e) {
           $data->markFailed($e->getMessage());
           throw $e;
       }
   }

}

```



---



## MappingEngine.php (🔥 NESTED SUPPORT)



```php

class MappingEngine

{
   public function run(ApiData $data)
   {
       $payload = $data->payload;
       $mapping = $data->config->mapping;


       foreach ($mapping as $entity) {
           $this->processEntity($entity, $payload, null);
       }
   }


   protected function processEntity(array $entity, array $payload, $parentModel = null)
   {
       $items = data_get($payload, $entity['path']);


       if (!$items) return;


       if (!($entity['many'] ?? false)) {
           $items = [$items];
       }


       foreach ($items as $item) {


           $row = [];


           foreach ($entity['fields'] as $field) {
               $value = data_get($item, $field['from']);


               if (isset($field['regex'])) {
                   $value = preg_replace($field['regex'], '', $value);
               }


               $row[$field['to']] = $value;
           }


           if ($parentModel && isset($entity['foreign_key'])) {
               $row[$entity['foreign_key']] = $parentModel->id;
           }


           $model = app(ModelSyncService::class)
               ->syncAndReturn($entity['table'], $entity['unique_by'] ?? null, $row);


           if (!empty($entity['children'])) {
               foreach ($entity['children'] as $child) {
                   $this->processEntity($child, $item, $model);
               }
           }
       }
   }

}

```



---



## ModelSyncService.php



```php

use Illuminate\Support\Facades\DB;



class ModelSyncService

{
   public function syncAndReturn($table, $uniqueBy, $data)
   {
       if ($uniqueBy && isset($data[$uniqueBy])) {
           DB::table($table)->updateOrInsert(
               [$uniqueBy => $data[$uniqueBy]],
               $data
           );


           return DB::table($table)
               ->where($uniqueBy, $data[$uniqueBy])
               ->first();
       }


       $id = DB::table($table)->insertGetId($data);
       return DB::table($table)->find($id);
   }

}

```



# =========================================



# ARTISAN COMMAND



# =========================================



```php

use Illuminate\Console\Command;

use Faros\ApiPipeline\Models\ApiConfig;



class ApiIngestCommand extends Command

{
   protected $signature = 'api:ingest {config_id?}';


   public function handle()
   {
       $configs = $this->argument('config_id')
           ? ApiConfig::whereId($this->argument('config_id'))->get()
           : ApiConfig::where('active', true)->get();


       foreach ($configs as $config) {
           app(IngestionService::class)->ingest($config);
       }


       $this->info('Done');
   }

}

```



# =========================================



# SAMPLE CONFIG JSON (REST)



# =========================================



```json

{
 "source_type": "rest",
 "source_config": {
   "url": "https://api.example.com/orders"
 },
 "data_path": "data",
 "mapping": [
   {
     "table": "orders",
     "path": "",
     "many": false,
     "unique_by": "external_id",
     "fields": [
       { "from": "id", "to": "external_id" },
       { "from": "customer_name", "to": "customer_name" }
     ],
     "children": [
       {
         "table": "order_items",
         "path": "items",
         "many": true,
         "foreign_key": "order_id",
         "fields": [
           { "from": "sku", "to": "sku" },
           { "from": "qty", "to": "quantity" }
         ]
       }
     ]
   }
 ]

}

```



# =========================================



# SAMPLE PAYLOAD



# =========================================



```json

{
 "data": [
   {
     "id": "ORD-1",
     "customer_name": "Ali",
     "items": [
       { "sku": "A1", "qty": 2 },
       { "sku": "B1", "qty": 1 }
     ]
   }
 ]

}

```



# =========================================



# INSTALL



# =========================================



```bash

composer require faros/api-pipeline



php artisan vendor:publish --tag=migrations

php artisan migrate



php artisan queue:work

php artisan api:ingest

```



```

```