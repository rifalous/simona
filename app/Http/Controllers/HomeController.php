<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaksi;
use App\Program;
use App\Kegiatan;
use App\SubKegiatan;
use App\User;
use App\AnggaranKas;
use App\DetailRealisasi;
use App\Jabatan;
use App\MappingUserToJabatan;
use Auth;
use DB;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user           = User::get();
        $program        = Program::get();
        $kegiatan       = Kegiatan::get();
        $subkegiatan    = SubKegiatan::get();

        $month = date('m')-2;

        $grafik1Anggaran = array();
        $grafik1Realisasi = array();
        for ($i=1; $i<=12; $i++) {
            if (Auth::user()->level == 'admin')
            {
                $query1 = AnggaranKas::where('bulan', '<=', $i)->sum('anggaran');
                $query2 = DetailRealisasi::where('bulan', '<=', $i)->sum('realisasi');
                $total_anggaran = AnggaranKas::sum('anggaran');
                $total_realisasi = DetailRealisasi::sum('realisasi');
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
            if ($total_anggaran != 0)     
                $query1 = ($query1 / $total_anggaran)*100; 
            else     
                $query1 = 0;
            // $query1 = ($query1 / $total_anggaran)*100;
            $query1 = number_format((float)$query1, 2, '.', '');
            array_push($grafik1Anggaran, $query1);

            if ($total_anggaran != 0)     
                $query2 = ($query2 / $total_anggaran)*100;
            else     
                $query2 = 0;
            // $query2 = ($query2 / $total_anggaran)*100;
            $query2 = number_format((float)$query2, 2, '.', '');
            if ($i >= $month) {
                array_push($grafik1Realisasi, 0);
            }
            else {
                array_push($grafik1Realisasi, $query2);
            }

        }

        $RTRealisasiKeuangan = ($total_realisasi / $total_anggaran)*100;
        $RTRealisasiKeuangan = number_format((float)$RTRealisasiKeuangan, 2, '.', '');
        $RTRealisasiKeuangan = $RTRealisasiKeuangan . " %";

        $month = date('m');
        $month = intval(ltrim($month, '0'));
        $prevmonth = $month-1;
        $nextmonth = $month+1;

        // $getUserJabatan = MappingUserToJabatan::where('id_user', '=', Auth::user()->id)->get();
        // $getUserJabatan = $getUserJabatan[0]->id_jabatan;
        // return $getUserJabatan;

        // $pptkRealisasi = DetailRealisasi::where('realisasi', '!=', 0)
        //                 ->whereIn('t_mapping_jabatan_to_subkeg.id_subkeg', array(23,24))
        //                 ->join('t_anggarankas', function($join) {
        //                     $join->on('t_det_realisasi.id_anggaran', '=', 't_anggarankas.id');
        //                 })
        //                 ->leftjoin('t_rincian', function($join) {
        //                     $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
        //                 })
        //                 ->leftjoin('t_subkeg', function($join) {
        //                     $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
        //                 })
        //                 ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
        //                     $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
        //                 })
        //                 ->leftjoin('t_jabatan', function($join) {
        //                     $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
        //                 })
        //                 ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
        //                 ->sum('realisasi'); 

        // return $pptkRealisasi;

        if (Auth::user()->level == 'admin')
        {
            $prevs = AnggaranKas::where([
                        ['bulan', '=', $prevmonth],
                        ['anggaran', '!=', 0]
                    ])
                    ->leftjoin('t_rincian', function($join) {
                        $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                    })
                    ->leftjoin('t_subkeg', function($join) {
                        $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                    })
                    ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian'])
                    ->get();
            
            $nexts = AnggaranKas::where([
                        ['bulan', '=', $nextmonth],
                        ['anggaran', '!=', 0]
                    ])
                    ->leftjoin('t_rincian', function($join) {
                        $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                    })
                    ->leftjoin('t_subkeg', function($join) {
                        $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                    })
                    ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian'])
                    ->get();

            $currents = AnggaranKas::where([
                        ['bulan', '=', $month],
                        ['anggaran', '!=', 0]
                    ])
                    ->leftjoin('t_rincian', function($join) {
                        $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                    })
                    ->leftjoin('t_subkeg', function($join) {
                        $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                    })
                    ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian'])
                    ->get();

            $getUserJabatan = '';
            $pptks = Jabatan::where('id', '>', 1)->orderBy('id', 'ASC')->get();

            $AnggaranSekcam = AnggaranKas::where('anggaran', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 2)
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('anggaran'); 

            $AnggaranSekcam2 = AnggaranKas::where('anggaran', '!=', 0)
                        ->whereIn('t_mapping_jabatan_to_subkeg.id_subkeg', array(7,23,24))
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('anggaran'); 

            $AnggaranSekcam = $AnggaranSekcam - $AnggaranSekcam2;

            $AnggaranGaji = AnggaranKas::where('anggaran', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_subkeg', '=', 7)
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('anggaran'); 

            $AnggaranBWM = AnggaranKas::where('anggaran', '!=', 0)
                        ->whereIn('t_mapping_jabatan_to_subkeg.id_subkeg', array(23,24))
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('anggaran'); 
            
            $AnggaranPemerintahan = AnggaranKas::where('anggaran', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 7)
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('anggaran'); 
            
            $AnggaranPemberdayaan = AnggaranKas::where('anggaran', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 6)
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('anggaran'); 

            $AnggaranTrantib = AnggaranKas::where('anggaran', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 5)
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('anggaran'); 

            $AnggaranPembangunan = AnggaranKas::where('anggaran', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 4)
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('anggaran'); 

            $AnggaranSosbud = AnggaranKas::where('anggaran', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 3)
                        ->leftjoin('t_rincian', function($join) {
                            $join->on('t_anggarankas.id_rincian', '=', 't_rincian.id');
                        })
                        ->leftjoin('t_subkeg', function($join) {
                            $join->on('t_rincian.id_subkeg', '=', 't_subkeg.id');
                        })
                        ->leftjoin('t_mapping_jabatan_to_subkeg', function($join) {
                            $join->on('t_subkeg.id', '=', 't_mapping_jabatan_to_subkeg.id_subkeg');
                        })
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('anggaran');
            
            $pptkAnggaran = array(
                $AnggaranSekcam,
                $AnggaranSosbud,
                $AnggaranPembangunan,
                $AnggaranTrantib,
                $AnggaranPemberdayaan,
                $AnggaranPemerintahan,
                $AnggaranGaji,
                $AnggaranBWM
            );

            $RealisasiSekcam = DetailRealisasi::where('realisasi', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 2)
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('realisasi'); 

            $RealisasiSekcam2 = DetailRealisasi::where('realisasi', '!=', 0)
                        ->whereIn('t_mapping_jabatan_to_subkeg.id_subkeg', array(7,23,24))
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('realisasi'); 

            $RealisasiSekcam = $RealisasiSekcam - $RealisasiSekcam2;

            $RealisasiGaji = DetailRealisasi::where('realisasi', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_subkeg', '=', 7)
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('realisasi'); 

            $RealisasiBWM = DetailRealisasi::where('realisasi', '!=', 0)
                        ->whereIn('t_mapping_jabatan_to_subkeg.id_subkeg', array(23,24))
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('realisasi');
            
            $RealisasiPemerintahan = DetailRealisasi::where('realisasi', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 7)
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('realisasi');
            
            $RealisasiPemberdayaan = DetailRealisasi::where('realisasi', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 6)
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('realisasi'); 

            $RealisasiTrantib = DetailRealisasi::where('realisasi', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 5)
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('realisasi');

            $RealisasiPembangunan = DetailRealisasi::where('realisasi', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 4)
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('realisasi'); 

            $RealisasiSosbud = DetailRealisasi::where('realisasi', '!=', 0)
                        ->where('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 3)
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('realisasi');
            

            $pptkRealisasi = array(
                $RealisasiSekcam,
                $RealisasiSosbud,
                $RealisasiPembangunan,
                $RealisasiTrantib,
                $RealisasiPemberdayaan,
                $RealisasiPemerintahan,
                $RealisasiGaji,
                $RealisasiBWM
            );
        }
        else {
            $getUserJabatan = MappingUserToJabatan::where('id_user', '=', Auth::user()->id)->first();
            $getUserJabatan = $getUserJabatan->id_jabatan;

            $currents  = AnggaranKas::where('anggaran', '!=', 0)
                    ->where('bulan', '=', $month)
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
                    ->leftjoin('t_jabatan', function($join) {
                        $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                    })
                    ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                    ->get();

            $prevs = AnggaranKas::where('anggaran', '!=', 0)
                    ->where('bulan', '=', $prevmonth)
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
                    ->leftjoin('t_jabatan', function($join) {
                        $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                    })
                    ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                    ->get();
                    
            $nexts = AnggaranKas::where('anggaran', '!=', 0)
                    ->where('bulan', '=', $nextmonth)
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
                    ->leftjoin('t_jabatan', function($join) {
                        $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                    })
                    ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                    ->get();

            $pptks = Jabatan::where('id', '=', $getUserJabatan)->orderBy('id', 'ASC')->get();

            $anggaran = AnggaranKas::where('anggaran', '!=', 0)
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_anggarankas.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('anggaran'); 
                
            $realisasi= DetailRealisasi::where('realisasi', '!=', 0)
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
                        ->leftjoin('t_jabatan', function($join) {
                            $join->on('t_mapping_jabatan_to_subkeg.id_jabatan', '=', 't_jabatan.id');
                        })
                        ->select(['t_det_realisasi.*', 't_subkeg.kd_subkeg', 't_subkeg.nama_subkeg', 't_rincian.nama_rincian', 't_jabatan.jabatan' ])
                        ->sum('realisasi');
            
            $pptkAnggaran = array($anggaran);
            $pptkRealisasi = array($realisasi);
        }

                        
        $chart1 = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->size(['width' => 400, 'height' => 250])
        ->labels(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agust', 'Sep', 'Okt', 'Nov', 'Des'])
        ->datasets([
            [
                "label" => "Anggaran (%)",
                'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => $grafik1Anggaran,
            ],
            [
                "label" => "Realisasi Realtime (%)",
                'backgroundColor' => "rgba(54, 162, 235, 0.3)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => $grafik1Realisasi,
            ]
        ])
        ->options([]);
         
        $chart2 = app()->chartjs
        ->name('RealisasiAnggaranSimda')
        ->type('bar')
        ->size(['width' => 400, 'height' => 250])
        ->labels(['Kecamatan Rancabali'])
        ->datasets([
            [
                "label" => "Anggaran",
                'backgroundColor' => ['rgba(255, 99, 132, 0.2)'],
                'data' => [$total_anggaran]
            ],
            [
                "label" => "Realisasi",
                'backgroundColor' => ['rgba(54, 162, 235, 0.2)'],
                'data' => [$total_realisasi]
            ],
        ])
        ->options([]);
        
        return view('home', compact('user', 'program', 'kegiatan', 'subkegiatan', 'chart1', 'chart2', 'currents', 'prevs', 'nexts', 'pptks', 'pptkAnggaran', 'pptkRealisasi', 'RTRealisasiKeuangan'));
    }
}
