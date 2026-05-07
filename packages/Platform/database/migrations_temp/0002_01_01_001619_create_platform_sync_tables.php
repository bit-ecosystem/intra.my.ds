<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PLATFORM / Sync
 * Intent:
 * Define and track synchronization with external systems.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_connections', function (Blueprint $table): void {
            $table->id();
            $table->string('system_name');
            $table->json('config');
            $table->timestamps();
        });

        Schema::create('sync_jobs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sync_connection_id');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('api_configs', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->index()->unique();
            $table->string('source_type');
            $table->json('source_config');
            $table->string('data_path')->nullable();
            $table->json('depends_on')->nullable();
            $table->json('mapping');
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('api_data', function (Blueprint $table): void {
            $table->id();
            // Link to api_configs
            $table->foreignId('api_config_id')->constrained('api_configs')->cascadeOnDelete();

            // Used to avoid duplicate records
            $table->string('fingerprint')->nullable()->index();

            // Raw fetched data
            $table->json('payload');

            // pending | processed | failed
            $table->string('status')->default('pending')->index();

            // When processing completed
            $table->timestamp('processed_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_jobs');
        Schema::dropIfExists('sync_connections');
    }
};
