<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('item_type', ['RM', 'SF', 'FG', 'SP', 'CO', 'TL', 'EQ', 'AS', 'PK', 'DG', 'SV', 'UT', 'QI', 'LG', 'FI', 'MD']);
            $table->string('category')->nullable();
            $table->string('base_uom')->default('pcs');
            $table->boolean('is_batch_tracked')->default(false);
            $table->boolean('is_serial_tracked')->default(false);
            $table->timestamps();
        });

        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->string('asset_type');
            $table->string('location')->nullable();
            $table->enum('status', ['Active', 'Maintenance', 'Disposed'])->default('Active');
            $table->timestamps();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['factory', 'distribution', 'vendor', 'virtual']);
            $table->string('location')->nullable();
            $table->timestamps();
        });

        Schema::create('storage_locations', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('warehouse_id')->constrained();
            $table->enum('type', ['rack', 'shelf', 'bin', 'locker', 'cold_room']);
            $table->foreignId('parent_id')->nullable()->constrained('storage_locations');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained();
            $table->foreignId('storage_location_id')->constrained();
            $table->decimal('quantity_on_hand', 18, 4)->default(0);
            $table->decimal('quantity_reserved', 18, 4)->default(0);
            $table->timestamps();
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('data_type', ['string', 'number', 'date', 'boolean', 'json']);
            $table->timestamps();
        });

        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->morphs('attributable');
            $table->foreignId('attribute_id')->constrained();
            $table->string('value_string')->nullable();
            $table->decimal('value_number', 18, 4)->nullable();
            $table->date('value_date')->nullable();
            $table->json('value_json')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('inventory_balances');
        Schema::dropIfExists('storage_locations');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('items');
    }
};
