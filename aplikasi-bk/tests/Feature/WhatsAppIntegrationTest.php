<?php

namespace Tests\Feature;

use App\Jobs\KirimNotifikasiWaJob;
use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\NotifikasiWa;
use App\Models\Siswa;
use App\Models\TataTertib;
use App\Models\User;
use App\Services\PelanggaranService;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use InvalidArgumentException;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WhatsAppIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_phone_numbers_are_normalized_and_invalid_numbers_are_rejected(): void
    {
        $this->assertSame('6281234567890', WhatsAppService::normalisasiNomor('0812 3456-7890'));
        $this->assertSame('6281234567890', WhatsAppService::normalisasiNomor('+62 (812) 3456-7890'));
        $this->assertSame('6281234567890', WhatsAppService::normalisasiNomor('0062 812 3456 7890'));

        $this->expectException(InvalidArgumentException::class);
        WhatsAppService::normalisasiNomor('nomor-tidak-valid');
    }

    public function test_service_sends_the_documented_sidobe_payload(): void
    {
        config()->set('services.sidobe.secret_key', 'test-secret');
        Http::fake([
            '*' => Http::response([
                'is_success' => true,
                'data' => ['status' => 'SUCCESS'],
            ]),
        ]);

        $response = app(WhatsAppService::class)->kirimPesan('081234567890', 'Pesan uji');

        $this->assertTrue($response['is_success']);
        Http::assertSent(fn (HttpRequest $request) => $request->url() === 'https://api.sidobe.com/wa/v1/send-message'
            && $request->hasHeader('X-Secret-Key', 'test-secret')
            && $request['phone'] === '+6281234567890'
            && $request['message'] === 'Pesan uji'
            && $request['is_async'] === false);
    }

    public function test_violation_creates_a_pending_notification_and_dispatches_a_job(): void
    {
        Queue::fake();
        $guruBk = $this->userWithRole('guru_bk');
        $student = $this->makeStudent('081234567890');
        $type = $this->makeViolationType();

        app(PelanggaranService::class)->buat([
            'siswa_id' => $student->id,
            'jenis_pelanggaran_id' => $type->id,
            'pelapor_id' => $guruBk->id,
            'tanggal' => '2026-11-23',
        ]);

        $notification = NotifikasiWa::query()->sole();
        $this->assertSame('6281234567890', $notification->nomor_tujuan);
        $this->assertSame('pending', $notification->status);
        $this->assertStringContainsString('Total poin: 5', $notification->pesan);
        Queue::assertPushed(KirimNotifikasiWaJob::class, fn ($job) => $job->notifikasiWaId === $notification->id);
    }

    public function test_job_marks_successful_delivery_and_records_attempt_count(): void
    {
        config()->set('services.sidobe.secret_key', 'test-secret');
        Http::fake([
            '*' => Http::response([
                'is_success' => true,
                'data' => ['status' => 'SUCCESS'],
            ]),
        ]);
        $notification = $this->makeNotification();

        (new KirimNotifikasiWaJob($notification->id))->handle(app(WhatsAppService::class));

        $this->assertSame('terkirim', $notification->refresh()->status);
        $this->assertSame(1, $notification->percobaan);
        $this->assertNotNull($notification->dikirim_pada);
    }

    public function test_failed_job_records_the_error_after_retry_exhaustion(): void
    {
        config()->set('services.sidobe.secret_key', 'test-secret');
        Http::fake(['*' => Http::response(['is_success' => false], 200)]);
        $notification = $this->makeNotification();
        $job = new KirimNotifikasiWaJob($notification->id);

        try {
            $job->handle(app(WhatsAppService::class));
            $this->fail('The failed Sidobe response should be retried.');
        } catch (RuntimeException $exception) {
            $job->failed($exception);
        }

        $this->assertSame('gagal', $notification->refresh()->status);
        $this->assertSame(1, $notification->percobaan);
        $this->assertStringContainsString('Sidobe menolak', $notification->respon);
        $this->assertSame(3, $job->tries);
        $this->assertSame([10, 30], $job->backoff());
    }

    public function test_admin_can_retry_a_failed_notification(): void
    {
        Queue::fake();
        $admin = $this->userWithRole('admin');
        $notification = $this->makeNotification();
        $notification->update([
            'status' => 'gagal',
            'percobaan' => 3,
            'respon' => 'Kegagalan sebelumnya',
        ]);

        $this->actingAs($admin)
            ->postJson('/bk/notifikasi-wa/'.$notification->id.'/kirim-ulang')
            ->assertAccepted()
            ->assertJsonPath('status', 'pending');

        $this->assertSame('pending', $notification->refresh()->status);
        $this->assertSame(0, $notification->percobaan);
        Queue::assertPushed(KirimNotifikasiWaJob::class, fn ($job) => $job->notifikasiWaId === $notification->id);
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::findOrCreate($roleName, 'web');
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    private function makeStudent(string $phone): Siswa
    {
        $kelas = Kelas::create([
            'nama' => 'X IPA 1',
            'tingkat' => 'X',
            'tahun_ajaran' => '2026/2027',
        ]);

        return Siswa::create([
            'kelas_id' => $kelas->id,
            'nis' => 'NIS-WA-1',
            'nama' => 'Siswa Uji',
            'jenis_kelamin' => 'L',
            'no_hp_ortu' => $phone,
        ]);
    }

    private function makeViolationType(): JenisPelanggaran
    {
        $tataTertib = TataTertib::create([
            'kode' => 'TT-WA-1',
            'nama' => 'Tata Tertib Uji',
        ]);

        return JenisPelanggaran::create([
            'tata_tertib_id' => $tataTertib->id,
            'kode' => 'JP-WA-1',
            'nama' => 'Terlambat',
            'kategori' => 'ringan',
            'poin' => 5,
            'is_active' => true,
        ]);
    }

    private function makeNotification(): NotifikasiWa
    {
        $student = $this->makeStudent('6281234567890');

        return NotifikasiWa::create([
            'siswa_id' => $student->id,
            'nomor_tujuan' => '6281234567890',
            'pesan' => 'Pesan uji',
            'status' => 'pending',
        ]);
    }
}
