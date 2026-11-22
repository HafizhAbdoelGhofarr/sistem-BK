<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Histori migration lama yang sudah dijalankan.
        // Struktur BK kini dibuat oleh migration terpisah di bawah.
    }

    public function down(): void
    {
        // Tidak menghapus tabel milik migration terpisah.
    }
};
