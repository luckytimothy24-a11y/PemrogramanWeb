@extends('layouts.app')

@section('title', 'Import Produk')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">&larr; Kembali ke daftar produk</a>
        <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Import Data Produk</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan banyak produk sekaligus dari file CSV.</p>
    </div>

    <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 p-5 text-sm text-blue-800 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
        <h2 class="mb-2 font-semibold">Format file CSV</h2>
        <p class="leading-6">Baris pertama adalah judul kolom. Kolom yang wajib: <strong>Nama</strong> dan <strong>Kategori</strong>. Kolom lain boleh dikosongkan (SKU kosong akan dibuat otomatis). Kategori &amp; supplier yang belum ada akan otomatis dibuat.</p>
        <ul class="mt-2 list-inside list-disc space-y-1">
            <li>Kolom: Nama, SKU, Kategori, Supplier, Harga Beli, Harga Jual, Stok, Stok Minimum, Deskripsi</li>
            <li>Baris dengan SKU duplikat atau tanpa kategori akan dilewati.</li>
            <li>File maksimal 4 MB.</li>
        </ul>
        <a href="{{ route('products.import.template') }}" class="mt-3 inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Download Template CSV</a>
    </div>

    <form action="{{ route('products.import.process') }}" method="POST" enctype="multipart/form-data"
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf

        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">File CSV</label>
        <input type="file" name="file" accept=".csv,.txt" required
            class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-500 file:mr-3 file:rounded-l-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:file:bg-blue-900/40 dark:file:text-blue-300">
        @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('products.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Import Data</button>
        </div>
    </form>
</div>
@endsection
