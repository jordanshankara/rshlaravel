@php
$map = [
    'LUNAS'       => ['label' => 'Lunas',       'class' => 'bg-green-100 text-green-800'],
    'BELUM_LUNAS' => ['label' => 'Belum Lunas', 'class' => 'bg-amber-100 text-amber-800'],
    'DIBATALKAN'  => ['label' => 'Dibatalkan',  'class' => 'bg-gray-100 text-gray-600'],
];
$cfg = $map[$status] ?? ['label' => $status, 'class' => 'bg-gray-100 text-gray-600'];
@endphp
<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $cfg['class'] }}">
    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
    {{ $cfg['label'] }}
</span>
