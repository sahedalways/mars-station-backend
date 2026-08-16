<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agreement_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agreement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('version_id')->nullable()->constrained('agreement_versions')->nullOnDelete();
            $table->string('title');
            $table->unsignedBigInteger('amount_pence');
            $table->enum('frequency', ['monthly', 'yearly'])->default('monthly');
            $table->string('stripe_customer_id')->nullable()->index();
            $table->string('stripe_subscription_id')->nullable()->unique();
            $table->string('stripe_price_id')->nullable();
            $table->enum('status', [
                'trialing', 'active', 'past_due', 'incomplete',
                'incomplete_expired', 'canceled', 'unpaid', 'paused', 'ended',
            ])->default('incomplete');
            $table->boolean('cancel_at_period_end')->default(false);
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreement_subscriptions');
    }
};
