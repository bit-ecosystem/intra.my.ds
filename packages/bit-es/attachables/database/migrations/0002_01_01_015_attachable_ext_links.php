<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attachable_ext_links', function (Blueprint $table) {
            $table->id();
            $table->string('url', 2048);
            $table->timestamps();

            // Polymorphic owner (any model can have links)
            $table->morphs('attachable'); // attachable_type, attachable_id (both indexed)

            $table->unique(['attachable_type', 'attachable_id'], 'attachable_unique');
            $table->index(['attachable_type', 'attachable_id'], 'attachable_ext_links_target_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachable_ext_links');
    }
};
