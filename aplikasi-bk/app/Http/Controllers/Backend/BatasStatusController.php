<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BatasStatus;
use App\Services\BatasStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BatasStatusController extends Controller
{
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', BatasStatus::class);

        return response()->json(BatasStatus::query()->orderBy('min_poin')->get());
    }

    public function update(Request $request, BatasStatusService $service): JsonResponse
    {
        Gate::authorize('update', BatasStatus::query()->firstOrFail());

        $data = $request->validate([
            'batas' => ['required', 'array', 'size:4'],
            'batas.*.id' => ['required', 'integer', 'distinct', 'exists:batas_status,id'],
            'batas.*.min_poin' => ['required', 'integer', 'min:0'],
        ]);

        $service->perbarui($data);

        return response()->json(BatasStatus::query()->orderBy('min_poin')->get());
    }
}
