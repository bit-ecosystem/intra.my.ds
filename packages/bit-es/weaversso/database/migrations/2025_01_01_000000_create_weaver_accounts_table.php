<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weaver_accounts', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignId('user_id')->index()->constrained()->cascadeOnDelete();
            $blueprint->string('weaver_login')->index();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weaver_accounts');
    }
};
