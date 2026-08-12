@extends('example.layouts.default.dashboard')

@section('title', 'Dashboard Stok')

@section('content')
<div class="p-4 sm:p-6">
  <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
    <div>
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard Stok</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan cepat kondisi persediaan dan aktivitas utama.</p>
    </div>
    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">Sistem aktif</span>
  </div>

  <div class="grid gap-4 md:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
      <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk</p>
      <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
      <p class="text-sm text-gray-500 dark:text-gray-400">Total Kategori</p>
      <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $totalCategories }}</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
      <p class="text-sm text-gray-500 dark:text-gray-400">Total Supplier</p>
      <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $totalSuppliers }}</p>
    </div>
  </div>

  <div class="mt-6 grid gap-4 lg:grid-cols-[1.4fr_0.8fr]">
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
      <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Selamat datang</h2>
      <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300">
        Kelola kategori, produk, supplier, dan status stok dari satu dashboard yang lebih rapi dan mudah dipantau.
      </p>
      <div class="mt-4 flex flex-wrap gap-3">
        <a href="{{ route('products.index') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Lihat Produk</a>
        <a href="{{ route('stock.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Lihat Stok</a>
      </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Hari Ini</h3>
      <ul class="mt-4 space-y-3 text-sm text-gray-600 dark:text-gray-300">
        <li class="flex items-center justify-between"><span>Data kategori</span><span class="font-semibold text-gray-900 dark:text-white">{{ $totalCategories }}</span></li>
        <li class="flex items-center justify-between"><span>Data supplier</span><span class="font-semibold text-gray-900 dark:text-white">{{ $totalSuppliers }}</span></li>
        <li class="flex items-center justify-between"><span>Data produk</span><span class="font-semibold text-gray-900 dark:text-white">{{ $totalProducts }}</span></li>
      </ul>
    </div>
  </div>
</div>
@endsection
