<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('batas_status')) {
            Schema::create('batas_status', function (Blueprint $table) {
                $table->id();
                $table->string('status', 20)->unique();
                $table->unsignedInteger('min_poin');
                $table->timestamps();
            });

            DB::table('batas_status')->insert([
                ['status' => 'aman', 'min_poin' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['status' => 'perhatian', 'min_poin' => 25, 'created_at' => now(), 'updated_at' => now()],
                ['status' => 'pembinaan', 'min_poin' => 40, 'created_at' => now(), 'updated_at' => now()],
                ['status' => 'kritis', 'min_poin' => 55, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down(): void
    {
        // Dipertahankan sebagai migration aman untuk database yang sudah ada.
    }
};
