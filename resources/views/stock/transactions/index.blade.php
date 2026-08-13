@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Transaksi Stok</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Catatan seluruh barang masuk dan keluar.</p>
    </div>
    @if(auth()->user()->isAdmin() || auth()->user()->isManager() || auth()->user()->isStaff())
        <div class="flex gap-2">
            <a href="{{ route('stock.transactions.create', 'in') }}" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">+ Barang Masuk</a>
            <a href="{{ route('stock.transactions.create', 'out') }}" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">- Barang Keluar</a>
        </div>
    @endif
</div>

<div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <form method="GET" action="{{ route('stock.transactions.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-5">
        <select name="type" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua Jenis</option>
            <option value="in" @selected(request('type') === 'in')>Barang Masuk</option>
            <option value="out" @selected(request('type') === 'out')>Barang Keluar</option>
        </select>
        <select name="product_id" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua Produk</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>{{ $product->name }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <input type="date" name="to" value="{{ request('to') }}" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Filter</button>
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
                    <th class="px-5 py-3">Qty</th>
                    <th class="px-5 py-3">Supplier</th>
                    <th class="px-5 py-3">Petugas</th>
                    <th class="px-5 py-3">Catatan</th>
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                        <th class="px-5 py-3 text-right">Aksi</th>
                    @endif
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
                        <td class="px-5 py-3">
                            <a href="{{ route('products.show', $transaction->product_id) }}" class="font-medium text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400">{{ $transaction->product->name ?? 'Produk terhapus' }}</a>
                            <p class="font-mono text-xs text-gray-400">{{ $transaction->product->sku ?? '' }}</p>
                        </td>
                        <td class="px-5 py-3 text-lg font-bold {{ $transaction->type === 'in' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $transaction->type === 'in' ? '+' : '-' }}{{ $transaction->quantity }}
                        </td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $transaction->supplier->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $transaction->user->name ?? '-' }}</td>
                        <td class="px-5 py-3 max-w-[200px] truncate text-gray-500 dark:text-gray-400">{{ $transaction->note ?? '-' }}</td>
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <td class="px-5 py-3">
                                <div class="flex justify-end">
                                    <form action="{{ route('stock.transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini? Stok akan disesuaikan kembali.')">
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
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
