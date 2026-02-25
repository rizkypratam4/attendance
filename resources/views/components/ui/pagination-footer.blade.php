<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
     style="border-top:1px solid var(--border)">

    <p style="font-size:13px;color:var(--text-3)">
        Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
    </p>

    <div>
        {{ $paginator->links() }}
    </div>

</div>