<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agreement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('version_id')->nullable()->constrained('agreement_versions')->nullOnDelete();
            $table->enum('type', ['full', 'milestone', 'subscription']);
            $table->foreignId('milestone_id')->nullable();
            $table->string('stripe_payment_intent_id')->nullable()->unique()->index();
            $table->string('stripe_invoice_id')->nullable()->index();
            $table->unsignedBigInteger('amount_pence');
            $table->string('currency', 3)->default('gbp');
            $table->enum('status', ['pending', 'requires_action', 'succeeded', 'failed', 'refunded', 'partially_refunded'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_method_brand')->nullable();
            $table->string('payment_method_last4', 4)->nullable();
            $table->unsignedBigInteger('refunded_amount_pence')->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->string('idempotency_key', 100)->nullable()->unique();
            $table->timestamps();

            $table->index(['agreement_id', 'type']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
