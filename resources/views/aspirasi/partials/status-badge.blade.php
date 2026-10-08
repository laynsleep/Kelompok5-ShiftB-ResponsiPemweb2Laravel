{{-- Shared status badge. Expects: $aspiration --}}
{{-- Admin dapat mengklik badge untuk membuka drop-down status; selain admin hanya melihat badge biasa. --}}
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

@can('updateStatus', $aspiration)
    {{-- Badge berupa drop-down: memilih salah satu opsi langsung mengirim perubahan status --}}
    <form method="POST" action="{{ route('aspirasi.status.update', $aspiration) }}" class="relative z-10 shrink-0">
        @csrf
        @method('PATCH')
        <label for="status-select-{{ $aspiration->id }}" class="sr-only">Ubah status aspirasi</label>
        <div class="relative inline-flex items-center rounded-full ring-1 ring-inset transition hover:ring-2 {{ $colorClass }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-2.5 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
            <select name="status" id="status-select-{{ $aspiration->id }}" onchange="this.form.submit()" title="Ubah status aspirasi"
                class="relative block cursor-pointer appearance-none bg-transparent py-1 pl-2.5 pr-7 text-xs font-medium text-current focus:outline-none">
                @foreach (\App\Enums\AspirationStatus::cases() as $statusOption)
                    <option value="{{ $statusOption->value }}" @selected($aspiration->status === $statusOption)>{{ $statusOption->label() }}</option>
                @endforeach
            </select>
        </div>
    </form>
@else
    <span class="inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
        {{ $aspiration->status->label() }}
    </span>
@endcan
