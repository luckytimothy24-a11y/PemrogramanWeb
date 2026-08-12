@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">&larr; Kembali ke daftar produk</a>
        <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Tambah Produk Baru</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Lengkapi informasi produk termasuk harga, stok, dan atribut.</p>
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @include('products._form')
    </form>
</div>
@endsection
