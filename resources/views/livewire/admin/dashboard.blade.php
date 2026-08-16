<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Overview of agreements, payments and pending items.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $cards = [
                ['label' => 'Total Agreements', 'value' => number_format($totalAgreements), 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z', 'color' => 'bg-indigo-50 text-indigo-700'],
                ['label' => 'Active Subscriptions', 'value' => number_format($activeSubscriptions), 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-emerald-50 text-emerald-700'],
                ['label' => 'Total Payments', 'value' => $totalPayments, 'icon' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z', 'color' => 'bg-blue-50 text-blue-700'],
                ['label' => 'Pending Signatures', 'value' => number_format($pendingSignatures), 'icon' => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10', 'color' => 'bg-amber-50 text-amber-700'],
                ['label' => 'Pending Get Services', 'value' => number_format($pendingGetServices), 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'bg-purple-50 text-purple-700'],
                ['label' => 'Pending Reviews', 'value' => number_format($pendingReviews), 'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z', 'color' => 'bg-pink-50 text-pink-700'],
                ['label' => 'Pending Complaints', 'value' => number_format($pendingComplaints), 'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z', 'color' => 'bg-red-50 text-red-700'],
                ['label' => 'Pending Queries', 'value' => number_format($pendingQueries), 'icon' => 'M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z', 'color' => 'bg-cyan-50 text-cyan-700'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">{{ $card['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $card['value'] }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $card['color'] }}">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-1">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">Recent Agreements</h2>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($recentAgreements as $agreement)
                    <li class="px-5 py-3">
                        <a href="{{ route('admin.agreements.show', $agreement) }}" class="block hover:bg-slate-50">
                            <div class="flex items-center justify-between gap-3">
                                <p class="truncate text-sm font-medium text-slate-900">{{ $agreement->title }}</p>
                                <x-badge :color="$agreement->status->value === 'signed' ? 'green' : ($agreement->status->value === 'expired' || $agreement->status->value === 'terminated' ? 'red' : 'blue')">
                                    {{ $agreement->status->label() }}
                                </x-badge>
                            </div>
                            <p class="mt-0.5 text-xs text-slate-500">
                                {{ $agreement->agreement_number }} &middot; {{ $agreement->client_name }} &middot; {{ $agreement->created_at->diffForHumans() }}
                            </p>
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-slate-400">No agreements yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-1">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">Recent Payments</h2>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($recentPayments as $payment)
                    <li class="px-5 py-3">
                        <div class="flex items-center justify-between gap-3">
                            <p class="truncate text-sm font-medium text-slate-900">{{ $payment->agreement?->title ?? 'Unknown agreement' }}</p>
                            <x-badge :color="$payment->status->value === 'succeeded' ? 'green' : ($payment->status->value === 'failed' ? 'red' : 'amber')">
                                {{ $payment->status->label() }}
                            </x-badge>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-500">
                            {{ $payment->formattedAmount() }} &middot; {{ $payment->created_at->diffForHumans() }}
                        </p>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-slate-400">No payments yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-1">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">Recent Get Services</h2>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($recentGetServices as $request)
                    <li class="px-5 py-3">
                        <div class="flex items-center justify-between gap-3">
                            <p class="truncate text-sm font-medium text-slate-900">{{ $request->full_name }}</p>
                            <x-badge :color="$request->is_read ? 'gray' : 'indigo'" :dot="! $request->is_read">
                                {{ $request->is_read ? 'Read' : 'Unread' }}
                            </x-badge>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-500">
                            {{ $request->email }} &middot; {{ $request->created_at->diffForHumans() }}
                        </p>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-slate-400">No requests yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
