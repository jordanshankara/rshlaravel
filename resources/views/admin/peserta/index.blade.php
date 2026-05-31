@extends('layouts.admin')
@section('title', 'Peserta Program')
@section('page-title', 'Peserta Program')
@section('header-actions')
<a href="{{ route('admin.energylevel.index') }}"
   class="inline-flex items-center gap-1.5 px-3 py-2 text-sm bg-[#2d6a4f] text-white rounded-lg hover:bg-[#1a5a3f]">
    📊 Dashboard Energy Level
</a>
@endsection
@section('content')

{{-- Filters --}}
<div class="bg-white rounded-xl border shadow-sm p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-40">
            <label class="block text-xs text-gray-500 mb-1">Cari Peserta</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama / kode…"
                   class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
        </div>
        <div class="w-52">
            <label class="block text-xs text-gray-500 mb-1">Periode</label>
            <select name="period_id" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                <option value="">Semua Periode</option>
                @foreach ($periods as $period)
                <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                    {{ $period->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="w-36">
            <label class="block text-xs text-gray-500 mb-1">Kehadiran</label>
            <select name="hadir" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                <option value="">Semua</option>
                <option value="1" {{ request('hadir') === '1' ? 'selected' : '' }}>Hadir</option>
                <option value="0" {{ request('hadir') === '0' ? 'selected' : '' }}>Belum Hadir</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-[#2d6a4f] text-white rounded-lg text-sm hover:bg-[#1a5a3f]">Filter</button>
        <a href="{{ route('admin.peserta.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-500 hover:bg-gray-50">Reset</a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border shadow-sm overflow-hidden">
    @if ($peserta->isEmpty())
    <div class="p-10 text-center text-gray-400 text-sm">
        Belum ada peserta. Peserta muncul setelah registrasi dikonfirmasi (status CONFIRMED atau FULLY_PAID).
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs">Peserta</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs">Periode</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs">Status</th>
                    <th class="text-center px-4 py-3 font-medium text-gray-500 text-xs">Kehadiran</th>
                    <th class="text-center px-4 py-3 font-medium text-gray-500 text-xs">Energy Level</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($peserta as $reg)
                @php
                    $progress = $reg->monitoringProgress();
                    [$done, $total] = explode('/', $progress);
                    $pct = $total > 0 ? round((int)$done / (int)$total * 100) : 0;
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-900">{{ $reg->full_name }}</p>
                        <p class="text-xs text-gray-400">{{ $reg->registration_code }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        {{ $reg->programPeriod?->name ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $statusClass = match($reg->status) {
                                'FULLY_PAID'  => 'bg-green-100 text-green-700',
                                'CONFIRMED'   => 'bg-blue-100 text-blue-700',
                                default       => 'bg-gray-100 text-gray-600',
                            };
                            $statusLabel = match($reg->status) {
                                'FULLY_PAID' => 'Lunas',
                                'CONFIRMED'  => 'Dikonfirmasi',
                                default      => $reg->status,
                            };
                        @endphp
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if ($reg->is_present)
                            <span class="inline-flex items-center gap-1 text-xs text-green-700 font-medium">
                                ✅ Hadir
                            </span>
                        @else
                            <form method="POST" action="{{ route('admin.peserta.hadir', $reg->id) }}">
                                @csrf
                                <button type="submit"
                                        class="text-xs px-2.5 py-1 border border-gray-300 rounded-lg text-gray-500 hover:bg-green-50 hover:border-green-300 hover:text-green-700 transition-colors"
                                        @click.prevent="adminConfirm('Tandai Hadir', 'Tandai {{ addslashes($reg->full_name) }} sebagai hadir dan generate 7 link Energy Level?', {okLabel:'Ya, Tandai'}).then(ok => ok && $el.closest('form').submit())">
                                    Tandai Hadir
                                </button>
                            </form>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($reg->is_present)
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-1.5" style="min-width:60px">
                                <div class="bg-[#2d6a4f] h-1.5 rounded-full" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 whitespace-nowrap">{{ $progress }} hari</span>
                        </div>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.peserta.show', $reg->id) }}"
                           class="text-xs px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Detail →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t">
        {{ $peserta->links() }}
    </div>
    @endif
</div>

@endsection
