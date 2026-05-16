<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;

class InvoiceProductController extends Controller
{
    public function index()
    {
        $products = InvoiceProduct::orderBy('category')->orderBy('name')->get();
        return view('admin.invoice-product.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'is_active'   => 'nullable|boolean',
        ]);
        $data['is_active'] = !empty($data['is_active']);

        InvoiceProduct::create($data);
        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, InvoiceProduct $invoiceProduct)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'is_active'   => 'nullable|boolean',
        ]);
        $data['is_active'] = !empty($data['is_active']);

        $invoiceProduct->update($data);
        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(InvoiceProduct $invoiceProduct)
    {
        $invoiceProduct->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function toggle(InvoiceProduct $invoiceProduct)
    {
        $invoiceProduct->update(['is_active' => !$invoiceProduct->is_active]);
        return response()->json(['is_active' => $invoiceProduct->is_active]);
    }
}
