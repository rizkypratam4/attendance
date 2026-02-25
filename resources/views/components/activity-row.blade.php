@props(['activity'])

<div class="flex items-center gap-4">
    <div class="w-11 h-11 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-white"
         style="font-size:13px; background: linear-gradient(135deg, {{ $activity['gradient_from'] }}, {{ $activity['gradient_to'] }})">
        {{ $activity['initials'] }}
    </div>
    <div class="flex-1 min-w-0">
        <p style="font-size:14px;font-weight:600;color:var(--text-1)">{{ $activity['name'] }}</p>
        <p style="font-size:12px;color:var(--text-3)" class="truncate">{{ $activity['department'] }}</p>
    </div>
    <div class="text-right flex-shrink-0">
        <p style="font-size:13px;font-weight:700;color:{{ $activity['status_color'] }}">
            {{ $activity['status'] }}
        </p>
        <p style="font-size:12px;color:var(--text-3)">{{ $activity['time'] }}</p>
    </div>
</div>