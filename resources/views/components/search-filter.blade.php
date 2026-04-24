@props([
    'searchId' => 'search',
    'searchPlaceholder' => 'Search...',
    'filters' => [], // Array of filter configs: ['id' => 'filter1', 'label' => 'Filter', 'options' => []]
])

@php $formId = 'filterForm_' . $searchId; @endphp

<div class="card rounded-2xl px-4 py-3 mb-5">
    <form id="{{ $formId }}" method="GET" class="flex flex-wrap items-center gap-3">
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
            <div class="custom-select-wrapper" data-name="{{ $filter['id'] }}" style="min-width:150px;position:relative">
                <input type="hidden" name="{{ $filter['id'] }}" value="{{ request($filter['id'], '') }}">
                <button type="button" class="custom-select-btn w-full px-4 py-2.5 rounded-xl flex items-center justify-between"
                        style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13px;cursor:pointer;font-family:inherit">
                    <span class="custom-select-label">
                        @php
                            $selectedValue = request($filter['id'], '');
                            if ($selectedValue && isset($filter['options'][$selectedValue])) {
                                echo $filter['options'][$selectedValue];
                            } else {
                                echo $filter['label'] ?? 'All';
                            }
                        @endphp
                    </span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="custom-select-dropdown" style="display:none;position:absolute;top:calc(100% + 6px);left:0;right:0;min-width:200px;z-index:999;background:#1a1625;border:1px solid var(--border);border-radius:12px;padding:5px;box-shadow:0 10px 40px rgba(0,0,0,.5);max-height:220px;overflow-y:auto">
                    <div class="custom-select-option" data-value="">{{ $filter['label'] ?? 'All' }}</div>
                    @foreach($filter['options'] ?? [] as $value => $label)
                        <div class="custom-select-option {{ request($filter['id']) == $value ? 'selected' : '' }}" data-value="{{ $value }}">
                            {{ $label }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Reset Button --}}
        @if(request('search') || collect(request()->query())->except('page')->isNotEmpty())
            <a href="{{ request()->url() }}"
               class="px-4 py-2.5 rounded-xl font-medium flex-shrink-0"
               style="background:rgba(239,68,68,.1);color:#ef4444;border:1px solid rgba(239,68,68,.2);cursor:pointer;font-size:13px;text-decoration:none">
                Clear
            </a>
        @endif

        {{-- Submit Button --}}
        <button type="submit"
                class="purbtn px-4 py-2.5 rounded-xl font-semibold flex-shrink-0"
                style="font-size:13px">
            Filter
        </button>
    </form>
</div>

<style>
.custom-select-btn { text-align:left;white-space:nowrap;overflow:hidden;transition:all .15s; }
.custom-select-btn:hover { border-color:rgba(124,58,237,.5); }
.custom-select-option { padding:8px 12px;border-radius:8px;font-size:13px;color:#cbd5e1;cursor:pointer;transition:background .12s;font-family:inherit; }
.custom-select-option:hover { background:rgba(124,58,237,.2);color:#e2e8f0; }
.custom-select-option.selected { background:rgba(124,58,237,.25);color:#a78bfa;font-weight:600; }
</style>

<script>
(function () {
    var formId = '{{ $formId }}';

    function initSearchFilter() {
        var form = document.getElementById(formId);
        if (!form) return;

        form.querySelectorAll('.custom-select-wrapper').forEach(function (wrapper) {
            var btn        = wrapper.querySelector('.custom-select-btn');
            var dropdown   = wrapper.querySelector('.custom-select-dropdown');
            var hidden     = wrapper.querySelector('input[type="hidden"]');

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var isOpen = dropdown.style.display === 'block';
                // Tutup semua dropdown lain
                document.querySelectorAll('.custom-select-dropdown').forEach(function (d) {
                    d.style.display = 'none';
                });
                dropdown.style.display = isOpen ? 'none' : 'block';
            });

            wrapper.querySelectorAll('.custom-select-option').forEach(function (opt) {
                opt.addEventListener('click', function (e) {
                    e.stopPropagation();
                    wrapper.querySelectorAll('.custom-select-option').forEach(function (o) {
                        o.classList.remove('selected');
                    });
                    opt.classList.add('selected');
                    btn.querySelector('.custom-select-label').textContent = opt.textContent.trim();
                    hidden.value = opt.dataset.value;
                    dropdown.style.display = 'none';
                });
            });
        });

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function () {
            document.querySelectorAll('.custom-select-dropdown').forEach(function (d) {
                d.style.display = 'none';
            });
        });

        // Submit on Enter
        var searchInput = document.getElementById('{{ $searchId }}');
        if (searchInput) {
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') form.submit();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearchFilter);
    } else {
        initSearchFilter();
    }
})();
</script>

