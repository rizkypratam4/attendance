@props([
    'searchId' => 'search',
    'searchPlaceholder' => 'Search...',
    'filters' => [], // Array of filter configs: ['id' => 'filter1', 'label' => 'Filter', 'options' => []]
])

<div class="card rounded-2xl px-4 py-3 mb-5">
    <form id="filterForm" method="GET" class="flex flex-wrap items-center gap-3">
        {{-- Search Input --}}
        <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl flex-1"
             style="background:var(--bg-input);border:1px solid var(--border-in);min-width:200px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                <circle cx="11" cy="11" r="8" />
                <path d="M21 21l-4.35-4.35" />
            </svg>
            <input type="text" 
                   name="search"
                   id="{{ $searchId }}"
                   placeholder="{{ $searchPlaceholder }}"
                   value="{{ request('search', '') }}"
                   style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13px;width:100%;font-family:inherit">
        </div>

        {{-- Filter Selects --}}
        @foreach($filters as $filter)
            <div class="relative flex-shrink-0">
                <select name="{{ $filter['id'] }}"
                        id="{{ $filter['id'] }}"
                        class="px-4 py-2.5 rounded-xl appearance-none pr-8 font-medium"
                        style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13px;outline:none;cursor:pointer;font-family:inherit">
                    <option value="">{{ $filter['label'] ?? 'All' }}</option>
                    @foreach($filter['options'] ?? [] as $value => $label)
                        <option value="{{ $value }}" {{ request($filter['id']) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </div>
        @endforeach

        {{-- Reset Button --}}
        @if(request('search') || request()->query() != request()->getQueryString())
            <button type="reset" onclick="window.location.href='{{ request()->url() }}'"
                    class="px-4 py-2.5 rounded-xl font-medium flex-shrink-0"
                    style="background:rgba(239,68,68,.1);color:#ef4444;border:none;cursor:pointer;font-size:13px">
                Clear Filters
            </button>
        @endif

        {{-- Submit Button --}}
        <button type="submit"
                class="purbtn px-4 py-2.5 rounded-xl font-semibold flex-shrink-0"
                style="font-size:13px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline;margin-right:6px">
                <path d="M4 12a8 8 0 1116 0 8 8 0 01-16 0z" />
                <path d="M9 9h6v6H9z" style="fill:currentColor;opacity:0" />
            </svg>
            Filter
        </button>
    </form>
</div>

<script>
    // Auto-submit form when select changes
    document.querySelectorAll('select[name!="search"]').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Submit form on Enter key in search input
    document.getElementById('{{ $searchId }}').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('filterForm').submit();
        }
    });
</script>
