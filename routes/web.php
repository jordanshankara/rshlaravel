<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Public as Pub;
use App\Http\Controllers\Public\StaticPageController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Admin ─────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Registrasi (Admin only)
    Route::prefix('registrasi')->name('registrasi.')->middleware('admin')->group(function () {
        Route::get('/', [Admin\RegistrasiController::class, 'index'])->name('index');
        Route::get('/{registration}', [Admin\RegistrasiController::class, 'show'])->name('show');
        Route::patch('/{registration}/status', [Admin\RegistrasiController::class, 'updateStatus'])->name('update-status');
        Route::post('/{registration}/reschedule', [Admin\RegistrasiController::class, 'reschedule'])->name('reschedule');
        Route::get('/{registration}/periods', [Admin\RegistrasiController::class, 'getAvailablePeriods'])->name('periods');
    });

    // Invoice (Admin only)
    Route::prefix('invoice')->name('invoice.')->middleware('admin')->group(function () {
        Route::get('/', [Admin\InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [Admin\InvoiceController::class, 'create'])->name('create');
        Route::post('/', [Admin\InvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}', [Admin\InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/edit', [Admin\InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [Admin\InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [Admin\InvoiceController::class, 'destroy'])->name('destroy');
        Route::get('/{invoice}/pdf', [Admin\InvoiceController::class, 'downloadPdf'])->name('pdf');
    });

    // Artikel
    Route::prefix('artikel')->name('artikel.')->group(function () {
        Route::get('/', [Admin\ArtikelController::class, 'index'])->name('index');
        Route::get('/create', [Admin\ArtikelController::class, 'create'])->name('create');
        Route::post('/', [Admin\ArtikelController::class, 'store'])->name('store');
        Route::post('/ai-generate', [Admin\ArtikelController::class, 'generateAI'])->name('ai-generate');
        Route::get('/kategoris', [Admin\ArtikelController::class, 'getCategories'])->name('kategoris.index');
        Route::post('/kategoris', [Admin\ArtikelController::class, 'createCategory'])->name('kategoris.store');
        Route::post('/upload-cover', [Admin\ArtikelController::class, 'uploadCover'])->name('upload-cover');
        Route::get('/{artikel}/edit', [Admin\ArtikelController::class, 'edit'])->name('edit');
        Route::put('/{artikel}', [Admin\ArtikelController::class, 'update'])->name('update');
        Route::delete('/{artikel}', [Admin\ArtikelController::class, 'destroy'])->name('destroy');
    });

    // Program Periods (Admin only)
    Route::prefix('program')->name('program.')->middleware('admin')->group(function () {
        Route::get('/', [Admin\ProgramPeriodController::class, 'index'])->name('index');
        Route::get('/create', [Admin\ProgramPeriodController::class, 'create'])->name('create');
        Route::post('/', [Admin\ProgramPeriodController::class, 'store'])->name('store');
        Route::get('/{program}/edit', [Admin\ProgramPeriodController::class, 'edit'])->name('edit');
        Route::put('/{program}', [Admin\ProgramPeriodController::class, 'update'])->name('update');
        Route::delete('/{program}', [Admin\ProgramPeriodController::class, 'destroy'])->name('destroy');
    });

    // Pengguna (Admin only)
    Route::prefix('pengguna')->name('pengguna.')->middleware('admin')->group(function () {
        Route::get('/', [Admin\PenggunaController::class, 'index'])->name('index');
        Route::get('/create', [Admin\PenggunaController::class, 'create'])->name('create');
        Route::post('/', [Admin\PenggunaController::class, 'store'])->name('store');
        Route::get('/{pengguna}/edit', [Admin\PenggunaController::class, 'edit'])->name('edit');
        Route::put('/{pengguna}', [Admin\PenggunaController::class, 'update'])->name('update');
        Route::delete('/{pengguna}', [Admin\PenggunaController::class, 'destroy'])->name('destroy');
    });

    // Pengaturan (Admin only)
    Route::prefix('pengaturan')->name('pengaturan.')->middleware('admin')->group(function () {
        Route::get('/', [Admin\PengaturanController::class, 'index'])->name('index');
        Route::post('/', [Admin\PengaturanController::class, 'update'])->name('update');
        Route::post('/payment-detail', [Admin\PengaturanController::class, 'storePaymentDetail'])->name('payment-detail.store');
        Route::put('/payment-detail/{paymentDetail}', [Admin\PengaturanController::class, 'updatePaymentDetail'])->name('payment-detail.update');
        Route::delete('/payment-detail/{paymentDetail}', [Admin\PengaturanController::class, 'destroyPaymentDetail'])->name('payment-detail.destroy');
        Route::post('/test-email', [Admin\PengaturanController::class, 'testEmail'])->name('test-email');
    });

    // Payment Detail standalone page (Admin only)
    Route::prefix('payment-detail')->name('payment-detail.')->middleware('admin')->group(function () {
        Route::get('/', [Admin\PaymentDetailController::class, 'index'])->name('index');
        Route::post('/', [Admin\PaymentDetailController::class, 'store'])->name('store');
        Route::put('/{paymentDetail}', [Admin\PaymentDetailController::class, 'update'])->name('update');
        Route::delete('/{paymentDetail}', [Admin\PaymentDetailController::class, 'destroy'])->name('destroy');
        Route::patch('/{paymentDetail}/default', [Admin\PaymentDetailController::class, 'setDefault'])->name('set-default');
    });

    // Invoice Products (Admin only)
    Route::prefix('invoice-product')->name('invoice-product.')->middleware('admin')->group(function () {
        Route::get('/', [Admin\InvoiceProductController::class, 'index'])->name('index');
        Route::post('/', [Admin\InvoiceProductController::class, 'store'])->name('store');
        Route::put('/{invoiceProduct}', [Admin\InvoiceProductController::class, 'update'])->name('update');
        Route::delete('/{invoiceProduct}', [Admin\InvoiceProductController::class, 'destroy'])->name('destroy');
        Route::patch('/{invoiceProduct}/toggle', [Admin\InvoiceProductController::class, 'toggle'])->name('toggle');
    });
});

// ── Public ───────────────────────────────────────────────────
Route::get('/', [Pub\HomeController::class, 'index'])->name('home');

Route::get('/tentang-kami', [StaticPageController::class, 'tentangKami'])->name('tentang-kami');
Route::get('/layanan', [StaticPageController::class, 'layanan'])->name('layanan');
Route::get('/kontak', [StaticPageController::class, 'kontak'])->name('kontak');
Route::get('/anand-krishna', [StaticPageController::class, 'anandKrishna'])->name('anand-krishna');
Route::get('/inspirator-kami', [StaticPageController::class, 'inspiratorKami'])->name('inspirator-kami');
Route::get('/sembuh-dari-leukimia', [StaticPageController::class, 'sembuhDariLeukimia'])->name('sembuh-dari-leukimia');
Route::get('/yayasan-anand-ashram', [StaticPageController::class, 'yayasanAnandAshram'])->name('yayasan-anand-ashram');

Route::prefix('artikel')->name('artikel.')->group(function () {
    Route::get('/', [Pub\ArtikelController::class, 'index'])->name('index');
    Route::get('/{slug}', [Pub\ArtikelController::class, 'show'])->name('show');
});

Route::prefix('daftar')->name('daftar.')->group(function () {
    Route::get('/', [Pub\PendaftaranController::class, 'index'])->name('index');
    Route::post('/', [Pub\PendaftaranController::class, 'store'])->name('store');
    Route::post('/konfirmasi', [Pub\PendaftaranController::class, 'konfirmasi'])->name('konfirmasi');
    Route::get('/invoice/{code}', [Pub\PendaftaranController::class, 'downloadInvoice'])->name('invoice');
    Route::get('/cek', [Pub\PendaftaranController::class, 'confirm'])->name('confirm');
    Route::post('/cek', [Pub\PendaftaranController::class, 'checkCode'])->name('check');
});
