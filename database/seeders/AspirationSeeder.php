<?php

namespace Database\Seeders;

use App\Enums\AspirationStatus;
use App\Models\Aspiration;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Upvote;
use App\Models\User;
use Illuminate\Database\Seeder;

class AspirationSeeder extends Seeder
{
    /**
     * Seed aspirasi mahasiswa yang realistis beserta komentar & upvote.
     * Membutuhkan CategorySeeder dan minimal beberapa user (role user).
     */
    public function run(): void
    {
        $students = User::where('role', 'user')->get();
        $categories = Category::pluck('id', 'name');

        if ($students->isEmpty() || $categories->isEmpty()) {
            return;
        }

        foreach ($this->aspirations() as $item) {
            $aspiration = Aspiration::create([
                'title' => $item['title'],
                'description' => $item['description'],
                'user_id' => $students->random()->id,
                'status' => $item['status'],
            ]);

            $aspiration->categories()->sync(
                collect($item['categories'])
                    ->map(fn (string $name) => $categories[$name] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );

            // Komentar hanya dari mahasiswa yang berbeda-beda
            foreach (collect($item['comments'])->all() as $text) {
                Comment::create([
                    'aspiration_id' => $aspiration->id,
                    'user_id' => $students->random()->id,
                    'comment' => $text,
                ]);
            }

            // Upvote tanpa duplikat (unique user_id + aspiration_id)
            $voters = $students->random(min($item['upvotes'], $students->count()));
            foreach ($voters as $voter) {
                Upvote::create([
                    'aspiration_id' => $aspiration->id,
                    'user_id' => $voter->id,
                    'voted_at' => now()->subDays(rand(0, 14)),
                ]);
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function aspirations(): array
    {
        $fasilitas = 'Fasilitas & Sarana Prasarana';
        $akademik = 'Akademik & Kurikulum';
        $keuangan = 'Keuangan & Beasiswa';
        $pelayanan = 'Dosen & Pelayanan Admin';
        $kemahasiswaan = 'Kemahasiswaan & Ormawa';
        $it = 'Sistem Informasi & IT Kampus';
        $keamanan = 'Keamanan & Lingkungan Kampus';

        return [
            [
                'title' => 'Wi-Fi di gedung perkuliahan sering putus saat jam kuliah',
                'description' => 'Setiap siang, terutama pukul 10.00 sampai 14.00, koneksi Wi-Fi di lantai 2 dan 3 gedung perkuliahan sangat lambat dan sering terputus. Hal ini mengganggu mahasiswa yang harus mengakses materi di LMS dan mengumpulkan tugas daring. Mohon dilakukan penambahan access point dan pengecekan kapasitas bandwidth.',
                'categories' => [$fasilitas, $it],
                'status' => AspirationStatus::InProgress,
                'upvotes' => 9,
                'comments' => [
                    'Setuju, di ruang kelas lantai 3 sinyalnya hampir tidak ada.',
                    'Waktu kuis daring kemarin banyak teman saya yang terputus di tengah pengerjaan.',
                ],
            ],
            [
                'title' => 'Penambahan stop kontak di ruang kelas dan perpustakaan',
                'description' => 'Mayoritas mahasiswa membawa laptop untuk mengerjakan tugas dan praktikum, namun jumlah stop kontak di ruang kelas dan area baca perpustakaan sangat terbatas. Mahasiswa harus berebut colokan atau menunggu giliran. Mohon ditambahkan terminal listrik di sisi dinding dan meja baca.',
                'categories' => [$fasilitas],
                'status' => AspirationStatus::Reviewed,
                'upvotes' => 7,
                'comments' => [
                    'Benar, sering harus datang pagi hanya untuk dapat tempat yang ada colokannya.',
                ],
            ],
            [
                'title' => 'Toilet lantai 1 sering kehabisan air dan tidak dibersihkan',
                'description' => 'Toilet di lantai 1 gedung utama beberapa kali kehabisan air pada siang hari dan kondisinya kurang bersih. Mohon dijadwalkan pembersihan rutin minimal dua kali sehari serta pengecekan pompa air secara berkala.',
                'categories' => [$fasilitas, $keamanan],
                'status' => AspirationStatus::Pending,
                'upvotes' => 5,
                'comments' => [],
            ],
            [
                'title' => 'Area parkir motor penuh dan tidak tertata',
                'description' => 'Area parkir motor di belakang gedung kuliah selalu penuh sebelum pukul 08.00 sehingga mahasiswa memarkir kendaraan di tepi jalan dan menghambat akses. Mohon dilakukan penataan ulang, penambahan kapasitas, atau pembagian zona parkir.',
                'categories' => [$fasilitas],
                'status' => AspirationStatus::Pending,
                'upvotes' => 4,
                'comments' => [
                    'Kalau bisa sekalian ditambah atap, soalnya kalau hujan motor jadi basah semua.',
                ],
            ],
            [
                'title' => 'Jadwal ujian tengah semester bertabrakan antar mata kuliah',
                'description' => 'Pada UTS semester ini, beberapa mahasiswa mengalami jadwal ujian bertabrakan antara mata kuliah wajib dan mata kuliah pilihan. Mohon jadwal ujian diumumkan lebih awal dan dilakukan pengecekan bentrok sebelum dipublikasikan.',
                'categories' => [$akademik],
                'status' => AspirationStatus::Resolved,
                'upvotes' => 8,
                'comments' => [
                    'Saya juga kena bentrok, akhirnya harus mengajukan ujian susulan.',
                    'Terima kasih sudah diperbaiki, jadwal revisinya sudah keluar.',
                ],
            ],
            [
                'title' => 'Mohon kejelasan alur dan panduan pengajuan topik skripsi',
                'description' => 'Informasi mengenai alur pengajuan topik skripsi, pemilihan dosen pembimbing, dan batas waktu seminar proposal tersebar di beberapa tempat dan sering berubah. Mohon dibuat satu panduan resmi yang mudah diakses oleh mahasiswa tingkat akhir.',
                'categories' => [$akademik, $pelayanan],
                'status' => AspirationStatus::Reviewed,
                'upvotes' => 6,
                'comments' => [
                    'Setuju, saya harus bertanya ke tiga orang berbeda dan jawabannya tidak sama.',
                ],
            ],
            [
                'title' => 'Evaluasi beban tugas pada mata kuliah praktikum',
                'description' => 'Beberapa mata kuliah dengan praktikum memiliki laporan mingguan yang cukup berat sementara tugas mata kuliah teori tetap banyak. Mohon dilakukan evaluasi agar beban tugas lebih seimbang dan mahasiswa memiliki waktu untuk memahami materi.',
                'categories' => [$akademik],
                'status' => AspirationStatus::Pending,
                'upvotes' => 6,
                'comments' => [],
            ],
            [
                'title' => 'Transparansi mekanisme banding UKT',
                'description' => 'Mahasiswa yang ingin mengajukan banding UKT sering kebingungan mengenai syarat dokumen, jadwal, dan kriteria penilaian. Mohon dipublikasikan alur banding UKT lengkap beserta estimasi waktu proses dan kontak yang dapat dihubungi.',
                'categories' => [$keuangan, $pelayanan],
                'status' => AspirationStatus::InProgress,
                'upvotes' => 10,
                'comments' => [
                    'Sangat dibutuhkan, banyak teman saya yang tidak tahu kalau banding itu ada jadwalnya.',
                    'Semoga diberi informasi juga tentang hasil banding paling lambat kapan.',
                ],
            ],
            [
                'title' => 'Informasi beasiswa yang terlambat diumumkan',
                'description' => 'Pengumuman pembukaan beberapa beasiswa sering diterima mahasiswa mendekati batas akhir pendaftaran sehingga persiapan berkas tidak maksimal. Mohon informasi beasiswa diumumkan melalui kanal resmi minimal dua minggu sebelum penutupan.',
                'categories' => [$keuangan],
                'status' => AspirationStatus::Reviewed,
                'upvotes' => 7,
                'comments' => [
                    'Kemarin saya baru tahu dua hari sebelum ditutup, akhirnya tidak sempat daftar.',
                ],
            ],
            [
                'title' => 'Jam layanan administrasi akademik bertabrakan dengan jam kuliah',
                'description' => 'Loket layanan administrasi akademik hanya buka pada jam yang sama dengan jam perkuliahan, sehingga mahasiswa harus meninggalkan kelas untuk mengurus surat. Mohon dipertimbangkan perpanjangan jam layanan atau layanan pengajuan surat secara daring.',
                'categories' => [$pelayanan],
                'status' => AspirationStatus::Pending,
                'upvotes' => 5,
                'comments' => [
                    'Kalau ada pengajuan surat online pasti sangat membantu.',
                ],
            ],
            [
                'title' => 'Percepatan pencairan dana kegiatan organisasi mahasiswa',
                'description' => 'Pencairan dana kegiatan ormawa sering terlambat sehingga panitia harus menalangi biaya terlebih dahulu. Mohon dibuat alur dan batas waktu pencairan yang jelas serta kontak penanggung jawab di bagian kemahasiswaan.',
                'categories' => [$kemahasiswaan, $keuangan],
                'status' => AspirationStatus::InProgress,
                'upvotes' => 6,
                'comments' => [
                    'Panitia kegiatan kami sampai harus patungan karena dana belum cair sampai hari H.',
                ],
            ],
            [
                'title' => 'Penyediaan ruang sekretariat yang layak untuk UKM',
                'description' => 'Beberapa unit kegiatan mahasiswa belum memiliki ruang sekretariat tetap sehingga rapat dan penyimpanan inventaris dilakukan seadanya. Mohon dilakukan pendataan kebutuhan ruang UKM dan penataan ulang penggunaan ruang yang ada.',
                'categories' => [$kemahasiswaan, $fasilitas],
                'status' => AspirationStatus::Pending,
                'upvotes' => 4,
                'comments' => [],
            ],
            [
                'title' => 'SIAKAD sering error pada masa pengisian KRS',
                'description' => 'Saat masa pengisian KRS, SIAKAD sering lambat atau tidak dapat diakses karena banyak mahasiswa login bersamaan. Mohon dilakukan peningkatan kapasitas server atau pembagian jadwal pengisian KRS per angkatan.',
                'categories' => [$it, $akademik],
                'status' => AspirationStatus::Reviewed,
                'upvotes' => 11,
                'comments' => [
                    'Semester lalu saya baru bisa masuk jam 2 pagi karena siang harinya error terus.',
                    'Pembagian jadwal per angkatan menurut saya solusi yang paling masuk akal.',
                ],
            ],
            [
                'title' => 'Notifikasi nilai dan pengumuman di LMS kurang jelas',
                'description' => 'Mahasiswa sering terlewat informasi perubahan jadwal dan pengumpulan tugas karena notifikasi di LMS tidak muncul atau tidak terkirim ke email. Mohon diperiksa pengaturan notifikasi dan disediakan panduan penggunaannya bagi mahasiswa dan dosen.',
                'categories' => [$it],
                'status' => AspirationStatus::Pending,
                'upvotes' => 3,
                'comments' => [],
            ],
            [
                'title' => 'Penambahan lampu penerangan di jalur menuju parkiran belakang',
                'description' => 'Jalur dari gedung kuliah menuju parkiran belakang sangat gelap pada malam hari, terutama bagi mahasiswa yang pulang setelah praktikum atau kegiatan organisasi. Mohon ditambahkan lampu penerangan dan dipertimbangkan patroli petugas keamanan pada jam tersebut.',
                'categories' => [$keamanan, $fasilitas],
                'status' => AspirationStatus::Resolved,
                'upvotes' => 9,
                'comments' => [
                    'Alhamdulillah lampunya sudah dipasang, jalurnya jauh lebih aman sekarang.',
                    'Mohon patroli malamnya tetap dilanjutkan ya.',
                ],
            ],
            [
                'title' => 'Penyediaan ruang aman untuk pengaduan mahasiswa',
                'description' => 'Mahasiswa yang mengalami perundungan atau pelecehan sering bingung harus melapor ke mana dan khawatir identitasnya diketahui. Mohon disediakan kanal pengaduan yang menjaga kerahasiaan pelapor beserta prosedur penanganan yang jelas.',
                'categories' => [$keamanan, $pelayanan],
                'status' => AspirationStatus::InProgress,
                'upvotes' => 12,
                'comments' => [
                    'Ini sangat penting. Semoga prosesnya benar-benar melindungi pelapor.',
                ],
            ],
            [
                'title' => 'Tempat sampah terpilah di area kantin dan taman kampus',
                'description' => 'Sampah di area kantin dan taman sering menumpuk karena jumlah tempat sampah terbatas dan tidak ada pemisahan organik dan anorganik. Mohon ditambahkan tempat sampah terpilah serta jadwal pengangkutan yang lebih rutin.',
                'categories' => [$keamanan, $fasilitas],
                'status' => AspirationStatus::Rejected,
                'upvotes' => 3,
                'comments' => [
                    'Sudah ada rencana dari pihak fakultas katanya, tapi belum ada kejelasan jadwalnya.',
                ],
            ],
        ];
    }
}
