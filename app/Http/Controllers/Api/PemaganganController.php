<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemagangan;
use App\Models\TenagaKerja;
use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;

class PemaganganController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            $data = Pemagangan::with(['tenagaKerja', 'perusahaan'])->latest()->get();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        $data = Pemagangan::with(['tenagaKerja', 'perusahaan'])->paginate(10);
        return view('pemagangan.index', compact('data'));
    }

    public function create()
    {
        $tenagaKerja = TenagaKerja::orderBy('nama')->get();
        $perusahaan = PerusahaanMitra::orderBy('nama_perusahaan')->get();
        return view('pemagangan.create', compact('tenagaKerja', 'perusahaan'));
    }

    public function store(Request $request)
    {
        $isApi = $request->wantsJson() || $request->is('api/*');

        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'perusahaan_id'   => 'required|exists:perusahaan_mitra,id',
            'bidang'          => 'nullable|string|max:255',
            'durasi'          => 'nullable|string|max:255',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'          => 'nullable|in:berjalan,selesai',
        ]);

        $pemagangan = Pemagangan::create($validated);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data pemagangan berhasil ditambahkan',
                'data' => $pemagangan
            ], 201);
        }

        return redirect()->route('pemagangan.index')->with('success', 'Pemagangan berhasil dibuat');
    }

    public function show(Request $request, string $id)
    {
        $pemagangan = Pemagangan::with(['tenagaKerja', 'perusahaan'])->find($id);

        if (!$pemagangan) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pemagangan tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $pemagangan
            ]);
        }

        abort(404);
    }

    public function edit(string $id)
    {
        $item = Pemagangan::findOrFail($id);
        $tenagaKerja = TenagaKerja::orderBy('nama')->get();
        $perusahaan = PerusahaanMitra::orderBy('nama_perusahaan')->get();
        return view('pemagangan.edit', compact('item', 'tenagaKerja', 'perusahaan'));
    }

    public function update(Request $request, string $id)
    {
        $pemagangan = Pemagangan::find($id);
        $isApi = $request->wantsJson() || $request->is('api/*');

        if (!$pemagangan) {
            if ($isApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pemagangan tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'perusahaan_id'   => 'required|exists:perusahaan_mitra,id',
            'bidang'          => 'nullable|string|max:255',
            'durasi'          => 'nullable|string|max:255',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'          => 'nullable|in:berjalan,selesai',
        ]);

        $pemagangan->update($validated);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data pemagangan berhasil diperbarui',
                'data' => $pemagangan
            ]);
        }

        return redirect()->route('pemagangan.index')->with('success', 'Pemagangan berhasil diperbarui');
    }

    public function destroy(Request $request, string $id)
    {
        $pemagangan = Pemagangan::find($id);
        $isApi = $request->wantsJson() || $request->is('api/*');

        if (!$pemagangan) {
            if ($isApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pemagangan tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        $pemagangan->delete();

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data pemagangan berhasil dihapus'
            ]);
        }

        return redirect()->route('pemagangan.index')->with('success', 'Pemagangan berhasil dihapus');
    }
}
