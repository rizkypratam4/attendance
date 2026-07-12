@props([
    'showing' => 0,
    'total'   => 0,
])

<div class="relative">
    <div class="flex items-center gap-3">

        {{-- Filter Button --}}
        <button id="filterToggleBtn"
                onclick="toggleFilterPanel()"
                class="flex items-center gap-2 px-4 py-2 rounded-lg font-semibold"
                style="background:rgba(124,58,237,.2);color:#a78bfa;border:none;cursor:pointer;font-size:13px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="4" y1="6" x2="20" y2="6"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
                <line x1="11" y1="18" x2="13" y2="18"/>
            </svg>
            Filter
            {{-- Badge jumlah filter aktif --}}
            <span id="filterBadge"
                  class="hidden w-4 h-4 rounded-full text-white flex items-center justify-center"
                  style="background:#7c3aed;font-size:10px;font-weight:700">0</span>
        </button>

        <span style="font-size:13px;color:var(--text-3)">
            Menampilkan {{ $showing }} dari {{ number_format($total) }} pengguna
        </span>

    </div>

    {{-- Dropdown Panel --}}
    <div id="filterPanel"
         class="hidden"
         style="position:absolute;top:calc(100% + 8px);left:0;z-index:999;
                width:340px;background:var(--bg-card);border:1px solid var(--border);
                border-radius:16px;padding:20px;box-shadow:0 10px 40px rgba(0,0,0,.4)">

        <form method="GET" action="{{ route('users.index') }}" id="filterForm">

            <div class="space-y-4">

                {{-- Search --}}
                <div>
                    <label style="font-size:11px;font-weight:600;color:var(--text-3);
                                  letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:6px">
                        Cari
                    </label>
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl"
                         style="background:var(--bg-input);border:1px solid var(--border-in)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input type="text"
                               id="filterSearch"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Nama atau email..."
                               style="background:transparent;border:none;outline:none;
                                      color:var(--text-2);font-size:13px;width:100%;font-family:inherit">
                        <button type="button"
                                id="clearSearch"
                                onclick="clearSearch()"
                                class="{{ request('search') ? '' : 'hidden' }}"
                                style="background:none;border:none;cursor:pointer;color:var(--text-3);padding:0;line-height:1">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Role --}}
                <div>
                    <label style="font-size:11px;font-weight:600;color:var(--text-3);
                                  letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:6px">
                        Peran
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['mis','manager','hr','employee'] as $role)
                            <button type="button"
                                    onclick="toggleChip(this, 'role[]', '{{ $role }}')"
                                    class="filter-chip px-3 py-1.5 rounded-lg font-medium"
                                    style="font-size:12.5px;border:1px solid var(--border);
                                           background:var(--bg-ghost);color:var(--text-2);cursor:pointer"
                                    data-value="{{ $role }}"
                                    {{ in_array($role, request()->input('role', [])) ? 'data-active=true' : '' }}>
                                {{ $role === 'employee' ? 'Karyawan' : ($role === 'manager' ? 'Manajer' : strtoupper($role)) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label style="font-size:11px;font-weight:600;color:var(--text-3);
                                  letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:6px">
                        Status
                    </label>
                    <div class="flex gap-2">
                        <button type="button"
                                onclick="toggleChip(this, 'status', 'active')"
                                class="filter-chip px-3 py-1.5 rounded-lg font-medium"
                                style="font-size:12.5px;border:1px solid var(--border);
                                       background:var(--bg-ghost);color:var(--text-2);cursor:pointer"
                                data-value="active"
                                {{ request('status') === 'active' ? 'data-active=true' : '' }}>
                            Aktif
                        </button>
                        <button type="button"
                                onclick="toggleChip(this, 'status', 'inactive')"
                                class="filter-chip px-3 py-1.5 rounded-lg font-medium"
                                style="font-size:12.5px;border:1px solid var(--border);
                                       background:var(--bg-ghost);color:var(--text-2);cursor:pointer"
                                data-value="inactive"
                                {{ request('status') === 'inactive' ? 'data-active=true' : '' }}>
                            Nonaktif
                        </button>
                    </div>
                </div>

            </div>

            {{-- Footer Actions --}}
            <div class="flex items-center gap-2 mt-5"
                 style="border-top:1px solid var(--border);padding-top:14px">
                <button type="button"
                        onclick="resetFilters()"
                        class="px-4 py-2 rounded-lg font-medium"
                        style="font-size:13px;border:1px solid var(--border);
                               background:var(--bg-ghost);color:var(--text-3);cursor:pointer">
                    Setel Ulang
                </button>
                <button type="submit"
                        class="flex-1 py-2 rounded-lg font-semibold purbtn"
                        style="font-size:13px">
                    Terapkan Filter
                </button>
            </div>

        </form>
    </div>
</div>