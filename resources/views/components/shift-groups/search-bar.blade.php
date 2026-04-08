<div class="card rounded-2xl p-4 mb-5">
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex items-center gap-3 flex-1 px-4 py-2.5 rounded-xl"
             style="background:var(--bg-input);border:1px solid var(--border-in)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" id="sgSearch" placeholder="Search shift groups by name or description..."
                   oninput="filterGroups()"
                   style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%;font-family:inherit">
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium ib-bg"
                    style="font-size:13.5px;color:var(--text-2)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
                </svg>
                Filter
            </button>
            <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium ib-bg"
                    style="font-size:13.5px;color:var(--text-2)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Export
            </button>
        </div>
    </div>
</div>