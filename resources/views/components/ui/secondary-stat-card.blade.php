@props([
  'title' => 'Title',
  'value' => '',
  'meta' => '',
  'icon' => null,
  'iconBg' => 'bg-purple-100',
  'metaColor' => 'text-gray-500'
])

<div {{ $attributes->merge(['class' => 'card rounded-2xl p-5']) }}>
  <div class="flex items-start justify-between mb-3">
    <p class="text-[11px] font-semibold text-gray-400 tracking-wider uppercase">{{ $title }}</p>
    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $iconBg }}">
      @if($icon)
        {!! $icon !!}
      @else
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2" class="text-purple-400">
          <circle cx="12" cy="12" r="10"/>
        </svg>
      @endif
    </div>
  </div>

  <p class="text-4xl font-extrabold text-gray-900 leading-none">{{ $value }}</p>

  @if($meta)
    <p class="text-sm mt-2 {{ $metaColor }}">{{ $meta }}</p>
  @endif
</div>
