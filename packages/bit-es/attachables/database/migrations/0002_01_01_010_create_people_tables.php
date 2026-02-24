<?php

// database/migrations/2026_02_14_000000_create_staff_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::create('staff', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->foreignId('work_shift_id')->nullable()->constrained('work_shifts');
        //     $table->timestamps();
        // });
        // Schema::create('job_positions', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('title');
        //     $table->boolean('outsourced')->default(false);
        //     $table->nullableMorphs('assignable'); // Staff or User
        //     $table->foreignId('work_shift_id')->nullable()->constrained('work_shifts');
        //     $table->timestamps();
        // });
        // Schema::create('people_attributes', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('key');
        //     $table->string('value');
        //     $table->timestamps();
        // });
        // Schema::create('attributeables', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('people_attribute_id');
        //     $table->morphs('attributeable');
        //     $table->timestamps();
        //     $table->foreign('people_attribute_id')->references('id')->on('people_attributes')->onDelete('cascade');
        // });
        // Schema::create('work_shifts', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('group');
        //     $table->string('pattern');
        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_shifts');
        Schema::dropIfExists('attributeables');
        Schema::dropIfExists('people_attributes');
        Schema::dropIfExists('job_positions');
        Schema::dropIfExists('staff');
    }
};
