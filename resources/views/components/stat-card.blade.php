@props([
    'title',
    'value',
    'subtitle' => null,
    'subtitleColor' => 'var(--text-3)',
])

<div class="card rounded-2xl p-5">
    <div class="flex items-start justify-between mb-3">
        <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
            {{ $title }}
        </p>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(124,58,237,.18)">
            {{ $icon ?? $slot }}
        </div>
    </div>

    <p style="font-size:36px;font-weight:800;color:var(--text-1);line-height:1">
        {{ $value }}
    </p>

    <p style="font-size:12px;color:{{ $subtitleColor }};margin-top:8px">
        {{ $subtitle }}
    </p>
</div>
