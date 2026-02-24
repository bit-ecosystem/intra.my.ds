<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // // -----------------------
        // // WORKFLOWS & WORKFLOWABLE (POLYMORPHIC)
        // // -----------------------
        // Schema::create('workflows', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->text('description')->nullable();
        //     $table->string('status')->default('active');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // Schema::create('workflowables', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();
        //     $table->morphs('workflowable'); // workflowable_id, workflowable_type
        //     $table->timestamps();
        // });

        // // -----------------------
        // // PROCESSES
        // // -----------------------
        // Schema::create('processes', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('org_unit_id')->constrained('p_org_units')->cascadeOnDelete();
        //     $table->string('name');
        //     $table->text('description')->nullable();
        //     $table->string('status')->default('active');
        //     $table->integer('version')->default(1);
        //     $table->foreignId('process_owner_id')->nullable()->constrained('staff')->nullOnDelete();
        //     $table->string('sla_kpi')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // // -----------------------
        // // TASKS & TASK ACTIONS
        // // -----------------------
        // Schema::create('tasks', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();
        //     $table->string('name');
        //     $table->text('description')->nullable();
        //     $table->integer('sequence')->default(1);
        //     $table->string('role')->nullable();
        //     $table->boolean('is_optional')->default(false);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // Schema::create('task_actions', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('task_id')->constrained()->cascadeOnDelete();
        //     $table->string('name');
        //     $table->text('description')->nullable();
        //     $table->string('action_type')->default('normal'); // approve/reject/escalate/other
        //     $table->json('config')->nullable();
        //     $table->timestamps();
        // });

        // -----------------------
        // SERVICE OFFERINGS + INVENTORY
        // -----------------------
        Schema::create('service_offerings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_unit_id')->constrained('p_org_units')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // e.g., IT, HR, Finance
            $table->string('delivery_type')->default('internal'); // internal/external
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_offering_id')->nullable()->constrained('service_offerings')->nullOnDelete();
            $table->foreignId('org_unit_id')->constrained('p_org_units')->cascadeOnDelete();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->integer('current_stock')->default(0);
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // -----------------------
        // AUDIT / ACTIVITY (OPTIONAL BUT USEFUL)
        // -----------------------
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->morphs('subject'); // action target
            $table->foreignId('performed_by_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('action');
            $table->text('meta')->nullable(); // json
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');

        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('service_offerings');

        // Schema::dropIfExists('task_actions');
        // Schema::dropIfExists('tasks');

        // Schema::dropIfExists('processes');

        // Schema::dropIfExists('workflowables');
        // Schema::dropIfExists('workflows');
    }
};
