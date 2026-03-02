@props([
    'branches' => [],
])


<table class="w-full" style="border-collapse:collapse;min-width:600px">
    <thead>
        <tr style="background:rgba(124,58,237,.10);border-bottom:1px solid var(--border)">
            <th class="text-left px-5 py-3.5 font-semibold"
                style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                Branch Name
            </th>
            <th class="text-left px-4 py-3.5 font-semibold"
                style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                Status
            </th>
            <th class="text-right px-5 py-3.5 font-semibold"
                style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                Actions
            </th>
        </tr>
    </thead>

    <tbody id="branchTableBody">
        @forelse ($branches as $branch)
            <tr class="branch-row" style="border-bottom:1px solid var(--border)"
                data-name="{{ $branch->name }} {{ $branch->manager ?? '' }} {{ $branch->city ?? '' }}">

                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                            style="background:rgba(124,58,237,.20)">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa"
                                stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" />
                                <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
                                <line x1="12" y1="12" x2="12" y2="16" />
                                <line x1="10" y1="14" x2="14" y2="14" />
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">
                                {{ $branch->name }}
                            </p>
                        </div>
                    </div>
                </td>

                <td class="px-4 py-4">
                    @if ($branch->is_active)
                        <span class="px-3 py-1 rounded-full font-semibold"
                            style="font-size:11px;color:#22c55e;border:1px solid rgba(34,197,94,.35);letter-spacing:.04em">
                            Active
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full font-semibold"
                            style="font-size:11px;color:#ef4444;border:1px solid rgba(239,68,68,.35);letter-spacing:.04em">
                            Inactive
                        </span>
                    @endif
                </td>

                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center" title="Edit">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"> <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" /> <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" /> </svg>
                        </button>
                        <button class="w-8 h-8 rounded-lg flex items-center justify-center"
                            style="background:rgba(239,68,68,.10);color:#f87171;" title="Delete">
                            <!-- icon -->
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"> 
                                <polyline points="3 6 5 6 21 6" /> <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" /> 
                                <path d="M10 11v6M14 11v6M9 6V4h6v2" /> 
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="3" class="text-center py-6" style="color:var(--text-3);font-size:13px">
                    No branches found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
