<?php

namespace App\Http\Controllers\Api\Lpk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelatihan;
use Illuminate\Support\Facades\Validator;

class PelatihanController extends Controller
{
    /**
     * Menampilkan seluruh data pelatihan
     */
    public function index()
    {
        $pelatihan = Pelatihan::with('lpk')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data pelatihan berhasil diambil',
            'data' => $pelatihan
        ]);
    }

    /**
     * Menyimpan pelatihan baru
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if ($user && $user->lpk) {
            $request->merge(['lpk_id' => $user->lpk->id]);
        }

        $validator = Validator::make($request->all(), [

            'lpk_id' => 'required|integer',

            'nama_pelatihan' => 'required|string|max:255',

            'jenis_pelatihan' => 'required|string|max:100',

            'jurusan' => 'required|string|max:255',

            'deskripsi' => 'nullable|string',

            'kuota' => 'required|integer|min:1',

            'status' => 'required|string',

            'tanggal_mulai' => 'required|date',

            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);

        }

        $pelatihan = Pelatihan::create([
    'lpk_id' => $request->lpk_id,
    'nama_pelatihan' => $request->nama_pelatihan,
    'jenis_pelatihan' => $request->jenis_pelatihan,
    'jurusan' => $request->jurusan,
    'deskripsi' => $request->deskripsi,
    'kuota' => $request->kuota,
    'status' => $request->status,
    'tanggal_mulai' => $request->tanggal_mulai,
    'tanggal_selesai' => $request->tanggal_selesai,
]);

        return response()->json([
            'success' => true,
            'message' => 'Pelatihan berhasil ditambahkan',
            'data' => $pelatihan
        ],201);
    }

    /**
     * Detail pelatihan
     */
    public function show($id)
    {
        $pelatihan = Pelatihan::with(['lpk', 'peserta'])->find($id);

        if(!$pelatihan){

            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);

        }

        return response()->json([
            'success'=>true,
            'data'=>$pelatihan
        ]);
    }

    /**
     * Update pelatihan
     */
    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::find($id);

        if(!$pelatihan){

            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);

        }

        $validator = Validator::make($request->all(), [

            'nama_pelatihan'=>'required|string|max:255',

            'jenis_pelatihan'=>'required',

            'jurusan'=>'required',

            'deskripsi'=>'nullable',

            'kuota'=>'required|integer|min:1',

            'status'=>'required',

            'tanggal_mulai'=>'required|date',

            'tanggal_selesai'=>'required|date|after_or_equal:tanggal_mulai',

        ]);

        if($validator->fails()){

            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],422);

        }

        $pelatihan->update([
    'nama_pelatihan' => $request->nama_pelatihan,
    'jenis_pelatihan' => $request->jenis_pelatihan,
    'jurusan' => $request->jurusan,
    'deskripsi' => $request->deskripsi,
    'kuota' => $request->kuota,
    'status' => $request->status,
    'tanggal_mulai' => $request->tanggal_mulai,
    'tanggal_selesai' => $request->tanggal_selesai,
]);

        return response()->json([
            'success'=>true,
            'message'=>'Pelatihan berhasil diperbarui',
            'data'=>$pelatihan
        ]);
    }

    /**
     * Hapus pelatihan
     */
    public function destroy($id)
    {
        $pelatihan = Pelatihan::find($id);

        if(!$pelatihan){

            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);

        }

        $pelatihan->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Pelatihan berhasil dihapus'
        ]);
    }
}