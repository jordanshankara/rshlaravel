<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function tentangKami()
    {
        return view('public.tentang-kami');
    }

    public function layanan()
    {
        return view('public.layanan');
    }

    public function kontak()
    {
        return view('public.kontak');
    }

    public function anandKrishna()
    {
        return view('public.anand-krishna');
    }

    public function inspiratorKami()
    {
        return view('public.inspirator-kami');
    }

    public function sembuhDariLeukimia()
    {
        return view('public.sembuh-dari-leukimia');
    }

    public function yayasanAnandAshram()
    {
        return view('public.yayasan-anand-ashram');
    }

    public function termsConditions()
    {
        return view('public.terms-conditions');
    }
}
