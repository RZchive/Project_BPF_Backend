<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PesertaPelatihan;
use Illuminate\Http\Request;

class PesertaPelatihanController extends Controller
{
    public function index()
    {
        $data = PesertaPelatihan::with(['tenagaKerja', 'pelatihan'])->paginate(10);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'pelatihan_id'    => 'required|exists:pelatihan,id',
            'status_peserta'  => 'nullable|in:aktif,lulus,tidak_lulus',
        ]);

        $p = PesertaPelatihan::create($validated);
        return response()->json(['success' => true, 'data' => $p], 201);
    }

    public function show($id)
    {
        $p = PesertaPelatihan::with(['tenagaKerja', 'pelatihan'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $p]);
    }

    public function update(Request $request, $id)
    {
        $p = PesertaPelatihan::findOrFail($id);
        $request->validate([
            'status_peserta' => 'required|in:aktif,lulus,tidak_lulus',
        ]);
        $p->update($request->only('status_peserta'));
        return response()->json(['success' => true, 'data' => $p]);
    }

    public function destroy($id)
    {
        PesertaPelatihan::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}