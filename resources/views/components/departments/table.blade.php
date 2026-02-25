@props([
    'departments' => [],
])
 
 <table class="w-full" style="border-collapse:collapse;min-width:520px">
     <thead>
         <tr style="border-bottom:1px solid var(--border)">
             <th class="text-left px-6 py-3.5 font-semibold"
                 style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                 Department Name
             </th>
             <th class="text-left px-6 py-3.5 font-semibold"
                 style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                 Head of Dept
             </th>
             <th class="text-left px-6 py-3.5 font-semibold"
                 style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                 Total Employees
             </th>
             <th class="text-right px-6 py-3.5 font-semibold"
                 style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                 Actions
             </th>
         </tr>
     </thead>
     <tbody id="deptTableBody">
        @forelse ($departments as $department)
            <tr class="dept-row" style="border-bottom:1px solid var(--border)" data-name="{{ $department->name }}">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                            style="background:rgba(99,179,237,.15)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#63b3ed"
                                stroke-width="2">
                                <polyline points="16 18 22 12 16 6" />
                                <polyline points="8 6 2 12 8 18" />
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:15px;font-weight:700;color:var(--text-1)">{{ $department->name }}</p>
                            <p style="font-size:12px;color:var(--text-3)">Tech &amp; Infrastructure</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $department->head_of_dept->avatar ?? 'https://i.pravatar.cc/36?img=5' }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0"
                            alt="{{ $department->head_of_dept->name ?? 'No Head' }}">
                        <span style="font-size:14px;font-weight:500;color:var(--text-1)">{{ $department->head_of_dept->name ?? 'No Head' }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-4 py-1.5 rounded-full font-semibold"
                        style="font-size:12px;background:rgba(124,58,237,.25);color:#c4b5fd">
                        {{ $department->employees_count }} Employees
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center" title="Edit">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f8fafc"
                                stroke-width="2">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </button>
                        <button class="w-8 h-8 rounded-lg flex items-center justify-center"
                            style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer" title="Delete">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="#f87a7a"
                                stroke="#f87a7a" stroke-width=".5">
                                <polyline points="{{ $department->id }} {{ $department->id }} {{ $department->id }}" />
                                <path d="{{ $department->id }}" />
                                <path d="{{ $department->id }}" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
             <tr>
                 <td colspan="4" class="text-center py-6" style="color:var(--text-3);font-size:14px">
                     No departments found.
                 </td>
             </tr>
        @endforelse
     </tbody>
 </table>
