<div class="space-y-6">
    @if ($message)
        <x-alert type="success" :message="$message"/>
    @endif

    @if ($error)
        <x-alert type="error" :message="$error"/>
    @endif

    @if ($step === 'otp')
        <div class="space-y-4">
            <h1 class="text-lg font-semibold text-slate-900">Verify your email</h1>
            <p class="text-sm text-slate-500">
                This agreement is protected. Enter the code we emailed to <strong>{{ $otpEmail }}</strong> to continue.
            </p>
            <x-input label="Verification code" type="text" inputmode="numeric" maxlength="6" wire:model="otp" placeholder="000000"/>
            <div class="flex items-center justify-between">
                <button type="button" wire:click="requestOtp" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Resend code</button>
                <x-button wire:click="verifyOtp" wire:loading.attr="disabled" wire:target="verifyOtp">Verify</x-button>
            </div>
        </div>
    @endif

    @if ($step === 'view' || $step === 'sign' || $step === 'payment')
        <div class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-slate-900">{{ $agreement->agreement_number }}</h1>
                    <p class="mt-1 text-sm text-slate-500">{{ $version->title }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if ($agreement->isExpired())
                        <x-badge color="red">Expired</x-badge>
                    @else
                        <x-badge color="green" :dot="true">Active</x-badge>
                    @endif
                    <x-badge color="slate">V{{ $version->version }}</x-badge>
                </div>
            </div>

            @if ($agreement->isExpired())
                <x-alert type="error" message="This agreement has expired and can no longer be signed."/>
            @endif

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <h2 class="text-sm font-semibold text-slate-900">Agreement between</h2>
                <p class="mt-2 text-sm text-slate-700"><strong>{{ config('app.name') }}</strong> and <strong>{{ $version->client_name }}</strong></p>
                <p class="text-sm text-slate-500">{{ $version->client_email }}</p>
                @if ($version->client_mobile)
                    <p class="text-sm text-slate-500">{{ $version->client_mobile }}</p>
                @endif
                @if ($version->validity_date)
                    <p class="mt-2 text-xs text-slate-400">Valid until {{ $version->validity_date->format('d M Y') }}</p>
                @endif
            </div>

            <div class="whitespace-pre-wrap text-sm leading-relaxed text-slate-700">{{ $version->content }}</div>

            @if ($version->payment_config)
                <div class="rounded-xl border border-slate-200 p-5">
                    <h2 class="text-sm font-semibold text-slate-900">Payment</h2>
                    @if ($agreement->payment_type->value === 'full')
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ $version->payment_config['title'] ?? $agreement->title }}</span>
                            <span class="text-lg font-semibold text-slate-900">{{ \App\Support\Money::format($version->payment_config['amount_pence']) }}</span>
                        </div>
                    @elseif ($agreement->payment_type->value === 'milestone')
                        <ul class="mt-3 space-y-2">
                            @foreach ($version->payment_config['milestones'] as $milestone)
                                <li class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">{{ $milestone['order_index'] }}. {{ $milestone['title'] }}</p>
                                        @if (!empty($milestone['description']))
                                            <p class="text-xs text-slate-500">{{ $milestone['description'] }}</p>
                                        @endif
                                    </div>
                                    <span class="text-sm font-semibold text-slate-900">{{ \App\Support\Money::format($milestone['amount_pence']) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @elseif ($agreement->payment_type->value === 'subscription')
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-sm text-slate-600">
                                {{ $version->payment_config['title'] ?? $agreement->title }}
                                · {{ ucfirst($version->payment_config['frequency'] ?? 'monthly') }}
                            </span>
                            <span class="text-lg font-semibold text-slate-900">{{ \App\Support\Money::format($version->payment_config['amount_pence']) }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-5">
                <x-button type="button" variant="secondary" wire:click="downloadPdf">Download PDF</x-button>

                @if ($step === 'view')
                    @if ($version->isSigned())
                        <div class="flex items-center gap-3">
                            <x-badge color="green">Signed {{ $version->signed_at->format('d M Y') }}</x-badge>
                            @if ($agreement->payment_type->value === 'none' || $agreement->isPaid())
                                <x-button wire:click="complete">View Status</x-button>
                            @else
                                <x-button wire:click="step = 'payment'">Continue</x-button>
                            @endif
                        </div>
                    @elseif (!$agreement->isExpired())
                        <x-button wire:click="step = 'sign'">Sign Agreement</x-button>
                    @endif
                @endif

                @if ($step === 'sign')
                    <div class="w-full space-y-4 rounded-xl border border-slate-200 bg-slate-50 p-5">
                        <h2 class="text-sm font-semibold text-slate-900">Sign this agreement</h2>
                        <x-input label="Full legal name" wire:model="signName" :error="$errors->first('signName')"/>
                        <x-input label="Email" type="email" wire:model="signEmail" :error="$errors->first('signEmail')"/>
                        <p class="text-xs text-slate-500">
                            By signing, you agree to the terms of this agreement. Your signature will be recorded with your IP address and timestamp.
                        </p>
                        <div class="flex items-center justify-end gap-3">
                            <x-button type="button" variant="secondary" wire:click="step = 'view'">Back</x-button>
                            <x-button wire:click="sign" wire:loading.attr="disabled" wire:target="sign">
                                <span wire:loading.remove wire:target="sign">Sign &amp; Continue</span>
                                <span wire:loading wire:target="sign">Signing&hellip;</span>
                            </x-button>
                        </div>
                    </div>
                @endif

                @if ($step === 'payment')
                    <div class="w-full space-y-4 rounded-xl border border-slate-200 bg-slate-50 p-5">
                        <h2 class="text-sm font-semibold text-slate-900">Payment</h2>

                        @if ($agreement->payment_type->value === 'none')
                            <p class="text-sm text-slate-600">No payment is required.</p>
                            <div class="flex justify-end">
                                <x-button variant="success" wire:click="complete">Finish</x-button>
                            </div>
                        @elseif ($agreement->isPaid())
                            <x-alert type="success" message="This agreement has been fully paid."/>
                            <div class="flex justify-end">
                                <x-button variant="success" wire:click="complete">Finish</x-button>
                            </div>
                        @elseif ($agreement->payment_type->value === 'milestone')
                            @php $milestone = $agreement->nextMilestone(); @endphp
                            <p class="text-sm text-slate-600">
                                Pay milestone <strong>{{ $milestone?->title }}</strong> to continue.
                            </p>
                            <p class="text-lg font-semibold text-slate-900">{{ $milestone?->formattedAmount() }}</p>
                            <div class="flex justify-end">
                                <x-button wire:click="payNow" wire:loading.attr="disabled" wire:target="payNow">Pay Now</x-button>
                            </div>
                        @else
                            <p class="text-sm text-slate-600">
                                Pay <strong>{{ $agreement->formatted_amount }}</strong> via secure checkout to activate your agreement.
                            </p>
                            <div class="flex justify-end">
                                <x-button wire:click="payNow" wire:loading.attr="disabled" wire:target="payNow">Pay Now</x-button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($step === 'complete')
        <div class="space-y-4 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-slate-900">Thank you</h1>
            <p class="text-sm text-slate-600">
                Your agreement with Mars Station is now complete. You can download a copy of the signed agreement below.
            </p>
            <div class="flex items-center justify-center gap-3">
                <x-button variant="secondary" wire:click="downloadPdf">Download Signed PDF</x-button>
            </div>
        </div>
    @endif
</div>
