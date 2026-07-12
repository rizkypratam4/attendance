@props(['active' => 'dashboard'])

<aside id="sidebar" class="flex flex-col">
    <div class="flex items-center px-5 border-b border-slate-700 h-[72px]">
       <div class="flex items-center gap-2 min-w-0">
    <div class="flex items-center gap-1.5 flex-shrink-0">
        <div
            style="width:36px;height:36px;background:#7c3aed;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <!-- Ikon absensi -->
                <rect x="4" y="5" width="16" height="15" rx="2" stroke="white" stroke-width="2"/>
                <path d="M8 3V7M16 3V7M4 10H20" stroke="white" stroke-width="2" stroke-linecap="round"/>
                <path d="M8 15L10 17L16 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    <div class="logo-txt min-w-0 flex flex-col gap-1">
        <div style="font-size:15px;font-weight:700;color:var(--text-1);line-height:1.1">
            Sistem Absensi
        </div>
        <div style="font-size:10px;color:#7c3aed;font-weight:500;letter-spacing:0.03em;line-height:1.2">
            Manajemen Kehadiran
        </div>
    </div>
</div>

        <button onclick="toggleDsk()" id="tBtn"
            class="hidden md:flex ib-bg w-8 h-8 rounded-lg items-center justify-center flex-shrink-0 ml-auto">
            <svg id="tIco" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5">
                <path d="M15 6l-6 6 6 6" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto overflow-x-hidden">

        <a href="{{ route('dashboard') }}" class="nav-a {{ $active === 'dashboard' ? 'nav-act' : '' }}">
            <svg class="flex-shrink-0" width="19" height="19" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <rect x="14" y="14" width="7" height="7" rx="1" />
            </svg>
            <span class="sl">Beranda</span>
        </a>

        <div class="menu-group">
            <button type="button" onclick="toggleSubmenu(this)" class="nav-a w-full text-left">
                <svg class="flex-shrink-0" width="19" height="19" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <ellipse cx="12" cy="5" rx="7" ry="3" />
                    <path d="M5 5v6c0 1.7 3.1 3 7 3s7-1.3 7-3V5" />
                    <path d="M5 11v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6" />
                </svg>

                <span class="sl flex-1">Master Data</span>

                <span class="caret flex-shrink-0">
                    <svg class="caretIcon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" style="transition:transform .3s">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </span>
            </button>

            <div class="submenu"
                style="max-height:{{ in_array($active, ['users', 'location', 'department', 'branch']) ? '200px' : '0' }};
                    overflow:hidden;transition:max-height .32s cubic-bezier(.4,0,.2,1)">
                <div class="ml-4 mt-1 pb-1 pl-4 space-y-0.5" style="border-left:2px solid rgba(124,58,237,.3)">
                    <a href="{{ route('users.index') }}" class="sub-a {{ $active === 'users' ? 'sub-act' : '' }}">
                        <span class="sl">Akun Pengguna</span>
                    </a>

                    <a href="{{ route('locations.index') }}"
                        class="sub-a {{ $active === 'location' ? 'sub-act' : '' }}">
                        <span class="sl">Lokasi</span>
                    </a>

                    <a href="{{ route('departments.index') }}"
                        class="sub-a {{ $active === 'department' ? 'sub-act' : '' }}">
                        <span class="sl">Departemen</span>
                    </a>

                    <a href="{{ route('branches.index') }}" class="sub-a {{ $active === 'branch' ? 'sub-act' : '' }}">
                        <span class="sl">Perusahaan</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="menu-group">
            <button type="button" onclick="toggleSubmenu(this)" class="nav-a w-full text-left">
                <svg class="flex-shrink-0" width="19" height="19" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                    <path d="M7 8L5 4M17 8l2-4" />

                    <path d="M5 10a7 7 0 0 1 14 0v5a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3z" />

                    <circle cx="9" cy="13" r="1" />
                    <circle cx="15" cy="13" r="1" />

                    <path d="M12 16v1" />
                </svg>

                <span class="sl flex-1">Kelola Shift</span>

                <span class="caret flex-shrink-0">
                    <svg class="caretIcon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" style="transition:transform .3s">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </span>
            </button>

            <div class="submenu"
                style="max-height:{{ in_array($active, ['shift-groups', 'shift-definition', 'shift-codes', 'shift-schedules', 'shift-rules']) ? '200px' : '0' }};
                    overflow:hidden;transition:max-height .32s cubic-bezier(.4,0,.2,1)">
                <div class="ml-4 mt-1 pb-1 pl-4 space-y-0.5" style="border-left:2px solid rgba(124,58,237,.3)">
                    <a href="{{ route('shift_groups.index') }}"
                        class="sub-a {{ $active === 'shift-groups' ? 'sub-act' : '' }}">
                        <span class="sl">Master Shift</span>
                    </a>

                    <a href="{{ route('shift_codes.index') }}"
                        class="sub-a {{ $active === 'shift-codes' ? 'sub-act' : '' }}">
                        <span class="sl">Kode Shift</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="menu-group">
            <button type="button" onclick="toggleSubmenu(this)" class="nav-a w-full text-left">
                <!-- Ikon Grup / Employee -->
                <svg class="flex-shrink-0" width="19" height="19" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Kepala orang 1 -->
                    <circle cx="8" cy="8" r="3" />
                    <!-- Kepala orang 2 -->
                    <circle cx="16" cy="8" r="3" />
                    <!-- Badan orang 1 -->
                    <path d="M5 21v-2a4 4 0 0 1 6 0v2" />
                    <!-- Badan orang 2 -->
                    <path d="M13 21v-2a4 4 0 0 1 6 0v2" />
                </svg>

                <span class="sl flex-1">Manajemen Karyawan</span>

                <span class="caret flex-shrink-0">
                    <svg class="caretIcon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" style="transition:transform .3s">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </span>
            </button>

            <div class="submenu"
                style="max-height:{{ in_array($active, ['employees', 'employee-shift-assignments']) ? '200px' : '0' }};
                    overflow:hidden;transition:max-height .32s cubic-bezier(.4,0,.2,1)">
                <div class="ml-4 mt-1 pb-1 pl-4 space-y-0.5" style="border-left:2px solid rgba(124,58,237,.3)">
                    <a href="{{ route('employees.index') }}"
                        class="sub-a {{ $active === 'employees' ? 'sub-act' : '' }}">
                        <span class="sl">Data Karyawan</span>
                    </a>

                    <a href="{{ route('employee_shift_assignments.index') }}"
                        class="sub-a {{ $active === 'employee-shift-assignments' ? 'sub-act' : '' }}">
                        <span class="sl">Penugasan Shift</span>
                    </a>
                </div>
            </div>
        </div>



        <div class="menu-group">
            <button type="button" onclick="toggleSubmenu(this)" class="nav-a w-full text-left">
                <svg class="flex-shrink-0" width="19" height="19" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>

                <span class="sl flex-1">Absensi</span>

                <span class="caret flex-shrink-0">
                    <svg class="caretIcon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" style="transition:transform .3s">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </span>
            </button>

            <div class="submenu"
                style="max-height:{{ in_array($active, ['fingerprint', 'process-attendance', 'attendance']) ? '200px' : '0' }};
                    overflow:hidden;transition:max-height .32s cubic-bezier(.4,0,.2,1)">
                <div class="ml-4 mt-1 pb-1 pl-4 space-y-0.5" style="border-left:2px solid rgba(124,58,237,.3)">
                    <a href="{{ route('fingerprint.index') }}"
                        class="sub-a {{ $active === 'fingerprint' ? 'sub-act' : '' }}">
                        <span class="sl">Log Fingerprint</span>
                    </a>
                    <a href="{{ route('process_attendances.index') }}"
                        class="sub-a {{ $active === 'process-attendance' ? 'sub-act' : '' }}">
                        <span class="sl">Proses Absensi</span>
                    </a>
                    <a href="{{ route('attendances.index') }}"
                        class="sub-a {{ $active === 'attendance' ? 'sub-act' : '' }}">
                        <span class="sl">Daftar Absensi</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- <div class="menu-group">
            <button type="button" onclick="toggleSubmenu(this)" class="nav-a w-full text-left">
                <svg class="flex-shrink-0" width="19" height="19" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="20" x2="20" y2="20"/>
                    <line x1="4" y1="4" x2="4" y2="20"/>

                    <rect x="7" y="12" width="3" height="8" rx="1"/>
                    <rect x="12" y="8" width="3" height="12" rx="1"/>
                    <rect x="17" y="5" width="3" height="15" rx="1"/>=
                </svg>

                <span class="sl flex-1">Reports</span>

                <span class="caret flex-shrink-0">
                    <svg class="caretIcon" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5"
                        style="transition:transform .3s">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </span>
            </button>

            <div class="submenu"
                style="max-height:{{ in_array($active, ['daily_report', 'monthly_report', 'late_report', 'recap_per_department']) ? '200px' : '0' }};
            overflow:hidden;transition:max-height .32s cubic-bezier(.4,0,.2,1)">
                <div class="ml-4 mt-1 pb-1 pl-4 space-y-0.5" style="border-left:2px solid rgba(124,58,237,.3)">
                    <a href="#" class="sub-a {{ $active === 'daily_report' ? 'sub-act' : '' }}">
                        <span class="sl">Daily Report</span>
                    </a>

                    <a href="#" class="sub-a {{ $active === 'monthly_report' ? 'sub-act' : '' }}">
                        <span class="sl">Monthly Report</span>
                    </a>

                    <a href="#" class="sub-a {{ $active === 'late_report' ? 'sub-act' : '' }}">
                        <span class="sl">Late Report</span>
                    </a>

                    <a href="#" class="sub-a {{ $active === 'recap_per_department' ? 'sub-act' : '' }}">
                        <span class="sl">Recap Per Departement</span>
                    </a>
                </div>
            </div>
        </div> --}}
    </nav>

    <div class="px-3 pb-5 pt-3" style="border-top:1px solid var(--border)">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-a w-full text-left">
                <svg class="flex-shrink-0" width="19" height="19" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="sl">Keluar</span>
            </button>
        </form>
    </div>
</aside>
