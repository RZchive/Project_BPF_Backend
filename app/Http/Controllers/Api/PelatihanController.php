<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => Pelatihan::with('lpk')->paginate(10)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lpk_id'          => 'required|exists:lpk,id',
            'nama_pelatihan'  => 'required',
            'jenis_pelatihan' => 'required',
            'jurusan'         => 'required',
            'kuota'           => 'required|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        $p = Pelatihan::create($validated);
        return response()->json(['success' => true, 'data' => $p], 201);
    }

    public function show($id)
    {
        $p = Pelatihan::with(['lpk', 'peserta.tenagaKerja'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $p]);
    }

    public function update(Request $request, $id)
    {
        $p = Pelatihan::findOrFail($id);
        $p->update($request->all());
        return response()->json(['success' => true, 'data' => $p]);
    }

    public function destroy($id)
    {
        Pelatihan::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}