<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;

class PerusahaanMitraController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => PerusahaanMitra::paginate(10)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required',
            'bidang_usaha'    => 'required',
            'alamat'          => 'required',
            'kontak'          => 'required',
            'email'           => 'required|email',
        ]);

        $p = PerusahaanMitra::create($validated);
        return response()->json(['success' => true, 'data' => $p], 201);
    }

    public function show($id)
    {
        return response()->json(['success' => true, 'data' => PerusahaanMitra::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $p = PerusahaanMitra::findOrFail($id);
        $p->update($request->all());
        return response()->json(['success' => true, 'data' => $p]);
    }

    public function destroy($id)
    {
        PerusahaanMitra::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}