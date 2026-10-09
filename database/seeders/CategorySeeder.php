<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fasilitas & Sarana Prasarana',
                'description' => 'Aspirasi terkait perbaikan, kebersihan, dan kelengkapan fasilitas kampus (Wi-Fi, ruang kelas, toilet, parkir).',
            ],
            [
                'name' => 'Akademik & Kurikulum',
                'description' => 'Masukan seputar jadwal kuliah, sistem KRS, magang, bimbingan skripsi, dan kurikulum.',
            ],
            [
                'name' => 'Keuangan & Beasiswa',
                'description' => 'Aspirasi mengenai pembayaran UKT, mekanisme banding UKT, beasiswa, dan transparansi biaya.',
            ],
            [
                'name' => 'Dosen & Pelayanan Admin',
                'description' => 'Evaluasi kinerja pengajaran dosen serta mutu pelayanan staf tata usaha (TU) dan administrasi.',
            ],
            [
                'name' => 'Kemahasiswaan & Ormawa',
                'description' => 'Aspirasi seputar pencairan dana kegiatan, perizinan event, fasilitas UKM, dan kegiatan ormawa.',
            ],
            [
                'name' => 'Sistem Informasi & IT Kampus',
                'description' => 'Laporan gangguan atau masukan terkait SIAKAD, LMS, situs kampus, dan jaringan internet.',
            ],
            [
                'name' => 'Keamanan & Lingkungan Kampus',
                'description' => 'Usulan terkait keselamatan lingkungan, penerangan area kampus, serta ruang aman aduan mahasiswa.',
            ],
        ];

        foreach ($categories as $category) {
            // Kunci pencarian hanya 'name' agar seeder idempotent (aman dijalankan ulang)
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']],
            );
        }
    }
}
