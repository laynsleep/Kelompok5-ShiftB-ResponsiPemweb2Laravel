@if (session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-700">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="font-medium">Terjadi kesalahan:</p>
        <ul class="mt-1 list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
