@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="mx-auto max-w-xl">
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">&larr; Kembali</a>
        <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Tambah Pengguna</h1>
    </div>

    <form action="{{ route('users.store') }}" method="POST"
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @include('users._form')
    </form>
</div>
@endsection
