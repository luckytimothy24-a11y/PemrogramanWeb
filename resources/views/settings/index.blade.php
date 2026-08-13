@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Umum</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Atur nama aplikasi dan logo yang ditampilkan di seluruh aplikasi.</p>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data"
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-5">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Aplikasi</label>
                <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'Stockify') }}" required maxlength="100"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <p class="mt-1 text-xs text-gray-400">Ditampilkan di sidebar, judul halaman, dan halaman login.</p>
                @error('app_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Logo Aplikasi</label>
                <input type="file" name="app_logo" accept="image/*"
                    class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-500 file:mr-3 file:rounded-l-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:file:bg-blue-900/40 dark:file:text-blue-300">
                <p class="mt-1 text-xs text-gray-400">Maksimal 2 MB (JPG, PNG, WebP, GIF, atau SVG). Kosongkan jika tidak diubah.</p>
                @error('app_logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            @if(! empty($settings['app_logo']))
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Logo Saat Ini</p>
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo aplikasi"
                            class="h-14 w-14 rounded-xl border border-gray-200 object-contain p-1 dark:border-gray-700">
                        <a href="{{ route('settings.logo.delete') }}" class="text-sm font-medium text-red-600 hover:underline dark:text-red-400"
                            onclick="return confirm('Yakin menghapus logo?')">Hapus logo</a>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('dashboard.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Simpan Pengaturan</button>
        </div>
    </form>
</div>
@endsection
