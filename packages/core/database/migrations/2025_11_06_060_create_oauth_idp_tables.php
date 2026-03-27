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
        Schema::create('oauth_auth_codes', function (Blueprint $blueprint): void {
            $blueprint->char('id', 80)->primary();
            $blueprint->foreignId('user_id')->index();
            $blueprint->foreignUuid('client_id');
            $blueprint->text('scopes')->nullable();
            $blueprint->boolean('revoked');
            $blueprint->dateTime('expires_at')->nullable();
        });
        Schema::create('oauth_access_tokens', function (Blueprint $blueprint): void {
            $blueprint->char('id', 80)->primary();
            $blueprint->foreignId('user_id')->nullable()->index();
            $blueprint->foreignUuid('client_id');
            $blueprint->string('name')->nullable();
            $blueprint->text('scopes')->nullable();
            $blueprint->boolean('revoked');
            $blueprint->timestamps();
            $blueprint->dateTime('expires_at')->nullable();
        });
        Schema::create('oauth_refresh_tokens', function (Blueprint $blueprint): void {
            $blueprint->char('id', 80)->primary();
            $blueprint->char('access_token_id', 80)->index();
            $blueprint->boolean('revoked');
            $blueprint->dateTime('expires_at')->nullable();
        });
        Schema::create('oauth_clients', function (Blueprint $blueprint): void {
            $blueprint->uuid('id')->primary();
            $blueprint->nullableMorphs('owner');
            $blueprint->string('name');
            $blueprint->string('secret')->nullable();
            $blueprint->string('provider')->nullable();
            $blueprint->text('redirect_uris');
            $blueprint->text('grant_types');
            $blueprint->boolean('revoked');
            $blueprint->timestamps();
        });
        Schema::create('oauth_device_codes', function (Blueprint $blueprint): void {
            $blueprint->char('id', 80)->primary();
            $blueprint->foreignId('user_id')->nullable()->index();
            $blueprint->foreignUuid('client_id')->index();
            $blueprint->char('user_code', 8)->unique();
            $blueprint->text('scopes');
            $blueprint->boolean('revoked');
            $blueprint->dateTime('user_approved_at')->nullable();
            $blueprint->dateTime('last_polled_at')->nullable();
            $blueprint->dateTime('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_access_tokens');
        Schema::dropIfExists('oauth_clients');
        Schema::dropIfExists('oauth_refresh_tokens');
        Schema::dropIfExists('oauth_access_tokens');
        Schema::dropIfExists('oauth_auth_codes');
    }

    /**
     * Get the migration connection name.
     */
    public function getConnection(): ?string
    {
        return $this->connection ?? config('passport.connection');
    }
};
