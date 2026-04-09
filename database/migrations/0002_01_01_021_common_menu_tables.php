<?php

use App\Enums\MenuCategory;
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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->enum('category', array_column(MenuCategory::cases(), 'value'));
            $table->string('title');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('internal_link')->nullable();
            // $table->string('external_link')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('attachable_roles', function (Blueprint $table) {
            $table->id();
            // Spatie role
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();

            // Polymorphic target (any model)
            $table->morphs('attachable'); // adds attachable_type (string), attachable_id (bigint)

            // OPTIONAL: OrgUnit/team scoping for record-level visibility (if needed)
            // Only use this if you want visibility bound to a specific OrgUnit per record.
            $table->foreignId('team_id')
                ->nullable()
                ->constrained('org_units') // your Bites\Organization\Structure\OrgUnit table
                ->nullOnDelete();

            $table->timestamps();

            // Avoid duplicates (per team)
            $table->unique(['role_id', 'attachable_type', 'attachable_id', 'team_id'], 'attachable_roles_unique');

            // Helpful indexes
            $table->index(['attachable_type', 'attachable_id'], 'attachable_roles_target_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachable_roles');
        Schema::dropIfExists('menus');
    }
};
