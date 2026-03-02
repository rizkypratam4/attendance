@props([
    'cities' => ['All Cities', 'New York, NY', 'San Francisco, CA', 'Chicago, IL', 'London, UK'],
    'sorts'  => ['Name A-Z', 'Name Z-A', 'City', 'Status'],
])

{{-- ── FILTER BAR ── --}}
<div class="flex flex-col sm:flex-row gap-3 mb-4">
    {{-- All Cities dropdown --}}
    <div class="relative">
        <button id="cityBtn" onclick="toggleDropdown('cityMenu')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium"
                style="background:var(--bg-card);border:1px solid var(--border);color:var(--text-2);font-size:13.5px;cursor:pointer;white-space:nowrap">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="4" y1="6" x2="20" y2="6"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
                <line x1="11" y1="18" x2="13" y2="18"/>
            </svg>
            All Cities
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </button>
        <div id="cityMenu" class="dd-menu hidden absolute left-0 mt-2 rounded-xl overflow-hidden z-50"
             style="min-width:160px;background:var(--dd-bg);border:1px solid var(--dd-border);box-shadow:0 16px 40px rgba(0,0,0,.35)">
            @foreach ($cities as $city)
                <div class="ddi" onclick="setCity('{{ $city }}')">{{ $city }}</div>
            @endforeach
        </div>
    </div>

    {{-- Sort dropdown --}}
    <div class="relative">
        <button id="sortBtn" onclick="toggleDropdown('sortMenu')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium"
                style="background:var(--bg-card);border:1px solid var(--border);color:var(--text-2);font-size:13.5px;cursor:pointer;white-space:nowrap">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="8" y1="6" x2="21" y2="6"/>
                <line x1="8" y1="12" x2="21" y2="12"/>
                <line x1="8" y1="18" x2="21" y2="18"/>
                <line x1="3" y1="6" x2="3.01" y2="6"/>
                <line x1="3" y1="12" x2="3.01" y2="12"/>
                <line x1="3" y1="18" x2="3.01" y2="18"/>
            </svg>
            Sort: Name A-Z
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </button>
        <div id="sortMenu" class="dd-menu hidden absolute left-0 mt-2 rounded-xl overflow-hidden z-50"
             style="min-width:180px;background:var(--dd-bg);border:1px solid var(--dd-border);box-shadow:0 16px 40px rgba(0,0,0,.35)">
            @foreach ($sorts as $sort)
                <div class="ddi" onclick="setSort('{{ $sort }}')">{{ $sort }}</div>
            @endforeach
        </div>
    </div>

    {{-- Search --}}
    <div class="flex items-center gap-3 flex-1 rounded-xl px-4 py-2.5"
         style="background:var(--bg-card);border:1px solid var(--border)">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
            <circle cx="11" cy="11" r="8"/>
            <path d="M21 21l-4.35-4.35"/>
        </svg>
        <input type="text" id="branchSearch" placeholder="Search branches by name or manager..."
               oninput="filterBranches()"
               style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%">
    </div>
</div>