@props(['assignment'])

<tr class="assign-row"
    data-name="{{ strtolower($assignment->employee->name) }}"
    data-id="{{ strtolower($assignment->employee->nik ?? $assignment->employee->id) }}"
    data-dept="{{ $assignment->employee->department->name ?? '' }}"
    data-shift="{{ strtolower($assignment->shiftCode->shiftGroup->name ?? 'unassigned') }}"
    style="border-bottom:1px solid var(--border)">
    <td class="px-5 py-4">
        <div class="flex items-center gap-3">
            <img src="{{ $assignment->employee->avatar_url ?? 'https://i.pravatar.cc/38' }}"
                 class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
            <div>
                <p style="font-size:14px;font-weight:700;color:var(--text-1)">{{ $assignment->employee->name }}</p>
                <p style="font-size:11.5px;color:var(--text-3)">ID: {{ $assignment->employee->nik ?? $assignment->employee->id }}</p>
            </div>
        </div>
    </td>
    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">{{ $assignment->employee->department->name ?? '' }}</td>
    <td class="px-4 py-4">
        @if($assignment->shiftCode)
            <div class="flex items-center gap-2 px-3 py-2 rounded-xl w-fit"
                 style="background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.25)">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" class="flex-shrink-0">
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                </svg>
                <span style="font-size:12.5px;font-weight:600;color:#4ade80;white-space:nowrap">
                    {{ $assignment->shiftCode->name }}
                </span>
            </div>
        @else
            <span style="font-size:12.5px;color:var(--text-3)">Unassigned</span>
        @endif
    </td>
    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">
        {{ optional($assignment->effective_date)->format('M d, Y') }} -
        {{ $assignment->end_date ? optional($assignment->end_date)->format('M d, Y') : 'Permanent' }}
    </td>
    <td class="px-5 py-4 text-right">
        <button class="assign-trigger ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto"
                data-name="{{ $assignment->employee->name }}" data-id="{{ $assignment->employee->nik ?? $assignment->employee->id }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
            </svg>
        </button>
    </td>
</tr>
