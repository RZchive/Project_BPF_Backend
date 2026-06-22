<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobFair;
use App\Models\JobFairPerusahaan;
use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;

class JobFairPerusahaanController extends Controller
{
    public function index()
    {
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
        $validated = $request->validate([
            'job_fair_id' => 'required|exists:job_fair,id',
            'perusahaan_id' => 'required|exists:perusahaan_mitra,id',
            'jumlah_lowongan' => 'nullable|integer',
            'realisasi_penempatan' => 'nullable|integer',
        ]);

        JobFairPerusahaan::create($validated);
        return redirect()->route('job-fair-perusahaan.index')->with('success', 'Data Job Fair Perusahaan berhasil dibuat');
    }

    public function edit($id)
    {
        $item = JobFairPerusahaan::findOrFail($id);
        $jobFairs = JobFair::orderBy('nama_kegiatan')->get();
        $companies = PerusahaanMitra::orderBy('nama_perusahaan')->get();
        return view('jobfair_perusahaan.edit', compact('item', 'jobFairs', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $item = JobFairPerusahaan::findOrFail($id);
        $validated = $request->validate([
            'job_fair_id' => 'required|exists:job_fair,id',
            'perusahaan_id' => 'required|exists:perusahaan_mitra,id',
            'jumlah_lowongan' => 'nullable|integer',
            'realisasi_penempatan' => 'nullable|integer',
        ]);

        $item->update($validated);
        return redirect()->route('job-fair-perusahaan.index')->with('success', 'Data Job Fair Perusahaan berhasil diperbarui');
    }

    public function destroy($id)
    {
        JobFairPerusahaan::findOrFail($id)->delete();
        return redirect()->route('job-fair-perusahaan.index')->with('success', 'Data Job Fair Perusahaan berhasil dihapus');
    }
}
