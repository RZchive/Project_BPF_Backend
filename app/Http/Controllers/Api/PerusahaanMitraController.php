<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;

class PerusahaanMitraController extends Controller
{
    private array $rules = [
        'nama_perusahaan' => 'required|string|max:255',
        'bidang_usaha'    => 'required|string|max:255',
        'alamat'          => 'required|string',
        'kontak'          => 'required|string|max:20',
        'email'           => 'required|email|max:255',
    ];

    public function index(Request $request)
    {
        $query = PerusahaanMitra::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('nama_perusahaan', 'like', "%$s%")
                  ->orWhere('bidang_usaha', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
        }

        return response()->json([
            'success' => true,
            'data'    => $query->orderByDesc('created_at')->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(array_merge($this->rules, [
            'email' => 'required|email|max:255|unique:perusahaan_mitra,email',
        ]));

        $p = PerusahaanMitra::create($validated);
        return response()->json(['success' => true, 'data' => $p], 201);
    }

    public function show($id)
    {
        $p = PerusahaanMitra::find($id);

        if (!$p) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $p]);
    }

    public function update(Request $request, $id)
    {
        $p = PerusahaanMitra::find($id);

        if (!$p) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate(array_merge($this->rules, [
            'email' => 'required|email|max:255|unique:perusahaan_mitra,email,' . $id,
        ]));

        $p->update($validated);
        return response()->json(['success' => true, 'data' => $p]);
    }

    public function destroy($id)
    {
        $p = PerusahaanMitra::find($id);

        if (!$p) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $p->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}