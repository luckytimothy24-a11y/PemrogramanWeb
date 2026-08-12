@php
    $isEdit = isset($supplier);
@endphp

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Supplier</label>
        <input type="text" name="name" value="{{ old('name', $isEdit ? $supplier->name : '') }}" required
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kontak</label>
        <input type="text" name="contact_person" value="{{ old('contact_person', $isEdit ? $supplier->contact_person : '') }}"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('contact_person') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $isEdit ? $supplier->phone : '') }}"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
        <input type="email" name="email" value="{{ old('email', $isEdit ? $supplier->email : '') }}"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
        <textarea name="address" rows="3"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('address', $isEdit ? $supplier->address : '') }}</textarea>
        @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('suppliers.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Batal</a>
    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Supplier' }}</button>
</div>
