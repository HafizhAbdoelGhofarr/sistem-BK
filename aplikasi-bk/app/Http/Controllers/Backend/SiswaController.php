<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class SiswaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Siswa::class);

        $query = Siswa::query()->with('kelas');
        if ($request->user()->hasRole('siswa')) {
            $query->where('user_id', $request->user()->id);
        }

        return response()->json($query->latest('id')->paginate(25));
    }

    public function show(Request $request, Siswa $siswa): JsonResponse
    {
        Gate::authorize('view', $siswa);

        return response()->json($siswa->load(['kelas', 'pelanggaran', 'pembinaan']));
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Siswa::class);

        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id', 'unique:siswa,user_id'],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'nis' => ['required', 'string', 'max:20', 'unique:siswa,nis'],
            'nisn' => ['nullable', 'string', 'max:20', 'unique:siswa,nisn'],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'nama_ortu' => ['nullable', 'string', 'max:100'],
            'no_hp_ortu' => ['nullable', 'string', 'max:20'],
        ]);

        $this->ensureStudentAccount($data['user_id'] ?? null);
        $siswa = Siswa::create($data);

        return response()->json($siswa->load('kelas'), 201);
    }

    public function update(Request $request, Siswa $siswa): JsonResponse
    {
        Gate::authorize('update', $siswa);

        $data = $request->validate([
            'user_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id', 'unique:siswa,user_id,'.$siswa->id],
            'kelas_id' => ['sometimes', 'integer', 'exists:kelas,id'],
            'nis' => ['sometimes', 'string', 'max:20', 'unique:siswa,nis,'.$siswa->id],
            'nisn' => ['sometimes', 'nullable', 'string', 'max:20', 'unique:siswa,nisn,'.$siswa->id],
            'nama' => ['sometimes', 'string', 'max:100'],
            'jenis_kelamin' => ['sometimes', 'in:L,P'],
            'tanggal_lahir' => ['sometimes', 'nullable', 'date'],
            'alamat' => ['sometimes', 'nullable', 'string'],
            'nama_ortu' => ['sometimes', 'nullable', 'string', 'max:100'],
            'no_hp_ortu' => ['sometimes', 'nullable', 'string', 'max:20'],
        ]);

        if (array_key_exists('user_id', $data)) {
            $this->ensureStudentAccount($data['user_id']);
        }

        $siswa->update($data);

        return response()->json($siswa->refresh()->load('kelas'));
    }

    public function destroy(Siswa $siswa): JsonResponse
    {
        Gate::authorize('delete', $siswa);
        $siswa->delete();

        return response()->json(null, 204);
    }

    private function ensureStudentAccount(?int $userId): void
    {
        if ($userId && ! User::query()->whereKey($userId)->role('siswa')->exists()) {
            throw ValidationException::withMessages([
                'user_id' => 'Akun yang ditautkan harus memiliki role siswa.',
            ]);
        }
    }
}
