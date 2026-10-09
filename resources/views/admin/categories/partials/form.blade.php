@php $category = $category ?? null; @endphp
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
    <form action="{{ $action }}" method="POST" class="space-y-6">
        @csrf
        @if (($method ?? 'POST') !== 'POST')
            @method($method)
        @endif

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $category?->name) }}" maxlength="255" required
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('name') border-red-300 @enderror">
            @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="4" maxlength="1000"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('description') border-red-300 @enderror">{{ old('description', $category?->description) }}</textarea>
            @error('description') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
            <a href="{{ route('admin.categories.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">{{ $submitLabel }}</button>
        </div>
    </form>
</div>
