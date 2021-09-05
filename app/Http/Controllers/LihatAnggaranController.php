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

class LihatAnggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $arr_dummy = [['id' => '', 'kode' => '', 'text' => '']];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $bulan = $request->get('bulan');

        if (Auth::user()->level == 'admin') {
            $id_jabatan = '';
        }
        else {
            $id_jabatan = $request->get('id_jabatan');
            $id_jabatan = ' AND id_jabatan = '.$id_jabatan;
        }

        $getDataAnggaran = DB::select("select 
                            `t_master_det_rincian`.`id` as `id_master`, 
                            `t_master_det_rincian`.`vol` as `vol_anggaran`, 
                            `t_master_det_rincian`.`satuan` as `satuan_anggaran`, 
                            `t_master_det_rincian`.`harga` as `harga_anggaran`, 
                            `t_master_det_rincian`.`total` as `total_anggaran`, 
                            `t_det_realisasi`.`bulan`, 
                            `t_det_realisasi`.`tahun`, 
                            `t_det_realisasi`.`vol` as `vol_realisasi`, 
                            `t_det_realisasi`.`satuan` as `satuan_realisasi`, 
                            `t_det_realisasi`.`harga` as `harga_realisasi`, 
                            `t_det_realisasi`.`realisasi` as `total_realisasi`, 
                            `t_det_rincian`.`nama_det_rincian` as `det_rincian`, 
                            `t_rincian`.`kd_rincian`, 
                            `t_rincian`.`nama_rincian` as `rincian`, 
                            `t_subkeg`.`kd_subkeg`, 
                            `t_subkeg`.`nama_subkeg` as `subkeg`, 
                            `t_keg`.`kd_keg`, 
                            `t_keg`.`nama_keg` as `keg`, 
                            `t_prog`.`kd_prog`, 
                            `t_prog`.`nama_prog` as `prog` , 
                            `t_mapping_jabatan_to_subkeg`.`id_jabatan` 
                        from 
                            `t_master_det_rincian` 
                            left join `t_det_realisasi` on `t_master_det_rincian`.`id` = `t_det_realisasi`.`id_master_det_rincian` 
                            inner join `t_det_rincian` on `t_master_det_rincian`.`id_det_rincian` = `t_det_rincian`.`id` 
                            inner join `t_rincian` on `t_det_rincian`.`id_rincian` = `t_rincian`.`id` 
                            inner join `t_subkeg` on `t_rincian`.`id_subkeg` = `t_subkeg`.`id` 
                            inner join `t_mapping_jabatan_to_subkeg` on `t_subkeg`.`id` = `t_mapping_jabatan_to_subkeg`.`id_subkeg` 
                            inner join `t_keg` on `t_subkeg`.`id_keg` = `t_keg`.`id` 
                            inner join `t_prog` on `t_keg`.`id_prog` = `t_prog`.`id`
                        where 
                            bulan = $bulan $id_jabatan
                        group by 
                            `id_master`");

        $programs = Program::get();
        // return response()->json($getDataAnggaran, 200);
        return view('transaksi.index', compact(['getDataAnggaran', 'programs']));
        // return response()->json(['months', 'anggaran', 'realisasi', 'persenAnggaran', 'persenRealisasi']);
        // return response()->json([$anggaran, $realisasi], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData()
    {
        
        $getData = DB::table('t_master_det_rincian')
                ->select('t_master_det_rincian.nama_det_rincian', 't_det_rincian.*', 't_rincian.*')
                ->join('t_det_rincian', 't_master_det_rincian.id_det_rincian', '=', 't_det_rincian.id')
                ->join('t_rincian', 't_det_rincian.id_rincian', '=', 't_rincian.id')
                ->get();

        return view('transaksi.create', compact('bukus', 'kode', 'anggotas'));
    }

    public function getKegiatan($id_prog)
    {
        $kegiatan = Kegiatan::select('id', 'kd_keg as kode', 'nama_keg as text')
                            ->where('id_prog', $id_prog)
                            ->get();

        foreach ($kegiatan as $k) {
            $k->text = $k->kode.' - '.$k->text;
        }
        return response()->json(array_merge($this->arr_dummy, $kegiatan->toArray()));
    }

    public function getSubKegiatan($id_keg)
    {
        $subkegiatan = SubKegiatan::select('id', 'kd_subkeg as kode', 'nama_subkeg as text')
                            ->where('id_keg', $id_keg)
                            ->get();
        foreach ($subkegiatan as $sk) {
            $sk->text = $sk->kode.' - '.$sk->text;
        }
        
        return response()->json(array_merge($this->arr_dummy, $subkegiatan->toArray()));
    }
}
