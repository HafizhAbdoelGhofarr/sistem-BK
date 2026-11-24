# 📋 PRODUCT REQUIREMENT DOCUMENT (PRD)
## SISTEM INFORMASI BIMBINGAN KONSELING & TATA TERTIB SISWA (APLIKASI BK)

---

### 📌 INFORMASI PROYEK
- **Nama Proyek:** Aplikasi Bimbingan Konseling & Tata Tertib Siswa (Sistem BK)
- **Tim Pengembang (Dua Orang):**
  - **Farrel:** Frontend Developer (UI/UX, Blade/Livewire Views, Layouting, Styling White & Light Green)
  - **Hafiz:** Backend Developer (Database, Migration, Service Layer, Queue, Sidobe WhatsApp API)
- **Status Backend:** Selesai (Database & Service Layer siap dikonsumsi Frontend)
- **Tema Desain UI:** Minimalis, Elegan, Modern (*White & Mint Green Theme*)

---

## 1. LATAR BELAKANG & TUJUAN

### 1.1 Latar Belakang
Pengelolaan tata tertib, pencatatan pelanggaran siswa, serta bimbingan konseling di sekolah seringkali menghadapi kendala pencatatan manual berbasis buku atau lembaran kertas. Hal ini menyebabkan lambatnya tindak lanjut, keterbatasan monitoring status kedisiplinan siswa oleh guru dan wali kelas, serta lambatnya penyampaian informasi kepada orang tua/wali murid.

### 1.2 Tujuan Sistem
1. **Digitalisasi Tata Tertib:** Menyediakan master data tata tertib dan jenis pelanggaran berbobot poin yang transparan.
2. **Monitoring Real-time:** Memantau akumulasi poin kedisiplinan siswa secara cepat dan otomatis.
3. **Pembinaan Terarah:** Memudahkan Guru BK dalam menjadwalkan pembinaan dan mencatat tindak lanjut kasus siswa.
4. **Notifikasi Otomatis Orang Tua:** Mengirimkan pesan WhatsApp instan via API Sidobe kepada nomor HP orang tua/wali setiap kali terjadi pelanggaran.
5. **Transparansi Siswa & Wali:** Memudahkan siswa mengecek status disiplin diri dan rekap poin secara mandiri.
6. **Autentikasi Terpadu:** Memberikan akses aman bagi Admin, Guru BK, dan Siswa melalui mekanisme Login, Registrasi Mandiri Siswa, serta Pemulihan Akun (Lupa Password).

---

## 2. PENGGUNA SISTEM (USER ROLES & PERMISSIONS)

Sistem dirancang mencakup kebutuhan civitas sekolah dengan pembagian hak akses:

```
                  ┌─────────────────────────────────────────┐
                  │            SISTEM INFORMASI BK          │
                  └────────────────────┬────────────────────┘
                                       │
     ┌───────────────┬─────────────────┼─────────────────┬───────────────┐
     ▼               ▼                 ▼                 ▼               ▼
 ┌─────────┐   ┌───────────┐     ┌───────────┐     ┌───────────┐   ┌───────────┐
 │  ADMIN  │   │  GURU BK  │     │   SISWA   │     │   GURU    │   │WALI KELAS │
 └─────────┘   └───────────┘     └───────────┘     └───────────┘   └───────────┘
```

### 2.1 Matriks Peran Pengguna

