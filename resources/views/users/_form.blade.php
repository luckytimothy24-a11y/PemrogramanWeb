@php
    $isEdit = isset($user);
@endphp

<div class="grid grid-cols-1 gap-4">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', $isEdit ? $user->name : '') }}" required
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
        <input type="email" name="email" value="{{ old('email', $isEdit ? $user->email : '') }}" required
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Role / Hak Akses</label>
        <select name="role" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            @foreach(\App\Models\User::ROLES as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $isEdit ? $user->role : 'staff') == $value)>{{ $label }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-400">
            Admin mengelola semua data &amp; pengguna. Manajer Gudang mencatat transaksi &amp; membuat laporan. Staff melihat &amp; membantu operasional.
        </p>
        @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Password {{ $isEdit ? '(kosongkan jika tidak diubah)' : '' }}
        </label>
        <input type="password" name="password" autocomplete="new-password"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <p class="mt-1 text-xs text-gray-400">Minimal 6 karakter, huruf &amp; angka{{ !$isEdit ? '. Default: password' : '' }}.</p>
        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('users.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Batal</a>
    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pengguna' }}</button>
</div>
