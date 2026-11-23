<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateBiodataTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_can_update_biodata_and_avatar(): void
    {
        Storage::fake('public');
        Role::findOrCreate('siswa', 'web');

        $user = User::factory()->create(['name' => 'Nama Awal']);
        $user->assignRole('siswa');

        $kelas = Kelas::create([
            'nama' => 'X-PPLG-1',
            'tingkat' => '10',
            'jurusan' => 'PPLG',
            'tahun_ajaran' => '2026/2027',
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'nis' => 'NIS-1111',
            'nama' => 'Nama Awal',
            'jenis_kelamin' => 'L',
        ]);

        $this->actingAs($user);

        $fakePhoto = UploadedFile::fake()->image('avatar.jpg');

        $component = Volt::test('profile.update-biodata-form')
            ->set('name', 'Farrel Siswa Edit')
            ->set('nis', 'NIS-9999')
            ->set('nama_ortu', 'Bapak Budi')
            ->set('no_hp_ortu', '081299998888')
            ->set('photo', $fakePhoto)
            ->call('updateBiodata');

        $component->assertHasNoErrors();

        $user->refresh();
        $siswa->refresh();

        $this->assertSame('Farrel Siswa Edit', $user->name);
        $this->assertSame('NIS-9999', $siswa->nis);
        $this->assertSame('Bapak Budi', $siswa->nama_ortu);
        $this->assertSame('081299998888', $siswa->no_hp_ortu);
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_guru_bk_can_update_nip_and_biodata(): void
    {
        Role::findOrCreate('guru_bk', 'web');

        $user = User::factory()->create(['name' => 'Guru Awal']);
        $user->assignRole('guru_bk');

        $guru = Guru::create([
            'user_id' => $user->id,
            'nip' => '198501012010011001',
            'nama' => 'Guru Awal',
            'jenis_kelamin' => 'L',
        ]);

        $this->actingAs($user);

        $component = Volt::test('profile.update-biodata-form')
            ->set('name', 'Ibu Rahma S.Pd Edit')
            ->set('nip', '199002022020022002')
            ->set('no_hp', '081234567890')
            ->call('updateBiodata');

        $component->assertHasNoErrors();

        $user->refresh();
        $guru->refresh();

        $this->assertSame('Ibu Rahma S.Pd Edit', $user->name);
        $this->assertSame('199002022020022002', $guru->nip);
        $this->assertSame('081234567890', $guru->no_hp);
    }
}
