<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobFair;
use App\Models\JobFairPerusahaan;
use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;

class JobFairPerusahaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            $data = JobFairPerusahaan::with(['jobFair', 'perusahaan'])->latest()->get();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        $data = JobFairPerusahaan::with(['jobFair', 'perusahaan'])->paginate(10);
        return view('jobfair_perusahaan.index', compact('data'));
    }

    public function create()
    {
        $jobFairs = JobFair::orderBy('nama_kegiatan')->get();
        $companies = PerusahaanMitra::orderBy('nama_perusahaan')->get();
        return view('jobfair_perusahaan.create', compact('jobFairs', 'companies'));
    }

    public function store(Request $request)
    {
        $isApi = $request->wantsJson() || $request->is('api/*');

        $validated = $request->validate([
            'job_fair_id'          => 'required|exists:job_fair,id',
            'perusahaan_id'        => 'required|exists:perusahaan_mitra,id',
            'jumlah_lowongan'      => 'nullable|integer',
            'realisasi_penempatan' => 'nullable|integer',
        ]);

        $item = JobFairPerusahaan::create($validated);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data perusahaan peserta job fair berhasil ditambahkan',
                'data' => $item
            ], 201);
        }

        return redirect()->route('job-fair-perusahaan.index')->with('success', 'Data Job Fair Perusahaan berhasil dibuat');
    }

    public function show(Request $request, string $id)
    {
        $item = JobFairPerusahaan::with(['jobFair', 'perusahaan'])->find($id);

        if (!$item) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data perusahaan peserta job fair tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $item
            ]);
        }

        abort(404);
    }

    public function edit(string $id)
    {
        $item = JobFairPerusahaan::findOrFail($id);
        $jobFairs = JobFair::orderBy('nama_kegiatan')->get();
        $companies = PerusahaanMitra::orderBy('nama_perusahaan')->get();
        return view('jobfair_perusahaan.edit', compact('item', 'jobFairs', 'companies'));
    }

    public function update(Request $request, string $id)
    {
        $item = JobFairPerusahaan::find($id);
        $isApi = $request->wantsJson() || $request->is('api/*');

        if (!$item) {
            if ($isApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data perusahaan peserta job fair tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'job_fair_id'          => 'required|exists:job_fair,id',
            'perusahaan_id'        => 'required|exists:perusahaan_mitra,id',
            'jumlah_lowongan'      => 'nullable|integer',
            'realisasi_penempatan' => 'nullable|integer',
        ]);

        $item->update($validated);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data perusahaan peserta job fair berhasil diperbarui',
                'data' => $item
            ]);
        }

        return redirect()->route('job-fair-perusahaan.index')->with('success', 'Data Job Fair Perusahaan berhasil diperbarui');
    }

    public function destroy(Request $request, string $id)
    {
        $item = JobFairPerusahaan::find($id);
        $isApi = $request->wantsJson() || $request->is('api/*');

        if (!$item) {
            if ($isApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data perusahaan peserta job fair tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        $item->delete();

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data perusahaan peserta job fair berhasil dihapus'
            ]);
        }

        return redirect()->route('job-fair-perusahaan.index')->with('success', 'Data Job Fair Perusahaan berhasil dihapus');
    }
}
