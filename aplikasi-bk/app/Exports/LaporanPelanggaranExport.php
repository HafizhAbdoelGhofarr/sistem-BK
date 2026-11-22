<?php

namespace App\Exports;

use App\Models\Pelanggaran;
use App\Services\LaporanPelanggaranService;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class LaporanPelanggaranExport extends DefaultValueBinder implements FromQuery, ShouldAutoSize, WithCustomValueBinder, WithHeadings, WithMapping, WithStrictNullComparison
{
    public function __construct(private readonly array $filters) {}

    public function query(): Builder
    {
        return app(LaporanPelanggaranService::class)->query($this->filters);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'NIS',
            'Nama siswa',
            'Kelas',
            'Kode pelanggaran',
            'Jenis pelanggaran',
            'Kategori',
            'Poin',
            'Pelapor',
            'Status',
            'Keterangan',
        ];
    }

    public function map($pelanggaran): array
    {
        /** @var Pelanggaran $pelanggaran */
        return [
            $pelanggaran->tanggal->format('Y-m-d'),
            $pelanggaran->siswa->nis,
            $pelanggaran->siswa->nama,
            $pelanggaran->siswa->kelas->nama,
            $pelanggaran->jenisPelanggaran->kode,
            $pelanggaran->jenisPelanggaran->nama,
            $pelanggaran->jenisPelanggaran->kategori,
            $pelanggaran->poin,
            $pelanggaran->pelapor->name,
            $pelanggaran->status,
            $pelanggaran->keterangan,
        ];
    }

    public function bindValue(Cell $cell, mixed $value): bool
    {
        if (is_string($value) && preg_match('/^[=+\-@]/', $value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
