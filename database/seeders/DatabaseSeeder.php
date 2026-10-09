<?php

namespace Database\Seeders;

use App\Models\Aspiration;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Upvote;
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
        $testMahasiswa = User::factory()->mahasiswa()->create([
            'name' => 'Mahasiswa Dummy',
            'username' => 'mahasiswa_dummy',
            'email' => 'mahasiswa.dummy@mhs.unsoed.ac.id',
            // password default dari factory adalah 'password'
        ]);

        // 3. Buat beberapa user mahasiswa random lainnya
        $mahasiswas = User::factory(10)->mahasiswa()->create();

        // Gabungkan semua mahasiswa untuk digunakan di interaksi
        $allMahasiswas = $mahasiswas->push($testMahasiswa);

        // 4. Buat beberapa Kategori
        
        $this->call([
            CategorySeeder::class,
        ]);
        $categories = Category::all();

        // 5. Buat Aspirasi beserta relasinya
        foreach ($allMahasiswas as $user) {
            // Setiap mahasiswa membuat 1 sampai 3 aspirasi
            Aspiration::factory(rand(1, 3))->create([
                'user_id' => $user->id,
            ])->each(function ($aspiration) use ($categories, $allMahasiswas) {
                // Attach 1-2 kategori secara random ke aspirasi ini
                $aspiration->categories()->attach(
                    $categories->random(rand(1, 2))->pluck('id')->toArray()
                );

                // Tambahkan 0 sampai 3 komentar dari user random
                Comment::factory(rand(0, 3))->create([
                    'aspiration_id' => $aspiration->id,
                    'user_id' => $allMahasiswas->random()->id,
                ]);

                // Tambahkan 0 sampai 5 upvote dari user random (tanpa duplikat)
                $upvoters = $allMahasiswas->random(rand(0, 5));
                foreach ($upvoters as $upvoter) {
                    Upvote::factory()->create([
                        'aspiration_id' => $aspiration->id,
                        'user_id' => $upvoter->id,
                    ]);
                }
            });
        }
    }
}
