<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('get_service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->enum('preferred_contact', ['email', 'phone'])->default('email');
            $table->json('selected_services')->nullable();
            $table->text('additional_notes')->nullable();
            $table->enum('status', ['new', 'processing', 'flagged', 'signed', 'completed'])->default('new');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('is_read');
            $table->index(['created_at', 'is_read']);
        });

        Schema::create('get_service_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('get_service_request_id')->constrained()->cascadeOnDelete();
            $table->string('storage_path');
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('get_service_attachments');
        Schema::dropIfExists('get_service_requests');
    }
};
