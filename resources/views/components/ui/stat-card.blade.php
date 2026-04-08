<div class="card rounded-2xl p-5">
    <div class="flex items-start justify-between mb-3">

        <p style="font-size:13px;color:var(--text-3)">
            {{ $title }}
        </p>

        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
             style="background: {{ $iconBg ?? 'rgba(124,58,237,.18)' }}">

            {{ $icon }}

        </div>

    </div>

    <div class="flex items-baseline gap-2">

        <p style="font-size:32px;font-weight:800;color:var(--text-1);line-height:1">
            {{ $value ?? NULL }}
        </p>

        @isset($meta)
            <span style="{{ $metaStyle ?? 'font-size:13px;font-weight:600;color:#22c55e' }}">
                {{ $meta }}
            </span>
        @endisset

    </div>

</div>