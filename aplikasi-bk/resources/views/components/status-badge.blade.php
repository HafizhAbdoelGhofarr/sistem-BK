@props(['status'])

@php
$status = strtolower($status ?? 'aman');

$configs = [
    'aman' => [
        'bg' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
        'dot' => 'bg-emerald-500',
        'label' => 'Aman',
    ],
    'perhatian' => [
        'bg' => 'bg-amber-50 border-amber-200 text-amber-700',
        'dot' => 'bg-amber-500',
        'label' => 'Perhatian',
    ],
    'pembinaan' => [
        'bg' => 'bg-orange-50 border-orange-200 text-orange-700',
        'dot' => 'bg-orange-500',
        'label' => 'Pembinaan',
    ],
    'kritis' => [
        'bg' => 'bg-rose-50 border-rose-200 text-rose-700',
        'dot' => 'bg-rose-500',
        'label' => 'Kritis',
    ],
];

$config = $configs[$status] ?? $configs['aman'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border ' . $config['bg']]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ $config['label'] }}
</span>
