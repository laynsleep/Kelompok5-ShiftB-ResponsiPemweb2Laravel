@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Pengguna</h1>
            <p class="mt-1 text-sm text-gray-500">Centang <span class="font-medium">Admin</span> lalu simpan untuk menjadikan pengguna sebagai admin. Hilangkan centang untuk mengembalikannya menjadi pengguna biasa.</p>
        </div>

        @include('admin.partials.alerts')

        <form method="GET" class="mb-6 flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, username, atau email..."
                class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <select name="role" onchange="this.form.submit()"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Semua Role</option>
                <option value="user" @selected(request('role') === 'user')>User</option>
                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
            </select>
            <button type="submit" class="rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 transition">Cari</button>
        </form>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3 text-center">Aspirasi</th>
                        <th class="px-4 py-3 text-right">Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">&#64;{{ $user->username }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $user->aspirations_count }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center justify-end gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_admin" value="0">
                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                        <input type="checkbox" name="is_admin" value="1"
                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            @checked($user->isAdmin())
                                            @disabled($user->is(auth()->user()))>
                                        Admin
                                    </label>
                                    @if ($user->is(auth()->user()))
                                        <span class="text-xs text-gray-400">(akun Anda)</span>
                                    @else
                                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500 transition">Simpan</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Pengguna tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $users->links() }}</div>
    </div>
@endsection
