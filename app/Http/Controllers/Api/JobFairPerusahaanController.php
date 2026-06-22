<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobFairPerusahaan;
use Illuminate\Http\Request;

class JobFairPerusahaanController extends Controller
{
    public function index()
    {
        $data = JobFairPerusahaan::with(['jobFair', 'perusahaan'])->paginate(10);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_fair_id' => 'required|exists:job_fair,id',
            'perusahaan_id' => 'required|exists:perusahaan_mitra,id',
            'jumlah_lowongan' => 'nullable|integer',
            'realisasi_penempatan' => 'nullable|integer',
        ]);

        $item = JobFairPerusahaan::create($validated);
        return response()->json(['success' => true, 'data' => $item], 201);
    }

    public function show($id)
    {
        $item = JobFairPerusahaan::with(['jobFair', 'perusahaan'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = JobFairPerusahaan::findOrFail($id);
        $validated = $request->validate([
            'job_fair_id' => 'sometimes|exists:job_fair,id',
            'perusahaan_id' => 'sometimes|exists:perusahaan_mitra,id',
            'jumlah_lowongan' => 'nullable|integer',
            'realisasi_penempatan' => 'nullable|integer',
        ]);

        $item->update($validated);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroy($id)
    {
        JobFairPerusahaan::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
