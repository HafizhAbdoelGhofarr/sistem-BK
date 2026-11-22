<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pelanggaran Siswa</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #172033; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .meta { color: #475569; margin-bottom: 14px; }
        .summary { margin: 10px 0 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 5px; text-align: left; }
        th { background: #e2e8f0; }
        .number { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Pelanggaran Siswa</h1>
    <div class="meta">Dibuat pada {{ now()->format('d-m-Y H:i') }}</div>
    <div class="summary">
        Jumlah catatan: {{ $jumlah }} |
        Total poin: {{ $totalPoin }} |
        Periode: {{ $filters['tanggal_mulai'] ?? 'Semua' }} s.d. {{ $filters['tanggal_selesai'] ?? 'Semua' }}
    </div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>NIS</th>
                <th>Nama siswa</th>
                <th>Kelas</th>
                <th>Jenis pelanggaran</th>
                <th>Kategori</th>
                <th class="number">Poin</th>
                <th>Pelapor</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr>
                    <td>{{ $record->tanggal->format('d-m-Y') }}</td>
                    <td>{{ $record->siswa->nis }}</td>
                    <td>{{ $record->siswa->nama }}</td>
                    <td>{{ $record->siswa->kelas->nama }}</td>
                    <td>{{ $record->jenisPelanggaran->nama }}</td>
                    <td>{{ ucfirst($record->jenisPelanggaran->kategori) }}</td>
                    <td class="number">{{ $record->poin }}</td>
                    <td>{{ $record->pelapor->name }}</td>
                    <td>{{ ucfirst($record->status) }}</td>
                    <td>{{ $record->keterangan }}</td>
                </tr>
            @empty
                <tr><td colspan="10">Tidak ada data untuk filter yang dipilih.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
