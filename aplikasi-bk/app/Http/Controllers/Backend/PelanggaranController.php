<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Services\PelanggaranService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PelanggaranController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Pelanggaran::class);

        $query = Pelanggaran::query()
            ->with(['siswa.kelas', 'jenisPelanggaran', 'pelapor:id,name'])
            ->latest('tanggal');

        if ($request->user()->hasRole('siswa')) {
            $query->whereHas('siswa', fn ($query) => $query->where('user_id', $request->user()->id));
        } elseif ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->integer('siswa_id'));
        }

        return response()->json($query->paginate(25));
    }

    public function show(Pelanggaran $pelanggaran): JsonResponse
    {
        Gate::authorize('view', $pelanggaran);

        return response()->json($pelanggaran->load([
            'siswa.kelas',
            'jenisPelanggaran.tataTertib',
            'pelapor:id,name',
            'pembinaan.tindakLanjut',
        ]));
    }

    public function store(Request $request, PelanggaranService $service): JsonResponse
    {
        Gate::authorize('create', Pelanggaran::class);

        $data = $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'jenis_pelanggaran_id' => ['required', 'integer', 'exists:jenis_pelanggaran,id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
            'bukti_foto' => ['nullable', 'string', 'max:255'],
        ]);
        $data['pelapor_id'] = $request->user()->id;

        $pelanggaran = $service->buat($data);

        return response()->json($pelanggaran->load([
            'siswa.kelas',
            'jenisPelanggaran',
            'pelapor:id,name',
        ]), 201);
    }

    public function update(Request $request, Pelanggaran $pelanggaran, PelanggaranService $service): JsonResponse
    {
        Gate::authorize('update', $pelanggaran);

        $data = $request->validate([
            'tanggal' => ['sometimes', 'date'],
            'poin' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            'keterangan' => ['sometimes', 'nullable', 'string'],
            'bukti_foto' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);
        $pelanggaran = $service->perbarui($pelanggaran, $data);

        return response()->json($pelanggaran->load([
            'siswa.kelas',
            'jenisPelanggaran',
            'pelapor:id,name',
        ]));
    }

    public function destroy(Pelanggaran $pelanggaran, PelanggaranService $service): JsonResponse
    {
        Gate::authorize('delete', $pelanggaran);
        $service->hapus($pelanggaran);

        return response()->json(null, 204);
    }
}
