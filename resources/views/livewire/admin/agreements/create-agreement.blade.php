<div class="space-y-6">

    @php
        $paymentOptions = [
            'none' => [
                'value' => 'none',
                'label' => 'No Payment Required',
                'desc'  => 'No payment is required for this agreement',
                'icon'  => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636',
                'bg'    => 'bg-slate-500/15 text-slate-300 ring-slate-500/30',
            ],
            'full' => [
                'value' => 'full',
                'label' => 'Full Payment',
                'desc'  => 'Client pays a single total amount',
                'icon'  => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
                'bg'    => 'bg-purple-500/15 text-purple-300 ring-purple-500/30',
            ],
            'milestone' => [
                'value' => 'milestone',
                'label' => 'Milestone Payment',
                'desc'  => 'Client pays in multiple milestones',
                'icon'  => 'M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5',
                'bg'    => 'bg-orange-500/15 text-orange-300 ring-orange-500/30',
            ],
            'subscription' => [
                'value' => 'subscription',
                'label' => 'Subscription Payment',
                'desc'  => 'Client pays on a recurring basis',
                'icon'  => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99',
                'bg'    => 'bg-emerald-500/15 text-emerald-300 ring-emerald-500/30',
            ],
        ];
    @endphp

    <form wire:submit="save" class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- ============================================================
             LEFT COLUMN — Agreement Details (2/3)
        ============================================================ --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-6 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
                <div>
                    <h2 class="text-lg font-bold text-white">Create New Agreement</h2>
                    <p class="mt-1 text-sm text-slate-400">Fill in the details below to create and send a new agreement to your client.</p>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Agreement Title --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">
                            Agreement Title <span class="text-red-400">*</span>
                        </label>
                        <input type="text" wire:model="title" placeholder="Enter agreement title"
                               class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 shadow-sm ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-purple-500"/>
                        @error('title') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Client Full Name --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">
                            Client Full Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" wire:model="client_name" placeholder="Enter client full name"
                               class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 shadow-sm ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-purple-500"/>
                        @error('client_name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Client Email --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">
                            Client Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" wire:model="client_email" placeholder="Enter client email address"
                               class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 shadow-sm ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-purple-500"/>
                        @error('client_email') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Client Mobile (with country selector) --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">
                            Client Mobile <span class="text-red-400">*</span>
                        </label>
                        <div
                            x-data="{
                                open: false,
                                search: '',
                                selectedCode: '+44',
                                selectedFlag: '🇬🇧',
                                selectedName: 'United Kingdom',
                                codes: [
                                    {flag:'🇦🇫',code:'+93',name:'Afghanistan'},{flag:'🇦🇱',code:'+355',name:'Albania'},
                                    {flag:'🇩🇿',code:'+213',name:'Algeria'},{flag:'🇦🇩',code:'+376',name:'Andorra'},
                                    {flag:'🇦🇴',code:'+244',name:'Angola'},{flag:'🇦🇬',code:'+1268',name:'Antigua & Barbuda'},
                                    {flag:'🇦🇷',code:'+54',name:'Argentina'},{flag:'🇦🇲',code:'+374',name:'Armenia'},
                                    {flag:'🇦🇺',code:'+61',name:'Australia'},{flag:'🇦🇹',code:'+43',name:'Austria'},
                                    {flag:'🇦🇿',code:'+994',name:'Azerbaijan'},{flag:'🇧🇸',code:'+1242',name:'Bahamas'},
                                    {flag:'🇧🇭',code:'+973',name:'Bahrain'},{flag:'🇧🇩',code:'+880',name:'Bangladesh'},
                                    {flag:'🇧🇧',code:'+1246',name:'Barbados'},{flag:'🇧🇾',code:'+375',name:'Belarus'},
                                    {flag:'🇧🇪',code:'+32',name:'Belgium'},{flag:'🇧🇿',code:'+501',name:'Belize'},
                                    {flag:'🇧🇯',code:'+229',name:'Benin'},{flag:'🇧🇹',code:'+975',name:'Bhutan'},
                                    {flag:'🇧🇴',code:'+591',name:'Bolivia'},{flag:'🇧🇦',code:'+387',name:'Bosnia & Herzegovina'},
                                    {flag:'🇧🇼',code:'+267',name:'Botswana'},{flag:'🇧🇷',code:'+55',name:'Brazil'},
                                    {flag:'🇧🇳',code:'+673',name:'Brunei'},{flag:'🇧🇬',code:'+359',name:'Bulgaria'},
                                    {flag:'🇧🇫',code:'+226',name:'Burkina Faso'},{flag:'🇧🇮',code:'+257',name:'Burundi'},
                                    {flag:'🇰🇭',code:'+855',name:'Cambodia'},{flag:'🇨🇲',code:'+237',name:'Cameroon'},
                                    {flag:'🇨🇦',code:'+1',name:'Canada'},{flag:'🇨🇻',code:'+238',name:'Cape Verde'},
                                    {flag:'🇨🇫',code:'+236',name:'Central African Republic'},{flag:'🇹🇩',code:'+235',name:'Chad'},
                                    {flag:'🇨🇱',code:'+56',name:'Chile'},{flag:'🇨🇳',code:'+86',name:'China'},
                                    {flag:'🇨🇴',code:'+57',name:'Colombia'},{flag:'🇰🇲',code:'+269',name:'Comoros'},
                                    {flag:'🇨🇬',code:'+242',name:'Congo'},{flag:'🇨🇩',code:'+243',name:'DR Congo'},
                                    {flag:'🇨🇷',code:'+506',name:'Costa Rica'},{flag:'🇭🇷',code:'+385',name:'Croatia'},
                                    {flag:'🇨🇺',code:'+53',name:'Cuba'},{flag:'🇨🇾',code:'+357',name:'Cyprus'},
                                    {flag:'🇨🇿',code:'+420',name:'Czech Republic'},{flag:'🇩🇰',code:'+45',name:'Denmark'},
                                    {flag:'🇩🇯',code:'+253',name:'Djibouti'},{flag:'🇩🇲',code:'+1767',name:'Dominica'},
                                    {flag:'🇩🇴',code:'+1809',name:'Dominican Republic'},{flag:'🇪🇨',code:'+593',name:'Ecuador'},
                                    {flag:'🇪🇬',code:'+20',name:'Egypt'},{flag:'🇸🇻',code:'+503',name:'El Salvador'},
                                    {flag:'🇬🇶',code:'+240',name:'Equatorial Guinea'},{flag:'🇪🇷',code:'+291',name:'Eritrea'},
                                    {flag:'🇪🇪',code:'+372',name:'Estonia'},{flag:'🇸🇿',code:'+268',name:'Eswatini'},
                                    {flag:'🇪🇹',code:'+251',name:'Ethiopia'},{flag:'🇫🇯',code:'+679',name:'Fiji'},
                                    {flag:'🇫🇮',code:'+358',name:'Finland'},{flag:'🇫🇷',code:'+33',name:'France'},
                                    {flag:'🇬🇦',code:'+241',name:'Gabon'},{flag:'🇬🇲',code:'+220',name:'Gambia'},
                                    {flag:'🇬🇪',code:'+995',name:'Georgia'},{flag:'🇩🇪',code:'+49',name:'Germany'},
                                    {flag:'🇬🇭',code:'+233',name:'Ghana'},{flag:'🇬🇷',code:'+30',name:'Greece'},
                                    {flag:'🇬🇹',code:'+502',name:'Guatemala'},{flag:'🇬🇳',code:'+224',name:'Guinea'},
                                    {flag:'🇬🇾',code:'+592',name:'Guyana'},{flag:'🇭🇹',code:'+509',name:'Haiti'},
                                    {flag:'🇭🇳',code:'+504',name:'Honduras'},{flag:'🇭🇰',code:'+852',name:'Hong Kong'},
                                    {flag:'🇭🇺',code:'+36',name:'Hungary'},{flag:'🇮🇸',code:'+354',name:'Iceland'},
                                    {flag:'🇮🇳',code:'+91',name:'India'},{flag:'🇮🇩',code:'+62',name:'Indonesia'},
                                    {flag:'🇮🇷',code:'+98',name:'Iran'},{flag:'🇮🇶',code:'+964',name:'Iraq'},
                                    {flag:'🇮🇪',code:'+353',name:'Ireland'},{flag:'🇮🇱',code:'+972',name:'Israel'},
                                    {flag:'🇮🇹',code:'+39',name:'Italy'},{flag:'🇯🇲',code:'+1876',name:'Jamaica'},
                                    {flag:'🇯🇵',code:'+81',name:'Japan'},{flag:'🇯🇴',code:'+962',name:'Jordan'},
                                    {flag:'🇰🇿',code:'+7',name:'Kazakhstan'},{flag:'🇰🇪',code:'+254',name:'Kenya'},
                                    {flag:'🇰🇼',code:'+965',name:'Kuwait'},{flag:'🇰🇬',code:'+996',name:'Kyrgyzstan'},
                                    {flag:'🇱🇦',code:'+856',name:'Laos'},{flag:'🇱🇻',code:'+371',name:'Latvia'},
                                    {flag:'🇱🇧',code:'+961',name:'Lebanon'},{flag:'🇱🇸',code:'+266',name:'Lesotho'},
                                    {flag:'🇱🇷',code:'+231',name:'Liberia'},{flag:'🇱🇾',code:'+218',name:'Libya'},
                                    {flag:'🇱🇹',code:'+370',name:'Lithuania'},{flag:'🇱🇺',code:'+352',name:'Luxembourg'},
                                    {flag:'🇲🇴',code:'+853',name:'Macau'},{flag:'🇲🇬',code:'+261',name:'Madagascar'},
                                    {flag:'🇲🇼',code:'+265',name:'Malawi'},{flag:'🇲🇾',code:'+60',name:'Malaysia'},
                                    {flag:'🇲🇻',code:'+960',name:'Maldives'},{flag:'🇲🇱',code:'+223',name:'Mali'},
                                    {flag:'🇲🇹',code:'+356',name:'Malta'},{flag:'🇲🇽',code:'+52',name:'Mexico'},
                                    {flag:'🇲🇩',code:'+373',name:'Moldova'},{flag:'🇲🇨',code:'+377',name:'Monaco'},
                                    {flag:'🇲🇳',code:'+976',name:'Mongolia'},{flag:'🇲🇪',code:'+382',name:'Montenegro'},
                                    {flag:'🇲🇦',code:'+212',name:'Morocco'},{flag:'🇲🇿',code:'+258',name:'Mozambique'},
                                    {flag:'🇲🇲',code:'+95',name:'Myanmar'},{flag:'🇳🇦',code:'+264',name:'Namibia'},
                                    {flag:'🇳🇵',code:'+977',name:'Nepal'},{flag:'🇳🇱',code:'+31',name:'Netherlands'},
                                    {flag:'🇳🇿',code:'+64',name:'New Zealand'},{flag:'🇳🇮',code:'+505',name:'Nicaragua'},
                                    {flag:'🇳🇪',code:'+227',name:'Niger'},{flag:'🇳🇬',code:'+234',name:'Nigeria'},
                                    {flag:'🇰🇵',code:'+850',name:'North Korea'},{flag:'🇲🇰',code:'+389',name:'North Macedonia'},
                                    {flag:'🇳🇴',code:'+47',name:'Norway'},{flag:'🇴🇲',code:'+968',name:'Oman'},
                                    {flag:'🇵🇰',code:'+92',name:'Pakistan'},{flag:'🇵🇦',code:'+507',name:'Panama'},
                                    {flag:'🇵🇬',code:'+675',name:'Papua New Guinea'},{flag:'🇵🇾',code:'+595',name:'Paraguay'},
                                    {flag:'🇵🇪',code:'+51',name:'Peru'},{flag:'🇵🇭',code:'+63',name:'Philippines'},
                                    {flag:'🇵🇱',code:'+48',name:'Poland'},{flag:'🇵🇹',code:'+351',name:'Portugal'},
                                    {flag:'🇶🇦',code:'+974',name:'Qatar'},{flag:'🇷🇴',code:'+40',name:'Romania'},
                                    {flag:'🇷🇺',code:'+7',name:'Russia'},{flag:'🇷🇼',code:'+250',name:'Rwanda'},
                                    {flag:'🇸🇦',code:'+966',name:'Saudi Arabia'},{flag:'🇸🇳',code:'+221',name:'Senegal'},
                                    {flag:'🇷🇸',code:'+381',name:'Serbia'},{flag:'🇸🇬',code:'+65',name:'Singapore'},
                                    {flag:'🇸🇰',code:'+421',name:'Slovakia'},{flag:'🇸🇮',code:'+386',name:'Slovenia'},
                                    {flag:'🇸🇴',code:'+252',name:'Somalia'},{flag:'🇿🇦',code:'+27',name:'South Africa'},
                                    {flag:'🇰🇷',code:'+82',name:'South Korea'},{flag:'🇪🇸',code:'+34',name:'Spain'},
                                    {flag:'🇱🇰',code:'+94',name:'Sri Lanka'},{flag:'🇸🇩',code:'+249',name:'Sudan'},
                                    {flag:'🇸🇪',code:'+46',name:'Sweden'},{flag:'🇨🇭',code:'+41',name:'Switzerland'},
                                    {flag:'🇸🇾',code:'+963',name:'Syria'},{flag:'🇹🇼',code:'+886',name:'Taiwan'},
                                    {flag:'🇹🇿',code:'+255',name:'Tanzania'},{flag:'🇹🇭',code:'+66',name:'Thailand'},
                                    {flag:'🇹🇬',code:'+228',name:'Togo'},{flag:'🇹🇹',code:'+1868',name:'Trinidad & Tobago'},
                                    {flag:'🇹🇳',code:'+216',name:'Tunisia'},{flag:'🇹🇷',code:'+90',name:'Turkey'},
                                    {flag:'🇹🇲',code:'+993',name:'Turkmenistan'},{flag:'🇺🇬',code:'+256',name:'Uganda'},
                                    {flag:'🇺🇦',code:'+380',name:'Ukraine'},{flag:'🇦🇪',code:'+971',name:'United Arab Emirates'},
                                    {flag:'🇬🇧',code:'+44',name:'United Kingdom'},{flag:'🇺🇸',code:'+1',name:'United States'},
                                    {flag:'🇺🇾',code:'+598',name:'Uruguay'},{flag:'🇺🇿',code:'+998',name:'Uzbekistan'},
                                    {flag:'🇻🇳',code:'+84',name:'Vietnam'},{flag:'🇾🇪',code:'+967',name:'Yemen'},
                                    {flag:'🇿🇲',code:'+260',name:'Zambia'},{flag:'🇿🇼',code:'+263',name:'Zimbabwe'}
                                ],
                                get filtered() {
                                    const q = this.search.toLowerCase();
                                    return this.codes.filter(c => c.name.toLowerCase().includes(q) || c.code.includes(q));
                                },
                                select(c) {
                                    this.selectedCode = c.code;
                                    this.selectedFlag = c.flag;
                                    this.selectedName = c.name;
                                    this.open = false;
                                    this.search = '';
                                    $wire.set('client_dial_code', c.code);
                                }
                            }"
                            class="relative"
                        >
                            <div class="flex items-center rounded-lg bg-slate-900/60 ring-1 ring-inset ring-purple-500/20 focus-within:ring-2 focus-within:ring-purple-500">
                                <button type="button" @click="open = !open" class="flex items-center gap-1.5 border-r border-purple-500/20 px-3 py-2.5 text-sm text-slate-300 hover:text-white transition shrink-0">
                                    <span class="text-base" x-text="selectedFlag"></span>
                                    <span class="ml-0.5" x-text="selectedCode"></span>
                                    <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </button>
                                <input type="text" wire:model="client_mobile" placeholder="Enter client mobile number" inputmode="numeric" pattern="[0-9]*"
                                       onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                       class="flex-1 border-0 bg-transparent px-3 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 focus:ring-0 focus:outline-none"/>
                            </div>
                            <input type="hidden" x-model="selectedCode" wire:model="client_dial_code">

                            <div x-show="open" @click.away="open = false" x-cloak x-transition
                                 class="absolute left-0 top-full z-30 mt-2 w-72 max-h-[404px] rounded-xl border border-purple-500/20 bg-slate-900 shadow-2xl shadow-purple-950/50 overflow-hidden">
                                <div class="border-b border-purple-500/10 p-2">
                                    <input type="text" x-model="search" placeholder="Search country..."
                                           class="w-full rounded-lg border-0 bg-slate-950/60 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 focus:ring-1 focus:ring-purple-500"/>
                                </div>
                                <ul class="overflow-y-auto scrollbar-thin p-1" style="max-height: 360px;">
                                    <template x-for="c in filtered" :key="c.code + c.name">
                                        <li>
                                            <button type="button" @click="select(c)"
                                                    class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm transition hover:bg-purple-500/10"
                                                    :class="selectedCode === c.code && selectedName === c.name ? 'bg-purple-500/15 text-purple-300' : 'text-slate-200'">
                                                <span class="text-base" x-text="c.flag"></span>
                                                <span class="flex-1 truncate" x-text="c.name"></span>
                                                <span class="text-xs text-slate-500" x-text="c.code"></span>
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" wire:model="client_dial_code" value="+44">
                        @error('client_mobile') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Agreement Content (WYSIWYG editor) --}}
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">
                            Agreement Content <span class="text-red-400">*</span>
                        </label>
                        <div wire:ignore class="overflow-hidden rounded-lg bg-slate-900/60 ring-1 ring-inset ring-purple-500/20 focus-within:ring-2 focus-within:ring-purple-500">
                            <div x-data="richEditor(@js($content), 'content')">
                                <div x-ref="editor"></div>
                                <div class="flex items-center justify-end gap-3 border-t border-purple-500/15 bg-slate-950/40 px-3 py-1.5 text-xs text-slate-500">
                                    <span x-text="words + ' words'"></span>
                                </div>
                            </div>
                        </div>
                        @error('content') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Validity Date --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">
                            Validity Date <span class="font-normal text-slate-500">(Optional)</span>
                        </label>
                        <div wire:ignore x-data="validityDatePicker(@js($validity_date), 'validity_date')" class="relative">
                            <svg class="pointer-events-none absolute inset-y-0 left-3.5 my-auto h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.375 19.5V6.75A2.25 2.25 0 015.625 4.5h12.75a2.25 2.25 0 012.25 2.25v12.75M3.375 19.5a2.25 2.25 0 002.25 2.25h12.75a2.25 2.25 0 002.25-2.25M3.375 19.5V9.75m18 9.75V9.75m-18 0h18"/>
                            </svg>
                            <input
                                x-ref="input"
                                type="text"
                                readonly
                                placeholder="Select validity date"
                                class="block w-full cursor-pointer rounded-lg border-0 bg-slate-900/60 py-2.5 pl-10 pr-3.5 text-sm text-slate-100 shadow-sm ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-purple-500"
                            />
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500">The client's signing link will expire after this date</p>
                        @error('validity_date') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Attachment --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">
                            Attachment <span class="font-normal text-slate-500">(Optional)</span>
                        </label>
                        <label class="flex cursor-pointer items-center justify-center gap-3 rounded-lg border border-dashed border-purple-500/30 bg-slate-900/40 px-4 py-3 transition hover:border-purple-500/60 hover:bg-slate-900/60">
                            <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15M9 12l3 3m0 0l3-3m-3 3V2.25"/>
                            </svg>
                            <div class="text-sm">
                                <div><span class="font-semibold text-slate-200">Click to upload</span> <span class="text-slate-400">or drag and drop</span></div>
                                <div class="text-xs text-slate-500">PDF, DOC, DOCX, JPG, PNG, HEIC (Max 10MB)</div>
                            </div>
                            <input type="file" wire:model="attachment" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.heic,.heif,.webp" class="sr-only"/>
                        </label>
                        <div wire:loading wire:target="attachment" class="mt-2 text-xs text-purple-300">Uploading…</div>
                        @error('attachment') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        @if ($attachment)
                            <p class="mt-2 text-xs text-slate-400">
                                Selected: <span class="text-slate-200">{{ $attachment->getClientOriginalName() }}</span>
                                ({{ number_format($attachment->getSize() / 1024, 1) }} KB)
                            </p>
                        @else
                            <p class="mt-2 text-xs text-slate-500">No file selected</p>
                        @endif
                    </div>




                    {{-- Payment dropdown --}}
                    <div class="sm:col-span-2" wire:ignore x-data="paymentDropdown(@js(array_values($paymentOptions)), @js($payment_type))" @click.outside="open = false">
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">
                            Payment <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <button type="button" @click="open = !open"
                                    class="flex w-full items-center gap-3 rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-left text-sm text-slate-100 shadow-sm ring-1 ring-inset ring-purple-500/20 transition hover:ring-purple-500/40 focus:ring-2 focus:ring-inset focus:ring-purple-500">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md ring-1 ring-inset" :class="current.bg">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" x-bind:d="current.icon"/>
                                    </svg>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-semibold" x-text="current.label"></span>
                                    <span class="block text-xs text-slate-400" x-text="current.desc"></span>
                                </span>
                                <svg class="h-4 w-4 shrink-0 text-slate-400 transition" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>

                            <div x-show="open" x-transition
                                 class="absolute z-30 mt-2 w-full overflow-hidden rounded-xl border border-purple-500/20 bg-slate-900 shadow-2xl shadow-purple-950/50 backdrop-blur-xl">
                                <template x-for="opt in options" :key="opt.value">
                                    <button type="button" @click="select(opt)"
                                            class="flex w-full items-center gap-3 px-3.5 py-3 text-left transition hover:bg-purple-500/10"
                                            :class="value === opt.value ? 'bg-purple-500/15' : ''">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md ring-1 ring-inset" :class="opt.bg">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" x-bind:d="opt.icon"/>
                                            </svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-sm font-medium" x-text="opt.label"></span>
                                            <span class="block text-xs text-slate-400" x-text="opt.desc"></span>
                                        </span>
                                        <svg x-show="value === opt.value" class="h-5 w-5 shrink-0 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500">Choose the payment type for this agreement</p>
                        @error('payment_type') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Full Payment Details --}}
                    @if ($payment_type === 'full')
                        <div class="sm:col-span-2 rounded-xl border border-purple-500/15 bg-slate-900/40 p-5 space-y-4">
                            <h3 class="text-sm font-bold text-white">Full Payment Details</h3>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-300">
                                    Payment Title <span class="text-red-400">*</span>
                                </label>
                                <input type="text" wire:model="full_title" placeholder="e.g., Law Farm Web"
                                       class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                                @error('full_title') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-300">
                                    Amount (GBP) <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-sm text-slate-400">£</span>
                                    <input type="number" step="0.01" min="0" wire:model="full_amount" placeholder="380.99"
                                           class="block w-full rounded-lg border-0 bg-slate-900/60 py-2.5 pl-8 pr-3 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                                </div>
                                @error('full_amount') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    {{-- Subscription Details --}}
                    @if ($payment_type === 'subscription')
                        <div class="sm:col-span-2 rounded-xl border border-purple-500/15 bg-slate-900/40 p-5 space-y-4">
                            <h3 class="text-sm font-bold text-white">Subscription Details</h3>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-300">Subscription Title <span class="text-red-400">*</span></label>
                                <input type="text" wire:model="subscription_title" placeholder="Hosting & Support"
                                       class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                                @error('subscription_title') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-300">Amount (£) <span class="text-red-400">*</span></label>
                                <input type="number" step="0.01" min="0" wire:model="subscription_amount" placeholder="49.99"
                                       class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                                @error('subscription_amount') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-300">Frequency</label>
                                <div class="relative">
                                    <select wire:model="subscription_frequency"
                                            class="block w-full appearance-none rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 pr-10 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                                        <option value="monthly" class="bg-slate-900">Monthly</option>
                                        <option value="yearly" class="bg-slate-900">Yearly</option>
                                    </select>
                                    <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </div>
                                @error('subscription_frequency') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif
                </div>

            {{-- Milestone builder (conditionally) --}}
                @if ($payment_type === 'milestone')
                    <div class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-slate-200">Milestones</h3>
                            <button type="button" wire:click="addMilestone"
                                    class="inline-flex items-center gap-1 rounded-lg bg-purple-500/15 px-3 py-1.5 text-xs font-semibold text-purple-300 ring-1 ring-inset ring-purple-500/30 transition hover:bg-purple-500/25">
                                + Add Milestone
                            </button>
                        </div>

                        @foreach ($milestones as $index => $milestone)
                            <div class="rounded-xl border border-purple-500/15 bg-slate-900/40 p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Milestone {{ $index + 1 }}</span>
                                    <button type="button" wire:click="removeMilestone({{ $index }})"
                                            class="text-xs font-medium text-red-400 transition hover:text-red-300 disabled:opacity-40"
                                            @if (count($milestones) === 1) disabled @endif>
                                        Remove
                                    </button>
                                </div>
                                <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-300">Title</label>
                                        <input type="text" wire:model="milestones.{{ $index }}.title" placeholder="Deposit"
                                               class="block w-full rounded-lg border-0 bg-slate-900/60 px-3 py-2 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                                        @error('milestones.'.$index.'.title') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-300">Amount (£)</label>
                                        <input type="number" step="0.01" min="0" wire:model="milestones.{{ $index }}.amount" placeholder="100.00"
                                               class="block w-full rounded-lg border-0 bg-slate-900/60 px-3 py-2 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                                        @error('milestones.'.$index.'.amount') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="mb-1.5 block text-xs font-medium text-slate-300">Description</label>
                                        <textarea wire:model="milestones.{{ $index }}.description" rows="2"
                                                  class="block w-full rounded-lg border-0 bg-slate-900/60 px-3 py-2 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500"></textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @error('milestones') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                @endif

                {{-- Actions --}}
                <div class="mt-6 flex items-center justify-between gap-3 border-t border-purple-500/10 pt-5">
                    <a href="{{ route('admin.agreements.index') }}"
                       class="rounded-lg bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-200 ring-1 ring-inset ring-slate-700 transition hover:bg-slate-700">
                        Cancel
                    </a>
                    <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="inline-flex cursor-pointer items-center gap-2 rounded-lg bg-gradient-to-r from-purple-600 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-purple-900/50 transition hover:from-purple-500 hover:to-purple-400 disabled:opacity-60">
                        <svg wire:loading.remove wire:target="save" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <svg wire:loading wire:target="save" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="save">Create Agreement</span>
                        <span wire:loading wire:target="save">Creating…</span>
                    </button>
                </div>
            </div>
        </div>

            {{-- ============================================================
                 RIGHT COLUMN — Payment Type (1/3)
        ============================================================ --}}
<div class="space-y-6">




            {{-- Info Card --}}
            <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    <h3 class="text-sm font-bold text-white">After creating the agreement</h3>
                </div>
                <ul class="mt-3 space-y-2 text-xs text-slate-400">
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        A unique 8-character Agreement No will be generated
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        A secure agreement link will be created
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        You will be able to send it to the client via email
                    </li>
                </ul>
            </div>
        </div>

    </form>
</div>
