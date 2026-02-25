@props(['department', 'last' => false])

<tr style="{{ !$last ? 'border-bottom:1px solid var(--border)' : '' }}">
    <td class="py-4" style="font-size:15px;color:var(--text-1);font-weight:500">
        {{ $department['name'] }}
    </td>
    <td class="py-4" style="font-size:15px;color:var(--text-2)">{{ $department['total'] }}</td>
    <td class="py-4" style="font-size:15px;color:var(--text-2)">{{ $department['present'] }}</td>
    <td class="py-4" style="font-size:15px;color:var(--text-2)">{{ $department['late'] }}</td>
    <td class="py-4">
        <div class="flex items-center gap-3">
            <div class="h-2 rounded-full overflow-hidden flex-1"
                 style="background:var(--bg-ghost);max-width:180px">
                <div class="ebar h-full rounded-full" style="width:{{ $department['efficiency'] }}%"></div>
            </div>
            <span style="font-size:13px;color:var(--text-3);font-weight:600">
                {{ $department['efficiency'] }}%
            </span>
        </div>
    </td>
</tr>