@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Informasi Situs --}}
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Informasi Situs</h2>
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-4">
            @csrf
            @foreach([
                'site_name'    => 'Nama Situs',
                'site_tagline' => 'Tagline',
                'site_address' => 'Alamat',
                'site_phone'   => 'Telepon',
                'site_email'   => 'Email',
                'whatsapp_number' => 'Nomor WhatsApp CS',
            ] as $key => $label)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
                <input type="{{ $key === 'site_email' ? 'email' : 'text' }}" name="{{ $key }}"
                       value="{{ old($key, $settings[$key] ?? '') }}"
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>
            @endforeach
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Program</label>
                <textarea name="program_description" rows="3"
                          class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none">{{ old('program_description', $settings['program_description'] ?? '') }}</textarea>
            </div>
            <button type="submit" class="px-5 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">Simpan</button>
        </form>
    </div>

    {{-- Notifikasi Email --}}
    <div class="bg-white rounded-xl border shadow-sm p-6"
         x-data="{
             testing: false,
             result: null,
             async testEmail() {
                 this.testing = true;
                 this.result = null;
                 try {
                     const res = await fetch('{{ route('admin.pengaturan.test-email') }}', {
                         method: 'POST',
                         headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                     });
                     this.result = await res.json();
                     console.log('[Test Email]', this.result);
                 } catch(e) {
                     this.result = { success: false, error: e.message };
                 }
                 this.testing = false;
             }
         }">
        <h2 class="font-semibold text-gray-800 mb-1">Notifikasi Email</h2>
        <p class="text-xs text-gray-400 mb-4">Email yang menerima notifikasi setiap kali ada pendaftaran baru. Pisahkan dengan koma jika lebih dari satu.</p>
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Penerima Notifikasi</label>
                <input type="text" name="notification_emails"
                       value="{{ old('notification_emails', $settings['notification_emails'] ?? '') }}"
                       placeholder="admin@rshsatubumi.id, cs@rshsatubumi.id"
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="px-5 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">Simpan</button>
                <button type="button" @click="testEmail()" :disabled="testing"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50">
                    <span x-show="!testing">Tes Kirim Email</span>
                    <span x-show="testing">Mengirim...</span>
                </button>
            </div>
        </form>
        <div x-show="result !== null" x-cloak class="mt-3 p-3 rounded-lg text-sm"
             :class="result?.success ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'">
            <template x-if="result?.success">
                <p>Email tes berhasil dikirim ke: <strong x-text="result.to"></strong></p>
            </template>
            <template x-if="result && !result.success">
                <div>
                    <p class="font-semibold mb-1">Gagal mengirim email</p>
                    <p x-text="result.to ? 'Ke: ' + result.to : ''"></p>
                    <p class="font-mono text-xs mt-1 break-all" x-text="result.error"></p>
                </div>
            </template>
        </div>
    </div>

    {{-- Konfigurasi AI --}}
    <div class="bg-white rounded-xl border shadow-sm p-6"
         x-data="{
             showKey: false,
             provider: '{{ old('ai_provider', $settings['ai_provider'] ?? 'openrouter') }}',
             providerUrls: {
                 openrouter: 'https://openrouter.ai/api/v1',
                 google:     'https://generativelanguage.googleapis.com/v1beta/openai/',
                 qwen:       'https://dashscope.aliyuncs.com/compatible-mode/v1',
                 custom:     ''
             },
             providerModels: {
                 openrouter: 'Contoh: meta-llama/llama-3.1-8b-instruct:free',
                 google:     'Contoh: gemini-2.0-flash-exp',
                 qwen:       'Contoh: qwen-turbo',
                 custom:     'Nama model sesuai provider'
             },
             get autoUrl() {
                 return this.providerUrls[this.provider] ?? '';
             },
             get autoModel() {
                 return this.providerModels[this.provider] ?? '';
             }
         }">
        <h2 class="font-semibold text-gray-800 mb-1">Konfigurasi AI Artikel</h2>
        <p class="text-xs text-gray-400 mb-4">Digunakan untuk fitur "Buat dengan AI" di halaman Artikel. Mendukung OpenRouter, Google AI Studio, Qwen DashScope, atau provider OpenAI-compatible lainnya.</p>
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-4">
            @csrf
            {{-- Provider --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Provider AI</label>
                <select name="ai_provider" x-model="provider"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                    <option value="openrouter">OpenRouter</option>
                    <option value="google">Google AI Studio (Gemini)</option>
                    <option value="qwen">Qwen DashScope</option>
                    <option value="custom">Custom / Lainnya</option>
                </select>
            </div>
            {{-- Base URL --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Base URL API</label>
                <input type="text" name="ai_base_url"
                       :value="'{{ old('ai_base_url', $settings['ai_base_url'] ?? '') }}' || autoUrl"
                       :placeholder="autoUrl"
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none font-mono text-xs">
            </div>
            {{-- API Key --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">API Key</label>
                <div class="relative">
                    <input :type="showKey ? 'text' : 'password'" name="ai_api_key"
                           value="{{ old('ai_api_key', $settings['ai_api_key'] ?? '') }}"
                           placeholder="sk-..."
                           class="w-full px-3 py-2 pr-10 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none font-mono">
                    <button type="button" @click="showKey = !showKey"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg x-show="!showKey" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showKey" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
            </div>
            {{-- Model --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Model</label>
                <input type="text" name="ai_model"
                       value="{{ old('ai_model', $settings['ai_model'] ?? '') }}"
                       :placeholder="autoModel"
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none font-mono text-xs">
            </div>
            {{-- Prompt --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">System Prompt</label>
                <textarea name="ai_article_prompt" rows="6"
                          placeholder="Biarkan kosong untuk menggunakan prompt bawaan. Gunakan {topic} dan {sources} sebagai placeholder."
                          class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none font-mono text-xs">{{ old('ai_article_prompt', $settings['ai_article_prompt'] ?? '') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Placeholder: <code class="font-mono bg-gray-100 px-1 rounded">{topic}</code> dan <code class="font-mono bg-gray-100 px-1 rounded">{sources}</code></p>
            </div>
            <button type="submit" class="px-5 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">Simpan</button>
        </form>
    </div>

    {{-- Keamanan --}}
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <h2 class="font-semibold text-gray-800 mb-1">Keamanan (Cloudflare Turnstile)</h2>
        <p class="text-xs text-gray-400 mb-4">Konfigurasi CAPTCHA diatur melalui file <code class="font-mono bg-gray-100 px-1 rounded">.env</code> di server.</p>
        <div class="flex items-center gap-3 p-3 rounded-lg {{ config('services.turnstile.site_key') ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200' }}">
            @if(config('services.turnstile.site_key'))
            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-green-800">Turnstile aktif</p>
                <p class="text-xs text-green-600 font-mono">Site Key: {{ substr(config('services.turnstile.site_key'), 0, 12) }}…</p>
            </div>
            @else
            <div class="w-5 h-5 rounded-full bg-amber-400 flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-amber-800">Turnstile belum dikonfigurasi</p>
                <p class="text-xs text-amber-600">Tambahkan <code class="font-mono">TURNSTILE_SITE_KEY</code> dan <code class="font-mono">TURNSTILE_SECRET_KEY</code> ke <code class="font-mono">.env</code></p>
            </div>
            @endif
        </div>
    </div>

    {{-- Detail Pembayaran --}}
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800">Detail Pembayaran</h2>
            <a href="{{ route('admin.payment-detail.index') }}" class="text-xs text-[#2d6a4f] font-medium hover:underline">Kelola →</a>
        </div>
        <div class="space-y-3">
            @forelse($paymentDetails as $pd)
            <div class="flex items-center justify-between p-3 border rounded-lg">
                <div>
                    <div class="text-sm font-medium text-gray-800">{{ $pd->bank_name }} — {{ $pd->account_number }}</div>
                    <div class="text-xs text-gray-500">{{ $pd->account_name }}
                        @if($pd->is_default)
                        <span class="ml-1 px-1.5 py-0.5 bg-green-100 text-green-700 rounded text-xs font-medium">Default</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400">Belum ada rekening. <a href="{{ route('admin.payment-detail.index') }}" class="text-[#2d6a4f] hover:underline">Tambah sekarang →</a></p>
            @endforelse
        </div>
    </div>

</div>
@endsection
