@extends('layouts.app')

@section('title', 'Aspirasi Mahasiswa')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Page Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Aspirasi Mahasiswa</h1>
                <p class="mt-1 text-sm text-gray-500">Selamat datang, <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span>. Berikut adalah daftar aspirasi yang telah disampaikan.</p>
            </div>
            <a href="{{ route('aspirasi.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Aspirasi
            </a>
        </div>

        {{-- Small Navigation (Semua / Aspirasi Saya) --}}
        @php
            $tabQuery = request()->except(['mine', 'page']);
            $isMineTab = request()->boolean('mine');
        @endphp
        <div class="mb-6 border-b border-gray-200">
            <nav class="flex items-center gap-6" aria-label="Navigasi daftar aspirasi">
                <a href="{{ route('aspirasi.index', $tabQuery) }}"
                    class="inline-flex items-center gap-1.5 border-b-2 px-1 pb-3 text-sm font-medium transition {{ ! $isMineTab ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    Semua Aspirasi
                </a>
                <a href="{{ route('aspirasi.index', array_merge($tabQuery, ['mine' => 1])) }}"
                    class="inline-flex items-center gap-1.5 border-b-2 px-1 pb-3 text-sm font-medium transition {{ $isMineTab ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Aspirasi Saya
                </a>
            </nav>
        </div>

        {{-- Success Alert --}}
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Filter & Search Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form action="{{ route('aspirasi.index', request()->boolean('mine') ? ['mine' => 1] : []) }}" method="GET" id="filter-form" class="flex flex-col md:flex-row gap-3 items-center">
                
                {{-- Search Input (Auto Submit saat mengetik) --}}
                <div class="flex-1 w-full">
                    <label for="search" class="sr-only">Cari Aspirasi</label>
                    <div class="flex items-center bg-white border border-gray-300 rounded-lg shadow-sm overflow-hidden focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition">
                        <div class="pl-3.5 pr-2 flex items-center justify-center text-gray-400 pointer-events-none">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" oninput="autoSubmitSearch()"
                            class="flex-1 block w-full py-2.5 pr-3 border-0 bg-transparent text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm" 
                            placeholder="Ketik kata kunci untuk mencari otomatis...">
                    </div>
                </div>

                {{-- Filter Dropdowns (Auto Submit) --}}
                <div class="flex gap-3 w-full md:w-auto shrink-0">
                    {{-- Status Filter --}}
                    <div class="w-full sm:w-44">
                        <label for="status" class="sr-only">Status</label>
                        <select name="status" id="status" onchange="this.form.submit()" class="block w-full py-2.5 pl-3 pr-10 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    {{-- Category Filter --}}
                    <div class="w-full sm:w-44">
                        <label for="category" class="sr-only">Kategori</label>
                        <select name="category" id="category" onchange="this.form.submit()" class="block w-full py-2.5 pl-3 pr-10 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Reset Icon Button (Tampil jika filter sedang aktif) --}}
                    @if(request()->anyFilled(['search', 'status', 'category']))
                        <a href="{{ route('aspirasi.index', request()->boolean('mine') ? ['mine' => 1] : []) }}" title="Reset Semua Filter" class="inline-flex items-center justify-center p-2.5 border border-gray-300 rounded-lg text-gray-400 bg-white hover:bg-red-50 hover:text-red-500 hover:border-red-200 focus:outline-none transition shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Aspiration List --}}
        @if ($aspirations->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ request()->boolean('mine') ? 'Kamu belum punya aspirasi' : 'Belum ada aspirasi' }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ request()->boolean('mine') ? 'Sampaikan aspirasimu melalui tombol "Tambah Aspirasi" di atas.' : 'Saat ini belum ada aspirasi yang disampaikan.' }}</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($aspirations as $aspiration)
                    <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-indigo-200 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                {{-- Title (stretched link: seluruh card bisa diklik) --}}
                                <h2 class="text-lg font-semibold text-gray-900 truncate">
                                    <a href="{{ route('aspirasi.show', $aspiration) }}" class="after:absolute after:inset-0 after:content-[''] hover:text-indigo-600 transition">{{ $aspiration->title }}</a>
                                </h2>

                                {{-- Author & Date --}}
                                <div class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ $aspiration->author->name ?? 'Anonim' }}</span>
                                    <span class="text-gray-300">•</span>
                                    <span>{{ $aspiration->created_at->diffForHumans() }}</span>
                                </div>

                                {{-- Description --}}
                                <p class="mt-3 text-sm text-gray-600 line-clamp-2">{{ $aspiration->description }}</p>

                                {{-- Categories --}}
                                @if ($aspiration->categories->isNotEmpty())
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach ($aspiration->categories as $category)
                                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Status Badge & Actions --}}
                            <div class="flex flex-col items-end gap-2 shrink-0">
                                {{-- Status badge (admin dapat mengkliknya untuk mengubah status) --}}
                                @include('aspirasi.partials.status-badge', ['aspiration' => $aspiration])

                                {{-- Aksi: Edit & Hapus --}}
                                <div class="flex items-center gap-1">
                                    @can('update', $aspiration)
                                        <a href="{{ route('aspirasi.edit', $aspiration) }}" title="Edit aspirasi" aria-label="Edit aspirasi"
                                            class="relative z-10 inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 ring-1 ring-transparent hover:bg-indigo-50 hover:text-indigo-600 hover:ring-indigo-200 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 3a2.828 2.828 0 114 4L7.5 20.5 2 22l1.5-5.5L17 3z" />
                                            </svg>
                                        </a>
                                    @endcan

                                    {{-- Tombol Hapus (hanya untuk pemilik / admin) --}}
                                    @can('delete', $aspiration)
                                        <form method="POST" action="{{ route('aspirasi.destroy', $aspiration) }}"
                                            class="relative z-10"
                                            onsubmit="return confirm('Yakin ingin menghapus aspirasi ini? Tindakan tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus aspirasi" aria-label="Hapus aspirasi"
                                                class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 ring-1 ring-transparent hover:bg-red-50 hover:text-red-600 hover:ring-red-200 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        {{-- Footer Stats & Upvote --}}
                        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                    </svg>
                                    <span>{{ $aspiration->upvotes_count }} vote</span>
                                </div>
                                <div class="flex items-center gap-1 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <span>{{ $aspiration->comments_count }} komentar</span>
                                </div>
                            </div>
                            @auth
                                @if($aspiration->is_voted)
                                    <form method="POST" action="{{ route('aspirasi.upvote.destroy', $aspiration) }}" class="relative z-10">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8-8 8z" />
                                            </svg>
                                            Upvoted ({{ $aspiration->upvotes_count }})
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('aspirasi.upvote', $aspiration) }}" class="relative z-10">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                            </svg>
                                            Upvote ({{ $aspiration->upvotes_count }})
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $aspirations->links() }}
            </div>
        @endif
    </div>

    {{-- Script untuk Live Search (Debounce) & Refocus --}}
    <script>
        let searchTimer;
        const searchInput = document.getElementById('search');

        // Pastikan kursor selalu di akhir teks jika halaman di-reload setelah pencarian
        if (searchInput && searchInput.value) {
            const length = searchInput.value.length;
            searchInput.setSelectionRange(length, length);
            searchInput.focus();
        }

        function autoSubmitSearch() {
            clearTimeout(searchTimer);
            // Tunggu 500ms setelah user berhenti mengetik sebelum disubmit
            searchTimer = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500); 
        }
    </script>
@endsection
