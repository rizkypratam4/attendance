<div class="card rounded-2xl p-4 mb-5">
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex items-center gap-3 flex-1 px-4 py-2.5 rounded-xl"
            style="background:var(--bg-input);border:1px solid var(--border-in)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2"
                class="flex-shrink-0">
                <circle cx="11" cy="11" r="8" />
                <path d="M21 21l-4.35-4.35" />
            </svg>
            <input type="text" id="sgSearch" placeholder="Cari grup shift..." oninput="filterGroups()"
                style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%;font-family:inherit">
        </div>
    </div>
</div>
