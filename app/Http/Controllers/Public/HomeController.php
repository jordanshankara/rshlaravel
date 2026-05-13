<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ProgramPeriod;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $latestArticles = Article::where('status', 'PUBLISHED')
            ->with('author', 'categories')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $activePeriods = ProgramPeriod::where('is_active', true)
            ->orderBy('start_date')
            ->get()
            ->map(function ($p) {
                $p->filled = $p->registrations()->whereNotIn('status', ['CANCELLED'])->count();
                return $p;
            });

        $settings = SiteSetting::getMany([
            'site_name', 'site_tagline', 'site_phone', 'site_email',
            'whatsapp_number', 'google_maps_embed', 'program_description',
        ]);

        return view('public.home', compact('latestArticles', 'activePeriods', 'settings'));
    }
}
