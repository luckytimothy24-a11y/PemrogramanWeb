@php
    $isEdit = isset($product);
    $attributes = $isEdit ? $product->attributes : [];
@endphp

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name', $isEdit ? $product->name : '') }}" required
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">SKU <span class="text-gray-400">(opsional)</span></label>
        <input type="text" name="sku" value="{{ old('sku', $isEdit ? $product->sku : '') }}"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <p class="mt-1 text-xs text-gray-400">Kosongkan untuk generate otomatis.</p>
        @error('sku') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
        <select name="category_id" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">— Pilih Kategori —</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $isEdit ? $product->category_id : '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier</label>
        <select name="supplier_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">— Tanpa Supplier —</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $isEdit ? $product->supplier_id : '') == $supplier->id)>{{ $supplier->name }}</option>
            @endforeach
        </select>
        @error('supplier_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Beli (Rp)</label>
        <input type="number" name="purchase_price" step="0.01" min="0" value="{{ old('purchase_price', $isEdit ? $product->purchase_price : '0') }}" required
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('purchase_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Jual (Rp)</label>
        <input type="number" name="selling_price" step="0.01" min="0" value="{{ old('selling_price', $isEdit ? $product->selling_price : '0') }}" required
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('selling_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Stok Awal</label>
        <input type="number" name="stock" min="0" value="{{ old('stock', $isEdit ? $product->stock : '0') }}" required
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('stock') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Stok Minimum</label>
        <input type="number" name="min_stock" min="0" value="{{ old('min_stock', $isEdit ? $product->min_stock : '0') }}"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <p class="mt-1 text-xs text-gray-400">Peringatan saat stok mencapai nilai ini.</p>
        @error('min_stock') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
        <textarea name="description" rows="3"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $isEdit ? $product->description : '') }}</textarea>
        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar Produk</label>
        <input type="file" name="image" accept="image/*"
            class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-500 file:mr-3 file:rounded-l-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:file:bg-blue-900/40 dark:file:text-blue-300">
        @if($isEdit && $product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="mt-3 h-20 w-20 rounded-lg object-cover" alt="Gambar produk">
        @endif
        @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <div class="mb-2 flex items-center justify-between">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Atribut Produk</label>
            <button type="button" id="add-attribute" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">+ Tambah atribut</button>
        </div>
        <div id="attribute-rows" class="space-y-2">
            @if(old('attributes'))
                @foreach(old('attributes') as $name => $value)
                    <div class="attribute-row flex gap-2">
                        <input type="text" name="attributes[{{ $loop->index }}][name]" value="{{ is_array($value) ? $value['name'] ?? '' : $name }}" placeholder="Nama (mis: Ukuran)"
                            class="w-1/3 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <input type="text" name="attributes[{{ $loop->index }}][value]" value="{{ is_array($value) ? $value['value'] ?? '' : $value }}" placeholder="Nilai (mis: L)"
                            class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <button type="button" class="remove-attribute rounded-lg p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endforeach
            @else
                @foreach($attributes as $attr)
                    <div class="attribute-row flex gap-2">
                        <input type="text" name="attributes[{{ $loop->index }}][name]" value="{{ $attr->name }}" placeholder="Nama (mis: Ukuran)"
                            class="w-1/3 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <input type="text" name="attributes[{{ $loop->index }}][value]" value="{{ $attr->value }}" placeholder="Nilai (mis: L)"
                            class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <button type="button" class="remove-attribute rounded-lg p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('products.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Batal</a>
    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Produk' }}</button>
</div>

@push('scripts')
<script>
    const attributeRows = document.getElementById('attribute-rows');
    let attributeIndex = attributeRows.children.length;

    document.getElementById('add-attribute').addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'attribute-row flex gap-2';
        row.innerHTML = `
            <input type="text" name="attributes[${attributeIndex}][name]" placeholder="Nama (mis: Ukuran)"
                class="w-1/3 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <input type="text" name="attributes[${attributeIndex}][value]" placeholder="Nilai (mis: L)"
                class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <button type="button" class="remove-attribute rounded-lg p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>`;
        attributeRows.appendChild(row);
        attributeIndex++;
    });

    attributeRows.addEventListener('click', (e) => {
        if (e.target.closest('.remove-attribute')) {
            e.target.closest('.attribute-row').remove();
        }
    });
</script>
@endpush
