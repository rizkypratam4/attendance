@props([
    'id',
    'title',
    'maxWidth' => '480px'
])

<div class="mbk" id="{{ $id }}" onclick="closeOut(event,'{{ $id }}')">

    <div class="mbox" style="max-width: {{ $maxWidth }}; z-index: 1050; position: fixed;">

        <div class="mhdr">

            <span class="mtitle">
                {{ $title }}
            </span>
 
            <button onclick="closeM('{{ $id }}')" class="mclose">
                ✕
            </button>

        </div>

        <div class="mbdy">

            {{ $slot }}

        </div>

    </div>

</div>