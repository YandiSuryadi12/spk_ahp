<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\KategoriRequest;
use App\Http\Services\KategoriService;
use App\Models\MatriksPenilaianAkhirAhp;
use Illuminate\Http\Request;
use App\Models\DataGuru;

class DataGuruController extends Controller
{
    public function index()
    {
        return view('dashboard.dataguru.index', [
            'judul' => 'Data Guru',
            'data' => DataGuru::all()
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|numeric|unique:data_gurus,nip',
            'keterangan' => 'nullable|string'
        ]);

        DataGuru::create($request->all());

        return back()->with('berhasil', 'Data guru berhasil ditambahkan.');
    }

    public function perbarui(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:data_gurus,id',
            'nama' => 'required|string|max:255',
            'nip' => 'required|numeric|unique:data_gurus,nip,' . $request->id,
            'keterangan' => 'nullable|string'
        ]);

        DataGuru::find($request->id)->update($request->except('id'));

        return back()->with('berhasil', 'Data guru berhasil diperbarui.');
    }

    public function hapus(Request $request)
    {
        DataGuru::destroy($request->id);

        return response()->json(['success' => true]);
    }

    public function ubah(Request $request)
    {
        $guru = DataGuru::find($request->id);
        return response()->json($guru);
    }
}
