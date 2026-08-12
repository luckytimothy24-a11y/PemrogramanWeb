@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
<div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Pengguna</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola akun dan hak akses pengguna aplikasi.</p>
    </div>
    <a href="{{ route('users.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-blue-700">+ Tambah Pengguna</a>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Pengguna</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Role</th>
                    <th class="px-5 py-3">Terdaftar</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full {{ $user->isAdmin() ? 'bg-violet-100 text-violet-700 dark:bg-violet-900 dark:text-violet-300' : ($user->isManager() ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300') }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    @if($user->id === auth()->id())
                                        <span class="text-xs text-blue-500">(Anda)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                        <td class="px-5 py-3">
                            @if($user->isAdmin())
                                <span class="rounded-full bg-violet-100 px-2 py-0.5 text-xs font-semibold text-violet-700 dark:bg-violet-900 dark:text-violet-300">Admin</span>
                            @elseif($user->isManager())
                                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700 dark:bg-blue-900 dark:text-blue-300">Manajer Gudang</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">Staff Gudang</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700" title="Ubah">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-red-500 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30" title="Hapus">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">Belum ada pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
