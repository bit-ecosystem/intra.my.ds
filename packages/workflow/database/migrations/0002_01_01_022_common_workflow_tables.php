<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('w_turtles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->string('input')->nullable();
            $table->string('output')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('org_roles')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('org_roles')->nullOnDelete();
            $table->string('resources')->nullable();
            $table->string('methods')->nullable();
            $table->string('kpis')->nullable();
            $table->timestamps();
        });

        Schema::create('w_workflows', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('turtle_id')->constrained('w_turtles')->cascadeOnDelete();
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->onDelete('cascade');
            $table->string('icon')->nullable();
            $table->enum('state', ['inactive', 'internal_workflow', 'external_workflow'])->default('inactive');
            $table->string('external_link')->nullable();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('w_workflowables', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_id')->constrained('w_workflows')->cascadeOnDelete();
            $table->morphs('workflowable'); // workflowable_id, workflowable_type
            $table->timestamps();
        });

        Schema::create('w_nodes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_id')->constrained('w_workflows')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_initial')->default(false);
            $table->boolean('is_final')->default(false);
            $table->foreignId('assignee_role_id')->nullable()->constrained('org_roles')->nullOnDelete();
            $table->integer('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('w_transitions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_id')->constrained('w_workflows')->cascadeOnDelete();
            $table->foreignId('from_state_id')->constrained('w_nodes')->cascadeOnDelete();
            $table->foreignId('to_state_id')->constrained('w_nodes')->cascadeOnDelete();
            $table->string('action_name');
            $table->integer('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('w_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_id')->constrained('w_workflows')->cascadeOnDelete();
            $table->foreignId('current_state_id')->nullable()->constrained('w_nodes')->nullOnDelete();
            $table->morphs('subject');
            $table->foreignId('initiator_id')->nullable()->constrained('org_roles')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('w_activities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_id')->constrained('w_workflows')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->morphs('activityable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('w_activities');
        Schema::dropIfExists('w_requests');
        Schema::dropIfExists('w_transitions');
        Schema::dropIfExists('w_nodes');
        Schema::dropIfExists('w_workflowables');
        Schema::dropIfExists('w_workflows');
        Schema::dropIfExists('w_turtles');
    }
};
