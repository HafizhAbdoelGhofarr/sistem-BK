<?php

namespace App\Services;

use App\Models\BatasStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BatasStatusService
{
    private const URUTAN_STATUS = ['aman', 'perhatian', 'pembinaan', 'kritis'];

    public function perbarui(array $data): void
    {
        $thresholds = collect($data['batas'])->keyBy('id');
        $statuses = BatasStatus::query()->orderBy('min_poin')->get()->keyBy('status');

        if ($statuses->keys()->sort()->values()->all() !== collect(self::URUTAN_STATUS)->sort()->values()->all()) {
            throw ValidationException::withMessages([
                'batas' => 'Konfigurasi status kedisiplinan tidak lengkap.',
            ]);
        }

        $byStatus = [];
        foreach ($thresholds as $item) {
            $status = $statuses->firstWhere('id', $item['id']);
            if ($status) {
                $byStatus[$status->status] = (int) $item['min_poin'];
            }
        }

        $incomplete = array_diff(self::URUTAN_STATUS, array_keys($byStatus)) !== [];
        $invalidOrder = false;
        $previous = null;
        foreach (self::URUTAN_STATUS as $status) {
            $current = $byStatus[$status] ?? null;
            if ($current === null || ($previous !== null && $current <= $previous)) {
                $invalidOrder = true;
                break;
            }
            $previous = $current;
        }

        if ($incomplete || $byStatus['aman'] !== 0 || $invalidOrder) {
            throw ValidationException::withMessages([
                'batas' => 'Batas poin harus lengkap, dimulai dari aman = 0, dan meningkat untuk setiap status.',
            ]);
        }

        DB::transaction(function () use ($thresholds) {
            foreach ($thresholds as $item) {
                BatasStatus::query()->whereKey($item['id'])->update([
                    'min_poin' => $item['min_poin'],
                    'updated_at' => now(),
                ]);
            }

            app(PelanggaranService::class)->hitungUlangSemuaStatus();
        });
    }
}
