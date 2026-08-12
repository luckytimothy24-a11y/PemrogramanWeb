@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Kategori</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelompokkan produk berdasarkan kategori.</p>
    </div>
    @if(auth()->user()->isAdmin())
        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">Hak akses Admin</span>
    @endif
</div>

@if(auth()->user()->isAdmin())
<div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <h2 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Tambah Kategori Baru</h2>
    <form action="{{ route('categories.store') }}" method="POST" class="flex flex-col gap-3 sm:flex-row">
        @csrf
        <input type="text" name="name" placeholder="Nama kategori (mis: Elektronik)" required
            class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700">+ Tambah</button>
    </form>
    @error('name') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
</div>
@endif

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">No</th>
                    <th class="px-5 py-3">Nama Kategori</th>
                    <th class="px-5 py-3">Slug</th>
                    <th class="px-5 py-3">Jumlah Produk</th>
                    @if(auth()->user()->isAdmin())
                        <th class="px-5 py-3 text-right">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($categories as $index => $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $category->name }}</td>
                        <td class="px-5 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $category->slug }}</td>
                        <td class="px-5 py-3">
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">{{ $category->products->count() }}</span>
                        </td>
                        @if(auth()->user()->isAdmin())
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700" title="Ubah">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin menghapus kategori ini? Produk di dalamnya akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-red-500 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30" title="Hapus">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
