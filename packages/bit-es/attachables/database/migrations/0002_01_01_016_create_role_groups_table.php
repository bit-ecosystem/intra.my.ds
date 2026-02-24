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
        Schema::create('role_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code');
            $table->string('name');
            $table->boolean('is_global')->default(false);
            $table->timestamps();
        });

        Schema::create('role_group_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('role_group_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_group_id')->constrained()->cascadeOnDelete();
            $table->string('role_type');
        });
        Schema::create('model_access_controls', function (Blueprint $table) {

            $table->id();
            $table->morphs('accessible');
            $table->foreignId('org_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('role_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('role_type');
            $table->timestamps();

            $table->index(['accessible_type', 'accessible_id', 'org_unit_id', 'role_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_group_permissions');
        Schema::dropIfExists('role_group_users');
        Schema::dropIfExists('role_groups');
    }
};
