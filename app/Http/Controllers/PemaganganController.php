<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pemagangan;
use App\Models\TenagaKerja;
use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;

class PemaganganController extends Controller
{
    public function index()
    {
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
        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'perusahaan_id' => 'required|exists:perusahaan_mitra,id',
            'bidang' => 'nullable|string|max:255',
            'durasi' => 'nullable|string|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|in:berjalan,selesai',
        ]);

        Pemagangan::create($validated);
        return redirect()->route('pemagangan.index')->with('success', 'Pemagangan berhasil dibuat');
    }

    public function edit($id)
    {
        $item = Pemagangan::findOrFail($id);
        $tenagaKerja = TenagaKerja::orderBy('nama')->get();
        $perusahaan = PerusahaanMitra::orderBy('nama_perusahaan')->get();
        return view('pemagangan.edit', compact('item', 'tenagaKerja', 'perusahaan'));
    }

    public function update(Request $request, $id)
    {
        $item = Pemagangan::findOrFail($id);
        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'perusahaan_id' => 'required|exists:perusahaan_mitra,id',
            'bidang' => 'nullable|string|max:255',
            'durasi' => 'nullable|string|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|in:berjalan,selesai',
        ]);

        $item->update($validated);
        return redirect()->route('pemagangan.index')->with('success', 'Pemagangan berhasil diperbarui');
    }

    public function destroy($id)
    {
        Pemagangan::findOrFail($id)->delete();
        return redirect()->route('pemagangan.index')->with('success', 'Pemagangan berhasil dihapus');
    }
}
