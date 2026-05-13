@extends('layouts.admin')
@section('title', 'Invoice '.$invoice->invoice_number)
@section('page-title', 'Invoice '.$invoice->invoice_number)
@section('header-actions')
<div class="flex gap-2">
    <a href="{{ route('admin.invoice.pdf', $invoice) }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50">Unduh PDF</a>
    @if(!$invoice->is_system_generated)
    <a href="{{ route('admin.invoice.edit', $invoice) }}" class="px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700">Edit</a>
    @endif
    <a href="{{ route('admin.invoice.index') }}" class="text-sm text-gray-500 hover:text-gray-700 self-center ml-2">← Kembali</a>
</div>
@endsection
@section('content')
<div class="max-w-2xl space-y-4">
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <div class="font-mono text-sm text-gray-500">{{ $invoice->invoice_number }}</div>
                <div class="text-xl font-bold mt-1">{{ $invoice->client_name }}</div>
                <div class="text-sm text-gray-500 mt-0.5">{{ $invoice->invoice_date->format('d M Y') }}</div>
            </div>
            <span class="text-sm px-3 py-1 rounded-full font-semibold
                {{ $invoice->payment_status === 'LUNAS' ? 'bg-green-100 text-green-700' :
                   ($invoice->payment_status === 'DIBATALKAN' ? 'bg-gray-100 text-gray-500' :
                   'bg-amber-100 text-amber-700') }}">
                {{ $invoice->payment_status }}
            </span>
        </div>

        <table class="w-full text-sm mb-4">
            <thead class="border-b">
                <tr class="text-xs text-gray-500 uppercase">
                    <th class="pb-2 text-left font-medium">Deskripsi</th>
                    <th class="pb-2 text-right font-medium">Qty</th>
                    <th class="pb-2 text-right font-medium">Harga</th>
                    <th class="pb-2 text-right font-medium">Disc</th>
                    <th class="pb-2 text-right font-medium">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr class="border-b last:border-0">
                    <td class="py-2 pr-4">{{ $item->description }}</td>
                    <td class="py-2 text-right">{{ $item->quantity }}</td>
                    <td class="py-2 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="py-2 text-right">{{ $item->discount > 0 ? $item->discount.'%' : '-' }}</td>
                    <td class="py-2 text-right font-medium">Rp {{ number_format($item->price * $item->quantity * (1 - $item->discount / 100), 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="pt-3 text-right font-semibold text-gray-700">Total</td>
                    <td class="pt-3 text-right font-bold text-lg">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if($invoice->notes)
        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-600">{{ $invoice->notes }}</div>
        @endif
    </div>

    @if($invoice->paymentDetail)
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <div class="font-semibold text-sm mb-3">Info Pembayaran</div>
        <div class="text-sm space-y-1">
            <div><span class="text-gray-500">Bank:</span> {{ $invoice->paymentDetail->bank_name }}</div>
            <div><span class="text-gray-500">Rekening:</span> {{ $invoice->paymentDetail->account_number }}</div>
            <div><span class="text-gray-500">Atas Nama:</span> {{ $invoice->paymentDetail->account_name }}</div>
        </div>
    </div>
    @endif

    @if($invoice->registration)
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <div class="font-semibold text-sm mb-3">Terkait Pendaftaran</div>
        <a href="{{ route('admin.registrasi.show', $invoice->registration) }}" class="text-sm text-emerald-600 hover:underline font-medium">
            {{ $invoice->registration->registration_code }} — {{ $invoice->registration->full_name }}
        </a>
    </div>
    @endif
</div>
@endsection
