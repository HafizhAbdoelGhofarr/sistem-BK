<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TindakLanjut;
use App\Services\PembinaanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TindakLanjutController extends Controller
{
    public function update(Request $request, TindakLanjut $tindakLanjut, PembinaanService $service): JsonResponse
    {
        Gate::authorize('update', $tindakLanjut->pembinaan);

        $data = $request->validate([
            'tanggal' => ['sometimes', 'date'],
            'deskripsi' => ['sometimes', 'string'],
            'hasil' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'in:proses,selesai'],
        ]);

        $tindakLanjut = $service->perbaruiTindakLanjut($tindakLanjut, $data);

        return response()->json($tindakLanjut->load('pembuat:id,name'));
    }
}
