@extends('layouts.app')

@section('title', 'Calendar View')

@php $active = 'calendar'; @endphp

@section('content')

<div class="flex flex-col lg:flex-row gap-0 min-h-screen -mx-4 sm:-mx-6" style="margin-top:-1rem">
    <div class="flex-1 px-4 sm:px-6 pt-6 pb-6 min-w-0">

        <div class="flex items-center justify-between mb-5">
            <h1 style="font-size:28px;font-weight:800;color:var(--text-1)">October 2023</h1>
            <div class="flex items-center gap-3">
                <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
                <button class="px-4 py-1.5 rounded-lg font-semibold" style="font-size:13px;background:var(--bg-card);border:1px solid var(--border);color:var(--text-2)">Today</button>
            </div>
        </div>

        {{-- Legend + View Toggle --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            {{-- Legend --}}
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#22c55e"></span>
                    <span style="font-size:12.5px;color:var(--text-3)">Morning</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#f97316"></span>
                    <span style="font-size:12.5px;color:var(--text-3)">Afternoon</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#7c3aed"></span>
                    <span style="font-size:12.5px;color:var(--text-3)">Night</span>
                </div>
            </div>
            {{-- Day / Week / Month --}}
            <div class="flex items-center rounded-xl overflow-hidden" style="background:var(--bg-card);border:1px solid var(--border)">
                <button onclick="setCalView('day',this)" class="cal-view-btn px-4 py-2 font-semibold ib-bg" style="font-size:13px;color:var(--text-2);border:none;cursor:pointer">Day</button>
                <button onclick="setCalView('week',this)" class="cal-view-btn px-4 py-2 font-semibold ib-bg" style="font-size:13px;color:var(--text-2);border:none;cursor:pointer">Week</button>
                <button onclick="setCalView('month',this)" class="cal-view-btn px-4 py-2 font-semibold purbtn rounded-xl" style="font-size:13px;border:none;cursor:pointer">Month</button>
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div class="rounded-2xl overflow-hidden" style="border:1px solid var(--border)">

            {{-- Day headers --}}
            <div class="grid grid-cols-7" style="background:rgba(124,58,237,.07);border-bottom:1px solid var(--border)">
                @foreach(['SUN','MON','TUE','WED','THU','FRI','SAT'] as $d)
                <div class="text-center py-2.5" style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.07em">{{ $d }}</div>
                @endforeach
            </div>

            {{-- Week rows --}}
            <div class="grid grid-cols-7" style="grid-auto-rows:1fr">

                @php
                // Helper pill renderers
                function pill($name,$type) {
                    $colors = [
                        'M' => ['bg'=>'rgba(34,197,94,.18)','color'=>'#4ade80','label'=>'M'],
                        'A' => ['bg'=>'rgba(249,115,22,.18)','color'=>'#fb923c','label'=>'A'],
                        'N' => ['bg'=>'rgba(124,58,237,.25)','color'=>'#a78bfa','label'=>'N'],
                    ];
                    $c = $colors[$type] ?? $colors['M'];
                    return '<span class="block px-1.5 py-0.5 rounded text-left truncate" style="font-size:10px;font-weight:600;background:'.$c['bg'].';color:'.$c['color'].';margin-bottom:2px">'.$name.' <span style="opacity:.7">('.$c['label'].')</span></span>';
                }

                $cells = [
                    // Row 1: 27,28,29,30, 01,02,03
                    ['num'=>27,'prev'=>true,'pills'=>[]],
                    ['num'=>28,'prev'=>true,'pills'=>[]],
                    ['num'=>29,'prev'=>true,'pills'=>[]],
                    ['num'=>30,'prev'=>true,'pills'=>[]],
                    ['num'=>1,'pills'=>[['Sarah L.','M'],['Mark J.','A']]],
                    ['num'=>2,'pills'=>[['James K.','N'],['Emma R.','M']]],
                    ['num'=>3,'pills'=>[]],
                    // Row 2: 04,05,06,07,08,09,10
                    ['num'=>4,'pills'=>[['Noah P.','A']]],
                    ['num'=>5,'today'=>true,'pills'=>[['Sarah L.','M'],['Liam T.','A'],['James K.','N']]],
                    ['num'=>6,'pills'=>[]],
                    ['num'=>7,'pills'=>[]],
                    ['num'=>8,'pills'=>[['John D.','M']]],
                    ['num'=>9,'pills'=>[['Sarah L.','M'],['Olivia W.','N']]],
                    ['num'=>10,'pills'=>[]],
                    // Row 3: 11,12,13,14,15,16,17
                    ['num'=>11,'pills'=>[['Chris M.','A']]],
                    ['num'=>12,'pills'=>[]],
                    ['num'=>13,'pills'=>[]],
                    ['num'=>14,'pills'=>[]],
                    ['num'=>15,'pills'=>[]],
                    ['num'=>16,'pills'=>[['Peter G.','M']]],
                    ['num'=>17,'pills'=>[]],
                    // Row 4: 18,19,20,21,22,23,24
                    ['num'=>18,'pills'=>[]],
                    ['num'=>19,'pills'=>[]],
                    ['num'=>20,'pills'=>[['Olivia W.','N']]],
                    ['num'=>21,'pills'=>[]],
                    ['num'=>22,'pills'=>[]],
                    ['num'=>23,'pills'=>[]],
                    ['num'=>24,'pills'=>[]],
                    // Row 5: 25,26,27,28,29,30,31
                    ['num'=>25,'pills'=>[]],
                    ['num'=>26,'pills'=>[]],
                    ['num'=>27,'pills'=>[]],
                    ['num'=>28,'pills'=>[]],
                    ['num'=>29,'pills'=>[]],
                    ['num'=>30,'pills'=>[]],
                    ['num'=>31,'pills'=>[]],
                ];
                @endphp

                @foreach($cells as $cell)
                <div class="cal-cell p-2 cursor-pointer"
                     style="min-height:90px;border-right:1px solid var(--border);border-bottom:1px solid var(--border);
                            {{ isset($cell['prev']) ? 'opacity:.35;' : '' }}
                            {{ isset($cell['today']) ? 'background:rgba(124,58,237,.1);border:2px solid #7c3aed;' : 'background:var(--bg-body);' }}"
                     onclick="selectDay({{ $cell['num'] }}, this)">
                    <div class="flex items-start justify-between mb-1">
                        <span style="font-size:13px;font-weight:{{ isset($cell['today']) ? '800' : '500' }};
                                     color:{{ isset($cell['today']) ? '#a78bfa' : 'var(--text-2)' }}">
                            {{ $cell['num'] }}
                        </span>
                        @if(isset($cell['today']))
                        <span class="px-1.5 py-0.5 rounded font-bold" style="font-size:9px;background:#7c3aed;color:#fff;letter-spacing:.05em">TODAY</span>
                        @endif
                    </div>
                    @foreach($cell['pills'] as $p)
                        {!! pill($p[0], $p[1]) !!}
                    @endforeach
                </div>
                @endforeach

            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════
         RIGHT: Detail Panel
    ══════════════════════════════════════ --}}
    <div class="w-full lg:w-72 xl:w-80 flex-shrink-0 flex flex-col"
         style="background:var(--bg-card);border-left:1px solid var(--border);min-height:100%">

        <div class="p-5 flex-1">

            {{-- Detail Header --}}
            <div class="mb-5">
                <h2 style="font-size:18px;font-weight:800;color:var(--text-1)">Details for Oct 05</h2>
                <p style="font-size:12.5px;color:var(--text-3);margin-top:3px">Today, 3 shifts active</p>
            </div>

            {{-- Shift Cards --}}
            <div class="space-y-3 mb-6">

                {{-- Morning Shift --}}
                <div class="rounded-xl p-4" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2)">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(34,197,94,.2)">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                                <circle cx="12" cy="12" r="5"/>
                                <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Morning Shift</p>
                            <p style="font-size:12px;color:#4ade80;margin-top:2px">08:00 AM - 04:00 PM</p>
                            <div class="flex items-center gap-2 mt-2.5">
                                <img src="https://i.pravatar.cc/22?img=1" class="w-5 h-5 rounded-full object-cover flex-shrink-0">
                                <span style="font-size:12px;color:var(--text-2)">Sarah Lawrence</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Afternoon Shift --}}
                <div class="rounded-xl p-4" style="background:rgba(249,115,22,.1);border:1px solid rgba(249,115,22,.2)">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(249,115,22,.2)">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2">
                                <circle cx="12" cy="12" r="5"/>
                                <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Afternoon Shift</p>
                            <p style="font-size:12px;color:#fb923c;margin-top:2px">04:00 PM - 12:00 AM</p>
                            <div class="flex items-center gap-2 mt-2.5">
                                <img src="https://i.pravatar.cc/22?img=7" class="w-5 h-5 rounded-full object-cover flex-shrink-0">
                                <span style="font-size:12px;color:var(--text-2)">Liam Turner</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Night Shift --}}
                <div class="rounded-xl p-4" style="background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.25)">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(124,58,237,.2)">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Night Shift</p>
                            <p style="font-size:12px;color:#a78bfa;margin-top:2px">12:00 AM - 08:00 AM</p>
                            <div class="flex items-center gap-2 mt-2.5">
                                <img src="https://i.pravatar.cc/22?img=11" class="w-5 h-5 rounded-full object-cover flex-shrink-0">
                                <span style="font-size:12px;color:var(--text-2)">James Kim</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Coverage Summary --}}
            <div>
                <p style="font-size:11px;font-weight:700;color:var(--text-3);letter-spacing:.09em;text-transform:uppercase;margin-bottom:14px">Coverage Summary</p>

                {{-- Front Desk --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1.5">
                        <span style="font-size:13px;color:var(--text-2);font-weight:500">Front Desk</span>
                        <span class="px-2 py-0.5 rounded font-bold" style="font-size:11px;background:rgba(34,197,94,.2);color:#22c55e">100%</span>
                    </div>
                    <div class="h-2 rounded-full overflow-hidden" style="background:var(--bg-ghost)">
                        <div class="h-full rounded-full" style="width:100%;background:linear-gradient(90deg,#16a34a,#22c55e)"></div>
                    </div>
                </div>

                {{-- Support Staff --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span style="font-size:13px;color:var(--text-2);font-weight:500">Support Staff</span>
                        <span class="px-2 py-0.5 rounded font-bold" style="font-size:11px;background:rgba(249,115,22,.2);color:#fb923c">75%</span>
                    </div>
                    <div class="h-2 rounded-full overflow-hidden" style="background:var(--bg-ghost)">
                        <div class="h-full rounded-full" style="width:75%;background:linear-gradient(90deg,#c2410c,#f97316)"></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer Button --}}
        <div class="p-5" style="border-top:1px solid var(--border)">
            <button class="w-full purbtn flex items-center justify-center gap-2 py-3 rounded-xl font-semibold" style="font-size:14px">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit Daily Schedule
            </button>
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
.cal-cell { transition: background .15s; }
.cal-cell:hover { background: var(--bg-hover) !important; }
.cal-view-btn { transition: background .2s, color .2s; }
</style>
@endpush

@push('scripts')
<script>
function setCalView(view, btn) {
    document.querySelectorAll('.cal-view-btn').forEach(b => {
        b.classList.remove('purbtn');
        b.classList.add('ib-bg');
        b.style.color = 'var(--text-2)';
        b.style.borderRadius = '';
    });
    btn.classList.add('purbtn');
    btn.classList.remove('ib-bg');
    btn.style.color = '#fff';
    btn.style.borderRadius = '0.75rem';
}

function selectDay(num, cell) {
    // Highlight selected cell
    document.querySelectorAll('.cal-cell').forEach(c => {
        if (!c.querySelector('.purbtn-today')) {
            c.style.outline = 'none';
        }
    });
    cell.style.outline = '2px solid #7c3aed';
}
</script>
@endpush