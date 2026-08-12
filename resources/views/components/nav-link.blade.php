@props(['route', 'label', 'params' => []])

@php
    $active = request()->routeIs($route) && (empty($params) || request()->route('type') === ($params['type'] ?? null));
@endphp

<li>
    <a href="{{ route($route, $params) }}"
       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
              {{ $active
                  ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
        {{ $slot }}
        <span class="flex-1">{{ $label }}</span>
        @if($active)
            <span class="h-2 w-2 rounded-full bg-blue-600"></span>
        @endif
    </a>
</li>
