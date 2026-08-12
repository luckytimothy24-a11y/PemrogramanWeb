@extends('example.layouts.default.baseof')
@section('main')
@vite(['resources/css/app.css','resources/js/app.js'])
@include('example.layouts.partials.navbar-dashboard')
<div class="flex min-h-screen bg-gray-50 pt-16 dark:bg-gray-900">
  @include('example.layouts.partials.sidebar')

  <div id="main-content" class="relative ml-0 flex-1 overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
    <main class="min-h-[calc(100vh-4rem)]">
      @yield('content')
    </main>
    @include('example.layouts.partials.footer-dashboard')
  </div>
</div>
@endsection
