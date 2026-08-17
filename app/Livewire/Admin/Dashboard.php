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
    public array $stats = [];

    public array $trends = [];

    public function render()
    {
        $since = now()->subDays(30);
        $previousStart = now()->subDays(60);

        $countSince = static function ($query) use ($since) {
            return (clone $query)->where('created_at', '>=', $since)->count();
        };

        $countPrevious = static function ($query) use ($previousStart, $since) {
            return (clone $query)
                ->where('created_at', '>=', $previousStart)
                ->where('created_at', '<', $since)
                ->count();
        };

        $totalAgreements = Agreement::query();
        $activeSubscriptions = AgreementSubscription::query()->whereIn('status', ['active', 'trialing', 'past_due']);
        $pendingSignatures = Agreement::query()->where('status', 'pending')->where('is_archived', false);
        $pendingGetServices = GetServiceRequest::query()->where('is_read', false);
        $pendingReviews = Review::query()->where('status', 'pending');
        $pendingComplaints = Complaint::query()->where('is_read', false);
        $pendingQueries = Query::query()->where('is_read', false);

        $paymentsSince = (int) Payment::query()
            ->where('status', 'succeeded')
            ->where('created_at', '>=', $since)
            ->sum('amount_pence');
        $paymentsPrevious = (int) Payment::query()
            ->where('status', 'succeeded')
            ->where('created_at', '>=', $previousStart)
            ->where('created_at', '<', $since)
            ->sum('amount_pence');

        $this->stats = [
            'totalAgreements' => $totalAgreements->count(),
            'activeSubscriptions' => $activeSubscriptions->count(),
            'totalPayments' => Money::format((int) Payment::query()
                ->where('status', 'succeeded')
                ->sum('amount_pence')),
            'pendingSignatures' => $pendingSignatures->count(),
            'pendingGetServices' => $pendingGetServices->count(),
            'pendingReviews' => $pendingReviews->count(),
            'pendingComplaints' => $pendingComplaints->count(),
            'pendingQueries' => $pendingQueries->count(),
        ];

        $this->trends = [
            'totalAgreements' => $this->trend($countSince($totalAgreements), $countPrevious($totalAgreements)),
            'activeSubscriptions' => $this->trend($countSince($activeSubscriptions), $countPrevious($activeSubscriptions)),
            'totalPayments' => $this->trend($paymentsSince, $paymentsPrevious),
            'pendingSignatures' => $this->trend($countSince($pendingSignatures), $countPrevious($pendingSignatures)),
            'pendingGetServices' => $this->trend($countSince($pendingGetServices), $countPrevious($pendingGetServices)),
            'pendingReviews' => $this->trend($countSince($pendingReviews), $countPrevious($pendingReviews)),
            'pendingComplaints' => $this->trend($countSince($pendingComplaints), $countPrevious($pendingComplaints)),
            'pendingQueries' => $this->trend($countSince($pendingQueries), $countPrevious($pendingQueries)),
        ];

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
            'recentAgreements' => $recentAgreements,
            'recentPayments' => $recentPayments,
            'recentGetServices' => $recentGetServices,
        ])->title('Dashboard');
    }

    private function trend(int $current, int $previous): array
    {
        $pct = $previous === 0
            ? ($current === 0 ? 0 : 100)
            : (int) round((($current - $previous) / $previous) * 100);

        return [
            'pct' => $pct,
            'up' => $pct >= 0,
        ];
    }
}
