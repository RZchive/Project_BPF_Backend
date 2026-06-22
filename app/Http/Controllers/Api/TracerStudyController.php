<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TracerStudy;
use Illuminate\Http\Request;

class TracerStudyController extends Controller
{
    public function index(Request $request)
    {
        $query = TracerStudy::with('tenagaKerja');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->whereHas('tenagaKerja', function($inner) use ($s) {
                    $inner->where('nama', 'like', "%$s%")
                          ->orWhere('nik', 'like', "%$s%");
                })->orWhere('nama_perusahaan', 'like', "%$s%")
                  ->orWhere('status_alumni', 'like', "%$s%");
            });
        }

        $data = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'status_alumni'   => 'required|in:bekerja_sesuai_bidang,membuka_usaha,belum_bekerja',
            'nama_perusahaan' => 'nullable|string|max:255',
            'jabatan'         => 'nullable|string|max:255',
            'gaji'            => 'nullable|string|max:255',
            'keterangan'      => 'nullable|string',
            'tanggal_update'  => 'nullable|date',
        ]);

        $tracerStudy = TracerStudy::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data tracer study berhasil ditambahkan',
            'data' => $tracerStudy
        ], 201);
    }

    public function show(string $id)
    {
        $tracerStudy = TracerStudy::with('tenagaKerja')->find($id);

        if (!$tracerStudy) {
            return response()->json([
                'success' => false,
                'message' => 'Data tracer study tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tracerStudy
        ]);
    }

    public function update(Request $request, string $id)
    {
        $tracerStudy = TracerStudy::find($id);

        if (!$tracerStudy) {
            return response()->json([
                'success' => false,
                'message' => 'Data tracer study tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'status_alumni'   => 'required|in:bekerja_sesuai_bidang,membuka_usaha,belum_bekerja',
            'nama_perusahaan' => 'nullable|string|max:255',
            'jabatan'         => 'nullable|string|max:255',
            'gaji'            => 'nullable|string|max:255',
            'keterangan'      => 'nullable|string',
            'tanggal_update'  => 'nullable|date',
        ]);

        $tracerStudy->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data tracer study berhasil diperbarui',
            'data' => $tracerStudy
        ]);
    }

    public function destroy(string $id)
    {
        $tracerStudy = TracerStudy::find($id);

        if (!$tracerStudy) {
            return response()->json([
                'success' => false,
                'message' => 'Data tracer study tidak ditemukan'
            ], 404);
        }

        $tracerStudy->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data tracer study berhasil dihapus'
        ]);
    }
}