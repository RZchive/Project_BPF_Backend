<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sertifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikasiController extends Controller
{
    // GET /api/sertifikasi
    public function index()
    {
        $data = Sertifikasi::with('tenagaKerja')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // POST /api/sertifikasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenaga_kerja_id'      => 'required|exists:tenaga_kerja,id',
            'nama_sertifikasi'     => 'required|string|max:255',
            'lembaga_sertifikasi'  => 'required|string|max:255',
            'nomor_sertifikat'     => 'required|string|max:255',
            'tanggal_terbit'       => 'required|date',
            'masa_berlaku'         => 'nullable|date',
            'foto'                 => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('sertifikasi', 'public');
        }

        $sertifikasi = Sertifikasi::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data sertifikasi berhasil ditambahkan',
            'data' => $sertifikasi
        ], 201);
    }

    // GET /api/sertifikasi/{id}
    public function show(string $id)
    {
        $sertifikasi = Sertifikasi::with('tenagaKerja')
            ->find($id);

        if (!$sertifikasi) {

            return response()->json([
                'success' => false,
                'message' => 'Data sertifikasi tidak ditemukan'
            ], 404);

        }

        return response()->json([
            'success' => true,
            'data' => $sertifikasi
        ]);
    }

    // PUT /api/sertifikasi/{id}
    public function update(Request $request, string $id)
    {
        $sertifikasi = Sertifikasi::find($id);

        if (!$sertifikasi) {

            return response()->json([
                'success' => false,
                'message' => 'Data sertifikasi tidak ditemukan'
            ], 404);

        }

        $validated = $request->validate([
            'tenaga_kerja_id'      => 'required|exists:tenaga_kerja,id',
            'nama_sertifikasi'     => 'required|string|max:255',
            'lembaga_sertifikasi'  => 'required|string|max:255',
            'nomor_sertifikat'     => 'required|string|max:255',
            'tanggal_terbit'       => 'required|date',
            'masa_berlaku'         => 'nullable|date',
            'foto'                 => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        if ($request->hasFile('foto')) {
            if ($sertifikasi->foto) {
                Storage::disk('public')->delete($sertifikasi->foto);
            }
            $validated['foto'] = $request->file('foto')->store('sertifikasi', 'public');
        }

        $sertifikasi->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data sertifikasi berhasil diperbarui',
            'data' => $sertifikasi
        ]);
    }

    // DELETE /api/sertifikasi/{id}
    public function destroy(string $id)
    {
        $sertifikasi = Sertifikasi::find($id);

        if (!$sertifikasi) {

            return response()->json([
                'success' => false,
                'message' => 'Data sertifikasi tidak ditemukan'
            ], 404);

        }

        if ($sertifikasi->foto) {
            Storage::disk('public')->delete($sertifikasi->foto);
        }

        $sertifikasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data sertifikasi berhasil dihapus'
        ]);
    }
}