@props([
    'shiftGroups' => [],
    'currentPage' => 1,
    'totalPages' => 3,
    'from' => 1,
    'to' => 4,
    'total' => 12,
])

<div class="card rounded-2xl mb-5" style="overflow:hidden">
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:580px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Nama
                        Grup Shift</th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Deskripsi</th>
                    <th class="text-right px-5 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Aksi
                    </th>
                </tr>
            </thead>
            <tbody id="sgTableBody">
                @foreach ($shiftGroups as $shift)
                    <tr class="sg-row" style="{{ !$loop->last ? 'border-bottom:1px solid var(--border)' : '' }}"
                        data-name="{{ strtolower($shift['name'] . ' ' . $shift['description']) }}">

                        {{-- Name --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background:rgba(96,165,250,.15)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="#60a5fa" stroke-width="2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                </div>
                                <span
                                    style="font-size:14px;font-weight:700;color:var(--text-1)">{{ $shift['name'] }}</span>
                            </div>
                        </td>

                        {{-- Description --}}
                        <td class="px-4 py-4" style="font-size:13px;color:var(--text-2);max-width:260px">
                            {{ $shift['description'] }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center"
                                    data-name="{{ $shift['name'] }}" data-description="{{ $shift['description'] }}"
                                    data-id="{{ $shift['id'] }}"
                                    onclick="openEditShiftGroup(this.dataset.id, this.dataset.name, this.dataset.description)">
                                    <svg width="13" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>

                                <form id="delete-form-shift-group-{{ $shift['id'] }}"
                                    action="{{ route('shift_groups.destroy', $shift['id']) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="w-8 h-8 rounded-lg flex items-center justify-center"
                                        style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer"
                                        onclick="openDeleteShiftGroup('{{ $shift['name'] }}', {{ $shift['id'] }})">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                            <path d="M10 11v6M14 11v6M9 6V4h6v2" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
        style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">Menampilkan {{ $from }} hingga {{ $to }} dari
            {{ $total }} grup</p>
        <div class="flex items-center gap-1">
            @if ($currentPage > 1)
                <a href="{{ route('shift_groups.index', ['page' => $currentPage - 1]) }}"
                    class="px-3 h-8 rounded-lg ib-bg font-medium"
                    style="font-size:13px;color:var(--text-3);text-decoration:none;display:inline-flex;align-items:center">Sebelumnya</a>
            @else
                <button class="px-3 h-8 rounded-lg ib-bg font-medium" disabled
                    style="font-size:13px;color:var(--text-3);opacity:0.5;cursor:not-allowed">Sebelumnya</button>
            @endif

            @for ($i = 1; $i <= $totalPages; $i++)
                @if ($i === $currentPage)
                    <button class="w-8 h-8 rounded-lg font-semibold purbtn" disabled style="font-size:13px">
                        {{ $i }}
                    </button>
                @else
                    <a href="{{ route('shift_groups.index', ['page' => $i]) }}" class="w-8 h-8 rounded-lg ib-bg"
                        style="font-size:13px;color:var(--text-2);text-decoration:none;display:inline-flex;align-items:center;justify-content:center">
                        {{ $i }}
                    </a>
                @endif
            @endfor

            @if ($currentPage < $totalPages)
                <a href="{{ route('shift_groups.index', ['page' => $currentPage + 1]) }}"
                    class="px-3 h-8 rounded-lg ib-bg font-medium"
                    style="font-size:13px;color:var(--text-2);text-decoration:none;display:inline-flex;align-items:center">Selanjutnya</a>
            @else
                <button class="px-3 h-8 rounded-lg ib-bg font-medium" disabled
                    style="font-size:13px;color:var(--text-2);opacity:0.5;cursor:not-allowed">Selanjutnya</button>
            @endif
        </div>
    </div>
</div>
