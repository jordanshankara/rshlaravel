@extends('layouts.admin')
@section('title', 'Buat Periode Baru')
@section('page-title', 'Buat Periode Baru')
@section('header-actions')
<a href="{{ route('admin.program.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form id="period-form" method="POST" action="{{ route('admin.program.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Periode</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>

            {{-- Durasi Program --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Program</label>
                <div class="flex flex-wrap items-center gap-4">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="duration_type" value="3" class="text-[#2d6a4f]">
                        <span class="text-sm text-gray-700">3 Hari</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="duration_type" value="7" class="text-[#2d6a4f]">
                        <span class="text-sm text-gray-700">7 Hari</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="duration_type" value="custom" class="text-[#2d6a4f]">
                        <span class="text-sm text-gray-700">Custom</span>
                    </label>
                    <div id="custom-days-wrap" class="hidden items-center gap-1.5">
                        <input type="number" id="custom-days" min="1" value="4" class="w-16 px-2 py-1 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                        <span class="text-sm text-gray-500">hari</span>
                    </div>
                </div>
                <p id="duration-hint" class="mt-1.5 text-xs text-amber-600">Pilih durasi terlebih dahulu sebelum mengisi tanggal.</p>
            </div>

            {{-- Tanggal --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none opacity-40 pointer-events-none select-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required readonly
                        class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-50 text-gray-400 cursor-not-allowed opacity-40">
                </div>
            </div>

            {{-- Harga & DP --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga (Rp)</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-sm font-medium text-gray-700">DP</label>
                        <label class="flex items-center gap-1.5 cursor-pointer select-none">
                            <span class="text-xs text-gray-500">Mode Persen</span>
                            <button type="button" id="dp-toggle-btn" role="switch" aria-checked="true"
                                class="relative w-8 h-4 rounded-full transition-colors bg-[#2d6a4f] focus:outline-none">
                                <span id="dp-toggle-thumb" class="absolute top-0.5 right-0.5 w-3 h-3 bg-white rounded-full shadow transition-transform"></span>
                            </button>
                        </label>
                    </div>
                    {{-- Mode persen --}}
                    <div id="dp-percent-wrap">
                        <div class="relative">
                            <input type="number" id="dp-percent" min="0" max="100" step="0.01" placeholder="50"
                                class="w-full px-3 py-2 pr-8 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">%</span>
                        </div>
                        <p id="dp-preview" class="mt-1 text-xs text-gray-500">–</p>
                    </div>
                    {{-- Mode manual --}}
                    <div id="dp-manual-wrap" class="hidden">
                        <input type="number" id="dp-manual" min="0" placeholder="0"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                    </div>
                    <input type="hidden" id="dp_amount" name="dp_amount" value="{{ old('dp_amount', 0) }}">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kuota</label>
                    <input type="number" name="quota" value="{{ old('quota', 20) }}" required min="1" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-[#2d6a4f]">
                        <span class="text-sm font-medium text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="w-full py-2.5 bg-[#2d6a4f] text-white font-semibold rounded-lg hover:bg-[#1a5a3f] text-sm">Simpan Periode</button>
        </form>
    </div>
</div>

<script>
(function () {
    // ── DP Toggle ───────────────────────────────────────────────────────────
    const priceInput     = document.getElementById('price');
    const toggleBtn      = document.getElementById('dp-toggle-btn');
    const toggleThumb    = document.getElementById('dp-toggle-thumb');
    const dpPercentWrap  = document.getElementById('dp-percent-wrap');
    const dpManualWrap   = document.getElementById('dp-manual-wrap');
    const dpPercentInput = document.getElementById('dp-percent');
    const dpManualInput  = document.getElementById('dp-manual');
    const dpPreview      = document.getElementById('dp-preview');
    const dpHidden       = document.getElementById('dp_amount');
    let dpPercentMode    = true;

    function setDPMode(isPercent) {
        dpPercentMode = isPercent;
        toggleBtn.setAttribute('aria-checked', isPercent ? 'true' : 'false');
        if (isPercent) {
            toggleBtn.classList.replace('bg-gray-300', 'bg-[#2d6a4f]');
            toggleThumb.style.transform = 'translateX(0)';
            dpPercentWrap.classList.remove('hidden');
            dpManualWrap.classList.add('hidden');
            updateDPCalc();
        } else {
            toggleBtn.classList.replace('bg-[#2d6a4f]', 'bg-gray-300');
            toggleThumb.style.transform = 'translateX(-16px)';
            dpPercentWrap.classList.add('hidden');
            dpManualWrap.classList.remove('hidden');
            dpHidden.value = dpManualInput.value || 0;
        }
    }

    function updateDPCalc() {
        if (!dpPercentMode) return;
        const price = parseInt(priceInput.value) || 0;
        const pct   = parseFloat(dpPercentInput.value) || 0;
        const dp    = Math.round(price * pct / 100);
        dpHidden.value = dp;
        dpPreview.textContent = (price && pct) ? '= Rp ' + dp.toLocaleString('id-ID') : '–';
    }

    toggleBtn.addEventListener('click', () => setDPMode(!dpPercentMode));
    priceInput.addEventListener('input', updateDPCalc);
    dpPercentInput.addEventListener('input', updateDPCalc);
    dpManualInput.addEventListener('input', function () { dpHidden.value = this.value || 0; });

    setDPMode(true);

    // ── Durasi & Tanggal ────────────────────────────────────────────────────
    const startInput   = document.getElementById('start_date');
    const endInput     = document.getElementById('end_date');
    const customWrap   = document.getElementById('custom-days-wrap');
    const customDays   = document.getElementById('custom-days');
    const durationHint = document.getElementById('duration-hint');
    const radios       = document.querySelectorAll('input[name="duration_type"]');

    const lockedClass = ['opacity-40', 'pointer-events-none', 'select-none'];

    function getSelectedDuration() {
        const sel = document.querySelector('input[name="duration_type"]:checked');
        if (!sel) return null;
        return sel.value === 'custom' ? (parseInt(customDays.value) || 1) : parseInt(sel.value);
    }

    function calcEndDate() {
        const dur = getSelectedDuration();
        if (!dur || !startInput.value) return;
        const d = new Date(startInput.value + 'T00:00:00');
        d.setDate(d.getDate() + dur - 1);
        const yyyy = d.getFullYear();
        const mm   = String(d.getMonth() + 1).padStart(2, '0');
        const dd   = String(d.getDate()).padStart(2, '0');
        endInput.value = `${yyyy}-${mm}-${dd}`;
    }

    function onDurationChange() {
        const sel = document.querySelector('input[name="duration_type"]:checked');
        if (!sel) return;
        const isCustom = sel.value === 'custom';
        customWrap.classList.toggle('hidden', !isCustom);
        customWrap.classList.toggle('flex', isCustom);
        // Unlock start_date
        lockedClass.forEach(c => startInput.classList.remove(c));
        startInput.removeAttribute('readonly');
        durationHint.classList.add('hidden');
        calcEndDate();
    }

    radios.forEach(r => r.addEventListener('change', onDurationChange));
    startInput.addEventListener('change', calcEndDate);
    customDays.addEventListener('input', calcEndDate);

    // Pre-fill from old() if validation failed
    @if(old('start_date') && old('end_date'))
    const oldStart = '{{ old('start_date') }}';
    const oldEnd   = '{{ old('end_date') }}';
    if (oldStart && oldEnd) {
        const diff = Math.round((new Date(oldEnd) - new Date(oldStart)) / 86400000) + 1;
        let matchRadio = document.querySelector(`input[name="duration_type"][value="${diff}"]`);
        if (!matchRadio) {
            matchRadio = document.querySelector('input[name="duration_type"][value="custom"]');
            customDays.value = diff;
        }
        if (matchRadio) {
            matchRadio.checked = true;
            onDurationChange();
        }
    }
    @endif
})();
</script>
@endsection
