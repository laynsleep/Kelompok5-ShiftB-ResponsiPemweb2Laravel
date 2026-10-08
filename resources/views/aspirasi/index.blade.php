@extends('layouts.app')

@section('title', 'Aspirasi Mahasiswa')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Aspirasi Mahasiswa</h1>
            <p class="mt-1 text-sm text-gray-500">Selamat datang, <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span>. Berikut adalah daftar aspirasi yang telah disampaikan.</p>
        </div>

        {{-- Aspiration List --}}
        @if ($aspirations->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada aspirasi</h3>
                <p class="mt-1 text-sm text-gray-500">Saat ini belum ada aspirasi yang disampaikan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($aspirations as $aspiration)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                {{-- Title --}}
                                <h2 class="text-lg font-semibold text-gray-900 truncate">{{ $aspiration->title }}</h2>

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

                            {{-- Status Badge --}}
                            <div>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                                        'reviewed' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                        'in_progress' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                        'resolved' => 'bg-green-50 text-green-700 ring-green-600/20',
                                        'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
                                    ];
                                    $colorClass = $statusColors[$aspiration->status->value] ?? 'bg-gray-50 text-gray-700 ring-gray-600/20';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                                    {{ $aspiration->status->label() }}
                                </span>
                            </div>
                        </div>

                        {{-- Footer Stats --}}
                        <div class="mt-4 flex items-center gap-4 border-t border-gray-100 pt-3">
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
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $aspirations->links() }}
            </div>
        @endif
    </div>
@endsection
