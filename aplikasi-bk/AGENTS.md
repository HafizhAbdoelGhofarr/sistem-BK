# Aplikasi BK (Bimbingan Konseling Sekolah)

Dokumen ini adalah konteks proyek untuk AI agent. Baca sampai habis sebelum menulis kode.
Bahasa proyek: nama tabel, kolom, dan UI memakai **Bahasa Indonesia**. Kode, nama class, dan komentar teknis boleh Inggris.

## 1. Ringkasan sistem

Aplikasi web untuk mencatat dan membina **pelanggaran tata tertib siswa** di sekolah.
Setiap pelanggaran punya **poin**. Akumulasi poin menentukan **status kedisiplinan** siswa
(aman, perhatian, pembinaan, kritis). Guru BK melakukan **pembinaan** dan mencatat **tindak lanjut**.
Saat pelanggaran dicatat, sistem mengirim **notifikasi WhatsApp otomatis ke orang tua/wali**
lewat **API Sidobe**.

Aplikasi bersifat **multi-role** dengan **3 role**: `admin`, `guru_bk`, `siswa`.

## 2. Tech stack

| Bagian | Teknologi |
|---|---|
| Framework | Laravel (versi stabil terbaru, cek `composer.json`) |
| UI | Livewire + Blade + Tailwind CSS (starter kit: Laravel Breeze Livewire) |
| Database | MySQL 8 |
| Role & permission | `spatie/laravel-permission` |
| Queue | Laravel Queue, driver `database` (worker dijalankan Supervisor di production) |
| Notifikasi WA | API Sidobe via `Illuminate\Support\Facades\Http` |
| Laporan | `barryvdh/laravel-dompdf` (PDF), `maatwebsite/excel` (Excel) |
| Deploy | Nginx + PHP-FPM + Supervisor di VPS |

Arsitektur: **monolith berlapis**.

```
Browser (Livewire/Blade)
  -> Route + Middleware (auth, role)
  -> Controller / Livewire Component
  -> Service Layer (logika bisnis)
  -> Eloquent -> MySQL
  -> Queue Job -> Sidobe API (WhatsApp)
```

## 3. Role dan hak akses

Hanya ada **3 role**: `admin`, `guru_bk`, `siswa`. Role disimpan lewat Spatie (`roles`, `model_has_roles`).
Semua orang login lewat tabel `users`, dan setiap user punya satu role.

Hak akses tiap role di bawah ini **persis sesuai spesifikasi aplikasi**. Jangan menambah hak akses di luar daftar ini.

### admin
- Mengelola seluruh data aplikasi
- Manajemen data siswa
- Manajemen data guru
- Manajemen kelas
- Manajemen tata tertib dan jenis pelanggaran
- Monitoring pelanggaran siswa
- Pengelolaan pengguna dan hak akses
- Rekap dan laporan data pelanggaran

### guru_bk
- Mengelola data pelanggaran
- Melakukan pembinaan siswa
- Monitoring poin pelanggaran
- Mencatat tindak lanjut pembinaan
- Melihat riwayat pelanggaran siswa
- Monitoring perkembangan kedisiplinan siswa

### siswa
- Melihat profil pribadi
- Melihat riwayat pelanggaran
- Melihat poin pelanggaran
- Melihat informasi pembinaan
- Monitoring status kedisiplinan

### Di luar cakupan
Role `guru` dan `wali_kelas` **tidak dipakai**. Fitur yang hanya milik role itu (absensi wali kelas, pelaporan pelanggaran oleh guru mapel, monitoring oleh wali kelas) **tidak dibuat**.
Data guru dan wali kelas hanya **data master** yang dikelola admin. Hanya guru BK yang punya akun login.

### Catatan penegakan
- `siswa` hanya boleh mengakses data **dirinya sendiri** (`siswa.user_id` = user login).
- Pembatasan data wajib ditegakkan lewat Laravel **Policy / query scope**, bukan hanya menyembunyikan menu.
- Hal yang **tidak tertulis** di daftar (contoh: laporan untuk guru BK, siswa menginput pelanggaran) berarti **belum ada**. Tanya dulu sebelum menambahkan.

## 4. Alur bisnis utama

### 4.1 Input pelanggaran (guru_bk / admin)
1. Pilih kelas -> pilih siswa -> pilih jenis pelanggaran (poin terisi otomatis) -> tanggal, keterangan, bukti foto (opsional).
2. Di dalam **satu DB transaction** (`PelanggaranService`):
   - buat baris `pelanggaran` dengan `poin` = snapshot dari `jenis_pelanggaran.poin`,
   - tambah `siswa.total_poin`,
   - hitung ulang `siswa.status_disiplin` memakai tabel `batas_status`.
3. Setelah commit: jika `siswa.no_hp_ortu` terisi, buat baris `notifikasi_wa` (status `pending`) dan dispatch job pengiriman WA ke queue.
4. Job memanggil Sidobe, lalu mengubah `notifikasi_wa.status` menjadi `terkirim` atau `gagal` (simpan `respon`, naikkan `percobaan`). Retry maksimal 3 kali dengan backoff.

### 4.2 Pembinaan (guru_bk)
Dashboard BK menampilkan siswa berpoin tinggi -> buat `pembinaan` (jadwal, jenis, catatan) -> catat `tindak_lanjut` -> status pembinaan dan pelanggaran diperbarui.

### 4.3 Status kedisiplinan
Ditentukan dari `siswa.total_poin` dan tabel `batas_status` (bisa diubah admin). Default:

| Status | Poin minimal |
|---|---|
| aman | 0 |
| perhatian | 25 |
| pembinaan | 40 |
| kritis | 55 |

