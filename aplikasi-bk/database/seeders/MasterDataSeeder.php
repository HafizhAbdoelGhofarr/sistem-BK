<?php

namespace Database\Seeders;

use App\Models\JenisPelanggaran;
use App\Models\TataTertib;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $tataTertib = TataTertib::firstOrCreate(
            ['kode' => 'TT-01'],
            [
                'nama' => 'Tata Tertib Umum',
                'deskripsi' => 'Ketentuan kedisiplinan siswa.',
            ]
        );

        $jenis = [
            ['kode' => 'P-01', 'nama' => 'Terlambat masuk sekolah', 'kategori' => 'ringan', 'poin' => 5],
            ['kode' => 'P-02', 'nama' => 'Tidak mengenakan seragam lengkap', 'kategori' => 'ringan', 'poin' => 10],
            ['kode' => 'P-03', 'nama' => 'Meninggalkan sekolah tanpa izin', 'kategori' => 'sedang', 'poin' => 20],
            ['kode' => 'P-04', 'nama' => 'Membawa barang terlarang', 'kategori' => 'berat', 'poin' => 30],
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
    }
}
