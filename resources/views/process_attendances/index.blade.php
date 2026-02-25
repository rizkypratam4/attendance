@extends('layouts.app')

@section('title', 'Process Attendance')

@php $active = 'process-attendance'; @endphp

@section('content')

<div class="mb-6">
    <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Process Attendance</h1>
    <p style="font-size:13px;color:var(--text-3);margin-top:5px">Transform raw clock-in/out logs into verified records.</p>
</div>

<div class="card rounded-2xl p-5 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:7px">Start Date</label>
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl"
                 style="background:var(--bg-input);border:1px solid var(--border-in);cursor:pointer">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <input type="text" value="01/10/2023"
                       style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%;font-family:inherit;cursor:pointer">
            </div>
        </div>

        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:7px">End Date</label>
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl"
                 style="background:var(--bg-input);border:1px solid var(--border-in);cursor:pointer">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <input type="text" value="31/10/2023"
                       style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%;font-family:inherit;cursor:pointer">
            </div>
        </div>

        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:7px">Department</label>
            <div class="relative">
                <select class="w-full px-4 py-2.5 rounded-xl"
                        style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13.5px;cursor:pointer;outline:none;appearance:none;font-family:inherit">
                    <option>All Departments</option>
                    <option>Engineering</option>
                    <option>Marketing</option>
                    <option>Sales</option>
                    <option>Design</option>
                    <option>HR</option>
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </div>
        </div>

        <div>
            <button onclick="startProcessing()"
                    class="w-full purbtn flex items-center justify-center gap-2 py-2.5 rounded-xl font-semibold"
                    style="font-size:14px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="5 3 19 12 5 21 5 3"/>
                </svg>
                Process
            </button>
        </div>

    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <div class="lg:col-span-2 card rounded-2xl p-5">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2.5">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
                <p style="font-size:12px;font-weight:700;color:var(--text-2);letter-spacing:.08em;text-transform:uppercase">Calculation Engine</p>
            </div>
            <span id="engineStatus" style="font-size:12.5px;font-weight:600;color:#fb923c">Processing...</span>
        </div>

        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <p style="font-size:13px;color:var(--text-2)">Batch processing status</p>
                <p id="progressPct" style="font-size:13px;font-weight:700;color:#a78bfa">68%</p>
            </div>
            <div class="h-2.5 rounded-full overflow-hidden" style="background:var(--bg-ghost)">
                <div id="progressBar" class="h-full rounded-full"
                     style="width:68%;background:linear-gradient(90deg,#7c3aed,#a78bfa);transition:width .4s ease"></div>
            </div>
        </div>

        <div>
            <p style="font-size:11.5px;font-weight:600;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase;margin-bottom:10px">Action Log</p>
            <div class="space-y-3" id="actionLog">

                <div class="flex items-start gap-2.5">
                    <svg class="flex-shrink-0 mt-0.5" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <div>
                        <p style="font-size:13px;color:var(--text-2)">Successfully fetched 1,240 raw logs from biometric server.</p>
                        <p style="font-size:11.5px;color:var(--text-3);margin-top:2px">14:02:11</p>
                    </div>
                </div>

                <div class="flex items-start gap-2.5">
                    <svg class="flex-shrink-0 mt-0.5" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <div>
                        <p style="font-size:13px;color:var(--text-2)">Employee shift mapping completed (420 profiles matched).</p>
                        <p style="font-size:11.5px;color:var(--text-3);margin-top:2px">14:02:15</p>
                    </div>
                </div>

                <div class="flex items-start gap-2.5">
                    <svg class="flex-shrink-0 mt-0.5 animate-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2.5">
                        <path d="M21 12a9 9 0 11-6.219-8.56"/>
                    </svg>
                    <div>
                        <p style="font-size:13px;color:var(--text-2)">Calculating overtime hours for Engineering department...</p>
                        <p style="font-size:11.5px;color:var(--text-3);margin-top:2px">14:02:19</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="flex flex-col gap-4">
        <div class="card rounded-2xl p-5 flex-1">
            <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">Total Records Found</p>
            <p style="font-size:38px;font-weight:800;color:var(--text-1);line-height:1">1,242</p>
        </div>

        <div class="card rounded-2xl p-5 flex-1">
            <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">Flagged Errors</p>
            <p style="font-size:38px;font-weight:800;color:#f87171;line-height:1">14</p>
        </div>

        <div class="card rounded-2xl p-5 flex-1">
            <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">Ready to Finalize</p>
            <p style="font-size:38px;font-weight:800;color:var(--text-1);line-height:1">1,228</p>
        </div>

    </div>

