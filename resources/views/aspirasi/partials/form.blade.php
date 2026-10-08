{{-- Shared aspiration form. Expects: $action, $submitLabel, $cancelUrl; optional: $method, $aspiration --}}
@php
    $method = $method ?? 'POST';
    $aspiration = $aspiration ?? null;
    $selectedCategories = old('categories', $aspiration?->categories->pluck('id')->all() ?? []);
@endphp

{{-- Validation Summary --}}
@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <div class="text-sm text-red-700">
                <p class="font-medium">Terjadi kesalahan pada formulir:</p>
                <ul class="mt-1 list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

{{-- Aspiration Form --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
    <form action="{{ $action }}" method="POST" class="space-y-6">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">
                Judul Aspirasi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" id="title" value="{{ old('title', $aspiration?->title) }}" maxlength="255" required
                placeholder="Contoh: Perpanjang jam buka perpustakaan"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition resize-none @error('title') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
            @error('title')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">
                Deskripsi <span class="text-red-500">*</span>
            </label>
            <textarea name="description" id="description" rows="6" required
                placeholder="Jelaskan aspirasimu secara rinci..."
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition resize-none @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('description', $aspiration?->description) }}</textarea>
            @error('description')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Categories --}}
        <div>
            <span class="block text-sm font-medium text-gray-700">Kategori</span>
            <p class="mt-0.5 text-xs text-gray-500">Pilih satu atau lebih kategori yang sesuai (opsional).</p>

            @if ($categories->isEmpty())
                <p class="mt-3 text-sm text-gray-400">Belum ada kategori tersedia.</p>
            @else
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($categories as $category)
                        <label for="category-{{ $category->id }}"
                            class="cursor-pointer inline-flex items-center gap-2 rounded-full border border-gray-300 px-3.5 py-1.5 text-sm font-medium text-gray-700 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 has-[:checked]:text-indigo-700 hover:border-indigo-400 hover:text-indigo-600">
                            <input type="checkbox" name="categories[]" id="category-{{ $category->id }}" value="{{ $category->id }}"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
                @error('categories')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('categories.*')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
            <a href="{{ $cancelUrl }}" class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</div>
