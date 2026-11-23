<?php

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $no_hp = '';
    public $photo = null;
    public ?string $currentAvatar = null;

    // Field Khusus Siswa
    public string $nis = '';
    public string $jenis_kelamin = 'L';
    public string $nama_ortu = '';
    public string $no_hp_ortu = '';
    public string $alamat = '';

    // Field Khusus Guru
    public string $nip = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name ?? '';
        $this->no_hp = $user->no_hp ?? '';
        $this->currentAvatar = $user->avatar;

        if ($user->hasRole('siswa') && $user->siswa) {
            $this->nis = $user->siswa->nis ?? '';
            $this->jenis_kelamin = $user->siswa->jenis_kelamin ?? 'L';
            $this->nama_ortu = $user->siswa->nama_ortu ?? '';
            $this->no_hp_ortu = $user->siswa->no_hp_ortu ?? '';
            $this->alamat = $user->siswa->alamat ?? '';
        } elseif ($user->hasRole('guru_bk') && $user->guru) {
            $this->nip = $user->guru->nip ?? '';
            $this->jenis_kelamin = $user->guru->jenis_kelamin ?? 'L';
            $this->no_hp = $user->guru->no_hp ?? $this->no_hp;
        }
    }

    public function updateBiodata(): void
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:2048'], // Maks 2MB
        ];

        if ($user->hasRole('siswa')) {
            $siswaId = $user->siswa?->id;
            $rules['nis'] = ['required', 'string', 'max:30', 'unique:siswa,nis,' . $siswaId];
            $rules['jenis_kelamin'] = ['required', 'in:L,P'];
            $rules['nama_ortu'] = ['nullable', 'string', 'max:100'];
            $rules['no_hp_ortu'] = ['nullable', 'string', 'max:20'];
            $rules['alamat'] = ['nullable', 'string', 'max:500'];
        } elseif ($user->hasRole('guru_bk')) {
            $guruId = $user->guru?->id;
            $rules['nip'] = ['nullable', 'string', 'max:30', 'unique:guru,nip,' . $guruId];
            $rules['jenis_kelamin'] = ['required', 'in:L,P'];
        }

        $validated = $this->validate($rules);

        // Upload foto profil jika ada berkas baru
        if ($this->photo) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $this->photo->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $this->currentAvatar = $avatarPath;
        }

        $user->name = $this->name;
        $user->no_hp = $this->no_hp;
        $user->save();

        // Simpan data spesifik per role
        if ($user->hasRole('siswa')) {
            if ($user->siswa) {
                $user->siswa->update([
                    'nama' => $this->name,
                    'nis' => $this->nis,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'nama_ortu' => $this->nama_ortu,
                    'no_hp_ortu' => $this->no_hp_ortu,
                    'alamat' => $this->alamat,
                ]);
            }
        } elseif ($user->hasRole('guru_bk')) {
            if ($user->guru) {
                $user->guru->update([
                    'nama' => $this->name,
                    'nip' => $this->nip,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'no_hp' => $this->no_hp,
                ]);
            }
        }

        $this->photo = null;
        $this->dispatch('profile-updated', name: $user->name);
        session()->flash('success_biodata', '✓ Biodata dan informasi profil Anda berhasil disimpan!');
    }

    public function hapusFoto(): void
    {
        $user = Auth::user();
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->avatar = null;
        $user->save();
        $this->currentAvatar = null;
        $this->photo = null;

        $this->dispatch('profile-updated', name: $user->name);
        session()->flash('success_biodata', '✓ Foto profil berhasil dihapus, kembali ke inisial nama.');
    }
}; ?>

