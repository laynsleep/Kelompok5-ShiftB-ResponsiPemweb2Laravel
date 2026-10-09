<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Aspirasi Mahasiswa UNSOED')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased">
    @auth
        {{-- Navbar --}}
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('aspirasi.index') }}" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <span class="text-xl font-bold text-gray-900">Aspirasi Mahasiswa</span>
                        </a>
                    </div>

                    <div class="flex items-center gap-4">
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.categories.index') }}"
                                class="text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'text-indigo-600' : 'text-gray-600 hover:text-indigo-600' }} transition">Kategori</a>
                            <a href="{{ route('admin.users.index') }}"
                                class="text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'text-indigo-600' : 'text-gray-600 hover:text-indigo-600' }} transition">Pengguna</a>
                            <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-700">Admin</span>
                        @endif
                        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <main>
        @yield('content')
    </main>
</body>
</html>
