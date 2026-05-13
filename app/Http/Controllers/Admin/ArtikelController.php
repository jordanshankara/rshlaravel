<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('author', 'categories')->orderByDesc('created_at');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $articles = $query->paginate(15)->withQueryString();
        return view('admin.artikel.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.artikel.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:500',
            'excerpt'     => 'required|string',
            'content'     => 'required|string',
            'status'      => 'required|in:DRAFT,PUBLISHED',
            'cover_image' => 'nullable|image|max:2048',
            'categories'  => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $slug = Str::slug($request->title);
        $base = $slug;
        $count = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('articles', 'public');
        }

        $data['slug'] = $slug;
        $data['author_id'] = Auth::id();
        $data['published_at'] = $request->status === 'PUBLISHED' ? now() : null;
        unset($data['categories']);

        $article = Article::create($data);
        if ($request->categories) {
            $article->categories()->sync($request->categories);
        }

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil disimpan.');
    }

    public function edit(Article $artikel)
    {
        $artikel->load('categories');
        $categories = Category::orderBy('name')->get();
        return view('admin.artikel.edit', compact('artikel', 'categories'));
    }

    public function update(Request $request, Article $artikel)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:500',
            'excerpt'     => 'required|string',
            'content'     => 'required|string',
            'status'      => 'required|in:DRAFT,PUBLISHED',
            'cover_image' => 'nullable|image|max:2048',
            'categories'  => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($artikel->cover_image) {
                Storage::disk('public')->delete($artikel->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('articles', 'public');
        }

        if ($request->status === 'PUBLISHED' && !$artikel->published_at) {
            $data['published_at'] = now();
        }
        unset($data['categories']);

        $artikel->update($data);
        $artikel->categories()->sync($request->categories ?? []);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $artikel)
    {
        if ($artikel->cover_image) {
            Storage::disk('public')->delete($artikel->cover_image);
        }
        $artikel->delete();
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
