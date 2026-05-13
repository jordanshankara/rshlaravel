<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;

class ArtikelController extends Controller
{
    public function index()
    {
        $articles = Article::where('status', 'PUBLISHED')
            ->with('author', 'categories')
            ->orderByDesc('published_at')
            ->paginate(9);

        $categories = Category::whereHas('articles', fn($q) => $q->where('status', 'PUBLISHED'))
            ->orderBy('name')
            ->get();

        return view('public.artikel.index', compact('articles', 'categories'));
    }

    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'PUBLISHED')
            ->with('author', 'categories')
            ->firstOrFail();

        $related = Article::where('status', 'PUBLISHED')
            ->where('id', '!=', $article->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('public.artikel.show', compact('article', 'related'));
    }
}
