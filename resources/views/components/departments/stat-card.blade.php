{{-- resources/views/components/stat-card.blade.php --}}

@props([
    'icon' => 'default',
    'color' => 'purple',
    'label' => 'Label',
    'value' => '0',
    'gradient' => null,
])

@php
$colors = [
    'purple' => [
        'bg' => 'rgba(124,58,237,.35)',
        'bgLight' => 'rgba(124,58,237,.15)',
        'border' => 'rgba(124,58,237,.3)',
        'solid' => '#7c3aed',
        'text' => 'rgba(167,139,250,.8)',
    ],
    'green' => [
        'bg' => 'rgba(16,185,129,.25)',
        'bgLight' => 'rgba(16,185,129,.10)',
        'border' => 'rgba(16,185,129,.25)',
        'solid' => '#059669',
        'text' => 'rgba(52,211,153,.8)',
    ],
    'orange' => [
        'bg' => 'rgba(251,146,60,.25)',
        'bgLight' => 'rgba(251,146,60,.10)',
        'border' => 'rgba(251,146,60,.25)',
        'solid' => '#ea580c',
        'text' => 'rgba(251,146,60,.8)',
    ],
    'blue' => [
        'bg' => 'rgba(59,130,246,.35)',
        'bgLight' => 'rgba(59,130,246,.15)',
        'border' => 'rgba(59,130,246,.3)',
        'solid' => '#2563eb',
        'text' => 'rgba(96,165,250,.8)',
    ],
    'red' => [
        'bg' => 'rgba(239,68,68,.35)',
        'bgLight' => 'rgba(239,68,68,.15)',
        'border' => 'rgba(239,68,68,.3)',
        'solid' => '#dc2626',
        'text' => 'rgba(248,113,113,.8)',
    ],
    'pink' => [
        'bg' => 'rgba(236,72,153,.35)',
        'bgLight' => 'rgba(236,72,153,.15)',
        'border' => 'rgba(236,72,153,.3)',
        'solid' => '#db2777',
        'text' => 'rgba(244,114,182,.8)',
    ],
];

$selectedColor = $colors[$color] ?? $colors['purple'];
$gradientStyle = $gradient ?? "linear-gradient(135deg,{$selectedColor['bg']} 0%,{$selectedColor['bgLight']} 100%)";

$icons = [
    'grid' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
    'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
    'users' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>',
    'chart' => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
    'folder' => '<path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>',
    'check' => '<polyline points="20 6 9 17 4 12"/>',
    'clock' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
    'dollar' => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>',
    'default' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
];

$selectedIcon = is_string($icon) && isset($icons[$icon]) ? $icons[$icon] : $icon;
@endphp

<div class="rounded-2xl p-5 flex items-center gap-4 transition-transform duration-200 hover:scale-[1.02]"
    style="background:{{ $gradientStyle }};border:1px solid {{ $selectedColor['border'] }}">
    
    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg"
        style="background:{{ $selectedColor['solid'] }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            {!! $selectedIcon !!}
        </svg>
    </div>
    
    <div class="min-w-0">
        <p class="text-[11px] font-semibold uppercase tracking-wider mb-1 truncate"
            style="color:{{ $selectedColor['text'] }}">
            {{ $label }}
        </p>
        <p class="text-[32px] font-extrabold text-white leading-none tracking-tight">
            {{ $value }}
        </p>
    </div>
</div>