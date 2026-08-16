<?php

use App\Livewire\Agreement\AgreementPortal;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::get('/agreement/{token}', AgreementPortal::class)->name('agreement.view');

Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');
