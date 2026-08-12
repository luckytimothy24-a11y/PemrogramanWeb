@extends('layouts.app')

@section('title', $type === 'in' ? 'Barang Masuk' : 'Barang Keluar')

@section('content')
@php
    $isIn = $type === 'in';
@endphp

<div class="mx-auto max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('stock.transactions.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">&larr; Kembali ke riwayat</a>
        <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Catat {{ $isIn ? 'Barang Masuk' : 'Barang Keluar' }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $isIn ? 'Catat penerimaan barang ke gudang (stok bertambah).' : 'Catat pengeluaran barang dari gudang (stok berkurang).' }}</p>
    </div>

    <form action="{{ route('stock.transactions.store', $type) }}" method="POST"
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf

        <div class="mb-4 flex gap-3">
            <a href="{{ route('stock.transactions.create', 'in') }}" class="flex-1 rounded-xl border-2 px-4 py-3 text-center text-sm font-semibold {{ $isIn ? 'border-green-500 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'border-gray-200 text-gray-400 dark:border-gray-700' }}">Barang Masuk</a>
            <a href="{{ route('stock.transactions.create', 'out') }}" class="flex-1 rounded-xl border-2 px-4 py-3 text-center text-sm font-semibold {{ !$isIn ? 'border-red-500 bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300' : 'border-gray-200 text-gray-400 dark:border-gray-700' }}">Barang Keluar</a>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Produk</label>
                <select name="product_id" id="product-select" required
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">— Pilih Produk —</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-stock="{{ $product->stock }}" data-buy="{{ $product->purchase_price }}" data-sell="{{ $product->selling_price }}"
                            @selected(old('product_id') == $product->id)>
                            {{ $product->name }} (stok: {{ $product->stock }})
                        </option>
                    @endforeach
                </select>
                @error('product_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            @if($isIn)
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier</label>
                <select name="supplier_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">— Tanpa Supplier —</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah ({!! $isIn ? 'masuk' : 'keluar' !!})</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('quantity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Satuan (Rp)</label>
                    <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" placeholder="Kosongkan = otomatis"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <p class="mt-1 text-xs text-gray-400">Kosongkan untuk memakai harga {{ $isIn ? 'beli' : 'jual' }} produk.</p>
                    @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Transaksi</label>
                <input type="datetime-local" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('transaction_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                <textarea name="note" rows="2" placeholder="Contoh: {{ $isIn ? 'Penerimaan PO #123' : 'Penjualan / retur / pemakaian internal' }}"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('note') }}</textarea>
                @error('note') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        @if($errors->any())
            <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('stock.transactions.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Batal</a>
            <button type="submit" class="rounded-lg {{ $isIn ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} px-6 py-2.5 text-sm font-medium text-white">
                {{ $isIn ? 'Catat Barang Masuk' : 'Catat Barang Keluar' }}
            </button>
        </div>
    </form>
</div>
@endsection
