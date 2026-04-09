<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint as SchemaBlueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blueprints', function (SchemaBlueprint $table): void {
            $table->id();
            $table->string('name');
            $table->json('form_blocks')->nullable();
            $table->json('infolist_blocks')->nullable();
            $table->json('action_blocks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blueprints');
    }
};
