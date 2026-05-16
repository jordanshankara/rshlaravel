<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;

class PaymentDetailController extends Controller
{
    public function index()
    {
        $paymentDetails = PaymentDetail::orderByDesc('is_default')->orderBy('bank_name')->get();
        return view('admin.payment-detail.index', compact('paymentDetails'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name'   => 'required|string|max:255',
            'is_default'     => 'nullable|boolean',
        ]);

        if (!empty($data['is_default'])) {
            PaymentDetail::query()->update(['is_default' => false]);
        }
        $data['is_default'] = !empty($data['is_default']);

        PaymentDetail::create($data);
        return back()->with('success', 'Rekening berhasil ditambahkan.');
    }

    public function update(Request $request, PaymentDetail $paymentDetail)
    {
        $data = $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name'   => 'required|string|max:255',
            'is_default'     => 'nullable|boolean',
        ]);

        if (!empty($data['is_default'])) {
            PaymentDetail::where('id', '!=', $paymentDetail->id)->update(['is_default' => false]);
        }
        $data['is_default'] = !empty($data['is_default']);

        $paymentDetail->update($data);
        return back()->with('success', 'Rekening berhasil diperbarui.');
    }

    public function destroy(PaymentDetail $paymentDetail)
    {
        $paymentDetail->delete();
        return back()->with('success', 'Rekening berhasil dihapus.');
    }

    public function setDefault(PaymentDetail $paymentDetail)
    {
        PaymentDetail::query()->update(['is_default' => false]);
        $paymentDetail->update(['is_default' => true]);
        return back()->with('success', 'Rekening default berhasil diubah.');
    }
}
