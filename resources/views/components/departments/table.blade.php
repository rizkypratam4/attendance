@props([
    'departments' => [],
    'totalEmployees' => 0,
])

<div class="overflow-x-auto">
    <div class="min-w-max">
        <table class="w-full">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-6 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Nama Departemen
                    </th>

                    <th class="text-left px-6 py-3.5 font-semibold whitespace-nowrap"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Total Karyawan
                    </th>
                    <th class="text-right px-6 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody id="deptTableBody">
                @forelse ($departments as $department)
                    <tr class="dept-row" style="border-bottom:1px solid var(--border)"
                        data-name="{{ $department->name }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background:rgba(99,179,237,.15)">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="#63b3ed" stroke-width="2">
                                        <polyline points="16 18 22 12 16 6" />
                                        <polyline points="8 6 2 12 8 18" />
                                    </svg>
                                </div>
                                <div>
                                    <p style="font-size:15px;font-weight:700;color:var(--text-1)">
                                        {{ $department->name }}
                                    </p>
                                    <p style="font-size:12px;color:var(--text-3)">{{ $department->subtitle }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-4 py-1.5 rounded-full font-semibold"
                                style="font-size:12px;background:rgba(124,58,237,.25);color:#c4b5fd">
                                {{ $department->employees_count }} Karyawan
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button"
                                    onclick="openEditDepartment(
                                    '{{ $department->id }}',
                                    '{{ $department->name }}',
                                    '{{ $department->subtitle }}',
                                    '{{ $department->head_employee_id }}'
                                )"
                                    class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>

                                <form id="delete-form-department-{{ $department->id }}"
                                    action="{{ route('departments.destroy', $department->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="openDeleteDepartment('{{ $department->name }}', {{ $department->id }})"
                                        class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center"
                                        style="background:rgba(239,68,68,.10);color:#f87171">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                            <path d="M10 11v6M14 11v6" />
                                            <path d="M9 6V4h6v2" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-6" style="color:var(--text-3);font-size:14px">
                            Tidak ada departemen ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- ── TOTAL FOOTER ── --}}
        <div class="flex items-center justify-between px-6 py-4 border-t"
            style="border-color:var(--border);background:rgba(124,58,237,.04)">
            <div class="flex items-center gap-2">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                </svg>
                <span style="font-size:13px;color:var(--text-3)">Total karyawan terdaftar di departemen</span>
            </div>
            <span style="font-size:15px;font-weight:800;color:#a78bfa">
                {{ $totalEmployees }} <span style="font-size:12px;font-weight:500;color:var(--text-3)">karyawan</span>
            </span>
        </div>

    </div>
</div>
