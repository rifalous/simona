<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Buku;
use App\Anggota;
use App\Transaksi;
use App\Program;
use App\Kegiatan;
use App\SubKegiatan;
use App\User;
use App\AnggaranKas;
use App\DetailRealisasi;
use App\Jabatan;
use Carbon\Carbon;
use Session;
use Auth;
use DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Redirect;

class KeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // if(Auth::user()->level == 'user')
        // {
        //     $datas = Transaksi::where('anggota_id', Auth::user()->anggota->id)
        //                         ->get();
        //     $datas = Transaksi::get();
        // } else {
        //     $datas = Transaksi::get();
        // }

        $bulanini = date('m');
        $total_anggaran = AnggaranKas::sum('anggaran');
        $total_realisasi = DetailRealisasi::sum('realisasi');

        $anggaran = array();
        $realisasi = array();
        $persenAnggaran = array();
        $persenRealisasi = array();
        $months = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        
        $keuangan = array();
        $dataKeuangan = array();
        $i = 1;
        foreach ($months as $month) {
            $keuangan['no'] = $i;
            $keuangan['bulan'] = $month;

            $query1 = AnggaranKas::where('bulan', '<=', $i)->sum('anggaran');
            $keuangan['anggaran'] = $query1;

            $query1 = ($query1 / $total_anggaran)*100;
            $query1 = number_format((float)$query1, 2, '.', '');
            $keuangan['persenAnggaran'] = $query1;

            $query2 = DetailRealisasi::where('bulan', '<=', $i)->sum('realisasi');
            if ($i >= $bulanini) {
                $keuangan['realisasi'] = 0;
            }
            else {
                $keuangan['realisasi'] = $query2;
            }

            $query2 = ($query2 / $total_anggaran)*100;
            $query2 = number_format((float)$query2, 2, '.', '');
            if ($i >= $bulanini) {
                $keuangan['persenRealisasi'] = 0;
            }
            else {
                $keuangan['persenRealisasi'] = $query2;
            }
            array_push($dataKeuangan, $keuangan);
            $i++;
        }

        // return response()->json($dataKeuangan, 200);
        return view('keuangan.index', compact('dataKeuangan'));
        // return response()->json(['months', 'anggaran', 'realisasi', 'persenAnggaran', 'persenRealisasi']);
        // return response()->json([$anggaran, $realisasi], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        // $getRow = Transaksi::orderBy('id', 'DESC')->get();
        // $rowCount = $getRow->count();
        
        // $lastId = $getRow->first();

        // $bukus = Buku::where('jumlah_buku', '>', 0)->get();
        // $anggotas = Anggota::get();
        // return view('transaksi.create', compact('bukus', 'kode', 'anggotas'));
        return view('keuangan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'kode_transaksi' => 'required|string|max:255',
        //     'tgl_pinjam' => 'required',
        //     'tgl_kembali' => 'required',
        //     'buku_id' => 'required',
        //     'anggota_id' => 'required',

        // ]);

        // $transaksi = Transaksi::create([
        //         'kode_transaksi' => $request->get('kode_transaksi'),
        //         'tgl_pinjam' => $request->get('tgl_pinjam'),
        //         'tgl_kembali' => $request->get('tgl_kembali'),
        //         'buku_id' => $request->get('buku_id'),
        //         'anggota_id' => $request->get('anggota_id'),
        //         'ket' => $request->get('ket'),
        //         'status' => 'pinjam'
        //     ]);

        // $transaksi->buku->where('id', $transaksi->buku_id)
        //                 ->update([
        //                     'jumlah_buku' => ($transaksi->buku->jumlah_buku - 1),
        //                     ]);

        // alert()->success('Berhasil.','Data telah ditambahkan!');
        // return redirect()->route('transaksi.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // $data = Transaksi::findOrFail($id);


        // if((Auth::user()->level == 'user') && (Auth::user()->anggota->id != $data->anggota_id)) {
        //         Alert::info('Oopss..', 'Anda dilarang masuk ke area ini.');
        //         return redirect()->to('/');
        // }


        // return view('transaksi.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        // $data = Transaksi::findOrFail($id);

        // if((Auth::user()->level == 'user') && (Auth::user()->anggota->id != $data->anggota_id)) {
        //         Alert::info('Oopss..', 'Anda dilarang masuk ke area ini.');
        //         return redirect()->to('/');
        // }

        // return view('buku.edit', compact('data'));
        return view('keuangan.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $transaksi = Transaksi::find($id);

        // $transaksi->update([
        //         'status' => 'kembali'
        //         ]);

        // $transaksi->buku->where('id', $transaksi->buku->id)
        //                 ->update([
        //                     'jumlah_buku' => ($transaksi->buku->jumlah_buku + 1),
        //                     ]);

        // alert()->success('Berhasil.','Data telah diubah!');
        // return redirect()->route('transaksi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Keuangan::find($id)->delete();
        alert()->success('Berhasil.','Data telah dihapus!');
        return redirect()->route('keuangan.index');
    }
}
