<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lpk;
use Illuminate\Http\Request;

class LpkController extends Controller
{
    // GET /api/lpk
    public function index()
    {
        $data = Lpk::with('user')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // POST /api/lpk
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama_lpk' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'bidang_keahlian' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'status_aktif' => 'boolean'
        ]);

        $lpk = Lpk::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'LPK berhasil ditambahkan',
            'data' => $lpk
        ], 201);
    }

    // GET /api/lpk/{id}
    public function show(string $id)
    {
        $lpk = Lpk::with('user')->find($id);

        if (!$lpk) {
            return response()->json([
                'success' => false,
                'message' => 'LPK tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lpk
        ]);
    }

    // PUT /api/lpk/{id}
    public function update(Request $request, string $id)
    {
        $lpk = Lpk::find($id);

        if (!$lpk) {
            return response()->json([
                'success' => false,
                'message' => 'LPK tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama_lpk' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'bidang_keahlian' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'status_aktif' => 'boolean'
        ]);

        $lpk->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'LPK berhasil diperbarui',
            'data' => $lpk
        ]);
    }

    // DELETE /api/lpk/{id}
    public function destroy(string $id)
    {
        $lpk = Lpk::find($id);

        if (!$lpk) {
            return response()->json([
                'success' => false,
                'message' => 'LPK tidak ditemukan'
            ], 404);
        }

        $lpk->delete();

        return response()->json([
            'success' => true,
            'message' => 'LPK berhasil dihapus'
        ]);
    }
}