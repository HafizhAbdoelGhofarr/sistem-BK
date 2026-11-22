<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahan kolom di tabel users bawaan Laravel
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_hp', 20)->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('password');
        });

        // Guru (Guru, Guru BK, Wali Kelas dibedakan lewat role spatie)
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nip', 30)->nullable()->unique();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 30);
            $table->string('tingkat', 5);
            $table->string('jurusan', 50)->nullable();
            $table->string('tahun_ajaran', 9); // contoh: 2026/2027
            $table->foreignId('wali_kelas_id')->nullable()->constrained('guru')->nullOnDelete();
            $table->timestamps();

            $table->unique(['nama', 'tahun_ajaran']);
        });

        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->restrictOnDelete();
            $table->string('nis', 20)->unique();
            $table->string('nisn', 20)->nullable()->unique();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama_ortu', 100)->nullable();
            $table->string('no_hp_ortu', 20)->nullable(); // tujuan notifikasi WhatsApp
            $table->unsignedInteger('total_poin')->default(0);
            $table->string('status_disiplin', 20)->default('aman');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status_disiplin');
        });

        Schema::create('tata_tertib', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 150);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('jenis_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tata_tertib_id')->constrained('tata_tertib')->restrictOnDelete();
            $table->string('kode', 20)->unique();
            $table->string('nama', 150);
            $table->enum('kategori', ['ringan', 'sedang', 'berat']);
            $table->unsignedSmallInteger('poin');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggaran')->restrictOnDelete();
            $table->foreignId('pelapor_id')->constrained('users')->restrictOnDelete();
            $table->date('tanggal');
            $table->unsignedSmallInteger('poin'); // snapshot poin saat input
            $table->text('keterangan')->nullable();
            $table->string('bukti_foto')->nullable();
            $table->enum('status', ['baru', 'dibina', 'selesai'])->default('baru');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['siswa_id', 'tanggal']);
        });

        Schema::create('pembinaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('pelanggaran_id')->nullable()->constrained('pelanggaran')->nullOnDelete();
            $table->foreignId('guru_bk_id')->constrained('users')->restrictOnDelete();
            $table->date('tanggal');
            $table->string('jenis', 50); // konseling, panggilan orang tua, peringatan, dll
            $table->text('catatan')->nullable();
            $table->enum('status', ['dijadwalkan', 'berlangsung', 'selesai'])->default('dijadwalkan');
            $table->timestamps();

            $table->index(['siswa_id', 'status']);
        });

        Schema::create('tindak_lanjut', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembinaan_id')->constrained('pembinaan')->cascadeOnDelete();
            $table->foreignId('dibuat_oleh')->constrained('users')->restrictOnDelete();
            $table->date('tanggal');
            $table->text('deskripsi');
            $table->text('hasil')->nullable();
            $table->enum('status', ['proses', 'selesai'])->default('proses');
            $table->timestamps();
        });

        Schema::create('absensi_wali_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('hadir');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['guru_id', 'kelas_id', 'tanggal']);
        });

        // Log notifikasi WhatsApp (Sidobe) untuk monitoring & retry
        Schema::create('notifikasi_wa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('pelanggaran_id')->nullable()->constrained('pelanggaran')->nullOnDelete();
            $table->string('nomor_tujuan', 20);
            $table->text('pesan');
            $table->enum('status', ['pending', 'terkirim', 'gagal'])->default('pending');
            $table->unsignedTinyInteger('percobaan')->default(0);
            $table->text('respon')->nullable();
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        // Batas poin untuk status kedisiplinan (bisa diubah admin)
        Schema::create('batas_status', function (Blueprint $table) {
            $table->id();
            $table->string('status', 20)->unique();
            $table->unsignedInteger('min_poin');
            $table->timestamps();
        });

        DB::table('batas_status')->insert([
            ['status' => 'aman',      'min_poin' => 0,  'created_at' => now(), 'updated_at' => now()],
            ['status' => 'perhatian', 'min_poin' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['status' => 'pembinaan', 'min_poin' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['status' => 'kritis',    'min_poin' => 55, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('batas_status');
        Schema::dropIfExists('notifikasi_wa');
        Schema::dropIfExists('absensi_wali_kelas');
        Schema::dropIfExists('tindak_lanjut');
        Schema::dropIfExists('pembinaan');
        Schema::dropIfExists('pelanggaran');
        Schema::dropIfExists('jenis_pelanggaran');
        Schema::dropIfExists('tata_tertib');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('guru');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_hp', 'is_active']);
        });
    }
};
