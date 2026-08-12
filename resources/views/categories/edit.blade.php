@extends('layouts.app')

@section('title', 'Ubah Kategori')

@section('content')
<div class="mx-auto max-w-xl">
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">&larr; Kembali</a>
        <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Ubah Kategori</h1>
    </div>

    <form action="{{ route('categories.update', $category->id) }}" method="POST"
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @method('PUT')
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kategori</label>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" required
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('categories.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
