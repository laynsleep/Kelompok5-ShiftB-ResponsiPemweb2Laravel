@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Kategori</h1>
                <p class="mt-1 text-sm text-gray-500">Kategori digunakan untuk mengelompokkan aspirasi. Hanya admin yang dapat mengubahnya.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition shrink-0">
                + Tambah Kategori
            </a>
        </div>

        @include('admin.partials.alerts')

        <form method="GET" class="mb-6 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori..."
                class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <button type="submit" class="rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 transition">Cari</button>
        </form>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3 text-center">Aspirasi</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $category->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($category->description, 90) }}</td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $category->aspirations_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="rounded-md bg-white px-3 py-1.5 text-xs font-medium text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 transition">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                        onsubmit="return confirm('Hapus kategori ini? Aspirasi tidak ikut terhapus, hanya label kategorinya.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 ring-1 ring-red-200 hover:bg-red-100 transition">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $categories->links() }}</div>
    </div>
@endsection
