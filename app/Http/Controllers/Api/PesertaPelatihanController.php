<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PesertaPelatihan;
use Illuminate\Http\Request;

class PesertaPelatihanController extends Controller
{
    public function index()
    {
        $data = PesertaPelatihan::with(['tenagaKerja', 'pelatihan'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'pelatihan_id'    => 'required|exists:pelatihan,id',
            'status_peserta'  => 'nullable|in:aktif,lulus,tidak_lulus',
        ]);

        $peserta = PesertaPelatihan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data peserta pelatihan berhasil ditambahkan',
            'data' => $peserta
        ], 201);
    }

    public function show(string $id)
    {
        $peserta = PesertaPelatihan::with(['tenagaKerja', 'pelatihan'])->find($id);

        if (!$peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Data peserta pelatihan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $peserta
        ]);
    }

    public function update(Request $request, string $id)
    {
        $peserta = PesertaPelatihan::find($id);

        if (!$peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Data peserta pelatihan tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'pelatihan_id'    => 'required|exists:pelatihan,id',
            'status_peserta'  => 'nullable|in:aktif,lulus,tidak_lulus',
        ]);

        $peserta->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data peserta pelatihan berhasil diperbarui',
            'data' => $peserta
        ]);
    }

    public function destroy(string $id)
    {
        $peserta = PesertaPelatihan::find($id);

        if (!$peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Data peserta pelatihan tidak ditemukan'
            ], 404);
        }

        $peserta->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data peserta pelatihan berhasil dihapus'
        ]);
    }
}