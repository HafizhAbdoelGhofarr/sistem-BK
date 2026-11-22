<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pelanggaran')) {
            Schema::create('pelanggaran', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
                $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggaran')->restrictOnDelete();
                $table->foreignId('pelapor_id')->constrained('users')->restrictOnDelete();
                $table->date('tanggal');
                $table->unsignedSmallInteger('poin');
                $table->text('keterangan')->nullable();
                $table->string('bukti_foto')->nullable();
                $table->enum('status', ['baru', 'dibina', 'selesai'])->default('baru');
                $table->timestamps();
                $table->softDeletes();
                $table->index(['siswa_id', 'tanggal']);
            });
        }
    }

    public function down(): void
    {
        // Dipertahankan sebagai migration aman untuk database yang sudah ada.
    }
};
