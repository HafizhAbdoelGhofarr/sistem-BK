<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('guru')) {
            Schema::create('guru', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->string('nip', 30)->nullable()->unique();
                $table->string('nama', 100);
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
                $table->string('no_hp', 20)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Dipertahankan sebagai migration aman untuk database yang sudah ada.
    }
};
