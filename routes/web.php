<?php

use App\Livewire\Agreement\AgreementPortal;
use Illuminate\Support\Facades\Route;

$clientSubdomain = config('app.client_subdomain', env('CLIENT_SUBDOMAIN', 'client'));
$adminSubdomain = config('app.admin_subdomain', env('ADMIN_SUBDOMAIN', 'hp200397'));
$domain = config('app.domain', env('APP_DOMAIN', 'marsstation.dev'));

// Main domain redirects to admin
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Client subdomain routes
Route::domain("{$clientSubdomain}.{$domain}")->group(function () {
    Route::get('/agreement/{token}', AgreementPortal::class)->name('agreement.view');
});

// Stripe webhook - accessible on admin subdomain (production) and main domain (local)
Route::domain("{$adminSubdomain}.{$domain}")->group(function () {
    Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])
        ->name('stripe.webhook');
});

// Also keep on main domain for local development
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook.main');
