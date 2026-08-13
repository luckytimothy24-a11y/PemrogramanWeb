@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Produk</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola data produk, harga, stok, dan atribut.</p>
    </div>
    <div class="flex flex-col gap-2 sm:flex-row">
        <form method="GET" action="{{ route('products.index') }}" class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / SKU..."
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Cari</button>
        </form>
        @if(auth()->user()->isAdmin())
            <div class="flex gap-2">
                <a href="{{ route('products.export') }}" class="rounded-lg border border-green-300 px-4 py-2 text-center text-sm font-medium text-green-700 hover:bg-green-50 dark:border-green-600 dark:text-green-300 dark:hover:bg-green-900/30" title="Unduh semua produk dalam format CSV">Export CSV</a>
                <a href="{{ route('products.import') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Import</a>
            </div>
        @endif
        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
            <a href="{{ route('products.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-blue-700">+ Tambah Produk</a>
        @endif
    </div>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Produk</th>
                    <th class="px-5 py-3">SKU</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Supplier</th>
                    <th class="px-5 py-3">Harga Jual</th>
                    <th class="px-5 py-3">Stok</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover">
                                @else
                                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                    </span>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $product->description ? \Illuminate\Support\Str::limit($product->description, 40) : '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }}</td>
                        <td class="px-5 py-3">
                            <span class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">{{ $product->category->name ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $product->supplier->name ?? '-' }}</td>
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            @if($product->stock_status === 'out')
                                <span class="font-semibold text-red-600 dark:text-red-400">{{ $product->stock }}</span>
                            @elseif($product->stock_status === 'low')
                                <span class="font-semibold text-yellow-600 dark:text-yellow-400">{{ $product->stock }}</span>
                            @else
                                <span class="font-semibold text-green-600 dark:text-green-400">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($product->stock_status === 'out')
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900 dark:text-red-300">Habis</span>
                            @elseif($product->stock_status === 'low')
                                <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">Menipis</span>
                            @else
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-900 dark:text-green-300">Aman</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('products.show', $product->id) }}" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700" title="Detail">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                </a>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                    <a href="{{ route('products.edit', $product->id) }}" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700" title="Ubah">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin menghapus produk ini?')">
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
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            <p class="text-base font-medium">Belum ada produk.</p>
                            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <a href="{{ route('products.create') }}" class="mt-2 inline-block text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">Tambah produk pertama</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
