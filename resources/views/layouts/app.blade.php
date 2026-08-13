<!doctype html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $settings['app_name'] ?? 'Stockify' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 antialiased dark:bg-gray-900">
    @php
        $currentUser = auth()->user();
    @endphp

    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar" class="fixed top-0 left-0 z-40 flex h-screen w-64 shrink-0 -translate-x-full flex-col border-r border-gray-200 bg-white transition-transform duration-200 lg:translate-x-0 dark:border-gray-700 dark:bg-gray-800" aria-label="Sidebar">
            <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 px-5 dark:border-gray-700">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2">
                    @if(! empty($settings['app_logo']))
                        <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="{{ $settings['app_name'] ?? 'Stockify' }}" class="h-9 w-9 rounded-xl object-contain">
                    @else
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 011-1h12a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V3zm2 2v2h10V5H5zm-2 5a1 1 0 011-1h12a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm2 2v2h10v-2H5z"></path></svg>
                        </span>
                    @endif
                    <div>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $settings['app_name'] ?? 'Stockify' }}</span>
                        <span class="block text-[10px] font-medium uppercase tracking-wider text-gray-400">Manajemen Stok</span>
                    </div>
                </a>
                <button data-drawer-hide="sidebar" class="text-gray-500 hover:text-gray-900 lg:hidden dark:text-gray-400 dark:hover:text-white" aria-label="Tutup sidebar">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-5">
                <ul class="space-y-1">
                    <x-nav-link :route="'dashboard.index'" label="Dashboard">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
                    </x-nav-link>
                    <x-nav-link :route="'products.index'" label="Produk">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v2H4V6zm0 4h7v4H4v-4zm9 0h3v4h-3v-4z"></path></svg>
                    </x-nav-link>
                    <x-nav-link :route="'categories.index'" label="Kategori">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 4a1 1 0 011-1h3a1 1 0 011 1v2h8a1 1 0 011 1v2H4a1 1 0 01-1-1V4zm2 4h10v7a1 1 0 01-1 1H6a1 1 0 01-1-1V8z"></path></svg>
                    </x-nav-link>
                    <x-nav-link :route="'suppliers.index'" label="Supplier">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 4a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2H5zm1 3h8v2H6V7zm0 4h5v2H6v-2z"></path></svg>
                    </x-nav-link>
                    <x-nav-link :route="'stock.index'" label="Stok / Monitoring">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 011-1h12a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V3zm2 2v2h10V5H5zm-2 5a1 1 0 011-1h12a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm2 2v2h10v-2H5z"></path></svg>
                    </x-nav-link>
                    <x-nav-link :route="'stock.transactions.index'" label="Riwayat Transaksi">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM2 9h16v5a2 2 0 01-2 2H4a2 2 0 01-2-2V9zm6 2a1 1 0 100 2h4a1 1 0 100-2H8z"></path></svg>
                    </x-nav-link>
                    <x-nav-link :route="'stock.opname.index'" label="Stock Opname">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2v8h8V6H6z"></path></svg>
                    </x-nav-link>
                </ul>

                @if($currentUser->isAdmin() || $currentUser->isManager() || $currentUser->isStaff())
                <p class="mt-6 mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Transaksi Stok</p>
                <ul class="space-y-1">
                    <x-nav-link :route="'stock.transactions.create'" :params="['type' => 'in']" label="Barang Masuk">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.75 2a.75.75 0 01.75.75v5.19l2.72-2.72a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 111.06-1.06l2.72 2.72V2.75a.75.75 0 01.75-.75zM3 12a1 1 0 011-1h12a1 1 0 011 1v5a1 1 0 01-1 1H4a1 1 0 01-1-1v-5z"></path></svg>
                    </x-nav-link>
                    <x-nav-link :route="'stock.transactions.create'" :params="['type' => 'out']" label="Barang Keluar">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.75 2a.75.75 0 01.75.75v5.19l2.72-2.72a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 111.06-1.06l2.72 2.72V2.75a.75.75 0 01.75-.75zM3 12a1 1 0 011-1h12a1 1 0 011 1v5a1 1 0 01-1 1H4a1 1 0 01-1-1v-5z"></path></svg>
                    </x-nav-link>
                </ul>
                @endif

                @if($currentUser->isAdmin() || $currentUser->isManager())
                <p class="mt-6 mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Laporan</p>
                <ul class="space-y-1">
                    <x-nav-link :route="'reports.stock'" label="Laporan Stok">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h.007L3 5v9a1 1 0 001 1h11a1 1 0 000-2H5V5h11l.002 9H17V5a1 1 0 00-1-1H3zM7 7a1 1 0 011-1h6a1 1 0 110 2H8a1 1 0 01-1-1zm0 4a1 1 0 011-1h6a1 1 0 110 2H8a1 1 0 01-1-1z"></path></svg>
                    </x-nav-link>
                    <x-nav-link :route="'reports.transactions'" label="Laporan Transaksi">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM2 9h16v5a2 2 0 01-2 2H4a2 2 0 01-2-2V9zm6 2a1 1 0 100 2h4a1 1 0 100-2H8z"></path></svg>
                    </x-nav-link>
                    @if($currentUser->isAdmin())
                        <x-nav-link :route="'reports.activities'" label="Aktivitas Pengguna">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                        </x-nav-link>
                    @endif
                </ul>
                @endif

                @if($currentUser->isAdmin())
                <p class="mt-6 mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Manajemen</p>
                <ul class="space-y-1">
                    <x-nav-link :route="'users.index'" label="Pengguna">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                    </x-nav-link>
                    <x-nav-link :route="'settings.index'" label="Pengaturan">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 4a1 1 0 00-1 1v1h12V5a1 1 0 00-1-1H5zm10 4H5v7a1 1 0 001 1h8a1 1 0 001-1V8zM3 3a2 2 0 012-2h10a2 2 0 012 2v1h.5A1.5 1.5 0 0119 5.5v.75l-.002.086A1.5 1.5 0 0117.5 7.5h-.5V15a3 3 0 01-3 3H6a3 3 0 01-3-3V7.5h-.5A1.5 1.5 0 011 6V5.5A1.5 1.5 0 012.5 4H3V3z"/></svg>
                    </x-nav-link>
                </ul>
                @endif
            </nav>

            <div class="shrink-0 border-t border-gray-200 p-4 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                        {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $currentUser->name }}</p>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $currentUser->role_label }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <div id="sidebarBackdrop" class="fixed inset-0 z-30 hidden bg-gray-900/50 lg:hidden"></div>

        <div class="flex min-w-0 flex-1 flex-col lg:pl-64">
            <header class="sticky top-0 z-20 flex h-16 shrink-0 items-center border-b border-gray-200 bg-white px-4 dark:border-gray-700 dark:bg-gray-800 sm:px-6">
                <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button"
                    class="inline-flex items-center rounded-lg p-2 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 lg:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                </button>

                <div class="ml-2 lg:ml-0">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Selamat datang kembali,</p>
                    <h1 class="text-base font-semibold text-gray-900 dark:text-white">{{ $currentUser->name }}</h1>
                </div>

                <div class="ml-auto flex items-center gap-3">
                    <button id="theme-toggle" type="button"
                        class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg id="theme-toggle-dark-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"/></svg>
                    </button>

                    <div class="relative">
                        <button type="button" data-dropdown-toggle="user-dropdown"
                            class="flex items-center gap-2 rounded-lg p-1.5 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">
                                {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                            </span>
                            <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                        <div id="user-dropdown" class="z-50 hidden w-56 rounded-xl border border-gray-200 bg-white py-2 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                            <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-700">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $currentUser->name }}</p>
                                <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $currentUser->email }}</p>
                                <span class="mt-1.5 inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">{{ $currentUser->role_label }}</span>
                            </div>
                            <a href="{{ route('products.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Produk</a>
                            <a href="{{ route('stock.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Stok</a>
                            <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-gray-100 dark:text-red-400 dark:hover:bg-gray-700">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                @if(session('success'))
                    <div class="mb-4 flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/30 dark:text-green-300" role="alert">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300" role="alert">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="shrink-0 border-t border-gray-200 bg-white px-6 py-4 text-center text-xs text-gray-400 dark:border-gray-700 dark:bg-gray-800">
                {{ $settings['app_name'] ?? 'Stockify' }} &copy; {{ date('Y') }} — Aplikasi Manajemen Stok Barang
            </footer>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    @stack('scripts')
</body>
</html>
