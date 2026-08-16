<div
    x-data="toast()"
    x-show="visible"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
    class="fixed bottom-6 right-6 z-[60]"
    role="status"
>
    <div x-show="visible" x-cloak
         :class="type === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : (type === 'error' ? 'border-red-200 bg-red-50 text-red-800' : 'border-blue-200 bg-blue-50 text-blue-800')"
         class="flex max-w-sm items-start gap-3 rounded-xl border bg-white px-4 py-3 text-sm shadow-lg">
        <div class="flex-1" x-text="message"></div>
        <button type="button" @click="visible = false" class="shrink-0 text-slate-400 hover:text-slate-600" aria-label="Dismiss">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<script>
    function toast() {
        return {
            visible: false,
            message: '',
            type: 'success',
            timer: null,
            init() {
                Livewire.on('toast', (payload) => {
                    this.message = payload[0]?.message ?? 'Done';
                    this.type = payload[0]?.type ?? 'success';
                    this.visible = true;
                    clearTimeout(this.timer);
                    this.timer = setTimeout(() => { this.visible = false; }, 4000);
                });
            },
        };
    }
</script>
