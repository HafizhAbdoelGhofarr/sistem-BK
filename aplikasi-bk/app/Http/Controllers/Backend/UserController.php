<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private const ROLES = ['admin', 'guru_bk', 'siswa'];

    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', User::class);

        return response()->json(
            User::query()->with('roles:id,name')->latest('id')->paginate(25)
        );
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', User::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
            'role' => ['required', Rule::in(self::ROLES)],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'no_hp' => $data['no_hp'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);
            $user->syncRoles(Role::findOrCreate($data['role'], 'web'));

            return $user;
        });

        return response()->json($user->load('roles:id,name'), 201);
    }

    public function update(Request $request, User $pengguna): JsonResponse
    {
        Gate::authorize('update', $pengguna);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,'.$pengguna->id],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'no_hp' => ['sometimes', 'nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
            'role' => ['sometimes', Rule::in(self::ROLES)],
        ]);

        $removingAdmin = $pengguna->hasRole('admin')
            && (($data['role'] ?? 'admin') !== 'admin' || ! (bool) ($data['is_active'] ?? true));

        if ($removingAdmin && User::query()->role('admin')->where('is_active', true)->count() <= 1) {
            throw ValidationException::withMessages([
                'role' => 'Minimal harus ada satu akun admin aktif.',
            ]);
        }

        DB::transaction(function () use ($pengguna, $data) {
            $pengguna->fill(collect($data)->except('role')->all())->save();

            if (isset($data['role'])) {
                $pengguna->syncRoles(Role::findOrCreate($data['role'], 'web'));
            }
        });

        return response()->json($pengguna->refresh()->load('roles:id,name'));
    }
}
