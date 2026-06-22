<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TenagaKerja;
use Illuminate\Http\Request;

class TenagaKerjaController extends Controller
{
    public function index(Request $request)
    {
        $data = TenagaKerja::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'nik' => 'required|unique:tenaga_kerja,nik',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'status_pekerjaan' => 'nullable|string|max:255',
            'foto' => 'nullable|string'

        ]);

        $tenagaKerja = TenagaKerja::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data tenaga kerja berhasil ditambahkan',
            'data' => $tenagaKerja
        ], 201);
    }

    public function show(string $id)
    {
        $data = TenagaKerja::find($id);

        if (!$data) {

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);

        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function update(Request $request, string $id)
    {
        $data = TenagaKerja::find($id);

        if (!$data) {

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);

        }

        $validated = $request->validate([

            'nik' => 'required|unique:tenaga_kerja,nik,' . $id,
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'status_pekerjaan' => 'nullable|string|max:255',
            'foto' => 'nullable|string'

        ]);

        $data->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data tenaga kerja berhasil diperbarui',
            'data' => $data
        ]);
    }

    public function destroy(string $id)
    {
        $data = TenagaKerja::find($id);

        if (!$data) {

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);

        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data tenaga kerja berhasil dihapus'
        ]);
    }
}