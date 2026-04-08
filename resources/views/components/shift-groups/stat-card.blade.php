@props([
    'iconBg'    => 'rgba(124,58,237,.18)',
    'icon'      => '',
    'label'     => '',
    'value'     => '',
    'subtext'   => '',
    'subtextColor' => 'var(--text-3)',
])

<div class="card rounded-2xl p-5">
    <div class="flex items-center gap-3 mb-3">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:{{ $iconBg }}">
            {!! $icon !!}
        </div>
        <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">{{ $label }}</p>
    </div>
    <p style="font-size:34px;font-weight:800;color:var(--text-1);line-height:1">{{ $value }}</p>
    <p style="font-size:12px;color:{{ $subtextColor }};margin-top:6px;font-weight:500">{{ $subtext }}</p>
</div>