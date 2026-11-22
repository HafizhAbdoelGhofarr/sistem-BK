<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('kelas')) {
            Schema::create('kelas', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 30);
                $table->string('tingkat', 5);
                $table->string('jurusan', 50)->nullable();
                $table->string('tahun_ajaran', 9);
                $table->foreignId('wali_kelas_id')->nullable()->constrained('guru')->nullOnDelete();
                $table->timestamps();
                $table->unique(['nama', 'tahun_ajaran']);
            });
        }
    }

    public function down(): void
    {
        // Dipertahankan sebagai migration aman untuk database yang sudah ada.
    }
};
