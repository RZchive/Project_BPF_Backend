<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $data = Laporan::with('user')->paginate(10);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'jenis_laporan' => 'required',
            'format_file'   => 'required|in:pdf,excel',
        ]);

        $l = Laporan::create($validated);
        return response()->json(['success' => true, 'data' => $l], 201);
    }

    public function show($id)
    {
        $l = Laporan::with('user')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $l]);
    }

    public function update(Request $request, $id)
    {
        $l = Laporan::findOrFail($id);
        $request->validate([
            'jenis_laporan' => 'sometimes',
            'format_file'   => 'sometimes|in:pdf,excel',
        ]);
        $l->update($request->all());
        return response()->json(['success' => true, 'data' => $l]);
    }

    public function destroy($id)
    {
        Laporan::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}