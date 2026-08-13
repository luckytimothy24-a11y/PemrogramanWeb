<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - {{ $settings['app_name'] ?? 'Stockify' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
    <div class="min-h-screen bg-slate-100 px-4 py-6 text-slate-800 sm:px-6 lg:px-8 lg:py-8">
        <div class="mx-auto flex max-w-md flex-col overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-[0_20px_50px_rgba(15,23,42,0.08)]">
            <div class="bg-slate-900 p-8 text-center text-white sm:p-10">
                <div class="inline-flex items-center rounded-full border border-slate-700 bg-slate-800 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-300">
                    {{ $settings['app_name'] ?? 'Stockify' }} Admin
                </div>
                <h1 class="mt-5 text-2xl font-semibold sm:text-3xl">Masuk ke akun Anda</h1>
                <p class="mt-2 text-sm leading-6 text-slate-300">
                    Gunakan username atau email serta kata sandi Anda untuk melanjutkan.
                </p>
            </div>

            <div class="p-8 sm:p-10">
                @if($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-600">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form class="space-y-5" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <label for="login" class="mb-2 block text-sm font-medium text-slate-700">Username atau Email</label>
                        <input id="login" name="login" type="text" required autocomplete="username" value="{{ old('login') }}" placeholder="Masukkan username atau email"
                            class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Kata Sandi</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Masukkan kata sandi"
                            class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div class="flex items-center justify-between text-sm text-slate-500">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="mr-2 rounded border-slate-300">
                            Ingat saya
                        </label>
                        <span class="text-slate-400">Aman</span>
                    </div>

                    <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        Masuk ke dashboard
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
