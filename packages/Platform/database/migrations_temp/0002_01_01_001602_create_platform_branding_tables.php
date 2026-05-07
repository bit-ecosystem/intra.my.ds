<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PLATFORM / Branding
 * Intent:
 * Store corporate identity such as logos, themes, and visual styles
 * that can be applied per organization or tenant.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brandings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organization_id')->nullable();
            $table->string('name');
            $table->json('theme');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brandings');
    }
};
