<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    private array $rules = [
        'lpk_id'          => 'required|exists:lpk,id',
        'nama_pelatihan'  => 'required|string|max:255',
        'jenis_pelatihan' => 'required|string|max:255',
        'jurusan'         => 'required|string|max:255',
        'kuota'           => 'required|integer',
        'tanggal_mulai'   => 'required|date',
        'tanggal_selesai' => 'required|date',
        'deskripsi'       => 'nullable|string',
        'status'          => 'nullable|in:aktif,selesai',
    ];

    public function index(Request $request)
    {
        $query = Pelatihan::with('lpk');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('nama_pelatihan', 'like', "%$s%")
                  ->orWhere('jenis_pelatihan', 'like', "%$s%")
                  ->orWhere('jurusan', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'success' => true,
            'data'    => $query->orderByDesc('created_at')->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
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
        $validated = $request->validate(array_merge($this->rules, [
            'lpk_id' => 'sometimes|exists:lpk,id',
            'nama_pelatihan' => 'sometimes|string|max:255',
            'jenis_pelatihan' => 'sometimes|string|max:255',
            'jurusan' => 'sometimes|string|max:255',
            'kuota' => 'sometimes|integer',
            'tanggal_mulai' => 'sometimes|date',
            'tanggal_selesai' => 'sometimes|date',
        ]));

        $p->update($validated);
        return response()->json(['success' => true, 'data' => $p]);
    }

    public function destroy($id)
    {
        Pelatihan::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}