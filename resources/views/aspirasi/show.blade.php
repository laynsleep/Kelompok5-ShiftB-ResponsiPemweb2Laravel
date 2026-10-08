@extends('layouts.app')

@section('title', $aspiration->title.' — Detail Aspirasi')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('aspirasi.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-indigo-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke daftar aspirasi
            </a>
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

        {{-- Aspiration Detail --}}
        <article class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    {{-- Title --}}
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $aspiration->title }}</h1>

                    {{-- Author & Date --}}
                    <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ $aspiration->author->name ?? 'Anonim' }}</span>
                        <span class="text-gray-300">•</span>
                        <span>{{ $aspiration->created_at->format('d M Y, H:i') }}</span>
                        <span class="text-gray-300">•</span>
                        <span>{{ $aspiration->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- Status Badge --}}
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
                <span class="inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                    {{ $aspiration->status->label() }}
                </span>
            </div>

            {{-- Categories --}}
            @if ($aspiration->categories->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($aspiration->categories as $category)
                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            @endif

            {{-- Full Description --}}
            <div class="mt-6 border-t border-gray-100 pt-6">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Deskripsi</h2>
                <div class="mt-3 text-sm leading-relaxed text-gray-700 whitespace-pre-line">{{ $aspiration->description }}</div>
            </div>

            {{-- Stats --}}
            <div class="mt-6 flex items-center gap-6 border-t border-gray-100 pt-4">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                    </svg>
                    <span class="font-semibold text-gray-900">{{ $aspiration->upvotes_count }}</span>
                    <span>upvote</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="font-semibold text-gray-900">{{ $aspiration->comments_count }}</span>
                    <span>komentar</span>
                </div>
            </div>
        </article>

        {{-- Comments Section --}}
        <section class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    Komentar
                    <span class="ml-1 text-sm font-normal text-gray-500">({{ $aspiration->comments_count }})</span>
                </h2>
            </div>

            {{-- Comment Form --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-4">
                <form action="{{ route('aspirasi.comments.store', $aspiration) }}" method="POST">
                    @csrf
                    <label for="comment" class="block text-sm font-medium text-gray-700">
                        Tulis Komentar <span class="text-red-500">*</span>
                    </label>
                    <textarea name="comment" id="comment" rows="3" required
                        placeholder="Tulis komentarmu di sini..."
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition resize-none @error('comment') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-3 flex items-center justify-end gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Kirim Komentar
                        </button>
                    </div>
                </form>
            </div>

            @if ($comments->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="mt-3 text-sm font-medium text-gray-900">Belum ada komentar</h3>
                    <p class="mt-1 text-sm text-gray-500">Jadilah yang pertama memberikan komentar.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($comments as $comment)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                                    {{ strtoupper(substr($comment->author->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $comment->author->name ?? 'Anonim' }}</p>
                                    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-gray-700 whitespace-pre-line">{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Comments Pagination (10 per halaman) --}}
                <div class="mt-6">
                    {{ $comments->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
