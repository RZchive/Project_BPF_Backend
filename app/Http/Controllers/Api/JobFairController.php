<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobFair;
use Illuminate\Http\Request;

class JobFairController extends Controller
{
    public function index()
    {
        $data = JobFair::with('perusahaan')->paginate(10);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required',
            'tanggal'       => 'required|date',
            'lokasi'        => 'required',
            'deskripsi'     => 'nullable',
        ]);

        $j = JobFair::create($validated);
        return response()->json(['success' => true, 'data' => $j], 201);
    }

    public function show($id)
    {
        $j = JobFair::with(['perusahaan.perusahaan'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $j]);
    }

    public function update(Request $request, $id)
    {
        $j = JobFair::findOrFail($id);
        $request->validate([
            'nama_kegiatan' => 'sometimes',
            'tanggal'       => 'sometimes|date',
            'lokasi'        => 'sometimes',
            'deskripsi'     => 'nullable',
        ]);
        $j->update($request->all());
        return response()->json(['success' => true, 'data' => $j]);
    }

    public function destroy($id)
    {
        JobFair::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}