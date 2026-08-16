<?php

namespace App\Livewire\Admin;

use App\Models\Agreement;
use App\Models\AgreementSubscription;
use App\Models\Complaint;
use App\Models\GetServiceRequest;
use App\Models\Payment;
use App\Models\Query;
use App\Models\Review;
use App\Support\Money;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        $totalAgreements = Agreement::query()->count();
        $activeSubscriptions = AgreementSubscription::query()
            ->whereIn('status', ['active', 'trialing', 'past_due'])
            ->count();
        $totalPaymentsPence = (int) Payment::query()
            ->where('status', 'succeeded')
            ->sum('amount_pence');
        $pendingSignatures = Agreement::query()
            ->where('status', 'pending')
            ->where('is_archived', false)
            ->count();
        $pendingGetServices = GetServiceRequest::query()
            ->where('is_read', false)
            ->count();
        $pendingReviews = Review::query()
            ->where('status', 'pending')
            ->count();
        $pendingComplaints = Complaint::query()
            ->where('is_read', false)
            ->count();
        $pendingQueries = Query::query()
            ->where('is_read', false)
            ->count();

        $recentAgreements = Agreement::query()
            ->with('currentVersion')
            ->where('is_archived', false)
            ->latest()
            ->limit(5)
            ->get();

        $recentPayments = Payment::query()
            ->with(['agreement' => fn ($q) => $q->select('id', 'agreement_number', 'title', 'client_name')])
            ->latest()
            ->limit(5)
            ->get();

        $recentGetServices = GetServiceRequest::query()
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'totalAgreements' => $totalAgreements,
            'activeSubscriptions' => $activeSubscriptions,
            'totalPayments' => Money::format($totalPaymentsPence),
            'pendingSignatures' => $pendingSignatures,
            'pendingGetServices' => $pendingGetServices,
            'pendingReviews' => $pendingReviews,
            'pendingComplaints' => $pendingComplaints,
            'pendingQueries' => $pendingQueries,
            'recentAgreements' => $recentAgreements,
            'recentPayments' => $recentPayments,
            'recentGetServices' => $recentGetServices,
        ])->title('Dashboard');
    }
}