</div>

{{-- ── RESULTS PREVIEW ── --}}
<div class="card rounded-2xl" style="overflow:hidden">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
         style="border-bottom:1px solid var(--border)">
        <p style="font-size:12px;font-weight:700;color:var(--text-2);letter-spacing:.08em;text-transform:uppercase">Results Preview</p>
        <div class="flex items-center gap-2 px-3 py-2 rounded-xl" style="background:var(--bg-input);border:1px solid var(--border-in);max-width:220px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" placeholder="Search employee..."
                   style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13px;width:100%;font-family:inherit">
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:560px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Employee</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Department</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Logs Count</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Work Hours</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Status</th>
                </tr>
            </thead>
            <tbody>

                {{-- James Sterling - CLEAN --}}
                <tr class="proc-row" style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                 style="background:linear-gradient(135deg,#7c3aed,#a78bfa);font-size:11px">JS</div>
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--text-1)">James Sterling</p>
                                <p style="font-size:11.5px;color:var(--text-3)">ID: EMP-4029</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span style="font-size:13.5px;color:var(--text-2);text-decoration:underline;cursor:pointer">Engineering</span>
                    </td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">22</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;font-weight:600;color:var(--text-1)">176.5h</td>
                    <td class="px-4 py-3.5">
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold w-fit"
                              style="font-size:10.5px;background:rgba(34,197,94,.15);color:#22c55e;letter-spacing:.05em">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>CLEAN
                        </span>
                    </td>
                </tr>

                {{-- Elena Bennett - MISSING OUT --}}
                <tr class="proc-row" style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                 style="background:linear-gradient(135deg,#db2777,#f472b6);font-size:11px">EB</div>
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--text-1)">Elena Bennett</p>
                                <p style="font-size:11.5px;color:var(--text-3)">ID: EMP-4982</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span style="font-size:13.5px;color:var(--text-2);text-decoration:underline;cursor:pointer">Marketing</span>
                    </td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">19</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;font-weight:600;color:var(--text-1)">152.0h</td>
                    <td class="px-4 py-3.5">
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold w-fit"
                              style="font-size:10.5px;background:rgba(239,68,68,.15);color:#f87171;letter-spacing:.05em">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>MISSING OUT
                        </span>
                    </td>
                </tr>

                {{-- Robert Klein - partial visible --}}
                <tr class="proc-row">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                 style="background:linear-gradient(135deg,#0369a1,#38bdf8);font-size:11px">RK</div>
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--text-1)">Robert Klein</p>
                                <p style="font-size:11.5px;color:var(--text-3)">ID: EMP-3847</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span style="font-size:13.5px;color:var(--text-2);text-decoration:underline;cursor:pointer">Sales</span>
                    </td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">21</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;font-weight:600;color:var(--text-1)">168.0h</td>
                    <td class="px-4 py-3.5">
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold w-fit"
                              style="font-size:10.5px;background:rgba(34,197,94,.15);color:#22c55e;letter-spacing:.05em">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>CLEAN
                        </span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
         style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">Showing 3 of 1,242 records</p>
        <button class="purbtn px-5 py-2 rounded-xl font-semibold" style="font-size:13.5px">
            Finalize &amp; Save Records
        </button>
    </div>

</div>

@endsection

@push('styles')
<style>
.proc-row { transition: background .15s; }
.proc-row:hover { background: var(--bg-hover); }

@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; transform-origin: center; }
</style>
@endpush

@push('scripts')
<script>
let processing = false;
let progress = 68;

function startProcessing() {
    if (processing) return;
    processing = true;
    progress = 0;

    document.getElementById('engineStatus').textContent = 'Processing...';
    document.getElementById('engineStatus').style.color = '#fb923c';

    const bar = document.getElementById('progressBar');
    const pct = document.getElementById('progressPct');

    const interval = setInterval(() => {
        progress += Math.random() * 8;
        if (progress >= 100) {
            progress = 100;
            clearInterval(interval);
            processing = false;
            document.getElementById('engineStatus').textContent = 'Completed';
            document.getElementById('engineStatus').style.color = '#22c55e';
        }
        bar.style.width = progress.toFixed(0) + '%';
        pct.textContent = progress.toFixed(0) + '%';
    }, 300);
}
</script>
@endpush