@extends('layouts.app')

@section('title', 'Laporan Aktivitas')

@section('content')
<div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Aktivitas Pengguna</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Jejak aktivitas seluruh pengguna dalam sistem.</p>
    </div>
    <a href="{{ route('reports.activities.export', $filters) }}" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
        <span class="flex items-center gap-2">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v8.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414L9 11.586V3a1 1 0 011-1zM3 15a1 1 0 011 1v1h12v-1a1 1 0 112 0v1a2 2 0 01-2 2H4a2 2 0 01-2-2v-1a1 1 0 011-1z"/></svg>
            Export CSV
        </span>
    </a>
</div>

<div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <form method="GET" action="{{ route('reports.activities') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-5">
        <select name="user_id" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua Pengguna</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(($filters['user_id'] ?? '') == $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
        <select name="action" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua Aksi</option>
            <option value="Login" @selected(($filters['action'] ?? '') === 'Login')>Login</option>
            <option value="Logout" @selected(($filters['action'] ?? '') === 'Logout')>Logout</option>
            <option value="Tambah Produk" @selected(($filters['action'] ?? '') === 'Tambah Produk')>Tambah Produk</option>
            <option value="Ubah Produk" @selected(($filters['action'] ?? '') === 'Ubah Produk')>Ubah Produk</option>
            <option value="Hapus Produk" @selected(($filters['action'] ?? '') === 'Hapus Produk')>Hapus Produk</option>
            <option value="Barang Masuk" @selected(($filters['action'] ?? '') === 'Barang Masuk')>Barang Masuk</option>
            <option value="Barang Keluar" @selected(($filters['action'] ?? '') === 'Barang Keluar')>Barang Keluar</option>
            <option value="Stock Opname" @selected(($filters['action'] ?? '') === 'Stock Opname')>Stock Opname</option>
        </select>
        <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <div class="flex gap-2">
            <button type="submit" class="flex-1 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Filter</button>
            <a href="{{ route('reports.activities') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Reset</a>
        </div>
    </form>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Waktu</th>
                    <th class="px-5 py-3">Pengguna</th>
                    <th class="px-5 py-3">Aksi</th>
                    <th class="px-5 py-3">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $log->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700 dark:bg-blue-900 dark:text-blue-300">{{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $log->user->name ?? 'Sistem' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">{{ $log->action }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-gray-400">Belum ada aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
