@extends('layouts.app')

@section('title', 'Stock Opname')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Stock Opname</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Cocokkan stok sistem dengan stok fisik aktual di gudang.</p>
</div>

@if(auth()->user()->isAdmin() || auth()->user()->isManager())
<div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <h2 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Catat Hasil Opname</h2>
    <form action="{{ route('stock.opname.store') }}" method="POST" class="grid grid-cols-1 gap-4 md:grid-cols-4">
        @csrf
        <div class="md:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Produk</label>
            <select name="product_id" id="opname-product" required
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">— Pilih Produk —</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">{{ $product->name }} (stok sistem: {{ $product->stock }})</option>
                @endforeach
            </select>
            @error('product_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Aktual</label>
            <input type="number" name="actual_qty" min="0" value="{{ old('actual_qty') }}" placeholder="Hasil hitungan fisik" required
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            @error('actual_qty') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">&nbsp;</label>
            <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Simpan Opname</button>
        </div>
        <div class="md:col-span-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
            <input type="text" name="note" value="{{ old('note') }}" placeholder="Keterangan selisih (jika ada)"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>
    </form>
    @if($errors->any())
        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">{{ $errors->first() }}</div>
    @endif
</div>
@endif

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Tanggal</th>
                    <th class="px-5 py-3">Produk</th>
                    <th class="px-5 py-3">Stok Sistem</th>
                    <th class="px-5 py-3">Stok Aktual</th>
                    <th class="px-5 py-3">Selisih</th>
                    <th class="px-5 py-3">Petugas</th>
                    <th class="px-5 py-3">Catatan</th>
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                        <th class="px-5 py-3 text-right">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($opnames as $opname)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $opname->opname_date->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $opname->product->name ?? 'Produk terhapus' }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $opname->system_qty }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-900 dark:text-white">{{ $opname->actual_qty }}</td>
                        <td class="px-5 py-3">
                            @if($opname->difference > 0)
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-900 dark:text-green-300">+{{ $opname->difference }}</span>
                            @elseif($opname->difference < 0)
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900 dark:text-red-300">{{ $opname->difference }}</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">0</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $opname->user->name ?? '-' }}</td>
                        <td class="px-5 py-3 max-w-[200px] truncate text-gray-500 dark:text-gray-400">{{ $opname->note ?? '-' }}</td>
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <td class="px-5 py-3">
                                <div class="flex justify-end">
                                    <form action="{{ route('stock.opname.destroy', $opname->id) }}" method="POST" onsubmit="return confirm('Hapus data opname ini?')">
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
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">Belum ada data stock opname.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
