<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;

class PerusahaanMitraController extends Controller
{
    // GET /api/perusahaan-mitra
    public function index()
    {
        $data = PerusahaanMitra::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // POST /api/perusahaan-mitra
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_usaha'    => 'nullable|string|max:255',
            'alamat'          => 'nullable|string',
            'kontak'          => 'nullable|string|max:255',
            'email'           => 'nullable|email|max:255',
        ]);

        $perusahaan = PerusahaanMitra::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Perusahaan mitra berhasil ditambahkan',
            'data'    => $perusahaan
        ], 201);
    }

    // GET /api/perusahaan-mitra/{id}
    public function show(string $id)
    {
        $perusahaan = PerusahaanMitra::find($id);

        if (!$perusahaan) {
            return response()->json([
                'success' => false,
                'message' => 'Perusahaan mitra tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $perusahaan
        ]);
    }

    // PUT /api/perusahaan-mitra/{id}
    public function update(Request $request, string $id)
    {
        $perusahaan = PerusahaanMitra::find($id);

        if (!$perusahaan) {
            return response()->json([
                'success' => false,
                'message' => 'Perusahaan mitra tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_usaha'    => 'nullable|string|max:255',
            'alamat'          => 'nullable|string',
            'kontak'          => 'nullable|string|max:255',
            'email'           => 'nullable|email|max:255',
        ]);

        $perusahaan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Perusahaan mitra berhasil diperbarui',
            'data'    => $perusahaan
        ]);
    }

    // DELETE /api/perusahaan-mitra/{id}
    public function destroy(string $id)
    {
        $perusahaan = PerusahaanMitra::find($id);

        if (!$perusahaan) {
            return response()->json([
                'success' => false,
                'message' => 'Perusahaan mitra tidak ditemukan'
            ], 404);
        }

        $perusahaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perusahaan mitra berhasil dihapus'
        ]);
    }
}