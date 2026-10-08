@extends('layouts.app')

@section('title', 'Edit Aspirasi')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Page Header --}}
        <div class="mb-8">
            <a href="{{ route('aspirasi.show', $aspiration) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-indigo-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke detail aspirasi
            </a>
            <h1 class="mt-3 text-3xl font-bold text-gray-900">Edit Aspirasi</h1>
            <p class="mt-1 text-sm text-gray-500">Perbarui judul, deskripsi, atau kategori dari aspirasimu.</p>
        </div>

        @include('aspirasi.partials.form', [
            'action' => route('aspirasi.update', $aspiration),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
            'cancelUrl' => route('aspirasi.show', $aspiration),
            'aspiration' => $aspiration,
        ])
    </div>
@endsection
