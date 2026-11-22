<?php

namespace App\Jobs;

use App\Models\NotifikasiWa;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class KirimNotifikasiWaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $notifikasiWaId) {}

    public function backoff(): array
    {
        return [10, 30];
    }

    public function handle(WhatsAppService $whatsApp): void
    {
        $notifikasi = NotifikasiWa::query()->findOrFail($this->notifikasiWaId);

        if ($notifikasi->status === 'terkirim') {
            return;
        }

        $notifikasi->increment('percobaan');

        try {
            $hasil = $whatsApp->kirimPesan($notifikasi->nomor_tujuan, $notifikasi->pesan);

            $notifikasi->forceFill([
                'status' => 'terkirim',
                'respon' => json_encode($hasil, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'dikirim_pada' => now(),
            ])->save();
        } catch (Throwable $exception) {
            $notifikasi->forceFill([
                'respon' => substr($exception->getMessage(), 0, 5000),
            ])->save();

            throw $exception;
        }
    }

    public function failed(?Throwable $exception): void
    {
        NotifikasiWa::query()->whereKey($this->notifikasiWaId)->update([
            'status' => 'gagal',
            'respon' => substr($exception?->getMessage() ?? 'Pengiriman gagal.', 0, 5000),
            'updated_at' => now(),
        ]);
    }
}
