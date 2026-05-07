<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PLATFORM / Auth
 * Intent:
 * Manage authentication, users, and external identity federation.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('username')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->text('app_authentication_secret')->nullable();
            $table->rememberToken();
            $table->string('ldap_domain')->nullable();
            $table->string('ldap_guid', 36)->nullable()->index();
            $table->string('employee_id')->nullable();
            $table->string('department')->nullable();
            $table->string('title')->nullable();
            $table->string('manager_dn')->nullable();
            $table->boolean('bio_readonly')->default(false);

            $table->timestamps();
            $table->unique('username');
            $table->unique('ldap_guid');
        });

        Schema::create('identity_providers', function (Blueprint $table): void {
            $table->id();
            $table->string('type'); // ldap, ad, keycloak
            $table->json('config');
            $table->timestamps();
        });

        Schema::create('external_identities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('identity_provider_id');
            $table->string('external_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_identities');
        Schema::dropIfExists('identity_providers');
        // Schema::dropIfExists('users');
    }
};
