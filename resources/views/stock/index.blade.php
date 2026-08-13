@extends('layouts.app')

@section('title', 'Stok / Monitoring')

@section('content')
<div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Monitoring Stok</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Pantau ketersediaan stok barang secara real-time.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('stock.index', ['status' => 'low']) }}" class="rounded-lg bg-yellow-100 px-3 py-1.5 text-xs font-medium text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">{{ $lowStockCount }} Menipis</a>
        <a href="{{ route('stock.index', ['status' => 'out']) }}" class="rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 dark:bg-red-900 dark:text-red-300">{{ $outOfStockCount }} Habis</a>
    </div>
</div>

<div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <form method="GET" action="{{ route('stock.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / SKU..."
            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <select name="category_id" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua Status</option>
            <option value="ok" @selected(request('status') === 'ok')>Aman</option>
            <option value="low" @selected(request('status') === 'low')>Menipis</option>
            <option value="out" @selected(request('status') === 'out')>Habis</option>
        </select>
        <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Filter</button>
    </form>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Produk</th>
                    <th class="px-5 py-3">SKU</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Stok</th>
                    <th class="px-5 py-3">Min. Stok</th>
                    <th class="px-5 py-3 w-40">Indikator</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($products as $product)
                    @php
                        $max = max($product->stock, $product->min_stock, 1);
                        $pct = round(($product->stock / $max) * 100);
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">
                            <a href="{{ route('products.show', $product->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ $product->name }}</a>
                        </td>
                        <td class="px-5 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-lg font-bold text-gray-900 dark:text-white">{{ $product->stock }}</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $product->min_stock }}</td>
                        <td class="px-5 py-3">
                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                                <div class="h-2 rounded-full {{ $product->stock_status === 'out' ? 'bg-red-500' : ($product->stock_status === 'low' ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ $pct }}%"></div>
                            </div>
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
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager() || auth()->user()->isStaff())
                                    <a href="{{ route('stock.transactions.create', 'in') }}" class="rounded-lg bg-green-50 p-2 text-green-600 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-300 dark:hover:bg-green-900/50" title="Barang Masuk">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a1 1 0 100-2H6a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H6V6h3zM15 3a1 1 0 00-1 1v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L16 9.586V4a1 1 0 00-1-1z"/></svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">Tidak ada data stok sesuai filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
