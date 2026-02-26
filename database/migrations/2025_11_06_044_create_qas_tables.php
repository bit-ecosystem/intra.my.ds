<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('q_methodologies', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->text('brief_explanation')->nullable();
            $table->boolean('needs_form')->default(false);
            $table->json('form_schema')->nullable();
            $table->boolean('needs_report')->default(false);
            $table->json('report_schema')->nullable();
            $table->string('typical_record_type')->nullable();
            $table->string('example_template_name')->nullable();
            $table->string('external_url')->nullable();
            $table->timestamps();
        });
        Schema::create('q_initiatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('methodology_id')->constrained('q_methodologies')->cascadeOnDelete();
            $table->foreignId('initiator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('draft');
            $table->json('inputs')->nullable(); // e.g., form responses or data
            $table->json('outputs')->nullable(); // e.g., reports, KPIs, findings
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('q_methodologies');
        Schema::dropIfExists('q_initiatives');
    }
};
