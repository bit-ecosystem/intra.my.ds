<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration // packages core core_tables
{
    public function up(): void
    {
        Schema::create('help_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('type')->default('help'); // e.g. help | faq | sop | guide
            $table->string('category')->nullable(); // e.g. 'Procurement', 'Onboarding'
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->nullOnDelete();

            $table->string('page_class')->index(); // e.g. App\Filament\Resources\UserResource\Pages\EditUser
            $table->unsignedBigInteger('record')->nullable(); // optional specific record ID
            $table->string('title');
            $table->longText('content'); // RichEditor HTML

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('help_pages');
    }
};
