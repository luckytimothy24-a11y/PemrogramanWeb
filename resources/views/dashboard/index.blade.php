@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard {{ $user->role_label }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan kondisi persediaan dan aktivitas terbaru.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        @if($user->isAdmin() || $user->isManager())
            <a href="{{ route('stock.transactions.create', 'in') }}" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">+ Barang Masuk</a>
            <a href="{{ route('stock.transactions.create', 'out') }}" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">- Barang Keluar</a>
        @endif
    </div>
</div>

<script type="application/json" id="stockify-chart-data">{{ json_encode($chart) }}</script>
<script type="application/json" id="stockify-category-data">{{ json_encode($stockByCategory) }}</script>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-stat-card label="Total Produk" :value="$summary['total_products']" icon="product" color="blue">
        <p class="text-xs text-gray-500 dark:text-gray-400">Produk terdaftar di gudang</p>
    </x-stat-card>
    <x-stat-card label="Total Stok Tersedia" :value="number_format(\App\Models\Product::sum('stock'), 0, ',', '.')" icon="stock" color="cyan">
        <p class="text-xs text-gray-500 dark:text-gray-400">Unit tersimpan secara keseluruhan</p>
    </x-stat-card>
    <x-stat-card label="Barang Masuk Hari Ini" :value="$summary['today_in']" icon="in" color="green">
        <p class="text-xs text-gray-500 dark:text-gray-400">Unit masuk hari ini</p>
    </x-stat-card>
    <x-stat-card label="Barang Keluar Hari Ini" :value="$summary['today_out']" icon="out" color="red">
        <p class="text-xs text-gray-500 dark:text-gray-400">Unit keluar hari ini</p>
    </x-stat-card>
</div>

<div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2 dark:border-gray-700 dark:bg-gray-800">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Transaksi Stok (14 Hari)</h2>
        </div>
        <div id="stockify-chart"></div>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Stok per Kategori</h2>
        <div id="stockify-category-chart"></div>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Stok Menipis</h2>
            <a href="{{ route('stock.index', ['status' => 'low']) }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-xs uppercase text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        <th class="pb-2 pr-2">Produk</th>
                        <th class="pb-2 pr-2">Stok</th>
                        <th class="pb-2">Min. Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($lowStockProducts as $product)
                        <tr>
                            <td class="py-2.5 pr-2 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                            <td class="py-2.5 pr-2">
                                <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">{{ $product->stock }}</span>
                            </td>
                            <td class="py-2.5 text-gray-500 dark:text-gray-400">{{ $product->min_stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-gray-400">Tidak ada stok menipis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Stok Habis</h2>
            <a href="{{ route('stock.index', ['status' => 'out']) }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-xs uppercase text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        <th class="pb-2 pr-2">Produk</th>
                        <th class="pb-2 pr-2">SKU</th>
                        <th class="pb-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($outOfStockProducts as $product)
                        <tr>
                            <td class="py-2.5 pr-2 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                            <td class="py-2.5 pr-2 text-gray-500 dark:text-gray-400">{{ $product->sku }}</td>
                            <td class="py-2.5">
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900 dark:text-red-300">Habis</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-gray-400">Semua produk tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h2>
        <ol class="relative space-y-4 border-l border-gray-200 pl-4 dark:border-gray-700">
            @forelse($recentActivities as $log)
                <li class="relative">
                    <span class="absolute -left-[21.5px] mt-1.5 h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->action }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->description }}</p>
                    <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">{{ $log->user->name ?? 'Sistem' }} &middot; {{ $log->created_at->diffForHumans() }}</p>
                </li>
            @empty
                <li class="text-sm text-gray-400">Belum ada aktivitas.</li>
            @endforelse
        </ol>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Data</h2>
        <ul class="space-y-3 text-sm">
            <li class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-700">
                <span class="text-gray-600 dark:text-gray-300">Kategori</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $summary['total_categories'] }}</span>
            </li>
            <li class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-700">
                <span class="text-gray-600 dark:text-gray-300">Supplier</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $summary['total_suppliers'] }}</span>
            </li>
            <li class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-700">
                <span class="text-gray-600 dark:text-gray-300">Pengguna</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $summary['total_users'] }}</span>
            </li>
            <li class="flex items-center justify-between rounded-xl bg-yellow-50 px-4 py-3 dark:bg-yellow-900/20">
                <span class="text-yellow-700 dark:text-yellow-300">Stok Menipis</span>
                <span class="font-semibold text-yellow-700 dark:text-yellow-300">{{ $summary['low_stock'] }}</span>
            </li>
            <li class="flex items-center justify-between rounded-xl bg-red-50 px-4 py-3 dark:bg-red-900/20">
                <span class="text-red-700 dark:text-red-300">Stok Habis</span>
                <span class="font-semibold text-red-700 dark:text-red-300">{{ $summary['out_of_stock'] }}</span>
            </li>
        </ul>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-blue-600 p-5 text-white shadow-sm">
        <h2 class="text-lg font-semibold">Petunjuk Cepat</h2>
        <p class="mt-2 text-sm leading-6 text-blue-100">
            Gunakan menu di sebelah kiri untuk mengelola produk, mencatat transaksi stok, melakukan stock opname, dan melihat laporan.
        </p>
        <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('products.index') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium hover:bg-white/30">Kelola Produk</a>
            <a href="{{ route('stock.opname.index') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium hover:bg-white/30">Stock Opname</a>
        </div>
    </div>
</div>
@endsection
