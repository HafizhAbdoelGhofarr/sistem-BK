<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('siswa')) {
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
                $table->string('no_hp_ortu', 20)->nullable();
                $table->unsignedInteger('total_poin')->default(0);
                $table->string('status_disiplin', 20)->default('aman');
                $table->timestamps();
                $table->softDeletes();
                $table->index('status_disiplin');
            });
        }
    }

    public function down(): void
    {
        // Dipertahankan sebagai migration aman untuk database yang sudah ada.
    }
};
