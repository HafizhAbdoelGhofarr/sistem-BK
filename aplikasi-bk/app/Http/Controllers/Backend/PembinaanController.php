<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pembinaan;
use App\Services\PembinaanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PembinaanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Pembinaan::class);

        $query = Pembinaan::query()
            ->with(['siswa.kelas', 'pelanggaran', 'guruBk:id,name', 'tindakLanjut.pembuat:id,name'])
            ->latest('tanggal');

        if ($request->user()->hasRole('siswa')) {
            $query->whereHas('siswa', fn ($query) => $query->where('user_id', $request->user()->id));
        } elseif ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->integer('siswa_id'));
        }

        return response()->json($query->paginate(25));
    }

    public function show(Pembinaan $pembinaan): JsonResponse
    {
        Gate::authorize('view', $pembinaan);

        return response()->json($pembinaan->load([
            'siswa.kelas',
            'pelanggaran.jenisPelanggaran',
            'guruBk:id,name',
            'tindakLanjut.pembuat:id,name',
        ]));
    }

    public function store(Request $request, PembinaanService $service): JsonResponse
    {
        Gate::authorize('create', Pembinaan::class);

        $data = $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'pelanggaran_id' => ['nullable', 'integer', 'exists:pelanggaran,id'],
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', 'string', 'max:50'],
            'catatan' => ['nullable', 'string'],
        ]);

        $pembinaan = $service->buat($data, $request->user());

        return response()->json($pembinaan->load([
            'siswa.kelas',
            'pelanggaran',
            'guruBk:id,name',
        ]), 201);
    }

    public function update(Request $request, Pembinaan $pembinaan, PembinaanService $service): JsonResponse
    {
        Gate::authorize('update', $pembinaan);

        $data = $request->validate([
            'tanggal' => ['sometimes', 'date'],
            'jenis' => ['sometimes', 'string', 'max:50'],
            'catatan' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'in:dijadwalkan,berlangsung,selesai'],
        ]);

        $pembinaan = $service->perbarui($pembinaan, $data);

        return response()->json($pembinaan->load(['siswa.kelas', 'pelanggaran', 'guruBk']));
    }

    public function storeTindakLanjut(Request $request, Pembinaan $pembinaan, PembinaanService $service): JsonResponse
    {
        Gate::authorize('update', $pembinaan);

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'deskripsi' => ['required', 'string'],
            'hasil' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:proses,selesai'],
        ]);

        $tindakLanjut = $service->tambahTindakLanjut($pembinaan, $request->user(), $data);

        return response()->json($tindakLanjut->load('pembuat:id,name'), 201);
    }
}
