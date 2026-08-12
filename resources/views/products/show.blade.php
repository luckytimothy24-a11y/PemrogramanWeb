@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">&larr; Kembali ke daftar produk</a>
        <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Detail Produk</h1>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full rounded-xl object-cover">
            @else
                <div class="flex h-56 w-full items-center justify-center rounded-xl bg-gray-100 text-gray-400 dark:bg-gray-700">
                    <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                </div>
            @endif
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">{{ $product->category->name ?? 'Tanpa Kategori' }}</span>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $product->supplier->name ?? 'Tanpa Supplier' }}</span>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2 dark:border-gray-700 dark:bg-gray-800">
            <div class="flex flex-wrap items-start justify-between gap-2">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h2>
                    <p class="font-mono text-xs text-gray-400">SKU: {{ $product->sku }}</p>
                </div>
                @if($product->stock_status === 'out')
                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 dark:bg-red-900 dark:text-red-300">Stok Habis</span>
                @elseif($product->stock_status === 'low')
                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">Stok Menipis</span>
                @else
                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-900 dark:text-green-300">Stok Aman</span>
                @endif
            </div>

            <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">{{ $product->description ?: 'Belum ada deskripsi.' }}</p>

            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Stok Tersedia</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $product->stock }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Stok Minimum</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $product->min_stock }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Harga Beli</p>
                    <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Harga Jual</p>
                    <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($product->attributes->count())
                <h3 class="mt-6 text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Atribut Produk</h3>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($product->attributes as $attr)
                        <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-700 dark:border-gray-600 dark:text-gray-300">
                            <span class="font-semibold">{{ $attr->name }}:</span> {{ $attr->value }}
                        </span>
                    @endforeach
                </div>
            @endif

            <div class="mt-6 flex gap-3">
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <a href="{{ route('products.edit', $product->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Ubah Produk</a>
                    <a href="{{ route('stock.transactions.create', 'in') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Tambah Stok</a>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Riwayat Transaksi Produk Ini</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-2.5">Tanggal</th>
                        <th class="px-4 py-2.5">Jenis</th>
                        <th class="px-4 py-2.5">Qty</th>
                        <th class="px-4 py-2.5">Petugas</th>
                        <th class="px-4 py-2.5">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($product->transactions()->latest('transaction_date')->take(10)->get() as $transaction)
                        <tr>
                            <td class="px-4 py-2.5 text-gray-600 dark:text-gray-300">{{ $transaction->transaction_date->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2.5">
                                @if($transaction->type === 'in')
                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-900 dark:text-green-300">Masuk</span>
                                @else
                                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900 dark:text-red-300">Keluar</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 font-semibold text-gray-900 dark:text-white">{{ $transaction->quantity }}</td>
                            <td class="px-4 py-2.5 text-gray-600 dark:text-gray-300">{{ $transaction->user->name ?? '-' }}</td>
                            <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400">{{ $transaction->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada transaksi untuk produk ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
