<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    private const SETTING_KEYS = [
        'site_name', 'site_tagline', 'site_address', 'site_phone', 'site_email',
        'bank_name', 'bank_account_number', 'bank_account_name',
        'google_maps_embed',
        'whatsapp_number',
        'program_description',
        'notification_emails',
        'ai_provider', 'ai_base_url', 'ai_api_key', 'ai_model', 'ai_article_prompt',
    ];

    public function index()
    {
        $settings = SiteSetting::getMany(self::SETTING_KEYS);
        $paymentDetails = PaymentDetail::orderByDesc('is_default')->get();
        return view('admin.pengaturan.index', compact('settings', 'paymentDetails'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'          => 'nullable|string|max:255',
            'site_email'         => 'nullable|email',
            'site_phone'         => 'nullable|string|max:30',
            'whatsapp_number'    => 'nullable|string|max:30',
            'notification_emails'=> 'nullable|string|max:500',
        ]);

        foreach (self::SETTING_KEYS as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, (string) $request->get($key, ''));
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function storePaymentDetail(Request $request)
    {
        $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name'   => 'required|string|max:255',
            'is_default'     => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            PaymentDetail::where('is_default', true)->update(['is_default' => false]);
        }

        PaymentDetail::create([
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'is_default'     => $request->boolean('is_default'),
        ]);

        return back()->with('success', 'Detail pembayaran berhasil ditambahkan.');
    }

    public function updatePaymentDetail(Request $request, PaymentDetail $paymentDetail)
    {
        $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name'   => 'required|string|max:255',
            'is_default'     => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            PaymentDetail::where('is_default', true)->where('id', '!=', $paymentDetail->id)
                ->update(['is_default' => false]);
        }

        $paymentDetail->update([
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'is_default'     => $request->boolean('is_default'),
        ]);

        return back()->with('success', 'Detail pembayaran berhasil diperbarui.');
    }

    public function destroyPaymentDetail(PaymentDetail $paymentDetail)
    {
        $paymentDetail->delete();
        return back()->with('success', 'Detail pembayaran berhasil dihapus.');
    }
}
