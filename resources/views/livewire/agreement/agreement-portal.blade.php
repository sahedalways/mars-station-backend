<div class="space-y-6" {{ $paymentPending ? 'wire:poll.3s=checkPaymentStatus' : '' }}>
    @if ($message)
        <x-alert type="success" :message="$message"/>
    @endif

    @if ($error)
        <x-alert type="error" :message="$error"/>
    @endif

    @if ($step === 'otp')
        <style>body { background: #0a0518 !important; }</style>
        <div class="fixed inset-0 -mx-4 -my-8 flex items-center overflow-hidden px-4 pb-8 sm:-mx-6 sm:px-6" style="background: radial-gradient(ellipse at 20% 60%, rgba(139,92,246,0.15) 0%, transparent 50%), radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%), linear-gradient(180deg, #0a0518 0%, #130826 50%, #0a0518 100%);">
            <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(1px 1px at 10% 20%, rgba(255,255,255,0.6), transparent), radial-gradient(1px 1px at 30% 70%, rgba(255,255,255,0.4), transparent), radial-gradient(1px 1px at 50% 40%, rgba(255,255,255,0.5), transparent), radial-gradient(1px 1px at 70% 80%, rgba(255,255,255,0.3), transparent), radial-gradient(1px 1px at 85% 15%, rgba(255,255,255,0.6), transparent), radial-gradient(1px 1px at 15% 90%, rgba(255,255,255,0.4), transparent), radial-gradient(1px 1px at 60% 10%, rgba(255,255,255,0.5), transparent), radial-gradient(1px 1px at 40% 55%, rgba(255,255,255,0.3), transparent), radial-gradient(1px 1px at 90% 60%, rgba(255,255,255,0.5), transparent);"></div>
            <div class="absolute left-[-300px] top-[30%] h-[700px] w-[700px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(139,92,246,0.25) 0%, rgba(139,92,246,0.05) 40%, transparent 70%);"></div>

            <div class="relative z-10 mx-auto max-w-md w-full">
                <div class="mb-20 text-center">
                    <img src="{{ asset('logo.png') }}" alt="Mars Station" class="mx-auto h-12 w-auto" style="filter: brightness(2) drop-shadow(0 0 8px rgba(168,85,247,0.6));">
                    <div class="mt-3 text-xs font-medium tracking-widest text-slate-300 uppercase mb-5 ">Client Portal</div>
                </div>

                <div class="rounded-2xl border border-purple-500/20 bg-slate-900/60 p-8 text-center shadow-2xl shadow-purple-900/20 backdrop-blur-xl">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl border border-purple-500/20 bg-gradient-to-br from-purple-500/20 to-violet-500/10 shadow-lg shadow-purple-500/10">
                        <svg class="h-8 w-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                        </svg>
                    </div>
                    <h2 class="mb-2 text-xl font-bold text-white">Verify your email</h2>
                    <p class="mb-1 text-sm text-slate-400">This agreement is protected. Enter the 6-digit verification code we sent to:</p>
                    <p class="mb-8 text-sm font-semibold text-purple-300">{{ $otpEmail }}</p>
                    <div class="mx-auto mb-6 max-w-[280px]">
                        <input type="text" inputmode="numeric" maxlength="6" wire:model="otp" placeholder="000000"
                            class="block w-full rounded-xl border border-purple-500/20 bg-slate-800/80 px-4 py-3.5 text-center text-2xl font-bold tracking-[0.35em] text-white outline-none transition placeholder:text-slate-600 placeholder:tracking-[0.35em] focus:border-purple-500/50 focus:ring-2 focus:ring-purple-500/20"/>
                    </div>
                    <x-button wire:click="verifyOtp" wire:loading.attr="disabled" wire:target="verifyOtp" class="w-full justify-center rounded-xl py-3.5 text-sm">
                        Verify &amp; Continue
                    </x-button>
                    <div class="mt-5 flex items-center justify-center gap-1 text-sm text-slate-500">
                        <span>Didn't receive the code?</span>
                        <button type="button" wire:click="requestOtp"
                            class="font-medium text-purple-400 transition hover:text-purple-300 hover:underline">Resend</button>
                    </div>
                </div>
            </div>
        </div>

        @if ($message || $error)
            <div class="fixed inset-0 z-[999] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
                <div class="w-full max-w-sm rounded-2xl border border-purple-500/20 bg-slate-900/90 p-8 text-center shadow-2xl shadow-purple-900/30 backdrop-blur-xl">
                    @if ($error)
                        <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full border border-red-500/20 bg-red-500/10">
                            <svg class="h-7 w-7 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        </div>
                        <h3 class="mb-2 text-lg font-bold text-white">Verification Failed</h3>
                        <p class="mb-6 text-sm text-slate-400">{{ $error }}</p>
                        <button type="button" wire:click="$set('error', '')" wire:loading.attr="disabled"
                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-200 ring-1 ring-inset ring-slate-600 shadow-sm transition hover:bg-slate-700 hover:text-white focus-visible:ring-slate-400 disabled:cursor-not-allowed disabled:opacity-60">
                            Try Again
                        </button>
                    @elseif ($message)
                        <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full border border-emerald-500/20 bg-emerald-500/10">
                            <svg class="h-7 w-7 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="mb-2 text-lg font-bold text-white">Code Sent</h3>
                        <p class="mb-6 text-sm text-slate-400">{{ $message }}</p>
                        <button type="button" wire:click="$set('message', '')" wire:loading.attr="disabled"
                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:ring-indigo-600 disabled:cursor-not-allowed disabled:opacity-60">
                            Got it
                        </button>
                    @endif
                </div>
            </div>
        @endif
    @endif

    @if ($step === 'view' || $step === 'sign')
        <style>body { background: #0a0518 !important; }</style>
        <div class="fixed inset-0 overflow-y-auto px-4 sm:px-6" style="background: radial-gradient(ellipse at 20% 60%, rgba(139,92,246,0.15) 0%, transparent 50%), radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%), radial-gradient(ellipse at 90% 90%, rgba(168,85,247,0.1) 0%, transparent 50%), linear-gradient(180deg, #0a0518 0%, #130826 50%, #0a0518 100%);">
            <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(1px 1px at 10% 20%, rgba(255,255,255,0.6), transparent), radial-gradient(1px 1px at 30% 70%, rgba(255,255,255,0.4), transparent), radial-gradient(1px 1px at 50% 40%, rgba(255,255,255,0.5), transparent), radial-gradient(1px 1px at 70% 80%, rgba(255,255,255,0.3), transparent), radial-gradient(1px 1px at 85% 15%, rgba(255,255,255,0.6), transparent), radial-gradient(1px 1px at 15% 90%, rgba(255,255,255,0.4), transparent), radial-gradient(1px 1px at 60% 10%, rgba(255,255,255,0.5), transparent), radial-gradient(1px 1px at 40% 55%, rgba(255,255,255,0.3), transparent), radial-gradient(1px 1px at 90% 60%, rgba(255,255,255,0.5), transparent);"></div>
            <div class="absolute left-[-300px] top-[30%] h-[700px] w-[700px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(139,92,246,0.25) 0%, rgba(139,92,246,0.05) 40%, transparent 70%);"></div>

        <div class="relative z-10 mx-auto max-w-3xl space-y-6 pt-16 pb-10">
            <div class="mb-12 text-center mt-5">
                <img src="{{ asset('logo.png') }}" alt="Mars Station" class="mx-auto h-12 w-auto" style="filter: brightness(2) drop-shadow(0 0 8px rgba(168,85,247,0.6));">
                <div class="mt-3 text-xs font-medium tracking-widest text-slate-300 uppercase">Client Portal</div>
            </div>

            @if ($paymentPending)
                <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 px-5 py-4 flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-500/15">
                        <svg class="h-5 w-5 animate-spin text-amber-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-amber-300">
                            @if ($paymentType === 'subscription')
                                Payment submitted. We are activating your subscription and confirming the payment...
                            @elseif ($paymentType === 'milestone')
                                Payment submitted. We are confirming your milestone payment...
                            @elseif ($paymentType === 'full')
                                Payment submitted. We are confirming your full payment...
                            @else
                                Payment submitted. We are confirming...
                            @endif
                        </p>
                        <p class="text-xs text-amber-400/70">This page will update automatically once the payment is confirmed.</p>
                    </div>
                </div>
            @endif

            @if ($error && ! $paymentPending)
                <div class="rounded-xl border border-red-500/30 bg-red-500/10 px-5 py-4 flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-500/15">
                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-red-300">{{ $error }}</p>
                    </div>
                    <button type="button" wire:click="$set('error', '')" class="text-xs text-red-400 hover:text-red-300 underline">Dismiss</button>
                </div>
            @endif

            @if ($agreement->isExpired())
                <x-alert type="error" message="This agreement has expired and can no longer be signed."/>
            @endif

            <style>
                .ap-doc-wrapper {
                    background: #ffffff;
                    border-radius: 16px;
                    overflow: hidden;
                    border: 1px solid #e9e5f5;
                    position: relative;
                    box-shadow: 0 25px 80px rgba(76,29,149,0.10), 0 8px 24px rgba(0,0,0,0.06);
                    border-top: 4px solid #7c3aed;
                }
                .ap-doc-wrapper::before {
                    content: '';
                    position: absolute;
                    top: 18%;
                    right: 2%;
                    transform: none;
                    width: 420px;
                    height: 420px;
                    background: url('{{ asset("watermark-logo.png") }}') no-repeat center center;
                    background-size: contain;
                    opacity: 0.04;
                    pointer-events: none;
                    z-index: 1;
                }
                .ap-doc-wrapper > * { position: relative; z-index: 2; }
                .ap-header {
                    padding: 28px 40px 24px;
                    border-bottom: 3px solid #4c1d95;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 24px;
                    background: linear-gradient(180deg, #f9f7ff 0%, #ffffff 100%);
                }
                .ap-brand img { display: block; max-width: 300px; height: auto; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.05)); }
                .ap-meta {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 10px 22px;
                    min-width: 320px;
                    max-width: 420px;
                }
                .ap-meta-row { display: flex; gap: 10px; align-items: flex-start; }
                .ap-meta-icon {
                    width: 30px; height: 30px; background: linear-gradient(135deg, #ede9fe, #ddd6fe); border-radius: 8px;
                    display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #4c1d95;
                    box-shadow: 0 1px 3px rgba(76,29,149,0.1);
                }
                .ap-meta-icon svg { width: 14px; height: 14px; }
                .ap-meta-label { font-size: 10px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px; }
                .ap-meta-value { font-size: 12px; color: #111827; font-weight: 600; }
                .ap-status {
                    display: inline-flex; align-items: center; gap: 4px; padding: 3px 12px; border-radius: 999px;
                    font-size: 11px; font-weight: 700; letter-spacing: 0.3px;
                }
                .ap-status-signed { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #166534; border: 1px solid #86efac; box-shadow: 0 1px 3px rgba(22,101,52,0.1); }
                .ap-status-pending { background: linear-gradient(135deg, #fef9c3, #fde68a); color: #854d0e; border: 1px solid #fcd34d; box-shadow: 0 1px 3px rgba(133,77,14,0.1); }
                .ap-status-expired { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #fca5a5; box-shadow: 0 1px 3px rgba(153,27,27,0.1); }
                .ap-status-default { background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #374151; border: 1px solid #d1d5db; }
                .ap-body { padding: 36px 40px 0; }
                .ap-section-label {
                    font-size: 13px; letter-spacing: 2px; text-transform: uppercase; color: #4c1d95;
                    font-weight: 800; border-left: 4px solid #4c1d95; padding-left: 12px;
                    margin: 32px 0 14px; font-family: 'Inter', sans-serif;
                }
                .ap-intro { font-size: 13px; line-height: 1.75; color: #374151; margin: 0 0 8px; }
                .ap-sub-intro { font-size: 12px; color: #6b7280; font-style: italic; margin: 0 0 20px; }
                .ap-block { margin-bottom: 22px; }
                .ap-block h3 { font-size: 12px; color: #4c1d95; font-weight: 800; margin: 0 0 8px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.3px; }
                .ap-block h3 .num {
                    display: inline-flex; align-items: center; justify-content: center;
                    width: 26px; height: 26px; background: linear-gradient(135deg, #7c3aed, #4c1d95); color: #fff;
                    border-radius: 50%; font-size: 10px; font-weight: 800;
                    box-shadow: 0 2px 6px rgba(76,29,149,0.25);
                }
                .ap-block p { font-size: 12.5px; line-height: 1.7; color: #374151; margin: 0 0 6px; }
                .ap-content-area { font-size: 12.5px; line-height: 1.7; color: #000000; }
                .ap-content-area * { color: #000000 !important; }
                .ap-content-area h1, .ap-content-area h2, .ap-content-area h3, .ap-content-area h4 { color: #000000 !important; margin: 14px 0 8px; font-weight: 700; }
                .ap-content-area strong, .ap-content-area b { font-weight: 700; }
                .ap-content-area ul, .ap-content-area ol { padding-left: 20px; margin: 8px 0; }
                .ap-content-area li { margin-bottom: 4px; }
                .ap-content-area p { margin: 0 0 6px; }
                .ap-content-area td, .ap-content-area th, .ap-content-area span,
                .ap-content-area a, .ap-content-area em, .ap-content-area i { color: #000000 !important; }
                .ap-acceptance {
                    background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #f5f3ff 100%);
                    border: 2px solid #c4b5fd; border-radius: 14px; padding: 28px 32px;
                    margin: 28px 40px 0; display: flex; gap: 24px; align-items: flex-start;
                    box-shadow: 0 4px 16px rgba(76,29,149,0.08), inset 0 2px 12px rgba(76,29,149,0.04);
                    position: relative; overflow: hidden;
                }
                .ap-acceptance::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    right: 20px;
                    transform: translateY(-50%);
                    width: 180px; height: 180px;
                    background: url('{{ asset("watermark-logo.png") }}') no-repeat center center;
                    background-size: contain;
                    opacity: 0.07; pointer-events: none;
                }
                .ap-acceptance > * { position: relative; z-index: 1; }
                .ap-acceptance-icon {
                    width: 56px; height: 56px; background: linear-gradient(135deg, #7c3aed, #4c1d95);
                    border: none; border-radius: 14px; display: flex; align-items: center; justify-content: center;
                    flex-shrink: 0; color: #fff; box-shadow: 0 6px 20px rgba(76,29,149,0.3);
                }
                .ap-acceptance-icon svg { width: 26px; height: 26px; }
                .ap-acceptance h4 { font-size: 15px; color: #1e1b4b; margin: 0 0 14px; font-weight: 800; letter-spacing: 0.8px; }
                .ap-sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 28px; }
                .ap-sig-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; font-weight: 600; margin-bottom: 2px; }
                .ap-sig-value { font-size: 12px; color: #111827; font-weight: 600; }
                .ap-sig-note {
                    margin-top: 14px; padding-top: 12px; border-top: 1px dashed #c4b5fd;
                    font-size: 10.5px; color: #4c1d95; font-weight: 600;
                    display: flex; align-items: center; gap: 6px;
                }
                @media (max-width: 640px) {
                    .ap-header { flex-direction: column; padding: 24px 16px 20px; gap: 0; }
                    .ap-brand { text-align: center; margin-bottom: 20px; }
                    .ap-brand img { max-width: 160px; }
                    .ap-meta { min-width: 0; max-width: 100%; width: 100%; grid-template-columns: 1fr; gap: 10px; padding-top: 18px; border-top: 1px solid #e9e5f5; }
                    .ap-acceptance { flex-direction: column; align-items: center; padding: 20px 16px; margin: 20px 12px 0; gap: 14px; }
                    .ap-acceptance-icon { width: 44px; height: 44px; border-radius: 10px; }
                    .ap-acceptance-icon svg { width: 20px; height: 20px; }
                    .ap-acceptance h4 { font-size: 13px; text-align: center; margin-bottom: 10px; }
                    .ap-sig-grid { grid-template-columns: 1fr; gap: 10px; width: 100%; }
                    .ap-sig-note { font-size: 10px; flex-wrap: wrap; }
                    .ap-footer-inner { padding: 24px 16px 0; }
                    .ap-footer-strip { gap: 12px; font-size: 9.5px; }
                    .ap-footer-bottom { flex-direction: column; gap: 10px; padding: 14px 16px; text-align: center; }
                    .ap-footer-links { justify-content: center; flex-wrap: wrap; gap: 12px; }
                    .ap-footer-social { justify-content: center; }
                }
                .ap-footer {
                    background: linear-gradient(180deg, #1e1b4b 0%, #0f0524 100%); color: #fff; padding: 0; margin-top: 32px;
                    position: relative; overflow: hidden;
                }
                .ap-footer::before {
                    content: '';
                    position: absolute;
                    top: -30px; right: -30px;
                    width: 180px; height: 180px;
                    background: url('{{ asset("watermark-logo.png") }}') no-repeat center center;
                    background-size: contain;
                    opacity: 0.04; pointer-events: none;
                }
                .ap-footer > * { position: relative; z-index: 1; }
                .ap-footer-inner { padding: 32px 40px 0; }
                .ap-footer-logo { text-align: center; margin-bottom: 20px; width: 100%; }
                .ap-footer-logo img { display: block; margin: 0 auto; height: 42px; width: auto; filter: brightness(0) invert(1); opacity: 0.85; }
                .ap-footer-divider {
                    height: 1px; margin: 0 0 18px;
                    background: linear-gradient(90deg, transparent, rgba(167,139,250,0.25), transparent);
                }
                .ap-footer-strip {
                    display: flex; justify-content: center; gap: 28px; flex-wrap: wrap;
                    font-size: 10.5px; color: #c4b5fd;
                }
                .ap-footer-strip strong { color: #e9d5ff; font-weight: 700; }
                .ap-footer-disclaimer {
                    margin-top: 18px; padding: 14px 20px;
                    background: rgba(255,255,255,0.03); border-radius: 8px;
                    font-size: 9px; color: #8b7fc7; line-height: 1.65; text-align: center;
                }
                .ap-footer-bottom {
                    margin-top: 18px; padding: 14px 40px;
                    background: rgba(0,0,0,0.25);
                    display: flex; justify-content: space-between; align-items: center;
                    font-size: 9px; color: #7c6daa;
                }
                .ap-footer-bottom a { color: #a78bfa; text-decoration: none; transition: color 0.2s; }
                .ap-footer-bottom a:hover { color: #c4b5fd; }
                .ap-footer-links { display: flex; gap: 18px; align-items: center; }
                .ap-footer-social { display: flex; gap: 10px; align-items: center; }
                .ap-footer-social a {
                    display: inline-flex; align-items: center; justify-content: center;
                    width: 24px; height: 24px; border-radius: 50%;
                    background: rgba(167,139,250,0.12); color: #a78bfa;
                    transition: all 0.2s;
                }
                .ap-footer-social a:hover { background: rgba(167,139,250,0.25); color: #c4b5fd; }
                .ap-footer-social a svg { width: 12px; height: 12px; }
                .ap-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid #e9e5f5; }
                .ap-table { width: 100%; border-collapse: collapse; font-size: 12px; }
                .ap-table thead { background: linear-gradient(135deg, #f5f3ff, #ede9fe); }
                .ap-table th { padding: 10px 16px; text-align: left; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #4c1d95; border-bottom: 2px solid #e9e5f5; }
                .ap-table td { padding: 12px 16px; color: #374151; border-bottom: 1px solid #f3f4f6; }
                .ap-table tbody tr:last-child td { border-bottom: none; }
                .ap-table tbody tr:hover { background: #faf9ff; }
            </style>

            <div class="ap-doc-wrapper">
                <div class="ap-header">
                    <div class="ap-brand">
                        <img src="{{ asset('logo.png') }}" alt="Mars Station">
                    </div>
                    <div class="ap-meta">
                        <div class="ap-meta-row">
                            <div class="ap-meta-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                            </div>
                            <div>
                                <div class="ap-meta-label">Agreement No.</div>
                                <div class="ap-meta-value">{{ $agreement->agreement_number }}</div>
                            </div>
                        </div>
                        <div class="ap-meta-row">
                            <div class="ap-meta-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            </div>
                            <div>
                                <div class="ap-meta-label">Agreement Date</div>
                                <div class="ap-meta-value">{{ $agreement->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="ap-meta-row">
                            <div class="ap-meta-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <div class="ap-meta-label">Client Name</div>
                                <div class="ap-meta-value">{{ $version->client_name }}</div>
                            </div>
                        </div>
                        <div class="ap-meta-row">
                            <div class="ap-meta-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            </div>
                            <div>
                                <div class="ap-meta-label">Client Email</div>
                                <div class="ap-meta-value">{{ $version->client_email }}</div>
                            </div>
                        </div>
                        <div class="ap-meta-row">
                            <div class="ap-meta-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <div class="ap-meta-label">Last Updated</div>
                                <div class="ap-meta-value">{{ $agreement->updated_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="ap-meta-row">
                            <div class="ap-meta-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 9 17 15 17 15 11"/><path d="M9 11V7a3 3 0 0 1 3-3h0a3 3 0 0 1 3 3v4"/></svg>
                            </div>
                            <div>
                                <div class="ap-meta-label">Document Status</div>
                                <div class="ap-meta-value">
                                    @php
                                        $statusVal = $version->status ?? 'pending';
                                        $statusClass = match($statusVal) {
                                            'signed' => 'ap-status-signed',
                                            'pending' => 'ap-status-pending',
                                            'expired' => 'ap-status-expired',
                                            default => 'ap-status-default',
                                        };
                                        $statusLabel = match($statusVal) {
                                            'signed' => 'Signed',
                                            'pending' => 'Pending',
                                            'expired' => 'Expired',
                                            default => ucfirst($statusVal),
                                        };
                                    @endphp
                                    <span class="ap-status {{ $statusClass }}">{{ $statusLabel }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ap-body">
                    <h2 class="ap-section-label">AGREEMENT</h2>
                    <p class="ap-intro">
                        This Agreement is made and entered into on <strong>{{ $agreement->created_at->format('F d, Y') }}</strong>, by and between <strong>Mars Station (the "Company")</strong> and <strong>{{ $version->client_name }} (the "Client")</strong>.
                    </p>
                    <p class="ap-sub-intro">Both parties agree to the terms and conditions outlined below.</p>

                    <div class="ap-block">
                        <h3><span class="num">1</span> SERVICES</h3>
                        <p>The Company agrees to provide the services described in this Agreement (the "Services") to the Client in accordance with the terms and conditions set forth herein.</p>
                    </div>

                    <div class="ap-block">
                        <h3><span class="num">2</span> SCOPE OF WORK</h3>
                        <p>The Company will work with the goal of delivering high-quality results. The specific deliverables, timelines, and milestones are outlined below.</p>
                    </div>

                    <div class="ap-block">
                        <h3><span class="num">3</span> SERVICES &amp; DELIVERABLES</h3>
                        @if ($version->content)
                            <div class="ap-content-area mt-2">{!! $version->content !!}</div>
                        @else
                            <p>No services or deliverables defined yet.</p>
                        @endif
                    </div>

                    @if ($version->payment_config)
                        @php
                            $payConfig = $version->payment_config;
                            $payType = $agreement->payment_type->value;
                        @endphp
                        <div class="ap-block">
                            <h3><span class="num">{{ $version->content ? '4' : '3' }}</span> PAYMENT TERMS</h3>
                            <p>Payment terms and schedule are outlined in the payment plan associated with this Agreement. All payments are non-refundable except as stated in our Payment &amp; Refund Policy.</p>

                            @if ($payType === 'full')
                                <p class="mt-2" style="font-weight:700; color:#4c1d95;">
                                    Total: {{ \App\Support\Money::format($payConfig['amount_pence']) }}
                                    @if (!empty($payConfig['title']))
                                        &mdash; {{ $payConfig['title'] }}
                                    @endif
                                </p>
                            @elseif ($payType === 'milestone')
                                <div class="ap-table-wrap" style="margin-top:12px;">
                                    <table class="ap-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Milestone</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payConfig['milestones'] ?? [] as $ms)
                                                <tr>
                                                    <td style="color:#4c1d95; font-weight:700;">{{ $ms['order_index'] ?? $loop->iteration }}</td>
                                                    <td style="font-weight:600; color:#1a1a2e;">{{ $ms['title'] }}</td>
                                                    <td>{{ $ms['description'] ?? '-' }}</td>
                                                    <td style="font-weight:600;">{{ \App\Support\Money::format($ms['amount_pence']) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif ($payType === 'subscription')
                                <p class="mt-2" style="font-weight:700; color:#4c1d95;">
                                    {{ \App\Support\Money::format($payConfig['amount_pence']) }}
                                    / {{ ucfirst($payConfig['frequency'] ?? 'monthly') }}
                                    @if (!empty($payConfig['title']))
                                        &mdash; {{ $payConfig['title'] }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="ap-acceptance">
                    <div class="ap-acceptance-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                    </div>
                    <div style="flex:1;">
                        <h4>CLIENT ACCEPTANCE</h4>
                        <div class="ap-sig-grid">
                            <div>
                                <div class="ap-sig-label">Client Name</div>
                                <div class="ap-sig-value">{{ $version->client_name }}</div>
                            </div>
                            <div>
                                <div class="ap-sig-label">Client Email</div>
                                <div class="ap-sig-value">{{ $version->client_email }}</div>
                            </div>
                            <div>
                                <div class="ap-sig-label">Signed on</div>
                                <div class="ap-sig-value">{{ $version->signed_at?->format('M d, Y') ?? 'Pending' }}</div>
                            </div>
                            <div>
                                <div class="ap-sig-label">Valid Until</div>
                                <div class="ap-sig-value">{{ $version->validity_date?->format('M d, Y') ?? 'Not set' }}</div>
                            </div>
                        </div>
                        <div class="ap-sig-note">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/></svg>
                            This agreement is valid from {{ $agreement->created_at->format('M d, Y') }}@if ($version->validity_date) to {{ $version->validity_date->format('M d, Y') }}@endif.
                        </div>
                        @if ($version->isSigned())
                            <div class="ap-sig-note" style="border-top: none; margin-top: 4px; padding-top: 0;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                                Electronically accepted and signed by the client.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="ap-footer">
                    <div class="ap-footer-inner">
                        <div class="ap-footer-logo">
                            <img src="{{ asset('logo.png') }}" alt="Mars Station">
                        </div>
                        <div class="ap-footer-divider"></div>
                        <div class="ap-footer-strip">
                            <div><strong>Agreement No.</strong> {{ $agreement->agreement_number }}</div>
                            <div><strong>Version</strong> v{{ $version->version }}</div>
                            <div><strong>Date</strong> {{ $agreement->created_at->format('M d, Y') }}</div>
                            <div><strong>Status</strong> {{ $statusLabel }}</div>
                            <div><strong>Page</strong> 1 of 1</div>
                        </div>
                        <div class="ap-footer-disclaimer">
                            This agreement is an official electronic document issued by Mars Station. Any unauthorized modification, or alteration may invalidate the document, please refer the official agreement available through the secure Mars Station link for verification.
                        </div>
                    </div>
                    <div class="ap-footer-bottom">
                        <div>&copy; {{ now()->year }} Mars Station. All rights reserved. Registered in United Kingdom.</div>
                        <div class="ap-footer-links">
                            <a href="https://marsstation.dev/privacy-policy">Privacy</a>
                            <a href="https://marsstation.dev/terms-conditions">Terms</a>
                            <a href="https://marsstation.dev/">Contact</a>
                            <div class="ap-footer-social">
                                <a href="https://facebook.com/marsstation" target="_blank" title="Facebook">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="https://linkedin.com/company/marsstation" target="_blank" title="LinkedIn">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                                <a href="https://twitter.com/marsstation" target="_blank" title="Twitter">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-col items-center gap-4 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                <x-button type="button" variant="secondary" wire:click="downloadPdf" wire:loading.attr="disabled" wire:target="downloadPdf">Download PDF</x-button>

                @if ($step === 'view')
                    @if ($version->isSigned())
                        <div class="flex items-center gap-3 sm:ml-auto">
                            <x-badge color="green">Signed {{ $version->signed_at->format('d M Y') }}</x-badge>
                            @if ($agreement->payment_type->value === 'none' || $agreement->isPaid())
                                <x-button wire:click="complete" wire:loading.attr="disabled" wire:target="complete">View Status</x-button>
                            @else
                                <x-button wire:click="goToPayment" wire:loading.attr="disabled" wire:target="goToPayment" :disabled="$paymentPending">Continue</x-button>
                            @endif
                        </div>
                    @elseif (!$agreement->isExpired())
                        <x-button wire:click="goToSign" wire:loading.attr="disabled" wire:target="goToSign">Sign Agreement</x-button>
                    @endif
                @endif
            </div>

            <p class="text-center text-xs text-slate-500 mb-5" style="margin-top: 40px;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. This is a secure document portal.
            </p>
        </div>
        </div>
    @endif

    @if ($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
            <div class="w-full max-w-md overflow-hidden rounded-2xl border border-purple-500/20 bg-slate-900 shadow-2xl shadow-purple-950/40 ring-1 ring-slate-700/50">
                <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/10">
                            <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-white">Payment</h2>
                            <p class="text-xs text-slate-400">Complete payment to proceed</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closePaymentModal" class="rounded-lg p-1 text-slate-400 hover:bg-slate-800 hover:text-slate-200 cursor-pointer" aria-label="Close">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-4">
                    @if ($error)
                        <div class="rounded-lg border border-red-500/20 bg-red-500/10 p-3 text-sm text-red-400">{{ $error }}</div>
                    @endif

                    @if ($agreement->payment_type->value === 'none')
                        <p class="text-sm text-slate-300">No payment is required.</p>
                        <div class="flex justify-end">
                            <x-button variant="success" wire:click="complete" wire:loading.attr="disabled" wire:target="complete">Finish</x-button>
                        </div>
                    @elseif ($agreement->isPaid())
                        <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-3 text-sm text-emerald-400">This agreement has been fully paid.</div>
                        <div class="flex justify-end">
                            <x-button variant="success" wire:click="complete" wire:loading.attr="disabled" wire:target="complete">Finish</x-button>
                        </div>
                    @elseif ($agreement->payment_type->value === 'milestone')
                        @php
                            $milestone = $agreement->nextMilestone();
                            $allMilestones = $agreement->milestones()->orderBy('order_index')->get();
                            $totalMilestones = $allMilestones->count();
                            $paidCount = $allMilestones->where('status', 'paid')->count();
                            $nextIndex = $milestone ? $allMilestones->search(fn($m) => $m->id === $milestone->id) + 1 : $totalMilestones + 1;
                        @endphp
                        <div class="rounded-xl border border-slate-700/50 bg-slate-800/50 p-4">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs text-slate-400">Milestone {{ $nextIndex }} of {{ $totalMilestones }}</p>
                                <p class="text-xs text-slate-500">{{ $paidCount }} of {{ $totalMilestones }} paid</p>
                            </div>
                            <div class="mb-3 h-1.5 w-full overflow-hidden rounded-full bg-slate-700">
                                <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 transition-all" style="width: {{ $totalMilestones > 0 ? ($paidCount / $totalMilestones) * 100 : 0 }}%"></div>
                            </div>
                            <p class="text-sm font-medium text-white">{{ $milestone?->title }}</p>
                            @if ($milestone?->description)
                                <p class="mt-1 text-xs text-slate-400">{{ $milestone->description }}</p>
                            @endif
                            <p class="mt-2 text-2xl font-bold text-purple-300">{{ $milestone?->formattedAmount() }}</p>
                        </div>

                        <div class="space-y-2">
                            @foreach ($allMilestones as $ms)
                                <div class="flex items-center gap-3 rounded-lg px-3 py-2 {{ $ms->status === 'paid' ? 'bg-emerald-500/5' : ($milestone && $ms->id === $milestone->id ? 'bg-indigo-500/10 ring-1 ring-indigo-500/20' : 'bg-slate-800/30') }}">
                                    @if ($ms->status === 'paid')
                                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20">
                                            <svg class="h-3 w-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        </div>
                                    @elseif ($milestone && $ms->id === $milestone->id)
                                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-500/20">
                                            <div class="h-2 w-2 rounded-full bg-indigo-400"></div>
                                        </div>
                                    @else
                                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-700/50">
                                            <div class="h-2 w-2 rounded-full bg-slate-600"></div>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium {{ $ms->status === 'paid' ? 'text-emerald-300' : ($milestone && $ms->id === $milestone->id ? 'text-white' : 'text-slate-400') }}">{{ $ms->title }}</p>
                                    </div>
                                    <span class="shrink-0 text-xs font-semibold {{ $ms->status === 'paid' ? 'text-emerald-400' : ($milestone && $ms->id === $milestone->id ? 'text-purple-300' : 'text-slate-500') }}">
                                        {{ $ms->formattedAmount() }}
                                    </span>
                                    @if ($ms->status === 'paid')
                                        <span class="shrink-0 rounded bg-emerald-500/15 px-1.5 py-0.5 text-[9px] font-bold text-emerald-300">PAID</span>
                                    @elseif ($milestone && $ms->id === $milestone->id)
                                        <span class="shrink-0 rounded bg-indigo-500/15 px-1.5 py-0.5 text-[9px] font-bold text-indigo-300">DUE</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-1">
                            <button type="button" wire:click="closePaymentModal" wire:loading.attr="disabled" wire:target="closePaymentModal" class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-200 ring-1 ring-inset ring-slate-600 shadow-sm transition hover:bg-slate-700 hover:text-white focus-visible:ring-slate-400 disabled:cursor-not-allowed disabled:opacity-60">
                                <span wire:loading.remove wire:target="closePaymentModal">Cancel</span>
                                <span wire:loading wire:target="closePaymentModal" class="inline-flex items-center gap-2"><svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg></span>
                            </button>
                            <button type="button"
                                x-data="{ paying: false }"
                                :disabled="paying || {{ $paymentPending ? 'true' : 'false' }}"
                                x-on:click="if (! paying) { paying = true; $wire.payNow().finally(() => paying = false) }"
                                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-indigo-600 px-16 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus-visible:ring-indigo-600 disabled:cursor-not-allowed disabled:opacity-60">
                                <span x-show="! paying">Pay Now</span>
                                <span x-cloak x-show="paying" class="inline-flex items-center gap-2"><svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg><span class="sr-only">Loading</span></span>
                            </button>
                        </div>
                    @elseif ($payType === 'subscription')
                        <div class="rounded-xl border border-slate-700/50 bg-slate-800/50 p-4">
                            <p class="text-xs text-slate-400">Subscription</p>
                            <p class="mt-2 text-2xl font-bold text-purple-300">{{ \App\Support\Money::format($payConfig['amount_pence']) }}<span class="text-base font-normal text-slate-400"> / {{ ucfirst($payConfig['frequency'] ?? 'monthly') }}</span></p>
                            <p class="mt-1 text-xs text-slate-500">Subscribe via secure checkout to activate your agreement.</p>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-1">
                            <button type="button" wire:click="closePaymentModal" wire:loading.attr="disabled" wire:target="closePaymentModal" class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-200 ring-1 ring-inset ring-slate-600 shadow-sm transition hover:bg-slate-700 hover:text-white focus-visible:ring-slate-400 disabled:cursor-not-allowed disabled:opacity-60">
                                <span wire:loading.remove wire:target="closePaymentModal">Cancel</span>
                                <span wire:loading wire:target="closePaymentModal" class="inline-flex items-center gap-2"><svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg></span>
                            </button>
                            <button type="button"
                                x-data="{ paying: false }"
                                :disabled="paying || {{ $paymentPending ? 'true' : 'false' }}"
                                x-on:click="if (! paying) { paying = true; $wire.payNow().finally(() => paying = false) }"
                                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-indigo-600 px-16 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus-visible:ring-indigo-600 disabled:cursor-not-allowed disabled:opacity-60">
                                <span x-show="! paying">Subscribe</span>
                                <span x-cloak x-show="paying" class="inline-flex items-center gap-2"><svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg><span class="sr-only">Loading</span></span>
                            </button>
                        </div>
                    @else
                        <div class="rounded-xl border border-slate-700/50 bg-slate-800/50 p-4">
                            <p class="text-xs text-slate-400">Total Amount</p>
                            <p class="mt-2 text-2xl font-bold text-purple-300">{{ $agreement->formatted_amount }}</p>
                            <p class="mt-1 text-xs text-slate-500">Pay via secure checkout to activate your agreement.</p>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-1">
                            <button type="button" wire:click="closePaymentModal" wire:loading.attr="disabled" wire:target="closePaymentModal" class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-200 ring-1 ring-inset ring-slate-600 shadow-sm transition hover:bg-slate-700 hover:text-white focus-visible:ring-slate-400 disabled:cursor-not-allowed disabled:opacity-60">
                                <span wire:loading.remove wire:target="closePaymentModal">Cancel</span>
                                <span wire:loading wire:target="closePaymentModal" class="inline-flex items-center gap-2"><svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg></span>
                            </button>
                            <button type="button"
                                x-data="{ paying: false }"
                                :disabled="paying || {{ $paymentPending ? 'true' : 'false' }}"
                                x-on:click="if (! paying) { paying = true; $wire.payNow().finally(() => paying = false) }"
                                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-indigo-600 px-16 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus-visible:ring-indigo-600 disabled:cursor-not-allowed disabled:opacity-60">
                                <span x-show="! paying">Pay Now</span>
                                <span x-cloak x-show="paying" class="inline-flex items-center gap-2"><svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg><span class="sr-only">Loading</span></span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($step === 'complete')
        <style>body { background: #0a0518 !important; }</style>
        <div class="fixed inset-0 flex items-center overflow-y-auto px-4 sm:px-6" style="background: radial-gradient(ellipse at 20% 60%, rgba(139,92,246,0.15) 0%, transparent 50%), radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%), linear-gradient(180deg, #0a0518 0%, #130826 50%, #0a0518 100%);">
            <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(1px 1px at 10% 20%, rgba(255,255,255,0.6), transparent), radial-gradient(1px 1px at 30% 70%, rgba(255,255,255,0.4), transparent), radial-gradient(1px 1px at 50% 40%, rgba(255,255,255,0.5), transparent), radial-gradient(1px 1px at 70% 80%, rgba(255,255,255,0.3), transparent), radial-gradient(1px 1px at 85% 15%, rgba(255,255,255,0.6), transparent), radial-gradient(1px 1px at 15% 90%, rgba(255,255,255,0.4), transparent), radial-gradient(1px 1px at 60% 10%, rgba(255,255,255,0.5), transparent), radial-gradient(1px 1px at 40% 55%, rgba(255,255,255,0.3), transparent), radial-gradient(1px 1px at 90% 60%, rgba(255,255,255,0.5), transparent);"></div>
            <div class="absolute left-[-300px] top-[30%] h-[700px] w-[700px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(139,92,246,0.25) 0%, rgba(139,92,246,0.05) 40%, transparent 70%);"></div>
            <div class="relative z-10 mx-auto max-w-md w-full py-10 text-center">
                <div class="mb-12 text-center">
                    <img src="{{ asset('logo.png') }}" alt="Mars Station" class="mx-auto h-12 w-auto" style="filter: brightness(2) drop-shadow(0 0 8px rgba(168,85,247,0.6));">
                    <div class="mt-3 text-xs font-medium tracking-widest text-slate-300 uppercase">Client Portal</div>
                </div>
                <div class="space-y-4">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-emerald-500/20 bg-emerald-500/10">
                        <svg class="h-8 w-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-white">Thank you</h1>
                    <p class="text-sm text-slate-400">
                        {{ $completionMessage ?: 'Your agreement with Mars Station is now complete. You can download a copy of the signed agreement below.' }}
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        <x-button variant="secondary" wire:click="downloadPdf" wire:loading.attr="disabled" wire:target="downloadPdf">Download Signed PDF</x-button>
                    </div>
                </div>
                <p class="mt-12 text-center text-xs text-slate-500 mb-5 mt-5">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. This is a secure document portal.
                </p>
            </div>
        </div>
    @endif

    @if ($step === 'sign')
        <div class="fixed inset-0 z-50" x-data="{ open: true }" x-show="open" x-cloak>
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative w-full max-w-xl overflow-hidden rounded-2xl border border-purple-500/20 bg-slate-900 shadow-xl shadow-purple-950/40 ring-1 ring-slate-700/50">
                        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-purple-500/10">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                </div>
                                <h3 class="text-base font-semibold text-slate-100">Sign this agreement</h3>
                            </div>
                            <button type="button" wire:click="goToView" wire:loading.attr="disabled" wire:target="goToView" class="shrink-0 rounded-lg p-1 text-slate-400 hover:bg-slate-800 hover:text-slate-200 cursor-pointer disabled:opacity-50" aria-label="Close">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="px-6 py-5">
                            <p class="mb-5 text-sm text-slate-400">Confirm your details to sign this agreement.</p>
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Full legal name <span class="text-red-400">*</span></label>
                                    <input type="text" wire:model="signName" class="block w-full rounded-lg border border-slate-700 bg-slate-800 px-3.5 py-2.5 text-sm text-slate-100 shadow-sm outline-none transition placeholder:text-slate-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/30" placeholder="Enter your full name"/>
                                    @error('signName') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Email <span class="text-red-400">*</span></label>
                                    <input type="email" wire:model="signEmail" class="block w-full rounded-lg border border-slate-700 bg-slate-800 px-3.5 py-2.5 text-sm text-slate-100 shadow-sm outline-none transition placeholder:text-slate-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/30" placeholder="you@example.com"/>
                                    @error('signEmail') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 border-t border-slate-800 bg-slate-950/60 px-6 py-4 sm:flex-row sm:items-center sm:justify-end">
                            <p class="text-xs text-slate-500 sm:mr-auto text-center sm:text-left">By signing, you agree to the terms. Your signature will be recorded with your IP and timestamp.</p>
                            <div class="flex items-center justify-center gap-3">
                                <button type="button" wire:click="goToView" wire:loading.attr="disabled" wire:target="goToView" class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-200 ring-1 ring-inset ring-slate-600 shadow-sm transition hover:bg-slate-700 hover:text-white focus-visible:ring-slate-400 disabled:cursor-not-allowed disabled:opacity-60">
                                    <span wire:loading.remove wire:target="goToView">Cancel</span>
                                    <span wire:loading wire:target="goToView" class="inline-flex items-center gap-2"><svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg><span class="sr-only">Loading</span></span>
                                </button>
                                <button type="button"
                                    x-data="{ signing: false }"
                                    :disabled="signing"
                                    x-on:click="if (! signing) { signing = true; $wire.sign().finally(() => signing = false) }"
                                    class="inline-flex cursor-pointer items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-indigo-600 px-8 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus-visible:ring-indigo-600 disabled:cursor-not-allowed disabled:opacity-60">
                                    <span x-show="! signing">Sign &amp; Continue</span>
                                    <span x-cloak x-show="signing" class="inline-flex items-center gap-2"><svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg><span class="sr-only">Loading</span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
