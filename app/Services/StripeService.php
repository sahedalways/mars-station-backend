<?php

namespace App\Services;

use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Event;
use Stripe\PaymentIntent;
use Stripe\Price;
use Stripe\Refund;
use Stripe\StripeClient;
use Stripe\Subscription;
use Stripe\Webhook;

class StripeService
{
    private ?StripeClient $client = null;

    public function client(): StripeClient
    {
        return $this->client ??= new StripeClient(config('mars.stripe.secret'));
    }

    public function isConfigured(): bool
    {
        return (bool) config('mars.stripe.secret');
    }

    public function currency(): string
    {
        return config('mars.stripe.currency', 'gbp');
    }

    public function createCustomer(string $email, string $name, ?array $metadata = null): Customer
    {
        return $this->client()->customers->create([
            'email' => $email,
            'name' => $name,
            'metadata' => $metadata ?? [],
        ]);
    }

    public function retrieveCustomer(string $customerId): Customer
    {
        return $this->client()->customers->retrieve($customerId);
    }

    public function createPrice(int $amountPence, string $currency, string $interval): Price
    {
        return $this->client()->prices->create([
            'unit_amount' => $amountPence,
            'currency' => $currency,
            'recurring' => ['interval' => $interval],
            'product_data' => ['name' => 'Mars Station Subscription'],
        ]);
    }

    public function createCheckoutSession(array $params): Session
    {
        return $this->client()->checkout->sessions->create($params);
    }

    public function retrieveCheckoutSession(string $id): Session
    {
        return $this->client()->checkout->sessions->retrieve($id, ['expand' => ['payment_intent', 'subscription']]);
    }

    public function retrievePaymentIntent(string $id): PaymentIntent
    {
        return $this->client()->paymentIntents->retrieve($id);
    }

    public function createSubscription(array $params): Subscription
    {
        return $this->client()->subscriptions->create($params);
    }

    public function retrieveSubscription(string $id): Subscription
    {
        return $this->client()->subscriptions->retrieve($id, ['expand' => ['latest_invoice']]);
    }

    public function cancelSubscription(string $id, bool $atPeriodEnd = true): Subscription
    {
        return $this->client()->subscriptions->cancel($id, ['at_period_end' => $atPeriodEnd]);
    }

    public function createRefund(array $params): Refund
    {
        return $this->client()->refunds->create($params);
    }

    public function constructEvent(string $payload, string $signature, string $secret): Event
    {
        return Webhook::constructEvent($payload, $signature, $secret);
    }

    public function mapPaymentMethod(?string $method): ?string
    {
        return match ($method) {
            'card' => 'Card',
            'pm_card_visa' => 'Card (Visa)',
            'pm_card_mastercard' => 'Card (Mastercard)',
            'pm_card_amex' => 'Card (Amex)',
            default => $method,
        };
    }
}
