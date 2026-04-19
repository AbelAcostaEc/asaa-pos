<x-pos-layout>
    <x-slot name="header">{{ __('inventory::ui.reports.purchase_report_title') }}</x-slot>

    <div class="space-y-6">
        <x-pos.crud-toolbar
            title="{{ __('inventory::ui.reports.purchase_report_title') }}"
            subtitle="{{ __('inventory::ui.reports.purchase_report_subtitle') }}">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('inventory.index') }}">
                    <x-pos.button variant="secondary">{{ __('inventory::ui.entries.list_title') }}</x-pos.button>
                </a>
                <a href="{{ route('inventory.reports.sales') }}">
                    <x-pos.button variant="outline">{{ __('inventory::ui.entries.sales_report') }}</x-pos.button>
                </a>
            </div>
        </x-pos.crud-toolbar>

        <x-pos.card>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('inventory::ui.reports.purchase_report_title') }}</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('inventory::ui.reports.purchase_report_subtitle') }}</p>
                </div>
                <div class="rounded-3xl bg-slate-950 px-5 py-4 text-white dark:bg-slate-900">
                    <p class="text-sm text-slate-300">{{ __('inventory::ui.reports.report_total') }}</p>
                    <p class="mt-2 text-2xl font-black">${{ number_format($total, 2) }}</p>
                </div>
            </div>
        </x-pos.card>

        <div class="space-y-5">
            @forelse($purchases as $purchase)
                <x-pos.card>
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ __('inventory::ui.reports.report_transaction') }} #{{ $purchase->id }}</p>
                            <h3 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ optional($purchase->supplier)->name ?? __('inventory::ui.reports.report_supplier') }}</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('inventory::ui.reports.report_date') }}: {{ $purchase->date }} • {{ __('inventory::ui.reports.report_invoice') }}: {{ $purchase->invoice_number ?? '-' }}</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 dark:bg-slate-900 dark:text-slate-200">{{ __('inventory::ui.reports.report_amount') }}: ${{ number_format((float) $purchase->total, 2) }}</span>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 dark:bg-slate-900 dark:text-slate-200">{{ __('inventory::ui.reports.report_items') }}: {{ $purchase->details->count() }}</span>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3">
                        @foreach($purchase->details as $detail)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-900/60">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ optional($detail->product)->name ?? __('inventory::ui.reports.report_description') }}</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('inventory::ui.reports.report_batch') }}: {{ optional($detail->batch)->code ?? '-' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ number_format((float) $detail->quantity, 2) }} {{ __('inventory::ui.entries.units_suffix') }}</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">${{ number_format((float) $detail->subtotal, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-pos.card>
            @empty
                <x-pos.card>
                    <p class="text-center text-sm text-slate-500 dark:text-slate-400">{{ __('inventory::ui.entries.empty') }}</p>
                </x-pos.card>
            @endforelse
        </div>
    </div>
</x-pos-layout>
