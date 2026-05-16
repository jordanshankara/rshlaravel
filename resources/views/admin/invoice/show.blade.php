@extends('layouts.admin')
@section('title', 'Invoice '.$invoice->invoice_number)
@section('page-title', 'Invoice '.$invoice->invoice_number)

@section('content')
<div class="max-w-3xl space-y-3">

    {{-- Action buttons --}}
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('admin.invoice.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1 mr-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <a href="{{ route('admin.invoice.pdf', $invoice) }}"
           class="px-4 py-2 border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
            Unduh PDF
        </a>
        @if(!$invoice->is_system_generated)
        <a href="{{ route('admin.invoice.edit', $invoice) }}"
           class="px-4 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">
            Edit Invoice
        </a>
        @endif
    </div>

    {{-- Professional invoice card --}}
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">

        {{-- Green gradient header --}}
        <div class="bg-gradient-to-r from-[#1a5a3f] to-[#2d6a4f] px-6 py-5 text-white flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <div class="text-lg font-bold">{{ $settings['site_name'] ?? 'RSH Satu Bumi' }}</div>
                @if(!empty($settings['site_address']))
                <div class="text-green-200 text-xs mt-0.5">{{ $settings['site_address'] }}</div>
                @endif
                @if(!empty($settings['site_phone']))
                <div class="text-green-200 text-xs">{{ $settings['site_phone'] }}</div>
                @endif
            </div>
            <div class="sm:text-right">
                <div class="text-xs text-green-300 uppercase tracking-widest font-semibold">Invoice</div>
                <div class="font-mono text-white text-sm font-bold mt-0.5">{{ $invoice->invoice_number }}</div>
                <div class="text-green-200 text-xs mt-0.5">{{ $invoice->invoice_date->format('d M Y') }}</div>
            </div>
        </div>

        {{-- Client + status row --}}
        <div class="px-6 py-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wide">Tagihan Kepada</div>
                <div class="text-base font-semibold text-gray-800 mt-0.5">{{ $invoice->client_name }}</div>
            </div>
            <div class="scale-110 origin-left sm:origin-right">
                <x-invoice-status-badge :status="$invoice->payment_status" />
            </div>
        </div>

        {{-- Line items --}}
        <div class="px-6 py-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-xs text-gray-400 uppercase tracking-wide">
                        <th class="pb-2 text-left font-medium w-6">#</th>
                        <th class="pb-2 text-left font-medium">Deskripsi</th>
                        <th class="pb-2 text-right font-medium">Qty</th>
                        <th class="pb-2 text-right font-medium">Harga Satuan</th>
                        <th class="pb-2 text-right font-medium">Disc</th>
                        <th class="pb-2 text-right font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $i => $item)
                    <tr class="border-b last:border-0">
                        <td class="py-2.5 text-gray-400 text-xs">{{ $i + 1 }}</td>
                        <td class="py-2.5 pr-4">{{ $item->description }}</td>
                        <td class="py-2.5 text-right text-gray-600">{{ $item->quantity }}</td>
                        <td class="py-2.5 text-right text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-2.5 text-right text-gray-500">{{ $item->discount > 0 ? $item->discount.'%' : '-' }}</td>
                        <td class="py-2.5 text-right font-semibold">Rp {{ number_format($item->price * $item->quantity * (1 - $item->discount / 100), 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Total --}}
            <div class="mt-4 flex justify-end">
                <div class="bg-[#2d6a4f] text-white rounded-lg px-6 py-3 flex items-center gap-6">
                    <span class="text-sm font-semibold text-green-200">Total Tagihan</span>
                    <span class="text-xl font-bold">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($invoice->notes)
        <div class="px-6 pb-4">
            <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-600">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1">Catatan</span>
                {{ $invoice->notes }}
            </div>
        </div>
        @endif

        {{-- Payment detail (only when unpaid) --}}
        @if($invoice->paymentDetail && $invoice->payment_status === 'BELUM_LUNAS')
        <div class="px-6 pb-5">
            <div class="border border-[#2d6a4f]/30 bg-green-50 rounded-lg p-4">
                <div class="text-xs font-semibold text-[#2d6a4f] uppercase tracking-wide mb-2">Info Pembayaran</div>
                <div class="text-sm space-y-1 text-gray-700">
                    <div><span class="text-gray-500 w-24 inline-block">Bank</span>{{ $invoice->paymentDetail->bank_name }}</div>
                    <div><span class="text-gray-500 w-24 inline-block">No. Rekening</span><span class="font-mono font-semibold">{{ $invoice->paymentDetail->account_number }}</span></div>
                    <div><span class="text-gray-500 w-24 inline-block">Atas Nama</span>{{ $invoice->paymentDetail->account_name }}</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Footer --}}
        <div class="border-t px-6 py-3 bg-gray-50 text-center text-xs text-gray-400">
            Terima kasih atas kepercayaan Anda.
        </div>
    </div>

    {{-- Related registration (outside main card) --}}
    @if($invoice->registration)
    <div class="bg-white rounded-xl border shadow-sm px-5 py-4 flex items-center gap-3">
        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-sm text-gray-500">Terkait pendaftaran:</span>
        <a href="{{ route('admin.registrasi.show', $invoice->registration) }}" class="text-sm text-[#2d6a4f] hover:underline font-medium">
            {{ $invoice->registration->registration_code }} — {{ $invoice->registration->full_name }}
        </a>
    </div>
    @endif

</div>
@endsection
