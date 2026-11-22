<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('notifikasi_wa')) {
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
        }
    }

    public function down(): void
    {
        // Dipertahankan sebagai migration aman untuk database yang sudah ada.
    }
};
