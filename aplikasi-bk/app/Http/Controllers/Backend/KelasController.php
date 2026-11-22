<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class KelasController extends Controller
{
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Kelas::class);

        return response()->json(Kelas::query()->with('waliKelas')->withCount('siswa')->orderBy('nama')->paginate(25));
    }

    public function show(Kelas $kelas): JsonResponse
    {
        Gate::authorize('view', $kelas);

        return response()->json($kelas->load('waliKelas')->loadCount('siswa'));
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Kelas::class);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:30'],
            'tingkat' => ['required', 'string', 'max:5'],
            'jurusan' => ['nullable', 'string', 'max:50'],
            'tahun_ajaran' => ['required', 'string', 'size:9'],
            'wali_kelas_id' => ['nullable', 'integer', 'exists:guru,id'],
        ]);
        $kelas = Kelas::create($data);

        return response()->json($kelas->load('waliKelas'), 201);
    }

    public function update(Request $request, Kelas $kelas): JsonResponse
    {
        Gate::authorize('update', $kelas);

        $data = $request->validate([
            'nama' => ['sometimes', 'string', 'max:30'],
            'tingkat' => ['sometimes', 'string', 'max:5'],
            'jurusan' => ['sometimes', 'nullable', 'string', 'max:50'],
            'tahun_ajaran' => ['sometimes', 'string', 'size:9'],
            'wali_kelas_id' => ['sometimes', 'nullable', 'integer', 'exists:guru,id'],
        ]);
        $kelas->update($data);

        return response()->json($kelas->refresh()->load('waliKelas'));
    }

    public function destroy(Kelas $kelas): JsonResponse
    {
        Gate::authorize('delete', $kelas);

        if ($kelas->siswa()->withTrashed()->exists()) {
            throw ValidationException::withMessages([
                'kelas' => 'Kelas yang masih memiliki siswa tidak dapat dihapus.',
            ]);
        }

        $kelas->delete();

        return response()->json(null, 204);
    }
}
