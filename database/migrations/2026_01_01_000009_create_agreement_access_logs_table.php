<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agreement_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agreement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('link_id')->nullable()->constrained('agreement_links')->nullOnDelete();
            $table->enum('type', ['link', 'otp'])->default('link');
            $table->string('email')->nullable();
            $table->enum('status', ['viewed', 'otp_sent', 'otp_verified', 'otp_failed', 'blocked', 'signed'])->default('viewed');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['agreement_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreement_access_logs');
    }
};
