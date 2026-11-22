<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jenis_pelanggaran')) {
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
        }
    }

    public function down(): void
    {
        // Dipertahankan sebagai migration aman untuk database yang sudah ada.
    }
};
