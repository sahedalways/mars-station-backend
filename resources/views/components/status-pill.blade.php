@props([
    'status' => 'pending',
])

@php
    $map = [
        'pending'     => ['bg' => 'bg-amber-500/15', 'text' => 'text-amber-300', 'ring' => 'ring-amber-500/30', 'label' => 'Pending'],
        'signed'      => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30', 'label' => 'Signed'],
        'in_progress' => ['bg' => 'bg-blue-500/15', 'text' => 'text-blue-300', 'ring' => 'ring-blue-500/30', 'label' => 'In Progress'],
        'completed'   => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30', 'label' => 'Completed'],
        'succeeded'   => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30', 'label' => 'Paid'],
        'paid'        => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30', 'label' => 'Paid'],
        'failed'      => ['bg' => 'bg-red-500/15', 'text' => 'text-red-300', 'ring' => 'ring-red-500/30', 'label' => 'Failed'],
        'expired'     => ['bg' => 'bg-red-500/15', 'text' => 'text-red-300', 'ring' => 'ring-red-500/30', 'label' => 'Expired'],
        'terminated'  => ['bg' => 'bg-red-500/15', 'text' => 'text-red-300', 'ring' => 'ring-red-500/30', 'label' => 'Terminated'],
        'processing'  => ['bg' => 'bg-blue-500/15', 'text' => 'text-blue-300', 'ring' => 'ring-blue-500/30', 'label' => 'Processing'],
        'read'        => ['bg' => 'bg-slate-500/15', 'text' => 'text-slate-300', 'ring' => 'ring-slate-500/30', 'label' => 'Read'],
        'unread'      => ['bg' => 'bg-purple-500/15', 'text' => 'text-purple-300', 'ring' => 'ring-purple-500/30', 'label' => 'Unread'],
    ];
    $s = $map[$status] ?? $map['pending'];
@endphp

<span class="inline-flex items-center rounded-md px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $s['bg'] }} {{ $s['text'] }} {{ $s['ring'] }}">
    {{ $s['label'] }}
</span>
