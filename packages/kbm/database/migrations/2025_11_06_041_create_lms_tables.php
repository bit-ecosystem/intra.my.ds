<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('l_courses', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique(); // e.g., ENT-IT-MFG-101
            $table->string('title');
            $table->string('category');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['status']);
        });

        Schema::create('l_modules', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();             // e.g., itil, networking, isa-95-level-3-mom
            $table->string('title');                      // display name
            $table->text('description')->nullable();
            $table->unsignedInteger('order_index')->default(0); // sequencing within course
            $table->unsignedInteger('estimated_duration_minutes')->nullable();
            $table->unsignedSmallInteger('validity_months')->nullable(); // Certificate validity in months (recert needed after expiry)
            $table->json('certificate_template')->nullable(); // Optional: certificate template definition (JSON for rendering)
            $table->timestamps();
        });

        Schema::create('l_course_module', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained('l_courses')->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('l_modules')->cascadeOnDelete();
            $table->unsignedInteger('order_index')->default(0); // sequence within course
            $table->timestamps();

            $table->unique(['course_id', 'module_id']);
            $table->index(['course_id', 'order_index']); // helps ordered retrieval
        });

        // MATERIALS are now independent…
        Schema::create('l_materials', function (Blueprint $table): void {
            $table->id();
            $table->string('title');                // e.g., "NIST CSF Overview"
            $table->string('type')->default('link'); // link|pdf|video|doc|other
            $table->string('url')->nullable();                  // absolute or app route
            $table->json('meta')->nullable();       // optional (author, length, etc.)
            $table->timestamps();
            // NOTE: module_id removed to allow many-to-many
        });

        // …and linked to modules via a pivot table
        Schema::create('l_material_module', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('material_id')->constrained('l_materials')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('l_modules')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('order_index')->default(0); // sequence within a module
            $table->timestamps();

            $table->unique(['material_id', 'module_id']); // prevent duplicates
            $table->index(['module_id', 'order_index']);   // ordered retrieval by module
        });

        Schema::create('l_quizzes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('module_id')->constrained('l_modules')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->decimal('passing_mark', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('examiner_style')->default(false);
            $table->json('schema'); // Filament form schema
            $table->timestamps();
            $table->index(['module_id', 'is_active']);
        });

        Schema::create('l_quiz_attempts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('quiz_id')->constrained('l_quizzes')->cascadeOnDelete();
            $table->json('data'); // stores filled form data
            // $table->enum('result', ['pass', 'fail', 'incomplete', 'pending'])->nullable();
            $table->boolean('result')->default(false);
            $table->decimal('score', 5, 2)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('for_staff')->nullable()->constrained('staff')->cascadeOnDelete();
            $table->foreignId('by_staff')->nullable()->constrained('staff')->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('l_modules')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->integer('time_taken')->nullable();
            $table->timestamps();
            $table->index(['quiz_id', 'user_id', 'result']);
            $table->index(['module_id']);
        });

        Schema::create('l_certificates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('module_id')->constrained('l_modules')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('for_staff')->nullable()->constrained('staff')->cascadeOnDelete();
            $table->foreignId('by_staff')->nullable()->constrained('staff')->cascadeOnDelete();
            $table->foreignId('quiz_attempt_id')->constrained('l_quiz_attempts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained('l_quizzes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('certificate_number')->unique(); // e.g., CERT-<module>-<user>-<yyyymmddhhmmss>
            $table->string('title')->nullable();            // e.g., "Networking Certificate"
            $table->timestamp('issued_at');
            $table->timestamp('expires_at')->nullable();
            $table->json('payload')->nullable(); // render-ready payload for PDF
            $table->enum('status', ['valid', 'expired', 'revoked'])->default('valid');
            $table->enum('action', ['pending', 'completed', 'none'])->default('pending');
            $table->timestamps();

            // FIX: previously referenced user_id which doesn't exist here
            $table->index(['module_id', 'for_staff', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('l_certificates');
        Schema::dropIfExists('l_quiz_attempts');
        Schema::dropIfExists('l_quizzes');
        Schema::dropIfExists('l_material_module'); // drop pivot before base tables
        Schema::dropIfExists('l_materials');
        Schema::dropIfExists('l_course_module');
        Schema::dropIfExists('l_modules');
        Schema::dropIfExists('l_courses');
    }
};
