@props(['users' => []])

<table class="w-full" style="border-collapse:collapse;min-width:560px">
    <thead>
        <tr style="border-bottom:1px solid var(--border)">
            <th class="text-left px-5 py-3 font-semibold"
                style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Nama Pengguna</th>
            <th class="text-left px-4 py-3 font-semibold"
                style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Peran</th>
            <th class="text-left px-4 py-3 font-semibold"
                style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Status</th>
            <th
                class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-violet-600 whitespace-nowrap">
                Terakhir Masuk
            </th>
            <th class="text-right px-5 py-3 font-semibold "
                style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($users as $user)
            @php
                $initials = collect(explode(' ', $user->first_name . ' ' . $user->last_name))
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->join('');
            @endphp

            <tr class="user-row" style="border-bottom:1px solid var(--border)">

                {{-- Username --}}
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                            style="background:linear-gradient(135deg,#7c3aed,#a78bfa);font-size:13px">
                            {{ $initials }}
                        </div>
                        <div>
                            <p style="font-size:14px;font-weight:600;color:var(--text-1)">
                                {{ $user->first_name . ' ' . $user->last_name }}
                            </p>
                            <p style="font-size:12px;color:var(--text-3)">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>

                {{-- Role --}}
                <td class="px-4 py-3.5">
                    <span class="px-3 py-1 rounded-lg font-semibold"
                        style="font-size:12px;background:#7c3aed;color:#fff">
                        {{ $user->role === 'employee' ? 'Karyawan' : ($user->role === 'manager' ? 'Manajer' : strtoupper($user->role)) }}
                    </span>
                </td>

                {{-- Status --}}
                <td class="px-4 py-3.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full flex-shrink-0"
                            style="background:{{ $user->status ? '#22c55e' : '#6b7280' }}"></span>
                        @if ($user->status)
                            <span class="text-green-400" style="font-size:13.5px">Aktif</span>
                        @else
                            <span class="text-gray-400" style="font-size:13.5px">Nonaktif</span>
                        @endif
                    </div>
                </td>

                {{-- Last Login --}}
                <td class="px-4 py-3.5 whitespace-nowrap" style="font-size:13.5px;color:var(--text-2)">
                    {{ $user->last_login?->diffForHumans() ?? '-' }}
                </td>

                {{-- Actions --}}
                <td class="px-5 py-3.5 text-right">
                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}"
                        method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>

                    <button class="act-trigger ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto"
                        data-name="{{ $user->first_name . ' ' . $user->last_name }}" data-email="{{ $user->email }}"
                        data-role="{{ $user->role }}" data-delete-id="{{ $user->id }}"
                        data-update-route="{{ route('users.update', $user->id) }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="5" r="1" />
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="12" cy="19" r="1" />
                        </svg>
                    </button>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="5" class="p-3 text-center text-sm text-gray-400">
                    Tidak ada pengguna ditemukan
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
