<?php

namespace App\Http\Controllers\Backend;

use App\Exports\LaporanPelanggaranExport;
use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Services\LaporanPelanggaranService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class LaporanPelanggaranController extends Controller
{
    public function pdf(Request $request, LaporanPelanggaranService $laporan): Response
    {
        Gate::authorize('exportReport', Pelanggaran::class);
        $filters = $this->validateFilters($request);
        $query = $laporan->query($filters);
        $jumlah = (clone $query)->count();

        if ($jumlah > 5000) {
            throw ValidationException::withMessages([
                'tanggal_mulai' => 'Laporan PDF maksimal 5.000 baris. Persempit rentang tanggal atau pilih kelas.',
            ]);
        }

        $records = $query->get();

        return Pdf::loadView('reports.pelanggaran', [
            'records' => $records,
            'filters' => $filters,
            'jumlah' => $jumlah,
            'totalPoin' => $records->sum('poin'),
        ])->setPaper('a4', 'landscape')->download('laporan-pelanggaran.pdf');
    }

    public function excel(Request $request): BinaryFileResponse
    {
        Gate::authorize('exportReport', Pelanggaran::class);
        $filters = $this->validateFilters($request);

        return Excel::download(new LaporanPelanggaranExport($filters), 'laporan-pelanggaran.xlsx');
    }

    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'tanggal_mulai' => ['sometimes', 'date'],
            'tanggal_selesai' => ['sometimes', 'date', 'after_or_equal:tanggal_mulai'],
            'kelas_id' => ['sometimes', 'integer', 'exists:kelas,id'],
            'status' => ['sometimes', 'in:baru,dibina,selesai'],
        ]);
    }
}
