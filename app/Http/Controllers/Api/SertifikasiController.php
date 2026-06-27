<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sertifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikasiController extends Controller
{
    // GET /api/sertifikasi?search=&pelatihan_id=&status=&tahun=
    public function index(Request $request)
    {
        $query = Sertifikasi::with([
            'pesertaPelatihan.tenagaKerja',
            'pesertaPelatihan.pelatihan',
        ]);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nomor_sertifikat', 'like', "%$s%")
                  ->orWhere('nama_sertifikasi', 'like', "%$s%")
                  ->orWhereHas('pesertaPelatihan.tenagaKerja', function ($q2) use ($s) {
                      $q2->where('nama', 'like', "%$s%")
                         ->orWhere('nik', 'like', "%$s%");
                  })
                  ->orWhereHas('pesertaPelatihan.pelatihan', function ($q2) use ($s) {
                      $q2->where('nama_pelatihan', 'like', "%$s%");
                  });
            });
        }

        if ($request->filled('pelatihan_id')) {
            $query->whereHas('pesertaPelatihan', function ($q) use ($request) {
                $q->where('pelatihan_id', $request->pelatihan_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status_sertifikat', $request->status);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_terbit', $request->tahun);
        }

        if ($request->query('paginate') === 'false') {
            $data = $query->latest()->get();
        } else {
            $data = $query->latest()->paginate(10);
        }

        // Statistik (tanpa filter agar selalu menampilkan total keseluruhan)
        $total       = Sertifikasi::count();
        $aktif       = Sertifikasi::where('status_sertifikat', 'aktif')->count();
        $tidakAktif  = Sertifikasi::where('status_sertifikat', 'tidak_aktif')->count();
        $bulanIni    = Sertifikasi::whereYear('tanggal_terbit', now()->year)
                                  ->whereMonth('tanggal_terbit', now()->month)
                                  ->count();

        return response()->json([
            'success' => true,
            'data'    => $data,
            'stats'   => compact('total', 'aktif', 'tidakAktif', 'bulanIni'),
        ]);
    }

    // POST /api/sertifikasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_pelatihan_id' => 'required|exists:peserta_pelatihan,id',
            'nama_sertifikasi'     => 'required|string|max:255',
            'lembaga_sertifikasi'  => 'required|string|max:255',
            'nomor_sertifikat'     => 'required|string|max:255|unique:sertifikasi,nomor_sertifikat',
            'tanggal_terbit'       => 'required|date',
            'masa_berlaku'         => 'nullable|date',
            'status_sertifikat'    => 'nullable|in:aktif,tidak_aktif',
            'file_sertifikat'      => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('file_sertifikat')) {
            $validated['file_sertifikat'] = $request->file('file_sertifikat')
                ->store('sertifikasi', 'public');
        }

        $sertifikasi = Sertifikasi::create($validated);
        $sertifikasi->load(['pesertaPelatihan.tenagaKerja', 'pesertaPelatihan.pelatihan']);

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil ditambahkan',
            'data'    => $sertifikasi,
        ], 201);
    }

    // GET /api/sertifikasi/{id}
    public function show($id)
    {
        $sertifikasi = Sertifikasi::with([
            'pesertaPelatihan.tenagaKerja',
            'pesertaPelatihan.pelatihan',
        ])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $sertifikasi]);
    }

    // PUT /api/sertifikasi/{id}
    public function update(Request $request, $id)
    {
        $sertifikasi = Sertifikasi::findOrFail($id);

        $validated = $request->validate([
            'peserta_pelatihan_id' => 'sometimes|exists:peserta_pelatihan,id',
            'nama_sertifikasi'     => 'sometimes|string|max:255',
            'lembaga_sertifikasi'  => 'sometimes|string|max:255',
            'nomor_sertifikat'     => 'sometimes|string|max:255|unique:sertifikasi,nomor_sertifikat,' . $id,
            'tanggal_terbit'       => 'sometimes|date',
            'masa_berlaku'         => 'nullable|date',
            'status_sertifikat'    => 'nullable|in:aktif,tidak_aktif',
            'file_sertifikat'      => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('file_sertifikat')) {
            // Hapus file lama jika ada
            if ($sertifikasi->file_sertifikat) {
                Storage::disk('public')->delete($sertifikasi->file_sertifikat);
            }
            $validated['file_sertifikat'] = $request->file('file_sertifikat')
                ->store('sertifikasi', 'public');
        }

        $sertifikasi->update($validated);
        $sertifikasi->load(['pesertaPelatihan.tenagaKerja', 'pesertaPelatihan.pelatihan']);

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil diperbarui',
            'data'    => $sertifikasi,
        ]);
    }

    // DELETE /api/sertifikasi/{id}
    public function destroy($id)
    {
        $sertifikasi = Sertifikasi::findOrFail($id);

        if ($sertifikasi->file_sertifikat) {
            Storage::disk('public')->delete($sertifikasi->file_sertifikat);
        }

        $sertifikasi->delete();

        return response()->json(['success' => true, 'message' => 'Sertifikat berhasil dihapus']);
    }

    // GET /api/sertifikasi/{id}/download
    public function download($id)
    {
        $sertifikasi = Sertifikasi::findOrFail($id);

        if (!$sertifikasi->file_sertifikat || !Storage::disk('public')->exists($sertifikasi->file_sertifikat)) {
            return response()->json(['success' => false, 'message' => 'File sertifikat tidak ditemukan'], 404);
        }

        $path     = Storage::disk('public')->path($sertifikasi->file_sertifikat);
        $filename = 'sertifikat_' . $sertifikasi->nomor_sertifikat . '.pdf';

        return response()->download($path, $filename);
    }
}