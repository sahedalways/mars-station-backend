<?php

use App\Http\Middleware\AdminAuthenticated;
use App\Http\Middleware\AdminGuest;
use App\Livewire\Admin\Agreements\AgreementEdit;
use App\Livewire\Admin\Agreements\AgreementIndex;
use App\Livewire\Admin\Agreements\AgreementShow;
use App\Livewire\Admin\Auth\AdminLogin;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Payments\PaymentHistory;
use Illuminate\Support\Facades\Route;

$adminSubdomain = config('app.admin_subdomain', env('ADMIN_SUBDOMAIN', 'hp200397'));
$domain = config('app.domain', env('APP_DOMAIN', 'marsstation.dev'));

Route::domain("{$adminSubdomain}.{$domain}")->group(function () {
    Route::middleware(['web', AdminGuest::class])->group(function () {
        Route::get('login', AdminLogin::class)->name('admin.login');
    });

    Route::middleware(['web', AdminAuthenticated::class])->group(function () {
        Route::get('/', Dashboard::class)->name('admin.dashboard');

        Route::prefix('agreements')->group(function () {
            Route::get('/', AgreementIndex::class)->name('admin.agreements.index');
            Route::get('{agreement}/edit', AgreementEdit::class)->name('admin.agreements.edit');
            Route::get('{agreement}', AgreementShow::class)->name('admin.agreements.show');
        });

        Route::get('payments', PaymentHistory::class)->name('admin.payments.index');

        Route::get('attachments/{attachment}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])
            ->name('admin.attachments.download');

        Route::get('get-services', \App\Livewire\Admin\GetServices\GetServiceIndex::class)->name('admin.get-services.index');
        Route::get('get-services/export', [\App\Http\Controllers\GetServiceExportController::class, 'export'])->name('admin.get-services.export');
        Route::get('services', \App\Livewire\Admin\Services\ServiceIndex::class)->name('admin.services.index');
        Route::get('reviews', \App\Livewire\Admin\Reviews\ReviewIndex::class)->name('admin.reviews.index');
        Route::get('complaints', \App\Livewire\Admin\Complaints\ComplaintIndex::class)->name('admin.complaints.index');
        Route::get('complaints/export', [\App\Http\Controllers\ComplaintExportController::class, 'export'])->name('admin.complaints.export');
        Route::get('queries', \App\Livewire\Admin\Queries\QueryIndex::class)->name('admin.queries.index');
        Route::get('queries/export', [\App\Http\Controllers\QueryExportController::class, 'export'])->name('admin.queries.export');
    });

    Route::post('logout', function () {
        auth('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('admin.login');
    })->middleware('web')->name('admin.logout');
});
