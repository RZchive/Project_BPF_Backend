<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelatihan::with('lpk');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('nama_pelatihan', 'like', "%$s%")
                  ->orWhere('jenis_pelatihan', 'like', "%$s%")
                  ->orWhere('jurusan', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
            'lpk_id'          => 'required|exists:lpk,id',
            'nama_pelatihan'  => 'required|string|max:255',
            'jenis_pelatihan' => 'required|string|max:255',
            'jurusan'         => 'required|string|max:255',
            'kuota'           => 'required|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date',
            'deskripsi'       => 'nullable|string',
            'status'          => 'nullable|in:aktif,selesai',
        ]);

        $pelatihan = Pelatihan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data pelatihan berhasil ditambahkan',
            'data' => $pelatihan
        ], 201);
    }

    public function show(string $id)
    {
        $pelatihan = Pelatihan::with(['lpk', 'peserta.tenagaKerja'])->find($id);

        if (!$pelatihan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelatihan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pelatihan
        ]);
    }

    public function update(Request $request, string $id)
    {
        $pelatihan = Pelatihan::find($id);

        if (!$pelatihan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelatihan tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'lpk_id'          => 'required|exists:lpk,id',
            'nama_pelatihan'  => 'required|string|max:255',
            'jenis_pelatihan' => 'required|string|max:255',
            'jurusan'         => 'required|string|max:255',
            'kuota'           => 'required|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date',
            'deskripsi'       => 'nullable|string',
            'status'          => 'nullable|in:aktif,selesai',
        ]);

        $pelatihan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data pelatihan berhasil diperbarui',
            'data' => $pelatihan
        ]);
    }

    public function destroy(string $id)
    {
        $pelatihan = Pelatihan::find($id);

        if (!$pelatihan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelatihan tidak ditemukan'
            ], 404);
        }

        $pelatihan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pelatihan berhasil dihapus'
        ]);
    }
}