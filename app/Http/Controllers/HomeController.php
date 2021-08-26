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

        // if(Auth::user()->level == 'user')
        // {
        //     $datas = Transaksi::where('status', 'pinjam')
        //                         ->where('anggota_id', Auth::user()->anggota->id)
        //                         ->get();
        // } else {
        //     $datas = Transaksi::where('status', 'pinjam')->get();
        // }

        $month = date('m');

        $total_anggaran = AnggaranKas::sum('anggaran');
        $total_realisasi = DetailRealisasi::sum('realisasi');
        $RTRealisasiKeuangan = ($total_realisasi / $total_anggaran)*100;
        $RTRealisasiKeuangan = number_format((float)$RTRealisasiKeuangan, 2, '.', '');
        $RTRealisasiKeuangan = $RTRealisasiKeuangan . " %";
        
        $grafik1Anggaran = array();
        $grafik1Realisasi = array();
        for ($i=1; $i<=12; $i++) {
            $query1 = AnggaranKas::where('bulan', '<=', $i)->sum('anggaran');
            $query1 = ($query1 / $total_anggaran)*100;
            $query1 = number_format((float)$query1, 2, '.', '');
            array_push($grafik1Anggaran, $query1);

            $query2 = DetailRealisasi::where('bulan', '<=', $i)->sum('realisasi');
            $query2 = ($query2 / $total_anggaran)*100;
            $query2 = number_format((float)$query2, 2, '.', '');
            if ($i >= $month) {
                array_push($grafik1Realisasi, 0);
            }
            else {
                array_push($grafik1Realisasi, $query2);
            }
        }

        // $RTRealisasiAnggaran = AnggaranKas::where('bulan', '=', 1)->sum('anggaran');

        // return $RTRealisasiAnggaran;
        // $month = ltrim($month, '0');

        $month = intval(ltrim($month, '0'));
        $prevmonth = $month-1;
        $nextmonth = $month+1;
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

        // return $currents;
        
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
        
        $pptks = Jabatan::where('id', '>', 1)->orderBy('jabatan', 'ASC')->get();
                        
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
        
        return view('home', compact('user', 'program', 'kegiatan', 'subkegiatan', 'chart1', 'chart2', 'currents', 'prevs', 'nexts', 'pptks', 'RTRealisasiKeuangan'));
    }
}
