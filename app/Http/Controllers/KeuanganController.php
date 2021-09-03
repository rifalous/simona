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
use App\MappingUserToJabatan;
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
        $bulanini = date('m')-2;

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

            
            if (Auth::user()->level == 'admin')
            {
                $total_anggaran = AnggaranKas::sum('anggaran');
                $total_realisasi = DetailRealisasi::sum('realisasi');
                $query1 = AnggaranKas::where('bulan', '<=', $i)->sum('anggaran');
                $query2 = DetailRealisasi::where('bulan', '<=', $i)->sum('realisasi');
            }
            else {
                $getUserJabatan = MappingUserToJabatan::where('id_user', '=', Auth::user()->id)->first();
                $getUserJabatan = $getUserJabatan->id_jabatan;
                $query1 = AnggaranKas::where('t_anggarankas.bulan', '<=', $i)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', $getUserJabatan)
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian'])
                        ->sum('anggaran');

                $query2 = DetailRealisasi::where('t_det_realisasi.bulan', '<=', $i)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', $getUserJabatan)
                        ->join('t_anggarankas', function($join) {
                            $join->on('t_det_realisasi.id_anggaran', '=', 't_anggarankas.id');
                        })
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian' ])
                        ->sum('realisasi');
                        
                $total_anggaran = AnggaranKas::where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', $getUserJabatan)
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian'])
                        ->sum('anggaran');

                $total_realisasi = DetailRealisasi::where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', $getUserJabatan)
                        ->join('t_anggarankas', function($join) {
                            $join->on('t_det_realisasi.id_anggaran', '=', 't_anggarankas.id');
                        })
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian' ])
                        ->sum('realisasi');
            }

            $keuangan['anggaran'] = $query1;

            $query1 = ($query1 / $total_anggaran)*100;
            $query1 = number_format((float)$query1, 2, '.', '');
            $keuangan['persenAnggaran'] = $query1;

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
    
    public function kegiatan()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Keuangan::find($id)->delete();
        // alert()->success('Berhasil.','Data telah dihapus!');
        // return redirect()->route('keuangan.index');
    }
}
