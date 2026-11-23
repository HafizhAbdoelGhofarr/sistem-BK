<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Refleksi Kebiasaan Digital
        Schema::create('digiself_refleksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('durasi_layar'); // e.g. "1-3 jam", "4-6 jam", "> 7 jam"
            $table->string('tujuan_penggunaan'); // e.g. "Belajar & Kuliah", "Hiburan & Medsos", "Gaming", "Campuran"
            $table->string('aplikasi_distraksi'); // e.g. "Instagram, TikTok, YouTube"
            $table->string('situasi_pemicu'); // e.g. "Saat merasa bosan atau cemas"
            $table->text('dampak_negatif')->nullable(); // e.g. "Begadang, tugas tertunda"
            $table->text('catatan_refleksi')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Rencana Aksi
        Schema::create('digiself_rencana_aksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tujuan_perubahan');
            $table->text('langkah_tindakan');
            $table->string('waktu_pelaksanaan');
            $table->string('indikator_keberhasilan');
            $table->string('status')->default('dalam_proses'); // 'dalam_proses', 'tercapai', 'dibatalkan'
            $table->timestamps();
        });

        // 3. Tabel Jurnal & Evaluasi Diri
        Schema::create('digiself_jurnal_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rencana_aksi_id')->nullable()->constrained('digiself_rencana_aksi')->nullOnDelete();
            $table->date('tanggal');
            $table->unsignedTinyInteger('skor_regulasi')->default(3); // 1-5
            $table->text('pengalaman_harian');
            $table->text('hambatan')->nullable();
            $table->text('langkah_perbaikan')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Permohonan Bantuan BK
        Schema::create('digiself_bantuan_bk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('topik');
            $table->string('urgensi')->default('sedang'); // 'rendah', 'sedang', 'tinggi'
            $table->text('cerita_kendala');
            $table->string('status')->default('menunggu'); // 'menunggu', 'dijadwalkan', 'selesai'
            $table->text('tanggapan_konselor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digiself_bantuan_bk');
        Schema::dropIfExists('digiself_jurnal_evaluasi');
        Schema::dropIfExists('digiself_rencana_aksi');
        Schema::dropIfExists('digiself_refleksi');
    }
};
