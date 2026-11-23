<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TataTertib;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Guru BK
        $userGuruBk = User::where('email', 'gurubk@sekolah.local')->first();
        $guruBk = Guru::firstOrCreate(
            ['nip' => '198501152010012001'],
            [
                'user_id' => $userGuruBk?->id,
                'nama' => 'Ibu Rahma, S.Pd',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567891',
            ]
        );

        // 2. Data Kelas
        $kelas1 = Kelas::firstOrCreate(
            ['nama' => 'X-PPLG-1', 'tahun_ajaran' => '2026/2027'],
            [
                'tingkat' => '10',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim',
                'wali_kelas_id' => $guruBk->id,
            ]
        );

        $kelas2 = Kelas::firstOrCreate(
            ['nama' => 'XI-PPLG-1', 'tahun_ajaran' => '2026/2027'],
            [
                'tingkat' => '11',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim',
                'wali_kelas_id' => $guruBk->id,
            ]
        );

        $kelas3 = Kelas::firstOrCreate(
            ['nama' => 'XII-PPLG-1', 'tahun_ajaran' => '2026/2027'],
            [
                'tingkat' => '12',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim',
                'wali_kelas_id' => $guruBk->id,
            ]
        );

        // 3. Data Tata Tertib & Jenis Pelanggaran
        $tataTertib = TataTertib::firstOrCreate(
            ['kode' => 'TT-01'],
            [
                'nama' => 'Tata Tertib Kedisiplinan Siswa',
                'deskripsi' => 'Ketentuan kedisiplinan dan tata tertib sekolah.',
            ]
        );

        $jenis = [
            ['kode' => 'P-01', 'nama' => 'Terlambat masuk sekolah (>15 menit)', 'kategori' => 'ringan', 'poin' => 5],
            ['kode' => 'P-02', 'nama' => 'Tidak mengenakan atribut seragam lengkap', 'kategori' => 'ringan', 'poin' => 5],
            ['kode' => 'P-03', 'nama' => 'Rambut/kuku tidak rapi sesuai ketentuan', 'kategori' => 'ringan', 'poin' => 5],
            ['kode' => 'P-04', 'nama' => 'Meninggalkan jam pelajaran tanpa izin (Membolos)', 'kategori' => 'sedang', 'poin' => 15],
            ['kode' => 'P-05', 'nama' => 'Menggunakan HP saat KBM tanpa instruksi guru', 'kategori' => 'sedang', 'poin' => 10],
            ['kode' => 'P-06', 'nama' => 'Merusak fasilitas sekolah atau mencoret meja/dinding', 'kategori' => 'sedang', 'poin' => 20],
            ['kode' => 'P-07', 'nama' => 'Membawa rokok/vape di lingkungan sekolah', 'kategori' => 'berat', 'poin' => 35],
            ['kode' => 'P-08', 'nama' => 'Terlibat perkelahian atau perundungan (Bullying)', 'kategori' => 'berat', 'poin' => 55],
        ];

        foreach ($jenis as $item) {
            JenisPelanggaran::firstOrCreate(
                ['kode' => $item['kode']],
                [
                    'tata_tertib_id' => $tataTertib->id,
                    'nama' => $item['nama'],
                    'kategori' => $item['kategori'],
                    'poin' => $item['poin'],
                    'is_active' => true,
                ]
            );
        }

        // 4. Data Contoh Siswa
        $userSiswa = User::where('email', 'siswa@sekolah.local')->first();

        Siswa::firstOrCreate(
            ['nis' => '20261001'],
            [
                'user_id' => $userSiswa?->id,
                'kelas_id' => $kelas1->id,
                'nama' => 'Farrel Alfarezi',
                'jenis_kelamin' => 'L',
                'nama_ortu' => 'Bpk. Hendra Alfarezi',
                'no_hp_ortu' => '081234567890',
                'total_poin' => 0,
                'status_disiplin' => 'aman',
            ]
        );

        Siswa::firstOrCreate(
            ['nis' => '20261002'],
            [
                'kelas_id' => $kelas1->id,
                'nama' => 'Ahmad Fauzan',
                'jenis_kelamin' => 'L',
                'nama_ortu' => 'Ibu Siti Aminah',
                'no_hp_ortu' => '081298765432',
                'total_poin' => 10,
                'status_disiplin' => 'aman',
            ]
        );

        Siswa::firstOrCreate(
            ['nis' => '20261003'],
            [
                'kelas_id' => $kelas2->id,
                'nama' => 'Putri Andini',
                'jenis_kelamin' => 'P',
                'nama_ortu' => 'Bpk. Bambang Suryo',
                'no_hp_ortu' => '085712345678',
                'total_poin' => 30,
                'status_disiplin' => 'perhatian',
            ]
        );

        Siswa::firstOrCreate(
            ['nis' => '20261004'],
            [
                'kelas_id' => $kelas3->id,
                'nama' => 'Dimas Prasetyo',
                'jenis_kelamin' => 'L',
                'nama_ortu' => 'Ibu Ratna Dewi',
                'no_hp_ortu' => '087812345678',
                'total_poin' => 55,
                'status_disiplin' => 'kritis',
            ]
        );
    }
}
