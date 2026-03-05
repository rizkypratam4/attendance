@props([
    'employees' => [],
])


    <table class="w-full" style="border-collapse:collapse;min-width:640px">
        <thead>
            <tr style="border-bottom:1px solid var(--border)">
                <th class="text-left px-5 py-3 font-semibold"
                    style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">No</th>
                <th class="text-left px-4 py-3 font-semibold"
                    style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Full Name
                </th>
                <th class="text-left px-4 py-3 font-semibold"
                    style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">NIK
                </th>
                <th class="text-left px-4 py-3 font-semibold"
                    style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Department
                </th>
                <th class="text-left px-4 py-3 font-semibold"
                    style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Position</th>
                <th class="text-right px-5 py-3 font-semibold"
                    style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Actions</th>
            </tr>
        </thead>
        <tbody id="empTableBody">
            @forelse ($employees as $employee)
            <tr class="emp-row" style="border-bottom:1px solid var(--border)"
                data-name="{{ strtolower($employee->name . ' ' . ($employee->department?->name ?? '') . ' ' . $employee->nik . ' ' . $employee->position) }}">
                <td class="px-5 py-3.5">
                    {{ $loop->iteration }}
                </td>
                <td class="px-4 py-3.5">
                    <p style="font-size:14px;font-weight:700;color:var(--text-1)">{{ $employee->name }}</p>
                </td>
                <td class="px-4 py-3.5">
                    <span style="font-size:13.5px;color:#7c3aed;font-weight:600">{{ $employee->nik }}</span>
                </td>
                <td class="px-4 py-3.5">
                    <span class="px-3 py-1 rounded-full font-bold"
                        style="font-size:10.5px;letter-spacing:.06em;text-transform:uppercase;background:rgba(99,179,237,.18);color:#63b3ed">
                        {{ $employee->department->name }}
                    </span>
                </td>

                <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">{{ $employee->position }}</td>
                {{-- Actions --}}
                <td class="px-5 py-3.5 text-right">
                    <form id="delete-form-{{ $employee->id }}"
                          action="{{ route('employees.destroy', $employee->id) }}"
                          method="POST"
                          class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>

                    <button class="act-trigger ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto"
                            data-entity="employee"
                            data-name="{{ $employee->name }}"
                            data-nik="{{ $employee->nik }}"
                            data-machine-barcode="{{ $employee->machine_barcode }}"
                            data-branch-id="{{ $employee->branch_id }}"
                            data-department-id="{{ $employee->department_id }}"
                            data-position="{{ $employee->position }}"
                            data-location-id="{{ $employee->location_id }}"
                            data-title="{{ $employee->title }}"
                            data-employee-status="{{ $employee->employee_status }}"
                            data-contract-count="{{ $employee->contract_count }}"
                            data-is-active="{{ $employee->is_active }}"
                            data-delete-id="{{ $employee->id }}"
                            data-update-route="{{ route('employees.update', $employee->id) }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="5" r="1"/>
                            <circle cx="12" cy="12" r="1"/>
                            <circle cx="12" cy="19" r="1"/>
                        </svg>
                    </button>
                </td>
            </tr>
             @empty
            <tr>
                <td colspan="4" class="text-center py-6" style="color:var(--text-3);font-size:14px">
                    No employees found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>