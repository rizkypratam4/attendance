{{-- ── BOTTOM: Pro Tip + Shift Utilization ── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

    {{-- Pro Tip --}}
    <div class="card rounded-2xl p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:rgba(124,58,237,.2)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        <div>
            <p style="font-size:14px;font-weight:700;color:var(--text-1);margin-bottom:6px">Pro Tip</p>
            <p style="font-size:12.5px;color:var(--text-3);line-height:1.65">
                Color tags help in visual identification when viewing the roster in calendar mode. Choose distinct colors for overlapping shifts.
            </p>
        </div>
    </div>

    {{-- Shift Utilization --}}
    <div class="card rounded-2xl p-5">
        <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:rgba(124,58,237,.15)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <path d="M3 9h18M3 15h18M9 3v18"/>
                </svg>
            </div>
            <p style="font-size:14px;font-weight:700;color:var(--text-1);margin-top:8px">Shift Utilization</p>
        </div>

        {{-- Morning --}}
        <div class="mb-3">
            <div class="flex items-center justify-between mb-1.5">
                <span style="font-size:12px;color:var(--text-3)">Morning Shift (M-01)</span>
                <span style="font-size:12px;font-weight:700;color:var(--text-2)">45%</span>
            </div>
            <div class="h-1.5 rounded-full overflow-hidden" style="background:var(--bg-ghost)">
                <div class="h-full rounded-full" style="width:45%;background:linear-gradient(90deg,#2563eb,#3b82f6)"></div>
            </div>
        </div>

        {{-- Afternoon --}}
        <div class="mb-3">
            <div class="flex items-center justify-between mb-1.5">
                <span style="font-size:12px;color:var(--text-3)">Afternoon (A-02)</span>
                <span style="font-size:12px;font-weight:700;color:var(--text-2)">32%</span>
            </div>
            <div class="h-1.5 rounded-full overflow-hidden" style="background:var(--bg-ghost)">
                <div class="h-full rounded-full" style="width:32%;background:linear-gradient(90deg,#c2410c,#f97316)"></div>
            </div>
        </div>

        {{-- Night --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <span style="font-size:12px;color:var(--text-3)">Night (N-03)</span>
                <span style="font-size:12px;font-weight:700;color:var(--text-2)">23%</span>
            </div>
            <div class="h-1.5 rounded-full overflow-hidden" style="background:var(--bg-ghost)">
                <div class="h-full rounded-full" style="width:23%;background:linear-gradient(90deg,#7c3aed,#a855f7)"></div>
            </div>
        </div>
    </div>

</div>