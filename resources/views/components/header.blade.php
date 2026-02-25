@props(['title' => ''])

<header id="main-hdr" class="flex items-center justify-between px-6 lg:px-8 flex-shrink-0"
        style="min-height:72px; border-bottom:1px solid var(--border);">

    <div class="flex items-center gap-4">
        {{-- Hamburger (mobile/tablet) --}}
        <button onclick="openMob()"
                class="lg:hidden ib-bg flex w-9 h-9 rounded-lg items-center justify-center flex-shrink-0">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5">
                <line x1="3" y1="6"  x2="21" y2="6"  />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </button>

        {{-- Search --}}
        <div class="hdr-search s-wrap hidden sm:flex items-center gap-3 rounded-xl px-4 py-2.5"
             style="width:320px;max-width:100%;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="var(--text-3)" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <path d="M21 21l-4.35-4.35" />
            </svg>
            <input type="text" placeholder="Search employees, reports, or logs...">
        </div>
    </div>

    <div class="flex items-center gap-3 lg:gap-4">
        {{-- Notification Bell --}}
        <button class="ib-bg relative w-10 h-10 flex items-center justify-center rounded-xl">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
            </svg>
            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-purple-500 rounded-full"
                  style="border:2px solid var(--bg-header)"></span>
        </button>

        {{-- Theme Toggle --}}
        <div class="hidden sm:flex items-center gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="var(--text-3)" stroke-width="2">
                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
            </svg>
            <button id="tpill" class="tpill" onclick="toggleTheme()" title="Toggle light/dark"></button>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="var(--text-3)" stroke-width="2">
                <circle cx="12" cy="12" r="5" />
                <line x1="12" y1="1" x2="12" y2="3" /><line x1="12" y1="21" x2="12" y2="23" />
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                <line x1="1" y1="12" x2="3" y2="12" /><line x1="21" y1="12" x2="23" y2="12" />
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
            </svg>
        </div>

        {{-- Profile Dropdown --}}
        <div class="relative" id="pWrap">
            <button onclick="toggleDD(event)"
                    class="ib-bg flex items-center gap-3 rounded-xl px-3 py-2 cursor-pointer">
                <div class="prof-name hidden lg:block text-right">
                    <div style="font-size:15px;font-weight:600;color:var(--text-1);line-height:1.2">
                        {{ auth()->user()->first_name ." ".auth()->user()->last_name ?? 'Alex Thompson' }}
                    </div>
                    <div style="font-size:12px;color:#a78bfa;font-weight:500">
                        {{ strtoupper(auth()->user()->role ?? 'SUPER ADMIN') }}
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-pink-500
                            flex items-center justify-center font-bold text-white flex-shrink-0"
                     style="font-size:15px">
                    {{ strtoupper(substr(auth()->user()->first_name ." ".auth()->user()->last_name ?? 'AT', 0, 2)) }}
                </div>
                <svg class="hidden lg:block flex-shrink-0" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="var(--text-3)" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6" />
                </svg>
            </button>

            {{-- Dropdown menu --}}
            <div id="pdd">
                <div class="px-4 py-4" style="border-bottom:1px solid var(--dd-border)">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-pink-500
                                    flex items-center justify-center font-bold text-white flex-shrink-0"
                             style="font-size:16px">
                            {{ strtoupper(substr(auth()->user()->first_name ." ".auth()->user()->last_name ?? 'AT', 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:15px;font-weight:600;color:var(--text-1)">
                                {{ auth()->user()->first_name ." ".auth()->user()->last_name ?? 'Alex Thompson' }}
                            </div>
                            <div style="font-size:12px;color:#a78bfa">
                                {{ ucfirst(auth()->user()->role ?? 'Super Admin') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-2">
                    <div class="ddi" onclick="openM('mProfile')">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Setting Profile
                    </div>
                    <div class="ddi" onclick="openM('mPassword')">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0110 0v4" />
                        </svg>
                        Change Password
                    </div>
                    <div class="dd-sep"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="ddi red w-full text-left">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2">
                                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>