<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Jobs\KirimNotifikasiWaJob;
use App\Models\NotifikasiWa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class NotifikasiWaController extends Controller
{
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', NotifikasiWa::class);

        return response()->json(
            NotifikasiWa::query()
                ->with(['siswa:id,nis,nama', 'pelanggaran:id,tanggal,status'])
                ->latest('id')
                ->paginate(25)
        );
    }

    public function retry(NotifikasiWa $notifikasiWa): JsonResponse
    {
        Gate::authorize('retry', $notifikasiWa);

        DB::transaction(function () use ($notifikasiWa) {
            $notifikasiWa = NotifikasiWa::query()->lockForUpdate()->findOrFail($notifikasiWa->id);
            Gate::authorize('retry', $notifikasiWa);
            $notifikasiWa->forceFill([
                'status' => 'pending',
                'percobaan' => 0,
                'respon' => null,
                'dikirim_pada' => null,
            ])->save();

            KirimNotifikasiWaJob::dispatch($notifikasiWa->id)->afterCommit();
        });

        return response()->json(['status' => 'pending'], 202);
    }
}
