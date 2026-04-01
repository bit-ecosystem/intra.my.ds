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
        Schema::create('tasks', function (Blueprint $table): void {
            $table->id();
            $table->morphs('taskable');
            $table->string('title');
            $table->foreignId('staff_id')->nullable()->constrained('staff')->cascadeOnDelete();
            $table->foreignId('role_mapper_id')->nullable()->constrained('role_mappers')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->nullOnDelete(); // Multi-team / org scoping
            $table->foreignId('owner_id')->nullable()->constrained('staff')->nullOnDelete(); // Ownership / organizer
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->boolean('is_all_day')->default(false);
            // Timezone handling:
            $table->dateTime('starts_at');       // local start time
            $table->dateTime('ends_at')->nullable(); // local end time
            $table->string('timezone', 64)->nullable();
            $table->dateTime('start_UTC')->nullable(); // for date-only queries (UTC date part of starts_at)
            $table->dateTime('end_UTC')->nullable();   // for date-only queries (UTC date part of ends_at)

            $table->string('type', 64)->nullable();     // your classify hierarchy can map here
            $table->string('status', 32)->default('planned'); // planned|tentative|confirmed|done|cancelled
            $table->string('color', 32)->nullable();
            $table->timestamps();

            // Indexing strategy
            $table->index('starts_at');
            $table->index('ends_at');
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('tasks');
    }
};