| Peran (Role) | Tanggung Jawab Utama | Akses Sistem Saat Ini |
|---|---|---|
| **ADMIN** | Mengelola seluruh data master (siswa, guru, kelas, tata tertib, jenis pelanggaran), manajemen akun & hak akses, monitoring seluruh pelanggaran, serta rekap & laporan. | **Aktif (Login: Admin)** |
| **GURU BK** | Menginput dan mengelola data pelanggaran, memonitor poin siswa, mencatat jadwal dan hasil sesi pembinaan, tindak lanjut kasus, serta memantau perkembangan kedisiplinan. | **Aktif (Login: Guru BK)** |
| **SISWA** | Registrasi akun siswa (validasi NIS), melihat profil pribadi, riwayat pelanggaran pribadi, total poin pelanggaran, informasi pembinaan, dan status kedisiplinan diri. | **Aktif (Login: Siswa)** |
| **GURU (Mapel)** | Melaporkan pelanggaran siswa di lingkungan sekolah, input data pelanggaran harian, serta melihat riwayat monitoring kedisiplinan. | Data Master terdaftar di Admin (Ekstensi Fase 2) |
| **WALI KELAS** | Memantau kedisiplinan dan poin siswa di kelasnya, rekap absensi wali kelas, dan memantau siswa binaan kelas. | Data Master terdaftar di Admin (Ekstensi Fase 2) |

> **Catatan Arsitektur Backend:**
> Pada implementasi inti backend yang telah diselesaikan oleh Hafiz, autentikasi aktif difokuskan pada 3 role utama yang berinteraksi langsung dengan sistem: **Admin**, **Guru BK**, dan **Siswa**. Data Guru dan Wali Kelas telah tersedia di basis data master kelas dan dapat dihubungkan ke akun login.

---

## 3. DESIGN SYSTEM & ATURAN TEMA (WHITE & MINT GREEN)

Sesuai referensi identitas DIGISELF, frontend mengusung gaya **Simple, Clean, Modern dengan dominasi hijau mint muda, permukaan putih, dan aksen hijau-teal**.

### 3.1 Palet Warna (Color Palette)

| Kategori Warna | Kode Hex | Penggunaan UI |
|---|---|---|
| **Mint Canvas** | `#F3FFF5` | Latar utama aplikasi dan kanvas halaman |
| **Soft Green Tint** | `#DDF7E2` | Background kartu highlight, badge, dan row hover |
| **Primary Green-Teal** | `#17736D` | Sidebar aktif, header brand, tombol utama, dan tautan |
| **Green Accent** | `#55B971` | Aksen hijau muda, hover state, indikator aktif |
| **Deep Teal** | `#103743` | Teks brand dan latar gelap beraksen |
| **Pure White** | `#FFFFFF` | Background konten utama, card container, modal, form input |
| **Subtle Gray** | `#F9FAFB` (Gray-50) | Permukaan netral sekunder |
| **Border Gray** | `#E5E7EB` (Gray-200) | Garis pemisah tabel, border card, border input form |
| **Text Primary** | `#1F2937` (Gray-800) | Teks judul, label form, teks tabel |
| **Text Muted** | `#6B7280` (Gray-500) | Subtitle, placeholder, waktu riwayat |

### 3.2 Indikator Status Kedisiplinan (Poin Pelanggaran)
Untuk kartu status kedisiplinan siswa, digunakan badge dengan warna yang tegas:
- **Aman (0 - 24 Poin):** Badge Hijau Lembut (`#DEF7EC` / `#03543F`)
- **Perhatian (25 - 39 Poin):** Badge Kuning Lembut (`#FEF08A` / `#713F12`)
- **Pembinaan (40 - 54 Poin):** Badge Oranye Lembut (`#FFEDD5` / `#9A3412`)
- **Kritis (≥ 55 Poin):** Badge Merah Lembut (`#FDE8E8` / `#9B1C1C`)

### 3.3 Pedoman Tipografi & Komponen
- **Font Family:** Inter / Plus Jakarta Sans / Sans-Serif Native
- **Tombol Utama (Primary Button):** Background `#17736D`, teks putih, sudut rounded (`rounded-lg`), efek hover transisi lembut ke `#155C63`.
- **Form Input:** Background putih bersih, border abu-abu tipis (`#E5E7EB`), outline fokus hijau lembut.
- **Card Container:** Background putih, shadow halus (`shadow-sm` sampai `shadow-md`), rounded-xl, padding lega (clean padding).

---

## 4. PEMBAGIAN KERJA TIM (JOBDESK)

Untuk menjaga kerapian repositori dan batasan pekerjaan tim:

