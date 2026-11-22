<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\JenisPelanggaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class JenisPelanggaranController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', JenisPelanggaran::class);

        $query = JenisPelanggaran::query()->with('tataTertib')->orderBy('kode');
        if (! $request->boolean('semua')) {
            $query->where('is_active', true);
        }

        return response()->json($query->paginate(25));
    }

    public function show(JenisPelanggaran $jenisPelanggaran): JsonResponse
    {
        Gate::authorize('view', $jenisPelanggaran);

        return response()->json($jenisPelanggaran->load('tataTertib'));
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', JenisPelanggaran::class);

        $data = $request->validate([
            'tata_tertib_id' => ['required', 'integer', 'exists:tata_tertib,id'],
            'kode' => ['required', 'string', 'max:20', 'unique:jenis_pelanggaran,kode'],
            'nama' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'in:ringan,sedang,berat'],
            'poin' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $data['is_active'] ??= true;

        return response()->json(JenisPelanggaran::create($data)->load('tataTertib'), 201);
    }

    public function update(Request $request, JenisPelanggaran $jenisPelanggaran): JsonResponse
    {
        Gate::authorize('update', $jenisPelanggaran);

        $data = $request->validate([
            'tata_tertib_id' => ['sometimes', 'integer', 'exists:tata_tertib,id'],
            'kode' => ['sometimes', 'string', 'max:20', 'unique:jenis_pelanggaran,kode,'.$jenisPelanggaran->id],
            'nama' => ['sometimes', 'string', 'max:150'],
            'kategori' => ['sometimes', 'in:ringan,sedang,berat'],
            'poin' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $jenisPelanggaran->update($data);

        return response()->json($jenisPelanggaran->refresh()->load('tataTertib'));
    }

    public function destroy(JenisPelanggaran $jenisPelanggaran): JsonResponse
    {
        Gate::authorize('delete', $jenisPelanggaran);

        if ($jenisPelanggaran->pelanggaran()->withTrashed()->exists()) {
            throw ValidationException::withMessages([
                'jenis_pelanggaran' => 'Jenis pelanggaran yang sudah tercatat tidak dapat dihapus; nonaktifkan saja.',
            ]);
        }

        $jenisPelanggaran->delete();

        return response()->json(null, 204);
    }
}
