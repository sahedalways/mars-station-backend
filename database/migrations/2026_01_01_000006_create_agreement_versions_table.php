<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agreement_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agreement_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->string('title');
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_mobile')->nullable();
            $table->date('validity_date')->nullable();
            $table->longText('content');
            $table->json('payment_config')->nullable();
            $table->enum('status', ['pending', 'signed'])->default('pending');
            $table->string('signed_name')->nullable();
            $table->string('signed_email')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('signed_ip_address', 45)->nullable();
            $table->string('signed_user_agent')->nullable();
            $table->string('signed_pdf_path')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();

            $table->unique(['agreement_id', 'version']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreement_versions');
    }
};