```
┌──────────────────────────────────────────────┬──────────────────────────────────────────────┐
│        FARREL (FRONTEND DEVELOPER)           │         HAFIZ (BACKEND DEVELOPER)            │
├──────────────────────────────────────────────┼──────────────────────────────────────────────┤
│ • Desain Halaman Auth (Login, Register, Lupa)│ • Database Migration & Model Eloquent        │
│ • Layouting Blade Auth (Clean Mint/White)    │ • Service Layer (Pelanggaran, Pembinaan)     │
│ • Pembuatan Layout Induk (App, Nav, Sidebar) │ • Logika Autentikasi, Password Reset & Hash │
│ • Komponen Tabel Data & Pagination           │ • Integrasi API Sidobe WhatsApp Gateway      │
│ • Form Input Pelanggaran & Pembinaan         │ • Queue Job (Notifikasi WA Background)       │
│ • Tampilan Dashboard Admin, BK, Siswa        │ • Policy & Spatie Role-Permission Handling   │
│ • Tampilan Rekap Data & Cetak Laporan (PDF)  │ • Controller, Form Request & Endpoint Route  │
│ • Modal Dialog, Toast Notifikasi & Validasi  │ • Seeder data master (Admin, Tata Tertib)    │
└──────────────────────────────────────────────┴──────────────────────────────────────────────┘
```

---

## 5. FITUR AUTENTIKASI & SISTEM ALUR PASCA-AUTENTIKASI

Modul autentikasi dirancang ramah pengguna (*user-friendly*) dengan latar hijau mint muda, kartu putih, dan aksen hijau-teal, dilengkapi fitur keamanan standar.

```
                      ┌────────────────────────────────┐
                      │    GERBANG AUTENTIKASI (AUTH)  │
                      └───────────────┬────────────────┘
                                      │
         ┌────────────────────────────┼────────────────────────────┐
         ▼                            ▼                            ▼
   ┌───────────┐                ┌───────────┐               ┌───────────────┐
   │   LOGIN   │                │ REGISTER  │               │ LUPA PASSWORD │
   └─────┬─────┘                └─────┬─────┘               └───────┬───────┘
         │                            │                             │
         ▼                            ▼                             ▼
┌──────────────────┐         ┌──────────────────┐          ┌──────────────────┐
│ Verifikasi Kredensial      │ Validasi NIS &   │          │ Input Email / WA │
│ Role Redirection │         │ Akun Dihubungkan │          │ Kirim Link Reset │
└────────┬─────────┘         └────────┬─────────┘          └────────┬─────────┘
         │                            │                             │
         ▼                            ▼                             ▼
┌──────────────────┐         ┌──────────────────┐          ┌──────────────────┐
│ Masuk Dashboard  │         │ Auto Login atau  │          │ Ganti Password   │
│ Sesuai Hak Akses │         │ Arahkan ke Login │          │ Berhasil Diupdate│
└──────────────────┘         └──────────────────┘          └──────────────────┘
```

### 5.1 Fitur Login
- **Field Form:** Email / Username / NIS dan Password, Checkbox "Ingat Saya" (*Remember Me*).
- **Tampilan UI:** Kartu putih terpusat (*centered card*) dengan aksen hijau-teal, logo sekolah/aplikasi BK, dan link bantuan ("Belum punya akun? Daftar" dan "Lupa Password?").
- **Validasi & Keamanan:** Pembatasan percobaan login (Rate Limiting/Throttling) dan enkripsi password `bcrypt`.

### 5.2 Fitur Registrasi (Khusus Siswa)
- **Tujuan:** Memungkinkan siswa mendaftarkan akun secara mandiri tanpa membebani admin untuk input akun satu per satu.
- **Field Form:**
  - Nomor Induk Siswa (NIS) — wajib sesuai dengan data master siswa yang diunggah Admin.
  - Nama Lengkap (otomatis terisi atau diverifikasi dengan data master).
  - Alamat Email aktif.
  - Nomor WhatsApp / HP.
  - Password & Konfirmasi Password.
