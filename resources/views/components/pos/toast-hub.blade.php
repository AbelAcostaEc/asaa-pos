<div
    x-data
    x-on:app-toast.window="$store.toast.push($event.detail)"
    class="pointer-events-none fixed inset-x-0 top-4 z-[80] flex justify-center px-4 sm:inset-x-auto sm:right-5 sm:top-5 sm:block sm:w-[380px]"
>
    <div class="flex w-full max-w-[380px] flex-col gap-3">
        <template x-for="toast in $store.toast.items" :key="toast.id">
            <div
                x-data="{ progressWidth: '100%' }"
                x-init="
                    if (toast.duration > 0) {
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => progressWidth = '0%');
                        });
                    }
                "
                x-show="true"
                x-transition:enter="transform transition duration-300 ease-out"
                x-transition:enter-start="translate-y-3 opacity-0 sm:translate-x-6 sm:translate-y-0"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transform transition duration-200 ease-in"
                x-transition:leave-start="translate-y-0 opacity-100"
                x-transition:leave-end="-translate-y-2 opacity-0 sm:translate-x-4"
                class="pointer-events-auto relative overflow-hidden rounded-[24px] p-4 text-white shadow-[0_20px_55px_-22px_rgba(15,23,42,0.7)]"
                :class="{
                    'bg-emerald-600': toast.type === 'success',
                    'bg-red-600': toast.type === 'danger',
                    'bg-amber-500 text-slate-950': toast.type === 'warning',
                    'bg-sky-600': !['success', 'danger', 'warning'].includes(toast.type)
                }"
            >
                <div class="absolute inset-0 opacity-20"
                     :class="{
                        'bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.4),_transparent_38%)]': true
                     }"></div>

                <div class="relative flex items-start gap-3">
                    <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-white/20 bg-white/15"
                         :class="{
                            'text-white': toast.type !== 'warning',
                            'text-slate-950 border-black/10 bg-white/35': toast.type === 'warning'
                         }">
                        <svg x-show="toast.type === 'success'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" /></svg>
                        <svg x-show="toast.type === 'danger'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" /></svg>
                        <svg x-show="toast.type === 'warning'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v3m0 4h.01M10.29 3.86l-7.5 13A1 1 0 003.66 18h16.68a1 1 0 00.87-1.5l-7.5-13a1 1 0 00-1.74 0z" /></svg>
                        <svg x-show="!['success', 'danger', 'warning'].includes(toast.type)" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p x-show="toast.title" x-text="toast.title" class="text-sm font-black tracking-tight"></p>
                        <p x-text="toast.message" class="text-sm leading-6" :class="{ 'text-white/90': toast.type !== 'warning', 'text-slate-950/80': toast.type === 'warning' }"></p>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <span x-show="toast.duration > 0"
                              x-text="`${Math.ceil(toast.duration / 1000)}s`"
                              class="rounded-full border border-white/20 bg-white/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.18em]"
                              :class="{ 'text-white/85': toast.type !== 'warning', 'border-black/10 bg-white/30 text-slate-950/70': toast.type === 'warning' }"></span>
                        <button @click="$store.toast.remove(toast.id)" class="flex h-8 w-8 items-center justify-center rounded-xl transition hover:bg-white/15"
                                :class="{ 'text-white/80 hover:text-white': toast.type !== 'warning', 'text-slate-900/70 hover:text-slate-950 hover:bg-white/35': toast.type === 'warning' }">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div x-show="toast.duration > 0" class="relative mt-4 h-1.5 overflow-hidden rounded-full bg-white/20" :class="{ 'bg-black/10': toast.type === 'warning' }">
                    <div class="h-full rounded-full bg-white/90"
                         :class="{ 'bg-slate-950/70': toast.type === 'warning' }"
                         :style="`width: ${progressWidth}; transition: width ${toast.duration}ms linear;`"></div>
                </div>
                <div x-show="toast.duration <= 0" class="relative mt-4 h-1.5 overflow-hidden rounded-full bg-white/20" :class="{ 'bg-black/10': toast.type === 'warning' }">
                    <div class="h-full w-1/3 rounded-full bg-white/70" :class="{ 'bg-slate-950/60': toast.type === 'warning' }"></div>
                </div>
            </div>
        </template>
    </div>
</div>