## 5. Skema database

Migration sudah dibuat, **satu file per tabel**, di `database/migrations/` (prefix `2026_11_22_0000NN_`). Tabel role Spatie dibuat oleh `vendor:publish` Spatie.

| Tabel | Isi | Kolom/relasi penting |
|---|---|---|
| `users` | Akun login semua role | + `no_hp`, `is_active` |
| `guru` | Data master guru (hanya guru BK yang punya akun) | `user_id` (nullable, unik), `nip`, `nama` |
| `kelas` | Kelas per tahun ajaran | `wali_kelas_id` -> `guru` (data master saja), `tahun_ajaran` |
| `siswa` | Profil siswa | `user_id` (nullable), `kelas_id`, `nis` (unik), `no_hp_ortu`, `total_poin`, `status_disiplin`, soft delete |
| `tata_tertib` | Pasal/aturan sekolah | `kode` (unik) |
| `jenis_pelanggaran` | Jenis pelanggaran + poin | `tata_tertib_id`, `kategori` (ringan/sedang/berat), `poin`, `is_active` |
| `pelanggaran` | Catatan pelanggaran siswa | `siswa_id`, `jenis_pelanggaran_id`, `pelapor_id` -> `users`, `poin` (snapshot), `status` (baru/dibina/selesai), soft delete |
| `pembinaan` | Sesi pembinaan | `siswa_id`, `pelanggaran_id` (nullable), `guru_bk_id` -> `users`, `status` (dijadwalkan/berlangsung/selesai) |
| `tindak_lanjut` | Tindak lanjut pembinaan | `pembinaan_id`, `dibuat_oleh` -> `users`, `status` (proses/selesai) |
| `notifikasi_wa` | Log dan antrean WA | `siswa_id`, `pelanggaran_id` (nullable), `status` (pending/terkirim/gagal), `percobaan`, `respon` |
| `batas_status` | Batas poin per status | `status` (unik), `min_poin` |

Relasi inti: `guru` opsional terhubung ke `users` (hanya guru BK), `users 1-0..1 siswa`, `guru 1-N kelas` (wali), `kelas 1-N siswa`, `siswa 1-N pelanggaran`, `jenis_pelanggaran 1-N pelanggaran`, `siswa 1-N pembinaan`, `pembinaan 1-N tindak_lanjut`, `pelanggaran 0..1-N notifikasi_wa`.

## 6. Aturan bisnis (invariants)

- `pelanggaran.poin` adalah **snapshot**. Mengubah poin di `jenis_pelanggaran` **tidak boleh** mengubah poin pelanggaran lama.
- `siswa.total_poin` dan `status_disiplin` hanya diubah lewat `PelanggaranService`. Jangan update langsung dari controller/Livewire.
- Menghapus (soft delete) atau mengubah poin pelanggaran harus **menghitung ulang** `total_poin` dan `status_disiplin` dalam transaction yang sama.
- Pengiriman WA **tidak boleh memblokir** request. Selalu lewat queue. Gagal kirim tidak boleh membatalkan pencatatan pelanggaran.
- Normalisasi nomor WA ke format internasional (`62...`) sebelum dikirim. Jangan kirim jika `no_hp_ortu` kosong.
- Jangan hard delete `siswa`, `pelanggaran`, atau data yang punya riwayat. Gunakan soft delete / nonaktifkan.
- Validasi lewat **Form Request** atau aturan validasi Livewire. Gunakan `$fillable` yang eksplisit.

## 7. Struktur kode yang diharapkan

```
app/
  Models/            Siswa, Guru, Kelas, TataTertib, JenisPelanggaran, Pelanggaran,
                     Pembinaan, TindakLanjut, NotifikasiWa, BatasStatus
  Services/          PelanggaranService, PembinaanService, WhatsAppService (Sidobe)
  Jobs/              KirimNotifikasiWaJob
  Policies/          PelanggaranPolicy, SiswaPolicy, PembinaanPolicy, ...
  Livewire/          komponen UI dikelompokkan per role/fitur
database/
  migrations/        satu file per tabel
  seeders/           RoleSeeder, AdminSeeder, contoh data tata tertib
```

Nama role (persis): `admin`, `guru_bk`, `siswa`.

## 8. Integrasi WhatsApp (Sidobe)

- Dibungkus di `WhatsAppService`. Endpoint, kredensial, dan format payload diambil dari **config/.env**, jangan di-hardcode:
  `SIDOBE_API_URL`, `SIDOBE_API_KEY` (sesuaikan nama dengan dokumentasi Sidobe).
- Detail endpoint dan format request/response Sidobe **belum diverifikasi**. Cek dokumentasi resmi Sidobe sebelum menulis implementasi, jangan menebak.
- Contoh isi pesan: nama siswa, kelas, jenis pelanggaran, poin, tanggal, dan total poin saat ini.



## 10. Perintah umum

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate --seed
php artisan queue:work        # wajib jalan agar WA terkirim
php artisan test
```

## 11. Panduan untuk AI agent

- Ikuti konvensi Laravel dan gaya kode yang sudah ada di repo. Cek file yang ada sebelum membuat yang baru.
- Taruh logika bisnis di **Service**, bukan di controller/Livewire/Blade.
- Setiap fitur baru harus memikirkan **role mana yang boleh akses** dan **data mana yang boleh dilihat**, dan hanya mengikuti daftar hak akses di bagian 3.
- Jangan mengubah migration yang sudah dijalankan. Buat migration baru.
- Jangan menyimpan kredensial atau nomor telepon asli di repo.
- Jika ada yang ambigu (aturan poin, format pesan WA, detail API Sidobe), **tanya dulu** dan jangan berasumsi.