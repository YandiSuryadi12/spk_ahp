<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\KategoriRequest;
use App\Http\Services\KategoriService;
use App\Http\Controllers\DataGuruController;
use App\Models\MatriksPenilaianAkhirAhp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\DataGuru;
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
        $judul = "Penilaian Calon";

        $hasil = MatriksPenilaianAkhirAhp::with(['kriteria', 'guru'])->get();

        $kriteriaList = $hasil
            ->pluck('kriteria.nama')
            ->unique()
            ->sort()
            ->values();

        $data = [];

        foreach ($hasil as $item) {
            $nama = $item->guru->nama;
            $kriteriaKey = $item->kriteria->nama;

            if (!isset($data[$nama])) {
                $data[$nama] = ['nama_pengguna' => $nama];
            }
            $data[$nama]['guru_id'] = $item->guru->id;
            $data[$nama][$kriteriaKey] = $item->nilai;
        }

        $rows = array_values($data);
        foreach ($rows as &$row) {
            $nilai = array_filter($row, fn($val, $key) => $key !== 'nama_pengguna', ARRAY_FILTER_USE_BOTH);
            $row['total_nilai'] = array_sum($nilai);
        }
        unset($row);

        usort($rows, function ($a, $b) {
            return $b['total_nilai'] <=> $a['total_nilai'];
        });

        foreach ($rows as $i => &$row) {
            $row['ranking'] = $i + 1;
        }
        unset($row);

        $calon = DataGuru::all();
        $data = MatriksPenilaianAkhirAhp::all();
        $matriksNilai = DB::table('matriks_nilai_prioritas_utama as nilai')
            ->join('kriteria as k', 'nilai.kriteria_id', '=', 'k.id')
            ->get();

        return view('dashboard.tambahnilaiguru.index', [
            "judul" => $judul,
            "data" => $data,
            "calon" => $calon,
            "kriteria" => $matriksNilai,
            "kriteriaList" => $kriteriaList,
            "rows" => $rows,
            "result" => $rows
        ]);
    }

    public function simpan(KategoriRequest $request)
    {

         $validator = Validator::make($request->all(), [
            'guru_id' => 'required|exists:data_gurus,id',
            'nilai_kriteria' => 'required|array|min:1',
            'nilai_kriteria.*' => 'required|numeric|min:0|max:100',
            'bobot_kriteria' => 'required|array|min:1',
            'bobot_kriteria.*' => 'required|numeric|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Validasi gagal. Mohon periksa kembali data yang dimasukkan.');
        }

        $guru_id = $request->input('guru_id');
        $nilai_kriteria = $request->input('nilai_kriteria');      // [kriteria_id => nilai]
        $bobot_kriteria = $request->input('bobot_kriteria');      // [kriteria_id => bobot]

        foreach ($nilai_kriteria as $kriteria_id => $nilai) {
            $bobot = $bobot_kriteria[$kriteria_id] ?? 0; // default 0 jika tidak ditemukan

            $nilai_konversi = $nilai / 100;
            $hasil = $nilai_konversi * $bobot;

            MatriksPenilaianAkhirAhp::create([
                "data_gurus_id" => $guru_id,
                "nilai" => round($hasil, 2),
                "kriteria_id" => $kriteria_id,
            ]);
        }

        return redirect('dashboard/tambahnilaiguru')->with('berhasil', "Data berhasil disimpan!");
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
            return redirect('dashboard/tambahnilaiguru')->with('gagal', $data[1]);
        }
        return redirect('dashboard/tambahnilaiguru')->with('berhasil', "Data berhasil diperbarui!");
    }

    public function hapus(Request $request)
    {
        $guru_id = $request->id;
        MatriksPenilaianAkhirAhp::where('data_gurus_id', $guru_id)->delete();
        return redirect('dashboard/tambahnilaiguru')->with('berhasil', 'Data berhasil dihapus!');
    }

    public function import(Request $request)
    {
        // validasi
        $request->validate([
            'import_data' => 'required|mimes:xls,xlsx'
        ]);

        $this->kategoriService->import($request);

        // alihkan halaman kembali
        return redirect('dashboard/tambahnilaigurud')->with('berhasil', "Data berhasil di import!");
    }
}