- **Validasi:** Sistem mencocokkan NIS pada tabel `siswa`. Jika NIS belum terdaftar oleh admin atau sudah memiliki akun, sistem akan menampilkan notifikasi penolakan yang ramah.

### 5.3 Fitur Lupa Password & Reset Password
- **Tahap 1 (Request Reset):** User memasukkan alamat email terdaftar pada form "Lupa Password".
- **Tahap 2 (Kirim Tautan / Token):** Backend men-generate token unik reset password dan mengirimkan tautan reset ke email user (atau notifikasi instruksi via WhatsApp).
- **Tahap 3 (Form Reset Password):** User mengklik tautan, membuka halaman pembaruan password dengan field:
  - Password Baru.
  - Konfirmasi Password Baru.
- **Tahap 4 (Penyelesaian):** Setelah berhasil, password baru disimpan dan token kedaluwarsa seketika.

---

### 5.4 SISTEM SETELAHNYA (POST-AUTHENTICATION SYSTEM FLOW)

Sistem setelah proses autentikasi (Login, Register, Lupa Password) mengatur ke mana dan bagaimana user diproses:

#### A. Alur Sistem Setelah Login Berhasil:
1. **Pengecekan Status Akun:** Sistem memeriksa kolom `is_active` pada tabel `users`. Jika akun non-aktif, sistem menolak akses dan memunculkan pesan peringatan.
2. **Perekaman Session:** Sistem menyimpan data session login dan merekam waktu login terakhir.
3. **Pengalihan Berdasarkan Role (Role-Based Redirection):**
   - **Role `admin`:** Otomatis dialihkan ke `/admin/dashboard` (Statistik sekolah, grafik pelanggaran, total siswa).
   - **Role `guru_bk`:** Otomatis dialihkan ke `/guru-bk/dashboard` (Daftar siswa prioritas pembinaan, kasus aktif).
   - **Role `siswa`:** Otomatis dialihkan ke `/siswa/dashboard` (Kartu status poin pribadi, notifikasi pembinaan).
4. **Flash Notification:** Menampilkan toast alert hijau lembut: *"Selamat Datang, [Nama Pengguna]!"*.

#### B. Alur Sistem Setelah Registrasi Berhasil:
1. **Penghubungan Akun (Account Binding):** Sistem mengaitkan `user_id` baru ke baris `siswa` yang cocok dengan NIS.
2. **Penetapan Role Otomatis:** Akun baru secara default diberikan role `siswa` via Spatie Role.
3. **Pilihan Transisi Pasca-Register:**
   - **Opsi A (Direct Dashboard):** User langsung diautentikasi dan dialihkan ke `/siswa/dashboard` dengan panduan perkenalan sistem (*onboarding tour*).
   - **Opsi B (Redirect to Login):** User diarahkan kembali ke `/login` disertai pesan sukses: *"Registrasi berhasil! Silakan masuk menggunakan akun baru Anda."*

#### C. Alur Sistem Setelah Lupa Password (Reset Selesai):
1. **Invalidasi Sesi Lama:** Semua token reset yang telah dipakai langsung dihapus agar tidak dapat digunakan kembali.
2. **Pengalihan ke Login:** User dialihkan secara otomatis ke halaman `/login`.
3. **Flash Notification:** Pesan sukses berwarna hijau: *"Password berhasil diperbarui! Silakan masuk dengan password baru Anda."*

#### D. Sistem Proteksi Akses & Middleware:
1. **Guest Middleware (`guest`):** Mencegah pengguna yang sudah login untuk mengakses kembali halaman `/login`, `/register`, atau `/forgot-password` (langsung diarahkan ke dashboard masing-masing).
2. **Auth Middleware (`auth`):** Memblokir akses ke halaman dalam bagi pengunjung anonim dan mengarahkan mereka ke `/login`.
3. **Role Permission Guard:** Menjaga isolasi antar-role; siswa dilarang keras membuka rute `/admin/*` atau `/guru-bk/*` (akan memunculkan halaman error 403 Forbidden bertema hijau mint dan putih).
4. **Alur Logout (Keluar Sistem):** Menghancurkan session, menghapus cookie remember-token, dan mengarahkan kembali ke `/login`.

