<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lpk;
use Illuminate\Http\Request;

class LpkController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => Lpk::with('user')->paginate(10)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'         => 'nullable|exists:users,id',
            'nama_lpk'        => 'required',
            'alamat'          => 'required',
            'bidang_keahlian' => 'required',
            'kontak'          => 'required',
            'email'           => 'required|email',
        ]);

        $lpk = Lpk::create($validated);
        return response()->json(['success' => true, 'data' => $lpk], 201);
    }

    public function show($id)
    {
        $lpk = Lpk::with(['user', 'pelatihan'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $lpk]);
    }

    public function update(Request $request, $id)
    {
        $lpk = Lpk::findOrFail($id);
        $lpk->update($request->all());
        return response()->json(['success' => true, 'data' => $lpk]);
    }

    public function destroy($id)
    {
        Lpk::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}