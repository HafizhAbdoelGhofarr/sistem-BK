<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tindak_lanjut')) {
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
        }
    }

    public function down(): void
    {
        // Dipertahankan sebagai migration aman untuk database yang sudah ada.
    }
};
