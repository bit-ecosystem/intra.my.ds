<?php

use App\Enums\OrgUnitType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration // packages core core_tables
{
    public function up(): void
    {
        // Companies (optional high-level)
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->boolean('isCustomer')->default(false);
            $table->boolean('isSupplier')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // ORG UNITS (company/division/department/team tree)
        Schema::create('org_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->enum('type', array_column(OrgUnitType::cases(), 'value'))->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('org_units')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('owner_id')->nullable()->constrained('job_positions')->onDelete('cascade');
            $table->string('owner_staff_number')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('org_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->foreignId('org_unit_id')->constrained('org_units')->nullOnDelete();
            $table->foreignId('job_position_id')->nullable()->constrained('job_positions')->nullOnDelete();
            $table->text('description')->nullable();
            $table->enum('scope', ['global', 'ou'])->default('ou');
            $table->timestamps();

            $table->unique(['job_position_id', 'name', 'scope']);
        });
        Schema::create('job_description_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Job title
            $table->longText('description'); // Detailed description
            $table->json('attributes')->nullable();
            // attributes will store tasks, basic_skills, specific_skills, knowledge, interest
            // Example: { "tasks": "...", "basic_skills": "...", "specific_skills": "...", "knowledge": "...", "interest": "..." }
            $table->string('masco_code')->nullable(); // MASCO code
            $table->timestamps();
        });

        Schema::create('workforce_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->nullOnDelete();
            $table->foreignId('job_title_id')->nullable()->constrained('job_description_templates')->nullOnDelete();
            $table->string('title'); // Job title
            $table->integer('required_quantity')->default(0);
            $table->timestamps();
            $table->unique(['org_unit_id', 'title'], 'org_unit_title_unique');
        });

        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_unit_id')->constrained('org_units')->cascadeOnDelete();
            $table->string('title');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('superior_id')->nullable()->constrained('job_positions')->nullOnDelete();
            // $table->boolean('isPeopleManager')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_positions');
        Schema::dropIfExists('workforce_plans');
        Schema::dropIfExists('job_description_templates');
        Schema::dropIfExists('org_roles');
        Schema::dropIfExists('org_units');
        Schema::dropIfExists('companies');
    }
};
