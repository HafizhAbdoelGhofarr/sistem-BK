<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class WhatsAppService
{
    public function kirimPesan(string $nomorTujuan, string $pesan): array
    {
        $secretKey = config('services.sidobe.secret_key');
        if (! is_string($secretKey) || $secretKey === '') {
            throw new RuntimeException('SIDOBE_API_KEY belum dikonfigurasi.');
        }

        $url = rtrim((string) config('services.sidobe.url'), '/').'/send-message';
        $response = Http::acceptJson()
            ->asJson()
            ->withHeaders(['X-Secret-Key' => $secretKey])
            ->timeout((int) config('services.sidobe.timeout', 15))
            ->post($url, [
                'phone' => '+'.self::normalisasiNomor($nomorTujuan),
                'message' => $pesan,
                'is_async' => false,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Sidobe mengembalikan HTTP '.$response->status().'.');
        }

        $hasil = $response->json();
        if (! is_array($hasil) || ($hasil['is_success'] ?? false) !== true) {
            throw new RuntimeException('Sidobe menolak permintaan pengiriman pesan.');
        }

        $data = $hasil['data'] ?? null;
        $status = is_array($data) ? ($data['status'] ?? null) : null;
        if (! in_array($status, ['PENDING', 'SUCCESS', 'FAILED'], true)) {
            throw new RuntimeException('Sidobe mengembalikan format respons yang tidak dikenal.');
        }

        if ($status === 'FAILED') {
            throw new RuntimeException('Sidobe melaporkan pengiriman pesan gagal.');
        }

        return $hasil;
    }

    public static function normalisasiNomor(string $nomor): string
    {
        $digits = preg_replace('/\D+/', '', trim($nomor));
        if (! is_string($digits) || $digits === '') {
            throw new InvalidArgumentException('Nomor WhatsApp tidak valid.');
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62'.$digits;
        }

        if (! str_starts_with($digits, '62') || strlen($digits) < 10 || strlen($digits) > 15) {
            throw new InvalidArgumentException('Nomor WhatsApp harus menggunakan nomor Indonesia yang valid.');
        }

        return $digits;
    }
}
