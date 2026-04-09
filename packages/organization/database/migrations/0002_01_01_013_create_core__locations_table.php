<?php

use App\Enums\LocationType;
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
        Schema::create('locations', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->unique();
            $table->enum('type', array_column(LocationType::cases(), 'value'))->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
