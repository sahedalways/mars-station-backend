<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->string('agreement_number', 8)->unique();
            $table->string('title');
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_mobile')->nullable();
            $table->date('validity_date')->nullable();
            $table->enum('payment_type', ['full', 'milestone', 'subscription', 'none'])->default('none');
            $table->enum('status', [
                'pending', 'expired', 'signed', 'in_progress',
                'subscribed', 'unsubscribed', 'completed', 'terminated',
            ])->default('pending');
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('client_email');
            $table->index('is_archived');
            $table->index(['created_at', 'is_archived']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};
