@extends('layouts.app')

@section('title', 'Akun Pengguna')

@php $active = 'users'; @endphp

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-white leading-tight">
                Akun Pengguna
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                Kelola, ubah, dan pantau seluruh akun pengguna yang terdaftar dalam sistem.
            </p>
        </div>

        <button onclick="openM('mCreateUser')"
            class="purbtn inline-flex self-start items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                <circle cx="8.5" cy="7" r="4" />
                <line x1="20" y1="8" x2="20" y2="14" />
                <line x1="23" y1="11" x2="17" y2="11" />
            </svg>
            Tambah Pengguna
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        <x-ui.stat-card title="Total Pengguna" :value="number_format($totalUsers)">
            <x-slot:icon>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                </svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card title="Sedang Aktif" :value="$activeNow"
            metaStyle="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.06em;text-transform:uppercase"
            iconBg="rgba(34,197,94,.15)">
            <x-slot:icon>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                    <path d="M5 12.55a11 11 0 0114.08 0" />
                    <path d="M1.42 9a16 16 0 0121.16 0" />
                    <path d="M8.53 16.11a6 6 0 016.95 0" />
                    <circle cx="12" cy="20" r="1" fill="#22c55e" />
                </svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card title="Hak Akses Terdaftar" :value="$totalRoles"
            metaStyle="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.06em;text-transform:uppercase">
            <x-slot:icon>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    <div class="card rounded-2xl" style="overflow:hidden">
        <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"
            style="border-bottom:1px solid var(--border)">

            <x-users.filter-header :showing="$users->count()" :total="$users->total()" />

            <div class="flex justify-start sm:justify-end">
                <x-action-buttons />
            </div>

        </div>

        <div class="overflow-x-auto">
            <x-users.table :users="$users">
            </x-users.table>
        </div>

        <x-ui.pagination-footer :paginator="$users" />
    </div>
    @include('users.create')

    @include('users.edit')

@endsection

@push('styles')
    <style>
        .user-row {
            transition: background .15s;
        }

        .user-row:hover {
            background: var(--bg-hover);
        }
    </style>
@endpush
