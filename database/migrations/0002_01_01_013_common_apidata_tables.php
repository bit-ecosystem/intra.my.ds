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
        Schema::create('api_data', function (Blueprint $table) {
            $table->id();
            $table->string('source')->nullable(); // optional: where the data came from
            $table->json('content'); // stores user data in JSON format
            $table->timestamps(); // created_at and updated_at
        });
    }

    // Schema::create('job_description_templates', function (Blueprint $table) {
    //     $table->id();
    //     $table->string('title'); // Job title
    //     $table->longText('description'); // Detailed description
    //     $table->json('attributes')->nullable();
    //     // attributes will store tasks, basic_skills, specific_skills, knowledge, interest
    //     // Example: { "tasks": "...", "basic_skills": "...", "specific_skills": "...", "knowledge": "...", "interest": "..." }
    //     $table->string('masco_code')->nullable(); // MASCO code
    //     $table->timestamps();
    // });

    // Schema::create('workforce_plans', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->nullOnDelete();
    //     $table->foreignId('job_title_id')->nullable()->constrained('job_description_templates')->nullOnDelete();
    //     $table->string('title'); // Job title
    //     $table->integer('required_quantity')->default(0);
    //     $table->timestamps();
    //     $table->unique(['org_unit_id', 'title'], 'org_unit_title_unique');
    // });
    // Schema::create('job_positions', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('org_unit_id')->constrained('org_units')->cascadeOnDelete();
    //     $table->string('title');
    //     $table->string('code')->nullable();
    //     $table->text('description')->nullable();
    //     $table->foreignId('superior_id')->nullable()->constrained('job_positions')->nullOnDelete();
    //     $table->timestamps();
    // });
    // // Job Vacancies table
    // Schema::create('r_job_vacancies', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('job_position_id')->constrained('job_positions')->onDelete('cascade');
    //     $table->string('location');
    //     $table->json('responsibilities');
    //     $table->json('qualifications');
    //     $table->string('salary_range')->nullable();
    //     $table->enum('status', ['open', 'closed'])->default('open');
    //     $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
    //     $table->timestamps();
    // });
    // // Applications table
    // Schema::create('r_applications', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('job_vacancy_id')->constrained('r_job_vacancies')->onDelete('cascade');
    //     // $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    //     $table->string('name');
    //     $table->string('email');
    //     $table->string('resume_path');
    //     $table->text('cover_letter')->nullable();
    //     $table->enum('status', ['applied', 'shortlisted', 'rejected'])->default('applied');
    //     $table->timestamp('applied_at');
    //     $table->timestamps();
    // });

    // // Application Status History table
    // Schema::create('r_application_status_history', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('application_id')->constrained('r_applications')->onDelete('cascade');
    //     $table->enum('status', ['applied', 'shortlisted', 'rejected']);
    //     $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
    //     $table->timestamp('changed_at');
    //     $table->timestamps();
    // });

    // // Screenings table
    // Schema::create('r_screenings', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('application_id')->constrained('r_applications')->onDelete('cascade');
    //     $table->integer('score')->nullable();
    //     $table->text('remarks')->nullable();
    //     $table->enum('status', ['pending', 'completed'])->default('pending');
    //     $table->timestamps();
    // });

    // // Interviews table
    // Schema::create('r_interviews', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('application_id')->constrained('r_applications')->onDelete('cascade');
    //     $table->foreignId('interviewer_id')->constrained('users')->onDelete('cascade');
    //     $table->timestamp('scheduled_at');
    //     $table->enum('mode', ['online', 'in-person']);
    //     $table->enum('status', ['scheduled', 'completed'])->default('scheduled');
    //     $table->text('feedback')->nullable();
    //     $table->timestamps();
    // });

    // // Offers table
    // Schema::create('r_offers', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('application_id')->constrained('r_applications')->onDelete('cascade');
    //     $table->string('offer_letter_path');
    //     $table->string('salary_offered');
    //     $table->enum('status', ['sent', 'accepted', 'declined'])->default('sent');
    //     $table->timestamp('sent_at');
    //     $table->timestamps();
    // });

    // Schema::create('r_skills', function (Blueprint $table) {
    //     $table->id();
    //     $table->string('name')->unique();
    //     $table->timestamps();
    // });

    // Schema::create('r_skillables', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('skill_id')->constrained('r_skills')->cascadeOnDelete();
    //     $table->morphs('skillable'); // skillable_id, skillable_type
    //     $table->boolean('is_required')->default(true); // extra column
    //     $table->timestamps();
    // });

    // // Job Skill pivot table
    // Schema::create('job_skill', function (Blueprint $table) {
    //     $table->foreignId('job_position_id')->constrained('job_positions')->onDelete('cascade');
    //     $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
    //     $table->primary(['job_position_id', 'skill_id']);
    // });

    // // Candidate Skill pivot table
    // Schema::create('candidate_skill', function (Blueprint $table) {
    //     // $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    //     $table->string('name');
    //     $table->string('email');
    //     $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
    //     // $table->primary(['user_id', 'skill_id']);
    // });

    // Documents table
    // Schema::create('documents', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
    //     // $table->string('name');
    //     // $table->string('email');
    //     $table->string('type');
    //     $table->string('file_path');
    //     $table->timestamps();
    // });

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('weaver_data');
        // Schema::dropIfExists('documents');
        // Schema::dropIfExists('candidate_skill');
        // Schema::dropIfExists('job_skill');
        // Schema::dropIfExists('r_skillables');
        // Schema::dropIfExists('r_skills');
        // Schema::dropIfExists('r_offers');
        // Schema::dropIfExists('r_interviews');
        // Schema::dropIfExists('r_screenings');
        // Schema::dropIfExists('r_application_status_history');
        // Schema::dropIfExists('r_applications');
        // Schema::dropIfExists('r_job_vacancies');
        // Schema::dropIfExists('job_positions');
        // Schema::dropIfExists('workforce_plans');
        // Schema::dropIfExists('job_description_templates');
    }
};
