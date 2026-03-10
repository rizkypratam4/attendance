<table class="w-full" id="shiftTable" style="border-collapse:collapse;min-width:680px">
    <thead>
        <tr style="border-bottom:1px solid var(--border)">
            <th class="text-left px-5 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;width:100px">Shift Code</th>
            <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Description</th>
            <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Schedule</th>
            <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Break</th>
            <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Color Tag</th>
            <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Status</th>
            <th class="text-right px-5 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Actions</th>
        </tr>
    </thead>
    <tbody>
        {{-- Morning Shift --}}
        <tr class="shift-row" data-code="M-01" data-name="Morning Shift" data-status="active" style="border-bottom:1px solid var(--border)">
            <td class="px-5 py-4">
                <span class="px-2.5 py-1.5 rounded-lg font-bold" style="font-size:12px;background:rgba(59,130,246,.2);color:#60a5fa;letter-spacing:.04em">M-01</span>
            </td>
            <td class="px-4 py-4">
                <p style="font-size:14px;font-weight:600;color:var(--text-1)">Shift 1</p>
            </td>
            <td class="px-4 py-4">
                <div class="flex items-center gap-2">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span style="font-size:13.5px;color:var(--text-2)">08:00 AM - 05:00 PM</span>
                </div>
            </td>
            <td class="px-4 py-4">
                <span style="font-size:13.5px;color:var(--text-2)">60</span>
                <span style="font-size:11.5px;color:var(--text-3);display:block">mins</span>
            </td>
            <td class="px-4 py-4">
                <span class="w-5 h-5 rounded-full inline-block" style="background:#3b82f6"></span>
            </td>
            <td class="px-4 py-4">
                <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full font-semibold w-fit"
                        style="font-size:11px;background:rgba(34,197,94,.15);color:#22c55e">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>Active
                </span>
            </td>
            <td class="px-5 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="openEditShift('M-01','Morning Shift','Standard general morning','08:00 AM','05:00 PM','60','#3b82f6','active')"
                            class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button onclick="confirmDeleteShift('Morning Shift')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center"
                            style="background:rgba(239,68,68,.12)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                            <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    </tbody>
</table>