---

## 6. FITUR UTAMA & SPESIFIKASI MODUL OPERASIONAL

### 6.1 Modul Master Data (Admin)
- **Data Siswa:** Manajemen NIS, nama, kelas, jenis kelamin, nomor HP orang tua/wali, total poin, dan status disiplin.
- **Data Guru:** Manajemen NIP, nama guru, bidang tugas (Guru BK / Wali Kelas).
- **Data Kelas:** Manajemen tingkatan kelas, jurusan/rombel, tahun ajaran, dan penugasan wali kelas.
- **Tata Tertib & Kategori Pelanggaran:** Pengaturan pasal aturan, kategori (Ringan, Sedang, Berat), serta default bobot poin.

### 6.2 Modul Pelanggaran Siswa (Admin & Guru BK)
- **Input Pelanggaran Cepat:**
  - Pilih Kelas ➔ Pilih Siswa.
  - Pilih Jenis Pelanggaran (Poin otomatis terisi).
  - Input Tanggal, Catatan Keterangan, dan Unggah Bukti Foto (opsional).
- **Snapshot Poin:** Poin tercatat sebagai snapshot historis (tidak berubah jika master poin diedit di kemudian hari).
- **Kalkulasi Otomatis:** Sistem backend secara otomatis menjumlahkan `total_poin` dan memperbarui `status_disiplin` siswa.

### 6.3 Modul Integrasi Notifikasi WhatsApp (Sidobe API)
- **Trigger Otomatis:** Saat pelanggaran berhasil disimpan, sistem memasukkan job ke antrean (*queue*).
- **Pesan WhatsApp Informatif:**
  ```text
  Yth. Orang Tua/Wali dari [Nama Siswa] (Kelas [Kelas]),
  Kami menginformasikan bahwa siswa ybs telah melakukan pelanggaran tata tertib:
  - Pelanggaran: [Nama Pelanggaran]
  - Poin: [Poin]
  - Tanggal: [Tanggal]
  - Akumulasi Poin Saat Ini: [Total Poin] ([Status Disiplin])
  Mohon kerja sama Bapak/Ibu untuk memberikan pembinaan di rumah. Terima kasih.
  - BK [Nama Sekolah]
  ```
- **Log Pengiriman WA:** Menampilkan status pengiriman (Pending, Terkirim, Gagal) pada tabel log notifikasi.

### 6.4 Modul Pembinaan & Tindak Lanjut (Guru BK)
- **Daftar Pantau Siswa Prioritas:** Menampilkan daftar siswa dengan status Pembinaan dan Kritis.
- **Pencatatan Sesi Bimbingan:** Form jadwal bimbingan konseling, jenis konseling (pribadi/panggilan orang tua), catatan konselor.
- **Tindak Lanjut Kasus:** Dokumentasi surat perjanjian, sanksi edukatif, atau penutupan kasus bimbingan.

### 6.5 Modul Portal Siswa
- **Kartu Identitas & Status Disiplin:** Menampilkan kartu profil elegan dengan warna status disiplin dan total poin akumulasi.
- **Tabel Riwayat Pelanggaran:** Daftar tanggal, pelanggaran yang dilakukan, dan poin yang diterima.
- **Informasi Jadwal Bimbingan:** Notifikasi jika ada jadwal temu konseling dengan Guru BK.

### 6.6 Modul Rekap & Laporan
- Filter laporan berdasarkan rentang tanggal, tingkatan kelas, atau kategori pelanggaran.
- Fitur Ekspor ke format **PDF** dan **Excel** untuk arsip sekolah dan rapat evaluasi kedisiplinan.

---

## 7. STRUKTUR HALAMAN FRONTEND (SITEMAP LENGKAP)

