@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Barang Masuk &amp; Keluar</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Rekap seluruh transaksi stok dalam periode tertentu.</p>
    </div>
    <a href="{{ route('reports.transactions.export', $filters) }}" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
        <span class="flex items-center gap-2">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v8.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414L9 11.586V3a1 1 0 011-1zM3 15a1 1 0 011 1v1h12v-1a1 1 0 112 0v1a2 2 0 01-2 2H4a2 2 0 01-2-2v-1a1 1 0 011-1z"/></svg>
            Export CSV
        </span>
    </a>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Unit Masuk</p>
        <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($summary['in']) }}</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Unit Keluar</p>
        <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($summary['out']) }}</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Transaksi</p>
        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($summary['transaction_count']) }}</p>
    </div>
</div>

<div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <form method="GET" action="{{ route('reports.transactions') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-5">
        <select name="type" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua Jenis</option>
            <option value="in" @selected(($filters['type'] ?? '') === 'in')>Barang Masuk</option>
            <option value="out" @selected(($filters['type'] ?? '') === 'out')>Barang Keluar</option>
        </select>
        <select name="product_id" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua Produk</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" @selected(($filters['product_id'] ?? '') == $product->id)>{{ $product->name }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <div class="flex gap-2">
            <button type="submit" class="flex-1 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Filter</button>
            <a href="{{ route('reports.transactions') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Reset</a>
        </div>
    </form>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Tanggal</th>
                    <th class="px-5 py-3">Jenis</th>
                    <th class="px-5 py-3">Produk</th>
                    <th class="px-5 py-3 text-right">Qty</th>
                    <th class="px-5 py-3 text-right">Harga</th>
                    <th class="px-5 py-3 text-right">Total</th>
                    <th class="px-5 py-3">Supplier</th>
                    <th class="px-5 py-3">Petugas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $transaction->transaction_date->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-3">
                            @if($transaction->type === 'in')
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-900 dark:text-green-300">Masuk</span>
                            @else
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900 dark:text-red-300">Keluar</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $transaction->product->name ?? 'Produk terhapus' }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900 dark:text-white">{{ $transaction->quantity }}</td>
                        <td class="px-5 py-3 text-right text-gray-600 dark:text-gray-300">{{ number_format($transaction->price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900 dark:text-white">{{ number_format($transaction->price * $transaction->quantity, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $transaction->supplier->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $transaction->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">Tidak ada transaksi sesuai filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
