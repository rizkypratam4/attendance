@props([
    'locations' => [],
])

<table class="w-full" style="border-collapse:collapse;min-width:580px">
    <thead>
        <tr style="background:rgba(124,58,237,.10);border-bottom:1px solid var(--border)">
            <th class="text-left px-5 py-3.5 font-semibold"
                style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                Location Name
            </th>
            <th class="text-left px-4 py-3.5 font-semibold"
                style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                Address
            </th>
            <th class="text-left px-4 py-3.5 font-semibold"
                style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                Coordinates
            </th>
            <th class="text-left px-4 py-3.5 font-semibold"
                style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                Status
            </th>
            <th class="text-right px-5 py-3.5 font-semibold"
                style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                Actions
            </th>
        </tr>
    </thead>

    <tbody>
        @forelse($locations as $location)
            <tr class="loc-row" style="border-bottom:1px solid var(--border)">
                <td class="px-5 py-4">
                    <p style="font-size:14px;font-weight:700;color:var(--text-1)">
                        {{ $location->name }}
                    </p>
                    <p style="font-size:12px;color:var(--text-3)">
                        {{ $location->description ?? '-' }}
                    </p>
                </td>

                <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">
                    {{ $location->address }}
                </td>

                <td class="px-4 py-4" style="font-size:12px;color:#a78bfa;font-weight:500">
                    {{ $location->latitude }}, {{ $location->longitude }}
                </td>

                <td class="px-4 py-4">
                    @if ($location->is_active)
                        <span class="px-3 py-1 rounded-full font-semibold"
                            style="font-size:11px;background:rgba(34,197,94,.15);color:#22c55e;">
                            ACTIVE
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full font-semibold"
                            style="font-size:11px;background:rgba(255,255,255,.07);color:var(--text-3);">
                            INACTIVE
                        </span>
                    @endif
                </td>

                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button type="button"
                            onclick="openEditLocation(
                                    '{{ $location->id }}',
                                    '{{ $location->name }}',
                                    '{{ $location->description }}',
                                    '{{ $location->address }}',
                                    '{{ $location->latitude }}',
                                    '{{ $location->longitude }}',
                                    '{{ $location->is_active }}'
                                )"
                            class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </button>
                        <form id="delete-form-location-{{ $location->id }}"
                            action="{{ route('locations.destroy', $location->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                onclick="openDeleteLocation('{{ $location->name }}', {{ $location->id }})"
                                class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center"
                                style="background:rgba(239,68,68,.10);color:#f87171">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                    <path d="M10 11v6M14 11v6" />
                                    <path d="M9 6V4h6v2" />
                                </svg>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-6" style="color:var(--text-3)">
                    No locations found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
