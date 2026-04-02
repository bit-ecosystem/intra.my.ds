<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration // packages core core_tables
{
    public function up(): void
    {

        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('staff_number')->unique();
            $table->string('name')->nullable();
            // $table->date('join_date')->nullable();
            // $table->date('end_date')->nullable();
            // $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->nullOnDelete();
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->nullOnDelete();
            $table->foreignId('job_position_id')->nullable()->constrained('job_positions')->nullOnDelete();
            $table->timestamps();
        });
        // for consumption of users, staffs (actual), job_posts (defined)
        Schema::create('person_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('key'); // e.g. 'gender', 'dob', 'phone'
            $table->text('value')->nullable();
            $table->morphs('attributable'); // adds attributable_id and attributable_type
            $table->timestamps();
        });
        // for consumption of assets, equipment, (actual,defined)
        Schema::create('asset_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('key'); // e.g. 'dimensions', 'type', 'location'
            $table->text('value')->nullable();
            $table->morphs('attributable'); // adds adds attributable_id and attributable_type
            $table->timestamps();
        });

        Schema::create('role_mappers', function (Blueprint $table) {
            $table->id();
            // Scope where this mapping applies: global vs. OrgUnit/team (ou)
            $table->enum('scope', ['global', 'ou']);
            // The role to assign when conditions match (e.g., ut_staff, st_employee, ou_manager)
            $table->string('role_name');
            // Ordering / precedence. Lower number runs first (adjust as needed).
            $table->unsignedInteger('priority')->default(100);
            // Optional constraints for targeting
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->nullOnDelete();
            $table->foreignId('job_position_id')->nullable()->constrained('job_positions')->nullOnDelete();
            // JSON conditions (restricted keys for deterministic evaluation)
            // Example:
            // {"employment_type":"employee","flags":{"is_people_manager":true},"require_prefix":["st_"],"deny_prefixes":["mod_"],"org_roles_scope":"global"}
            $table->json('conditions')->nullable();
            // Enable/disable without deleting the row
            $table->boolean('enabled')->default(true);
            $table->boolean('task_group')->default(false);
            // Optional descriptive fields
            $table->string('label')->nullable();
            $table->string('category')->nullable(); // e.g., "canonical", "implied", "override"
            $table->timestamps();

            // Indexes for common lookups
            $table->index(['scope', 'enabled']);
            $table->index(['org_unit_id', 'scope', 'enabled']);
            $table->index(['job_position_id', 'scope', 'enabled']);
            $table->index(['priority']);
            $table->index(['role_name']);
        });

        Schema::create('model_stake_holders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->morphs('assignable');
            // assignable_type, assignable_id
            $table->boolean('can_view')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->timestamps();

            $table->unique(['role_id','assignable_type','assignable_id',]);
        }); 

        Schema::create('role_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            // Optional: attach to a specific OU/team context
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->nullOnDelete();
            // Optional: enable/disable the link (without deleting)
            $table->boolean('enabled')->default(true);
            // Optional: time-bound link
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            // Optional: notes/metadata on the link
            $table->string('note')->nullable();
            // Optional: per-link precedence (overrides RoleMapper.priority if you want)
            $table->unsignedInteger('link_priority')->nullable();
            $table->timestamps();

            // Prevent duplicate links for same trio keys (role_mapper + staff + OU)
            $table->unique(['role_id', 'staff_id', 'org_unit_id'], 'role_staff_unique');
            $table->index(['staff_id', 'enabled', 'org_unit_id']);
            $table->index(['role_id', 'enabled', 'org_unit_id']);
        });

        Schema::create('c_panels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('path')->unique();
            $table->string('primary_color')->nullable();
            $table->string('gray_color')->nullable();
            $table->string('success_color')->nullable();
            $table->string('info_color')->nullable();
            $table->string('warning_color')->nullable();
            $table->string('danger_color')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('panel_title')->nullable();
            $table->json('resource_classes')->nullable();
            $table->json('widget_classes')->nullable();
            $table->json('page_classes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('c_panels');
        Schema::dropIfExists('role_staff');
        Schema::dropIfExists('role_assignables');
        Schema::dropIfExists('role_mappers');
        Schema::dropIfExists('asset_attributes');
        Schema::dropIfExists('person_attributes');
        Schema::dropIfExists('staff');
    }
};
