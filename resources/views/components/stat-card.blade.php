@props(['label', 'value', 'icon' => 'product'])

@php
    $icons = [
        'product' => '<path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v2H4V6zm0 4h7v4H4v-4zm9 0h3v4h-3v-4z"></path>',
        'stock' => '<path d="M3 3a1 1 0 011-1h12a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V3zm2 2v2h10V5H5zm-2 5a1 1 0 011-1h12a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm2 2v2h10v-2H5z"></path>',
        'in' => '<path d="M10.75 2a.75.75 0 01.75.75v5.19l2.72-2.72a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 111.06-1.06l2.72 2.72V2.75a.75.75 0 01.75-.75zM3 12a1 1 0 011-1h12a1 1 0 011 1v5a1 1 0 01-1 1H4a1 1 0 01-1-1v-5z"></path>',
        'out' => '<path d="M10.75 2a.75.75 0 01.75.75v5.19l2.72-2.72a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 111.06-1.06l2.72 2.72V2.75a.75.75 0 01.75-.75zM3 12a1 1 0 011-1h12a1 1 0 011 1v5a1 1 0 01-1 1H4a1 1 0 01-1-1v-5z"></path>',
    ];
    $colorClasses = [
        'blue' => 'bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300',
        'cyan' => 'bg-cyan-50 text-cyan-600 dark:bg-cyan-900/40 dark:text-cyan-300',
        'green' => 'bg-green-50 text-green-600 dark:bg-green-900/40 dark:text-green-300',
        'red' => 'bg-red-50 text-red-600 dark:bg-red-900/40 dark:text-red-300',
        'amber' => 'bg-amber-50 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300',
        'violet' => 'bg-violet-50 text-violet-600 dark:bg-violet-900/40 dark:text-violet-300',
    ];
    $color = $colorClasses[$attributes->get('color', 'blue')] ?? $colorClasses['blue'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800']) }}>
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
        </div>
        <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $color }}">
            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">{!! $icons[$icon] ?? $icons['product'] !!}</svg>
        </span>
    </div>
    <div class="mt-3">{{ $slot }}</div>
</div>
