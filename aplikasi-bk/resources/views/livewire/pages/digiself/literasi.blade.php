<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public $activeTab = 'kritis'; // 'kritis', 'keamanan', 'etika', 'tanggungjawab', 'kuis'

    // Kuis Interaktif State
    public $q1 = null;
    public $q2 = null;
    public $q3 = null;
    public $score = null;
    public $feedback = null;

    public function submitQuiz()
    {
        $this->validate([
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
        ], [
            'q1.required' => 'Silakan jawab Pertanyaan 1.',
            'q2.required' => 'Silakan jawab Pertanyaan 2.',
            'q3.required' => 'Silakan jawab Pertanyaan 3.',
        ]);

        $currentScore = 0;
        if ($this->q1 === 'c') $currentScore += 33;
        if ($this->q2 === 'b') $currentScore += 33;
        if ($this->q3 === 'a') $currentScore += 34;

        $this->score = $currentScore;
        if ($this->score >= 80) {
            $this->feedback = 'Luar biasa! Anda memahami prinsip literasi digital kritis, aman, dan beretika.';
        } else {
            $this->feedback = 'Bagus! Cermati kembali poin penting pada materi evaluasi sumber dan keamanan akun untuk memperkuat pemahaman Anda.';
        }
    }
}; ?>

<div class="space-y-6">
    <!-- Header Navigasi Submodul -->
    <x-digiself-header 
        active="literasi" 
        title="Literasi Digital" 
        description="Menyajikan materi psikoedukasi mengenai evaluasi informasi, keamanan data, privasi, dan etika berteknologi." 
    />

    <!-- Tab Selector 4 Dimensi + Kuis -->
    <div class="flex overflow-x-auto pb-1 gap-2 border-b border-purple-100">
        <button wire:click="$set('activeTab', 'kritis')" 
                class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition flex items-center gap-2 {{ $activeTab === 'kritis' ? 'bg-purple-700 text-white shadow-md shadow-purple-700/20' : 'bg-white text-gray-600 hover:text-purple-700 hover:bg-purple-50' }}">
            <span class="w-2 h-2 rounded-full {{ $activeTab === 'kritis' ? 'bg-white' : 'bg-purple-500' }}"></span>
            1. Berpikir Kritis & Evaluasi
        </button>
        <button wire:click="$set('activeTab', 'keamanan')" 
                class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition flex items-center gap-2 {{ $activeTab === 'keamanan' ? 'bg-purple-700 text-white shadow-md shadow-purple-700/20' : 'bg-white text-gray-600 hover:text-purple-700 hover:bg-purple-50' }}">
            <span class="w-2 h-2 rounded-full {{ $activeTab === 'keamanan' ? 'bg-white' : 'bg-purple-500' }}"></span>
            2. Keamanan & Privasi
        </button>
        <button wire:click="$set('activeTab', 'etika')" 
                class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition flex items-center gap-2 {{ $activeTab === 'etika' ? 'bg-purple-700 text-white shadow-md shadow-purple-700/20' : 'bg-white text-gray-600 hover:text-purple-700 hover:bg-purple-50' }}">
            <span class="w-2 h-2 rounded-full {{ $activeTab === 'etika' ? 'bg-white' : 'bg-purple-500' }}"></span>
            3. Etika Komunikasi Digital
        </button>
        <button wire:click="$set('activeTab', 'tanggungjawab')" 
                class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition flex items-center gap-2 {{ $activeTab === 'tanggungjawab' ? 'bg-purple-700 text-white shadow-md shadow-purple-700/20' : 'bg-white text-gray-600 hover:text-purple-700 hover:bg-purple-50' }}">
            <span class="w-2 h-2 rounded-full {{ $activeTab === 'tanggungjawab' ? 'bg-white' : 'bg-purple-500' }}"></span>
            4. Penggunaan Bertanggung Jawab
        </button>
        <button wire:click="$set('activeTab', 'kuis')" 
                class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition flex items-center gap-2 {{ $activeTab === 'kuis' ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Uji Pemahaman (Kuis)
        </button>
    </div>

    <!-- Konten Tab 1: Keterampilan Informasi dan Berpikir Kritis -->
    @if ($activeTab === 'kritis')
        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">1</div>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">Keterampilan Informasi dan Berpikir Kritis</h3>
                    <p class="text-xs text-gray-500">Mengevaluasi kredibilitas sumber informasi dan menghindari bias serta manipulasi konten digital.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <div class="p-5 rounded-2xl bg-purple-50/60 border border-purple-100 space-y-3">
                    <h4 class="font-bold text-sm text-purple-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Prinsip Evaluasi Sumber (Metode CRAAP)
                    </h4>
                    <ul class="text-xs text-gray-700 space-y-2">
                        <li><strong>Currency (Kekinian):</strong> Kapan informasi tersebut diterbitkan? Apakah masih relevan dengan situasi saat ini?</li>
                        <li><strong>Relevance (Relevansi):</strong> Apakah data tersebut sesuai dengan kebutuhan tugas atau kajian Anda?</li>
                        <li><strong>Authority (Otoritas):</strong> Siapa penulis atau lembaga penerbitnya? Apakah memiliki kredibilitas keilmuan yang jelas?</li>
                        <li><strong>Accuracy (Akurasi):</strong> Apakah klaim didukung data empiris, referensi ilmiah, atau bukti yang dapat diverifikasi?</li>
                        <li><strong>Purpose (Tujuan):</strong> Apakah informasi dibuat untuk mengedukasi, menjual produk, atau menyebarkan opini bias?</li>
                    </ul>
                </div>

                <div class="p-5 rounded-2xl bg-indigo-50/60 border border-indigo-100 space-y-3">
                    <h4 class="font-bold text-sm text-indigo-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Waspada Clickbait & Berita Palsu (Hoax)
                    </h4>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Ciri-ciri informasi yang patut dicurigai: judul bernada provokatif ("Wajib Tahu!", "Heboh!"), penggunaan huruf kapital berlebih, tanda seru ganda, tidak mencantumkan tanggal rilis, dan tautan mengarah ke domain asing yang tidak resmi (misal: <em>.xyz, .top, blogspot gratisan</em>).
                    </p>
                    <div class="p-3 bg-white rounded-xl border border-indigo-100 text-xs text-indigo-800">
                        <strong>Langkah Praktis:</strong> Selalu cek silang minimal ke 2 sumber media berita terverifikasi Dewan Pers atau jurnal bereputasi sebelum membagikan link.
                    </div>
                </div>
            </div>

            <!-- Studi Kasus Sesuai Proposal -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-purple-700">Contoh Kasus Nyata Mahasiswa</span>
                <h5 class="text-sm font-bold text-gray-900">Pemberitahuan Dana Bantuan Kuliah di Grup Pesan</h5>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Siswa/mahasiswa menerima broadcast pesan WhatsApp yang menyatakan adanya bantuan dana UKT Rp 2.500.000 dengan tautan Google Form mencurigakan yang meminta nomor induk, kata sandi email, dan foto KTP. 
                    Tindakan literasi kritis: <strong>Jangan mengisi data!</strong> Segera cek pengumuman resmi di portal kampus / website kementerian resmi, dan laporkan pesan sebagai penipuan.
                </p>
            </div>
        </div>
    @endif

    <!-- Konten Tab 2: Keamanan dan Privasi Digital -->
    @if ($activeTab === 'keamanan')
        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">2</div>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">Keamanan dan Privasi Digital</h3>
                    <p class="text-xs text-gray-500">Membangun kebiasaan proteksi akun, izin aplikasi perangkat, dan pencegahan pencurian identitas.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                <div class="p-5 rounded-2xl bg-white border border-purple-100 shadow-xs space-y-2.5">
                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">A</div>
                    <h4 class="font-bold text-sm text-gray-900">Kekuatan Kata Sandi</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Gunakan kombinasi minimal 12 karakter yang memuat huruf besar, huruf kecil, angka, dan simbol unik. Hindari tanggal lahir atau nama diri. Jangan pernah gunakan kata sandi yang sama di akun bank/kampus dan media sosial.
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-purple-100 shadow-xs space-y-2.5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">B</div>
                    <h4 class="font-bold text-sm text-gray-900">Autentikasi 2 Faktor (2FA)</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Aktifkan verifikasi dua langkah (2FA) melalui aplikasi authenticator (Google/Microsoft Authenticator) pada email utama, WhatsApp, dan portal akademik. 2FA melindungi akun meski sandi Anda bocor.
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-purple-100 shadow-xs space-y-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">C</div>
                    <h4 class="font-bold text-sm text-gray-900">Audit Izin Aplikasi</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Periksa menu Pengaturan > Privasi di ponsel Anda. Matikan akses lokasi, kontak, mikrofon, dan galeri untuk aplikasi game atau utilitas yang tidak memerlukannya saat berjalan di latar belakang.
                    </p>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-900 flex items-start gap-3">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <strong>Peringatan Oversharing:</strong> Jangan mengunggah foto boarding pass, kartu identitas pelajar/KTP, dokumen nilai, atau jadwal kegiatan harian real-time di Instagram Story / TikTok publik karena dapat dimanfaatkan untuk kejahatan rekayasa sosial (social engineering).
                </div>
            </div>
        </div>
    @endif

    <!-- Konten Tab 3: Etika Komunikasi Digital -->
    @if ($activeTab === 'etika')
        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">3</div>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">Etika Komunikasi Digital (Netiquette)</h3>
                    <p class="text-xs text-gray-500">Membangun budaya komunikasi santun, menghargai privasi sesama, serta mencegah cyberbullying.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <div class="p-5 rounded-2xl bg-purple-50/60 border border-purple-100 space-y-2">
                    <h4 class="font-bold text-sm text-purple-900">Etika Menghubungi Dosen / Guru BK</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Saat mengirim pesan chat kepada pendidik, selalu gunakan format baku:
                    </p>
                    <ol class="text-xs text-gray-700 list-decimal pl-4 space-y-1">
                        <li>Ucapkan salam pembuka yang santun (Selamat pagi/siang atau Assalamu'alaikum).</li>
                        <li>Sampaikan permohonan maaf mengganggu waktu beliau.</li>
                        <li>Perkenalkan identitas lengkap (Nama, Kelas/NIM, Program Studi).</li>
                        <li>Tuliskan maksud dan tujuan pesan secara ringkas dan jelas.</li>
                        <li>Ucapkan terima kasih dan salam penutup.</li>
                        <li>Perhatikan jam kerja wajar (hindari larut malam atau akhir pekan kecuali darurat).</li>
                    </ol>
                </div>

                <div class="p-5 rounded-2xl bg-emerald-50/60 border border-emerald-100 space-y-2">
                    <h4 class="font-bold text-sm text-emerald-900">Anti Cyberbullying & Hak Cipta</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Hindari menyebarkan foto atau rahasia orang lain tanpa izin tertulis (doxxing). Berikan komentar yang membangun tanpa mencela fisik atau suku/agama.
                    </p>
                    <div class="p-3 bg-white rounded-xl border border-emerald-100 text-xs text-emerald-800">
                        <strong>Integritas Akademik:</strong> Saat menggunakan materi visual, kutipan, atau data digital dalam tugas, selalu sertakan sumber rujukan dan hindari plagiarisme.
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Konten Tab 4: Penggunaan Teknologi Bertanggung Jawab -->
    @if ($activeTab === 'tanggungjawab')
        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">4</div>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">Penggunaan Teknologi Secara Bertanggung Jawab</h3>
                    <p class="text-xs text-gray-500">Menyeimbangkan aktivitas digital dengan kesehatan fisik, mental, dan produktivitas akademik.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                <div class="p-5 rounded-2xl bg-white border border-gray-100 shadow-xs space-y-2">
                    <h4 class="font-bold text-sm text-gray-900">Mindful Screen Time</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Sadar akan tujuan saat menyentuh gawai. Tanyakan pada diri sendiri: <em>"Apakah saya membuka HP karena butuh, atau hanya refleks bosan?"</em>
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-gray-100 shadow-xs space-y-2">
                    <h4 class="font-bold text-sm text-gray-900">Aturan 20-20-20</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Setiap 20 menit menatap layar, alihkan pandangan ke objek sejauh 20 kaki (sekitar 6 meter) selama minimal 20 detik untuk mencegah ketegangan mata dan sakit kepala.
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-gray-100 shadow-xs space-y-2">
                    <h4 class="font-bold text-sm text-gray-900">Digital Detox Pra-Tidur</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Hentikan penggunaan layar gawai minimal 45 menit sebelum tidur. Cahaya biru (blue light) menekan hormon melatonin yang mengganggu siklus tidur nyenyak.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Konten Tab 5: Kuis Pemahaman Literasi Digital -->
    @if ($activeTab === 'kuis')
        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Lampiran 3 Proposal</span>
                    <h3 class="text-xl font-extrabold text-gray-900">Uji Pemahaman Literasi Digital</h3>
                    <p class="text-xs text-gray-500">Jawab 3 pertanyaan studi kasus untuk menguji kepekaan literasi digital Anda.</p>
                </div>
            </div>

            @if ($score !== null)
                <div class="p-5 rounded-2xl {{ $score >= 80 ? 'bg-emerald-50 border border-emerald-200 text-emerald-900' : 'bg-amber-50 border border-amber-200 text-amber-900' }} space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider">Hasil Evaluasi Pemahaman</span>
                        <span class="text-2xl font-black">{{ $score }}/100</span>
                    </div>
                    <p class="text-xs leading-relaxed">{{ $feedback }}</p>
                    <button wire:click="$set('score', null)" class="text-xs font-bold underline mt-2 inline-block">
                        Ulangi Kuis
                    </button>
                </div>
            @endif

            <form wire:submit.prevent="submitQuiz" class="space-y-6">
                <!-- Soal 1 -->
                <div class="p-5 rounded-2xl bg-purple-50/40 border border-purple-100 space-y-3">
                    <p class="text-xs font-bold text-gray-800">1. Anda menemukan artikel berita heboh tentang penerimaan beasiswa dengan judul serba kapital dan link formbit.ly. Tindakan literasi kritis terbaik adalah:</p>
                    <div class="space-y-2 text-xs">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="q1" value="a" class="text-purple-600 focus:ring-purple-500">
                            <span>A. Langsung membagikan ke grup kelas agar teman-teman tidak ketinggalan informasi.</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="q1" value="b" class="text-purple-600 focus:ring-purple-500">
                            <span>B. Mengisi form pendaftaran dengan email dan password akun pribadi Anda.</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="q1" value="c" class="text-purple-600 focus:ring-purple-500">
                            <span>C. Menahan diri, tidak membagikan, lalu memeriksa kebenaran informasi di laman resmi universitas/lembaga terkait.</span>
                        </label>
                    </div>
                    @error('q1') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Soal 2 -->
                <div class="p-5 rounded-2xl bg-purple-50/40 border border-purple-100 space-y-3">
                    <p class="text-xs font-bold text-gray-800">2. Cara paling efektif untuk mengamankan akun portal akademik dan email dari risiko peretasan adalah:</p>
                    <div class="space-y-2 text-xs">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="q2" value="a" class="text-purple-600 focus:ring-purple-500">
                            <span>A. Menggunakan tanggal lahir sendiri agar selalu mudah diingat.</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="q2" value="b" class="text-purple-600 focus:ring-purple-500">
                            <span>B. Mengaktifkan Autentikasi Dua Faktor (2FA) dan menggunakan kombinasi kata sandi unik.</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="q2" value="c" class="text-purple-600 focus:ring-purple-500">
                            <span>C. Menyimpan password di bio catatan profil media sosial.</span>
                        </label>
                    </div>
                    @error('q2') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Soal 3 -->
                <div class="p-5 rounded-2xl bg-purple-50/40 border border-purple-100 space-y-3">
                    <p class="text-xs font-bold text-gray-800">3. Menghubungi Dosen Pembimbing atau Guru BK melalui aplikasi chat sebaiknya dilakukan dengan:</p>
                    <div class="space-y-2 text-xs">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="q3" value="a" class="text-purple-600 focus:ring-purple-500">
                            <span>A. Salam santun, memperkenalkan nama & kelas/NIM, maksud pesan yang jelas, pada jam kerja yang wajar.</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="q3" value="b" class="text-purple-600 focus:ring-purple-500">
                            <span>B. Mengirim pesan singkat "P" berulang kali di tengah malam agar segera dibaca.</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="q3" value="c" class="text-purple-600 focus:ring-purple-500">
                            <span>C. Langsung menelepon berkali-kali tanpa mengirim perkenalan terlebih dahulu.</span>
                        </label>
                    </div>
                    @error('q3') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                        Periksa Jawaban Kuis
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
