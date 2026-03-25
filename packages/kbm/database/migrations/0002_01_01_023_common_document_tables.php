<?php

use App\Enums\DocClass;
use App\Enums\DocType;
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
        // -----------------------
        // DOCUMENT MANAGEMENT (POLYMORPHIC)
        // -----------------------
        Schema::create('d_document_classifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('d_document_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('level', ['public', 'internal', 'confidential', 'strictly_confidential']); // Public, Internal, Confidential, Strictly Confidential
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('d_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained('d_document_levels')->cascadeOnDelete();
            $table->foreignId('classification_id')->constrained('d_document_classifications')->cascadeOnDelete();
            $table->foreignId('owner_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('d_documents')->nullOnDelete();
            $table->string('code')->unique();                 // from "Key" (e.g., L1-ENTGOV-POL-0001)
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('effective_at')->nullable();
            $table->timestamp('retired_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('d_document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('d_documents')->cascadeOnDelete();
            $table->integer('version_number');
            $table->string('file_path');
            $table->text('change_summary')->nullable();
            $table->boolean('is_active')->default(false);
            $table->foreignId('uploaded_by_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('d_document_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_version_id')->constrained('d_document_versions')->cascadeOnDelete();
            $table->foreignId('approver_staff_id')->constrained('staff')->cascadeOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // allow documents to attach to any model (documentables)
        Schema::create('d_documentables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('d_documents')->cascadeOnDelete();
            $table->morphs('documentable'); // documentable_id, documentable_type
            $table->string('role')->nullable(); // e.g., 'supporting', 'required'
            $table->timestamps();
        });

        // attachments polymorphic: files attached to many models
        Schema::create('d_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->enum('class', array_column(DocClass::cases(), 'value'))->nullable();
            $table->enum('type', array_column(DocType::cases(), 'value'))->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            // $table->morphs('attachmentable'); // attachmentable_id, attachmentable_type
            $table->foreignId('uploaded_by_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('d_vectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('d_documents')->cascadeOnDelete();
            $table->string('model')->nullable();
            $table->text('chunk_text'); // NEW: actual text chunk for retrieval
            $table->json('vector')->nullable(); // embedding vector
            $table->json('metadata')->nullable(); // optional metadata (tags, page number, etc.)
            $table->timestamps();
            $table->unique(['document_id', 'model', 'chunk_text'], 'dms_vector_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_vectors');
        Schema::dropIfExists('d_attachments');
        Schema::dropIfExists('d_documentables');
        Schema::dropIfExists('d_document_approvals');
        Schema::dropIfExists('d_document_versions');
        Schema::dropIfExists('d_documents');
        Schema::dropIfExists('d_document_classifications');
        Schema::dropIfExists('d_document_types');
    }
};
