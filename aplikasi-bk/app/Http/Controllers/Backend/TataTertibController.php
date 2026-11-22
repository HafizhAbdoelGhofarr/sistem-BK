<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TataTertib;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class TataTertibController extends Controller
{
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', TataTertib::class);

        return response()->json(TataTertib::query()->withCount('jenisPelanggaran')->orderBy('kode')->paginate(25));
    }

    public function show(TataTertib $tataTertib): JsonResponse
    {
        Gate::authorize('view', $tataTertib);

        return response()->json($tataTertib->load('jenisPelanggaran'));
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', TataTertib::class);

        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:tata_tertib,kode'],
            'nama' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        return response()->json(TataTertib::create($data), 201);
    }

    public function update(Request $request, TataTertib $tataTertib): JsonResponse
    {
        Gate::authorize('update', $tataTertib);

        $data = $request->validate([
            'kode' => ['sometimes', 'string', 'max:20', 'unique:tata_tertib,kode,'.$tataTertib->id],
            'nama' => ['sometimes', 'string', 'max:150'],
            'deskripsi' => ['sometimes', 'nullable', 'string'],
        ]);
        $tataTertib->update($data);

        return response()->json($tataTertib->refresh());
    }

    public function destroy(TataTertib $tataTertib): JsonResponse
    {
        Gate::authorize('delete', $tataTertib);

        if ($tataTertib->jenisPelanggaran()->exists()) {
            throw ValidationException::withMessages([
                'tata_tertib' => 'Tata tertib yang memiliki jenis pelanggaran tidak dapat dihapus.',
            ]);
        }

        $tataTertib->delete();

        return response()->json(null, 204);
    }
}
