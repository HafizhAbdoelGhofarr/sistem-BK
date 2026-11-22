<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class GuruController extends Controller
{
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Guru::class);

        return response()->json(Guru::query()->with('user:id,name,email')->orderBy('nama')->paginate(25));
    }

    public function show(Guru $guru): JsonResponse
    {
        Gate::authorize('view', $guru);

        return response()->json($guru->load('user:id,name,email'));
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Guru::class);

        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id', 'unique:guru,user_id'],
            'nip' => ['nullable', 'string', 'max:30', 'unique:guru,nip'],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ]);
        $this->ensureGuruBkAccount($data['user_id'] ?? null);
        $guru = Guru::create($data);

        return response()->json($guru->load('user:id,name,email'), 201);
    }

    public function update(Request $request, Guru $guru): JsonResponse
    {
        Gate::authorize('update', $guru);

        $data = $request->validate([
            'user_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id', 'unique:guru,user_id,'.$guru->id],
            'nip' => ['sometimes', 'nullable', 'string', 'max:30', 'unique:guru,nip,'.$guru->id],
            'nama' => ['sometimes', 'string', 'max:100'],
            'jenis_kelamin' => ['sometimes', 'nullable', 'in:L,P'],
            'no_hp' => ['sometimes', 'nullable', 'string', 'max:20'],
        ]);
        if (array_key_exists('user_id', $data)) {
            $this->ensureGuruBkAccount($data['user_id']);
        }
        $guru->update($data);

        return response()->json($guru->refresh()->load('user:id,name,email'));
    }

    public function destroy(Guru $guru): JsonResponse
    {
        Gate::authorize('delete', $guru);

        if ($guru->user_id) {
            throw ValidationException::withMessages([
                'guru' => 'Lepaskan akun Guru BK sebelum menghapus data guru.',
            ]);
        }

        $guru->delete();

        return response()->json(null, 204);
    }

    private function ensureGuruBkAccount(?int $userId): void
    {
        if ($userId && ! User::query()->whereKey($userId)->role('guru_bk')->exists()) {
            throw ValidationException::withMessages([
                'user_id' => 'Akun yang ditautkan harus memiliki role guru_bk.',
            ]);
        }
    }
}