```
├── / (Root)                       -> Redirect otomatis ke /login jika belum auth
│
├── AUTHENTICATION PAGES (Tema: Mint Green & White Card)
│   ├── /login                     -> Halaman Masuk Akun
│   ├── /register                  -> Halaman Pendaftaran Akun Siswa (Validasi NIS)
│   ├── /forgot-password           -> Form Permintaan Reset Password
│   ├── /reset-password/{token}    -> Form Input Password Baru
│   └── /logout                    -> Endpoint Keluar Sesi
│
├── /admin/
│   ├── dashboard                  -> Statistik pelanggaran, ringkasan siswa, chart
│   ├── siswa                      -> CRUD Siswa + Import Excel
│   ├── guru                       -> CRUD Master Guru & Penugasan
│   ├── kelas                      -> CRUD Data Kelas & Wali Kelas
│   ├── tata-tertib                -> Aturan & Bobot Poin Pelanggaran
│   ├── pelanggaran                -> Riwayat & Input Pelanggaran Global
│   ├── notifikasi-wa              -> Log status pengiriman WhatsApp Sidobe
│   └── laporan                    -> Filter & Cetak Laporan (PDF/Excel)
│
├── /guru-bk/
│   ├── dashboard                  -> Monitoring siswa berisiko (Kritis/Pembinaan)
│   ├── pelanggaran/create         -> Form Cepat Catat Pelanggaran Siswa
│   ├── pembinaan                  -> Manajemen sesi konseling & panggilan ortu
│   ├── tindak-lanjut              -> Catatan penanganan & hasil konseling
│   └── riwayat-siswa/{id}         -> Kartu rekam jejak kedisiplinan per siswa
│
└── /siswa/
    ├── dashboard                  -> Kartu Poin Saya, Status Disiplin, & Riwayat Terkini
    └── riwayat                    -> Detail daftar pelanggaran & catatan pembinaan
```

---

## 8. RENCANA IMPLEMENTASI FRONTEND (FARREL)

1. **Sprint 1: Modul Autentikasi & Desain Sistem (Mint Green & White)**
   - Setup styling Tailwind CSS dengan palet hijau mint, hijau-teal, dan putih.
   - Merancang halaman `login.blade.php`, `register.blade.php`, `forgot-password.blade.php`, dan `reset-password.blade.php`.
   - Menguji alur perpindahan pasca-login (redirection ke dashboard Admin, Guru BK, dan Siswa).

2. **Sprint 2: Master Layout & Komponen Global**
   - Pembuatan layout induk `app.blade.php` (Sidebar collapsible deep teal/putih, Topbar bersih, Card Container).
   - Komponen badge status kedisiplinan (Aman, Perhatian, Pembinaan, Kritis) dan flash toast notifikasi.

3. **Sprint 3: Halaman Master Data & Form Pelanggaran**
   - Menghubungkan tampilan tabel Master Data Siswa, Kelas, dan Tata Tertib.
   - Merancang form Input Pelanggaran Siswa yang intuitif dan responsif dengan preview poin otomatis.

4. **Sprint 4: Dashboard, Pembinaan & Laporan**
   - Membangun antarmuka Dashboard masing-masing role.
   - Merancang antarmuka sesi pembinaan dan log tindak lanjut untuk Guru BK.
   - Merancang tampilan layout cetak laporan PDF yang rapi dan siap cetak.

---

## 9. KEBUTUHAN NON-FUNGSIONAL
- **Responsif:** Dapat diakses dengan nyaman melalui desktop, laptop, maupun tablet/smartphone.
- **Kemudahan Penggunaan (User Friendly):** Desain bersih (*clean space*), teks mudah dibaca oleh guru maupun siswa, serta instruksi formulir yang jelas.
- **Keamanan Data:** Informasi catatan konseling bimbingan siswa bersifat rahasia dan hanya dapat diakses oleh Guru BK dan Admin.
- **Proteksi Session:** Token sesi otomatis diperbarui dan kedaluwarsa setelah periode tidak aktif untuk mencegah pembajakan sesi.
