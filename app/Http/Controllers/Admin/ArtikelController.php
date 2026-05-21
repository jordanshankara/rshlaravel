<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        $counts = [
            'all'       => Article::count(),
            'PUBLISHED' => Article::where('status', 'PUBLISHED')->count(),
            'DRAFT'     => Article::where('status', 'DRAFT')->count(),
        ];
        return view('admin.artikel.index', compact('articles', 'counts'));
    }

    public function create()
    {
        $aiDraft = session()->pull('ai_draft');
        return view('admin.artikel.create', compact('aiDraft'));
    }

    public function generateAI(Request $request)
    {
        if (auth()->user()->role !== 'ADMIN') abort(403, 'Hanya admin yang dapat menggunakan AI.');

        $request->validate([
            'topic'   => 'required|string|max:2000',
            'sources' => 'nullable|string|max:5000',
        ]);

        $apiKey  = SiteSetting::get('ai_api_key');
        $baseUrl = SiteSetting::get('ai_base_url', 'https://openrouter.ai/api/v1');
        $model   = SiteSetting::get('ai_model', 'openai/gpt-4o-mini');
        $prompt  = SiteSetting::get('ai_article_prompt') ?: $this->defaultAiPrompt();

        if (!$apiKey) {
            return back()->with('error', 'API Key AI belum dikonfigurasi. Pergi ke Pengaturan → Konfigurasi AI.');
        }

        if (!$this->isAllowedAiUrl($baseUrl)) {
            return back()->with('error', 'Base URL AI tidak valid. Harus HTTPS dan bukan alamat jaringan internal.');
        }

        $systemPrompt = str_replace(
            ['{topic}', '{sources}'],
            [$request->topic, $request->sources ?? '(tidak ada)'],
            $prompt
        );

        try {
            $response = Http::withToken($apiKey)
                ->timeout(90)
                ->post(rtrim($baseUrl, '/') . '/chat/completions', [
                    'model'       => $model,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => 'Tulis artikelnya sekarang dalam format JSON yang diminta.'],
                    ],
                    'temperature' => 0.7,
                ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal terhubung ke AI: ' . $e->getMessage());
        }

        if (!$response->successful()) {
            $errMsg = $response->json('error.message') ?? $response->body();
            return back()->with('error', 'Gagal menghubungi AI: ' . $errMsg);
        }

        $raw  = $response->json('choices.0.message.content', '');
        $json = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', trim($raw));
        $data = json_decode($json, true);

        if (!$data || empty($data['title'])) {
            return back()->with('error', 'Format respons AI tidak valid. Coba ubah prompt atau model.');
        }

        session(['ai_draft' => $data]);
        return redirect()->route('admin.artikel.create');
    }

    private function isAllowedAiUrl(string $url): bool
    {
        $parsed = parse_url($url);
        if (!$parsed || ($parsed['scheme'] ?? '') !== 'https') return false;
        $host = strtolower($parsed['host'] ?? '');
        if (!$host) return false;
        if (in_array($host, ['localhost', '::1'], true)) return false;
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            // Reject private, loopback, and reserved IP ranges
            return filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
        }
        return true;
    }

    private function defaultAiPrompt(): string
    {
        return "Kamu adalah penulis artikel kesehatan holistik untuk RSH Satu Bumi (rumah sehat di Indonesia). Tulis artikel informatif dan engaging dalam Bahasa Indonesia tentang topik berikut:\n{topic}\n\nReferensi tambahan:\n{sources}\n\nBerikan respons HANYA dalam format JSON berikut (tanpa teks lain):\n{\"title\":\"...\",\"excerpt\":\"...(max 200 karakter, ringkasan menarik)\",\"content\":\"...(HTML lengkap menggunakan tag <p>, <h2>, <h3>, <ul>, <li>, <strong>)\",\"categories\":[\"...\",\"...\"]}";
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:500',
            'slug'         => 'nullable|string|max:600',
            'excerpt'      => 'nullable|string',
            'content'      => 'required|string',
            'status'       => 'required|in:DRAFT,PUBLISHED',
            'cover_image'  => 'nullable|string',
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);
        $base = $slug;
        $count = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }

        $data['slug']         = $slug;
        $data['published_at'] = $request->status === 'PUBLISHED' ? now() : null;
        unset($data['categories']);

        $data['content'] = $this->sanitizeContent($data['content']);

        $article = Article::create($data);
        $article->author_id = Auth::id();
        $article->save();
        if ($request->categories) {
            $article->categories()->sync($request->categories);
        }

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil disimpan.');
    }

    public function edit(Article $artikel)
    {
        $artikel->load('categories');
        return view('admin.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, Article $artikel)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:500',
            'slug'         => 'nullable|string|max:600',
            'excerpt'      => 'nullable|string',
            'content'      => 'required|string',
            'status'       => 'required|in:DRAFT,PUBLISHED',
            'cover_image'  => 'nullable|string',
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($request->filled('cover_image') && $request->cover_image !== $artikel->cover_image) {
            if ($artikel->cover_image) {
                Storage::disk('public')->delete($artikel->cover_image);
            }
        }

        if ($request->status === 'PUBLISHED' && !$artikel->published_at) {
            $data['published_at'] = now();
        }

        if ($request->filled('slug')) {
            $newSlug = Str::slug($request->slug);
            if ($newSlug !== $artikel->slug) {
                $base = $newSlug;
                $count = 1;
                while (Article::where('slug', $newSlug)->where('id', '!=', $artikel->id)->exists()) {
                    $newSlug = $base . '-' . $count++;
                }
                $data['slug'] = $newSlug;
            }
        }

        unset($data['categories']);

        $data['content'] = $this->sanitizeContent($data['content']);

        $artikel->update($data);
        $artikel->categories()->sync($request->categories ?? []);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $artikel)
    {
        if (auth()->user()->role !== 'ADMIN') abort(403, 'Hanya admin yang dapat menghapus artikel.');

        if ($artikel->cover_image) {
            Storage::disk('public')->delete($artikel->cover_image);
        }
        $artikel->delete();
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function getCategories()
    {
        return response()->json(Category::orderBy('name')->get(['id', 'name']));
    }

    public function createCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:categories,name']);
        $cat = Category::create(['name' => $request->name]);
        return response()->json(['id' => $cat->id, 'name' => $cat->name]);
    }

    public function uploadCover(Request $request)
    {
        $request->validate(['file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:4096']);
        $path = $request->file('file')->store('articles', 'public');
        return response()->json(['url' => $path]);
    }

    private function sanitizeContent(string $html): string
    {
        $allowed = '<p><br><strong><em><u><s><h2><h3><ol><ul><li><blockquote><a><img>';
        $html = strip_tags($html, $allowed);
        // Remove on* event attributes (onerror, onclick, onload, etc.)
        $html = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\')/i', '', $html);
        // Replace javascript: in href/src with #
        $html = preg_replace('/\b(href|src)\s*=\s*["\']?\s*javascript:/i', '$1="#"', $html);
        return $html;
    }
}
