<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat akun Admin
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'username' => 'admin_utama',
            'email' => 'admin.user@admin.unsoed.ac.id',
        ]);

        // 2. Buat akun dummy mahasiswa spesifik untuk memudahkan testing login
        User::factory()->mahasiswa()->create([
            'name' => 'Mahasiswa Dummy',
            'username' => 'mahasiswa_dummy',
            'email' => 'mahasiswa.dummy@mhs.unsoed.ac.id',
            // password default dari factory adalah 'password'
        ]);

        // 3. Buat beberapa user mahasiswa random lainnya
        User::factory(10)->mahasiswa()->create();


        // 4. Seed kategori & aspirasi realistis (lengkap dengan komentar dan upvote)
        $this->call([
            CategorySeeder::class,
            AspirationSeeder::class,
        ]);
    }
}
