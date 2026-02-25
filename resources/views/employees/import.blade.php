<x-ui.modal id="mImportEmployee" title="Import Employee" maxWidth="480px">

    <form action="#" method="POST">
        <div class="flex gap-3 mt-6">
                <button type="button"
                        onclick="closeM('mImportEmployee')"
                        class="flex-1 py-2.5 rounded-xl font-medium"
                        style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                    Cancel
                </button>

                <button type="submit"
                        class="flex-1 purbtn py-2.5 rounded-xl font-semibold"
                        style="font-size:14px">
                    Import Employee
                </button>
        </div>
    </form>

</x-ui.modal>

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mImportEmployee'));
    </script>
@endif