<section>
    <header class="text-center pb-4 border-b border-gray-100">
        <h2 class="text-base font-extrabold text-gray-900">
            {{ __('Ubah Biodata & Foto Profil') }}
        </h2>
        <p class="mt-1 text-xs text-gray-500">
            {{ __('Perbarui data identitas diri dan foto akun Anda secara langsung') }}
        </p>
    </header>

    @if (session('success_biodata'))
        <div class="mt-4 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold text-center">
            {{ session('success_biodata') }}
        </div>
    @endif

    <form wire:submit="updateBiodata" class="mt-6 space-y-5">
        <!-- 1. Foto Profil Bulat Interaktif -->
        <div class="flex flex-col items-center justify-center p-4 rounded-2xl bg-purple-50/50 border border-purple-100/70">
            <span class="text-xs font-bold text-gray-700 mb-2">Foto Profil Akun</span>
            <div class="relative group">
                <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-purple-700 to-indigo-600 text-white flex items-center justify-center font-black text-2xl shadow-lg border-3 border-white ring-2 ring-purple-200 overflow-hidden">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" alt="Preview Foto">
                    @elseif ($currentAvatar && Storage::disk('public')->exists($currentAvatar))
                        <img src="{{ asset('storage/' . $currentAvatar) }}" class="w-full h-full object-cover" alt="Foto Profil">
                    @else
                        {{ strtoupper(substr($name ?: 'U', 0, 1)) }}
                    @endif
                </div>

                <label for="upload-photo" class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-purple-700 hover:bg-purple-800 text-white flex items-center justify-center cursor-pointer shadow-md transition transform hover:scale-105" title="Unggah Foto Baru">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </label>
                <input type="file" id="upload-photo" wire:model="photo" accept="image/*" class="hidden">
            </div>

            <div class="mt-2.5 flex items-center gap-3">
                <label for="upload-photo" class="cursor-pointer text-[11px] font-bold text-purple-700 hover:text-purple-900 hover:underline">
                    Pilih Berkas Foto (Maks 2MB)
                </label>
                @if ($currentAvatar || $photo)
                    <button type="button" wire:click="hapusFoto" class="text-[11px] font-bold text-rose-600 hover:text-rose-800 hover:underline">
                        Hapus Foto
                    </button>
                @endif
            </div>

            <div wire:loading wire:target="photo" class="text-[11px] text-purple-600 font-semibold mt-1">
                Mengunggah preview foto...
            </div>
            <x-input-error class="mt-1" :messages="$errors->get('photo')" />
        </div>

        <!-- 2. Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-bold text-xs text-gray-700 mb-1" />
            <x-text-input wire:model="name" id="name" type="text" class="block w-full py-2.5 text-xs font-semibold" required />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <!-- 3. Nomor HP Akun Pribadi -->
        <div>
            <x-input-label for="no_hp" :value="__('Nomor Handphone / WhatsApp Pribadi')" class="font-bold text-xs text-gray-700 mb-1" />
            <x-text-input wire:model="no_hp" id="no_hp" type="text" placeholder="Contoh: 081234567890" class="block w-full py-2.5 text-xs font-mono" />
            <x-input-error class="mt-1" :messages="$errors->get('no_hp')" />
        </div>

        <!-- 4. Field Spesifik Siswa -->
        @if (auth()->user()->hasRole('siswa'))
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-purple-50/40 border border-purple-100/70">
                <div>
                    <x-input-label for="nis" :value="__('Nomor Induk Siswa (NIS)')" class="font-bold text-xs text-gray-700 mb-1" />
                    <x-text-input wire:model="nis" id="nis" type="text" class="block w-full py-2.5 text-xs font-mono font-bold" required />
                    <x-input-error class="mt-1" :messages="$errors->get('nis')" />
                </div>

                <div>
                    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" class="font-bold text-xs text-gray-700 mb-1" />
                    <select wire:model="jenis_kelamin" id="jenis_kelamin" class="block w-full py-2.5 px-3 text-xs font-semibold rounded-xl border-gray-300 shadow-xs focus:border-purple-500 focus:ring-purple-500">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                    <x-input-error class="mt-1" :messages="$errors->get('jenis_kelamin')" />
                </div>

                <div>
                    <x-input-label for="nama_ortu" :value="__('Nama Orang Tua / Wali')" class="font-bold text-xs text-gray-700 mb-1" />
                    <x-text-input wire:model="nama_ortu" id="nama_ortu" type="text" placeholder="Nama wali murid" class="block w-full py-2.5 text-xs" />
                    <x-input-error class="mt-1" :messages="$errors->get('nama_ortu')" />
                </div>

                <div>
                    <x-input-label for="no_hp_ortu" :value="__('Nomor WhatsApp Orang Tua / Wali')" class="font-bold text-xs text-gray-700 mb-1" />
                    <x-text-input wire:model="no_hp_ortu" id="no_hp_ortu" type="text" placeholder="08xxxxxxxxxx" class="block w-full py-2.5 text-xs font-mono" />
                    <x-input-error class="mt-1" :messages="$errors->get('no_hp_ortu')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="alamat" :value="__('Alamat Tempat Tinggal')" class="font-bold text-xs text-gray-700 mb-1" />
                    <textarea wire:model="alamat" id="alamat" rows="2" placeholder="Alamat lengkap siswa..." class="block w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-purple-500 focus:ring-purple-500"></textarea>
                    <x-input-error class="mt-1" :messages="$errors->get('alamat')" />
                </div>
            </div>
        @endif

        <!-- 5. Field Spesifik Guru BK -->
        @if (auth()->user()->hasRole('guru_bk'))
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-amber-50/40 border border-amber-100/70">
                <div>
                    <x-input-label for="nip" :value="__('Nomor Induk Pegawai (NIP)')" class="font-bold text-xs text-gray-700 mb-1" />
                    <x-text-input wire:model="nip" id="nip" type="text" placeholder="NIP Guru BK" class="block w-full py-2.5 text-xs font-mono font-bold" />
                    <x-input-error class="mt-1" :messages="$errors->get('nip')" />
                </div>

                <div>
                    <x-input-label for="jenis_kelamin_guru" :value="__('Jenis Kelamin')" class="font-bold text-xs text-gray-700 mb-1" />
                    <select wire:model="jenis_kelamin" id="jenis_kelamin_guru" class="block w-full py-2.5 px-3 text-xs font-semibold rounded-xl border-gray-300 shadow-xs focus:border-purple-500 focus:ring-purple-500">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                    <x-input-error class="mt-1" :messages="$errors->get('jenis_kelamin')" />
                </div>
            </div>
        @endif

        <!-- Tombol Simpan Terpusat -->
        <div class="pt-3 text-center">
            <x-primary-button class="py-2.5 px-8 text-xs font-bold shadow-md shadow-purple-600/20">
                {{ __('Simpan Perubahan Biodata') }}
            </x-primary-button>
        </div>
    </form>
</section>
