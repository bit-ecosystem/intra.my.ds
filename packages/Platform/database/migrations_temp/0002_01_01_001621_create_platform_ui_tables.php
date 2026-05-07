<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PLATFORM / UI
 * Intent:
 * Provide shared UI-related configuration for Filament panels,
 * including resource layout preferences and UI metadata.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ui_preferences', function (Blueprint $table): void {
            $table->id();
            $table->morphs('owner'); // user or org
            $table->json('settings');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ui_preferences');
    }
};
