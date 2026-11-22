<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pembinaan')) {
            Schema::create('pembinaan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
                $table->foreignId('pelanggaran_id')->nullable()->constrained('pelanggaran')->nullOnDelete();
                $table->foreignId('guru_bk_id')->constrained('users')->restrictOnDelete();
                $table->date('tanggal');
                $table->string('jenis', 50);
                $table->text('catatan')->nullable();
                $table->enum('status', ['dijadwalkan', 'berlangsung', 'selesai'])->default('dijadwalkan');
                $table->timestamps();
                $table->index(['siswa_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        // Dipertahankan sebagai migration aman untuk database yang sudah ada.
    }
};
