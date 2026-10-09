@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">&larr; Kembali ke daftar kategori</a>
        <h1 class="mt-3 mb-8 text-3xl font-bold text-gray-900">Edit Kategori</h1>

        @include('admin.categories.partials.form', [
            'action' => route('admin.categories.update', $category),
            'method' => 'PUT',
            'submitLabel' => 'Perbarui Kategori',
            'category' => $category,
        ])
    </div>
@endsection
