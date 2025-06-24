<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\KategoriRequest;
use App\Http\Services\KategoriService;
use App\Models\MatriksPenilaianAkhirAhp;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    protected $kategoriService;

    public function __construct(KategoriService $kategoriService)
    {
        $this->kategoriService = $kategoriService;
    }

    public function index()
    {
        $judul = "Data Calon";
        $data = MatriksPenilaianAkhirAhp::all();
        $matriksNilai = DB::table('matriks_nilai_prioritas_utama as nilai')
            ->join('kriteria as k', 'nilai.kriteria_id', '=', 'k.id')
            ->get();

        return view('dashboard.kategori.index', [
            "judul" => $judul,
            "data" => $data,
            "kriteria" => $matriksNilai
        ]);
    }

    public function simpan(KategoriRequest $request)
    {
        $bobot_kriteria = $request->input('bobot_kriteria');
        dd($bobot_kriteria);
        foreach($bobot_kriteria as $data) {
            dd($data);
        }

        // $data = MatriksPenilaianAkhirAhp::create([
        //     'nama' => $request->nama,
        //     'kriteria_id' => $request->kriteria_id,
        //     'nilai' => $request->nilai
        // ]);
        if (!$data[0]) {
            return redirect('dashboard/kategori')->with('gagal', $data[1]);
        }
        return redirect('dashboard/kategori')->with('berhasil', "Data berhasil disimpan!");
    }

    public function ubah(Request $request)
    {
        $data = $this->kategoriService->ubahGetData($request);
        return $data;
    }

    public function perbarui(KategoriRequest $request)
    {
        $data = $this->kategoriService->perbaruiPostData($request);
        if (!$data[0]) {
            return redirect('dashboard/kategori')->with('gagal', $data[1]);
        }
        return redirect('dashboard/kategori')->with('berhasil', "Data berhasil diperbarui!");
    }

    public function hapus(Request $request)
    {
        $this->kategoriService->hapusPostData($request->id);
        return redirect('dashboard/kategori');
    }

    public function import(Request $request)
    {
        // validasi
        $request->validate([
            'import_data' => 'required|mimes:xls,xlsx'
        ]);

        $this->kategoriService->import($request);

        // alihkan halaman kembali
        return redirect('dashboard/kategori')->with('berhasil', "Data berhasil di import!");
    }
}
