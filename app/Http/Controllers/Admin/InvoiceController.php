<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PaymentDetail;
use App\Models\InvoiceProduct;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('author', 'paymentDetail', 'registration')
            ->orderByDesc('invoice_date');

        if ($status = $request->get('status')) {
            $query->where('payment_status', $status);
        }
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        $invoices = $query->paginate(20)->withQueryString();
        $counts = [
            'all'         => Invoice::count(),
            'BELUM_LUNAS' => Invoice::where('payment_status', 'BELUM_LUNAS')->count(),
            'LUNAS'       => Invoice::where('payment_status', 'LUNAS')->count(),
            'DIBATALKAN'  => Invoice::where('payment_status', 'DIBATALKAN')->count(),
        ];
        $totalCount   = $counts['all'];
        $totalRevenue = Invoice::where('payment_status', 'LUNAS')->sum('total_amount');
        $unpaidCount  = $counts['BELUM_LUNAS'];

        return view('admin.invoice.index', compact('invoices', 'counts', 'totalCount', 'totalRevenue', 'unpaidCount'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items', 'paymentDetail', 'author', 'registration.programPeriod');
        $settings = SiteSetting::getMany(['site_name', 'site_address', 'site_phone']);
        return view('admin.invoice.show', compact('invoice', 'settings'));
    }

    public function create()
    {
        $paymentDetails = PaymentDetail::all();
        $products = InvoiceProduct::where('is_active', true)->get();
        return view('admin.invoice.create', compact('paymentDetails', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name'       => 'required|string|max:255',
            'invoice_date'      => 'required|date',
            'payment_status'    => 'required|in:LUNAS,BELUM_LUNAS,DIBATALKAN',
            'payment_detail_id' => 'nullable|exists:payment_details,id',
            'notes'             => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.price'       => 'required|numeric|min:0',
            'items.*.discount'    => 'nullable|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request) {
            $year = now()->year;
            $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);
            $last = Invoice::whereYear('created_at', $year)->whereMonth('created_at', now()->month)
                ->where('is_system_generated', false)->count() + 1;
            $invoiceNumber = "INV-{$year}{$month}" . str_pad($last, 3, '0', STR_PAD_LEFT);

            $total = collect($request->items)->sum(function ($item) {
                $discount = $item['discount'] ?? 0;
                return $item['price'] * $item['quantity'] * (1 - $discount / 100);
            });

            $invoice = Invoice::create([
                'invoice_number'      => $invoiceNumber,
                'client_name'         => $request->client_name,
                'invoice_date'        => $request->invoice_date,
                'payment_status'      => $request->payment_status,
                'total_amount'        => $total,
                'notes'               => $request->notes,
                'author_id'           => Auth::id(),
                'payment_detail_id'   => $request->payment_detail_id,
                'is_system_generated' => false,
            ]);

            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'price'       => $item['price'],
                    'discount'    => $item['discount'] ?? 0,
                ]);
            }
        });

        return redirect()->route('admin.invoice.index')->with('success', 'Invoice berhasil dibuat.');
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items', 'paymentDetail');
        $paymentDetails = PaymentDetail::all();
        $products = InvoiceProduct::where('is_active', true)->get();
        return view('admin.invoice.edit', compact('invoice', 'paymentDetails', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'client_name'       => 'required|string|max:255',
            'invoice_date'      => 'required|date',
            'payment_status'    => 'required|in:LUNAS,BELUM_LUNAS,DIBATALKAN',
            'payment_detail_id' => 'nullable|exists:payment_details,id',
            'notes'             => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.price'       => 'required|numeric|min:0',
            'items.*.discount'    => 'nullable|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            $total = collect($request->items)->sum(function ($item) {
                $discount = $item['discount'] ?? 0;
                return $item['price'] * $item['quantity'] * (1 - $discount / 100);
            });

            $invoice->update([
                'client_name'       => $request->client_name,
                'invoice_date'      => $request->invoice_date,
                'payment_status'    => $request->payment_status,
                'total_amount'      => $total,
                'notes'             => $request->notes,
                'payment_detail_id' => $request->payment_detail_id,
            ]);

            InvoiceItem::where('invoice_id', $invoice->id)->delete();
            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'price'       => $item['price'],
                    'discount'    => $item['discount'] ?? 0,
                ]);
            }
        });

        return redirect()->route('admin.invoice.show', $invoice)->with('success', 'Invoice berhasil diperbarui.');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->is_system_generated) {
            return back()->withErrors(['error' => 'Invoice otomatis tidak dapat dihapus.']);
        }
        $invoice->delete();
        return redirect()->route('admin.invoice.index')->with('success', 'Invoice berhasil dihapus.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load('items', 'paymentDetail', 'registration.programPeriod');
        $settings = SiteSetting::getMany(['site_name', 'site_address', 'site_phone', 'site_email']);

        $logoPath = public_path('assets/logo/logo-rec-white.png');
        $logoData = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

        $pdf = Pdf::loadView('admin.invoice.pdf', compact('invoice', 'settings', 'logoData'));
        $pdf->setPaper('a4', 'portrait');

        $statusLabel = match($invoice->payment_status) {
            'LUNAS'      => 'LUNAS',
            'DIBATALKAN' => 'DIBATALKAN',
            default      => 'BELUM-LUNAS',
        };
        $filename = "Invoice-{$invoice->invoice_number}-{$statusLabel}.pdf";
        return $pdf->download($filename);
    }
}
