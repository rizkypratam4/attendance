<div class="flex items-center gap-2">

    {{-- Download Button --}}
    @if($download ?? true)
        <button 
            {{ $downloadAttributes ?? '' }}
            class="ib-bg w-9 h-9 rounded-xl flex items-center justify-center"
        >
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
        </button>
    @endif

    {{-- Print Button --}}
    @if($print ?? true)
        <button 
            {{ $printAttributes ?? '' }}
            class="ib-bg w-9 h-9 rounded-xl flex items-center justify-center"
        >
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <polyline points="6 9 6 2 18 2 18 9" />
                <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                <rect x="6" y="14" width="12" height="8" />
            </svg>
        </button>
    @endif

</div>