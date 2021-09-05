<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Buku;
use App\Anggota;
use App\Transaksi;
use App\MappingUserToJabatan;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Excel;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class LaporanController extends Controller
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

    public function realisasiAnggaran()
    {

        return view('laporan.index');
    }


    public function realisasiAnggaranPdf(Request $request)
    {
        if (Auth::user()->level == 'admin') {
            $getUserInfo = '';
        }
        else {
            $getUserInfo = User::where('t_users.id', '=', Auth::user()->id)
            ->select('t_users.*', 't_mapping_user_to_jabatan.*','t_jabatan.jabatan')
            ->join('t_mapping_user_to_jabatan', function($join) {
                $join->on('t_users.id', '=', 't_mapping_user_to_jabatan.id_user');
            })
            ->leftjoin('t_jabatan', function($join) {
                $join->on('t_mapping_user_to_jabatan.id_jabatan', '=', 't_jabatan.id');
            })
            ->first();
        }

        $dataAnggaran = DB::select("select 
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
                    `t_prog`.`nama_prog` as `prog` 
                from 
                    `t_master_det_rincian` 
                    left join `t_det_realisasi` on `t_master_det_rincian`.`id` = `t_det_realisasi`.`id_master_det_rincian` 
                    inner join `t_det_rincian` on `t_master_det_rincian`.`id_det_rincian` = `t_det_rincian`.`id` 
                    inner join `t_rincian` on `t_det_rincian`.`id_rincian` = `t_rincian`.`id` 
                    inner join `t_subkeg` on `t_rincian`.`id_subkeg` = `t_subkeg`.`id` 
                    inner join `t_keg` on `t_subkeg`.`id_keg` = `t_keg`.`id` 
                    inner join `t_prog` on `t_keg`.`id_prog` = `t_prog`.`id` 
                group by 
                    `id_master`
                order by `kd_subkeg` ASC");

        return view('laporan.realisasi_anggaran_global', compact(['dataAnggaran', 'getUserInfo']));
        // $pdf = PDF::loadView('laporan.realisasi_anggaran_global', compact('dataAnggaran'));
        // return $pdf->download('laporan_transaksi_'.date('Y-m-d_H-i-s').'.pdf');
    }


    public function realisasiAnggaranExcel(Request $request)
    {
        $nama = 'realisasi_anggaran_'.date('Y-m-d_H-i-s');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Laporan Data Transaksi', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:K1');
        // $sheet->mergeCells('A2:A3');
        // $sheet->mergeCells('B2:B3');
        // $sheet->mergeCells('C2:F2');
        // $sheet->mergeCells('G2:J2');

    //    $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('LAPORAN DATA REALISASI'));
        // $sheet->row(2, array("KODE REKENING", "URAIAN (Program/Kegiatan/Sub Kegiatan/Rincian Belanja)", "ANGGARAN", "REALISASI", "SISA ANGGARAN"));
        // $sheet->row(3, array("Volume Anggaran", "Satuan Anggaran", "Harga Anggaran", "Total Anggaran", "Volume Realisasi", "Satuan Realisasi", "Harga Realisasi", "Total Realisasi"));
        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $datas = DB::select("select 
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
                    `t_prog`.`nama_prog` as `prog` 
                from 
                    `t_master_det_rincian` 
                    left join `t_det_realisasi` on `t_master_det_rincian`.`id` = `t_det_realisasi`.`id_master_det_rincian` 
                    inner join `t_det_rincian` on `t_master_det_rincian`.`id_det_rincian` = `t_det_rincian`.`id` 
                    inner join `t_rincian` on `t_det_rincian`.`id_rincian` = `t_rincian`.`id` 
                    inner join `t_subkeg` on `t_rincian`.`id_subkeg` = `t_subkeg`.`id` 
                    inner join `t_keg` on `t_subkeg`.`id_keg` = `t_keg`.`id` 
                    inner join `t_prog` on `t_keg`.`id_prog` = `t_prog`.`id` 
                group by 
                    `id_master`");

        // $sheet->appendRow(array_keys($datas[0]));
        $sheet->row($sheet->getHighestRow(), function ($row) {
            $row->setFontWeight('bold');
        });

         $datasheet = array();
        //  $datasheet[0] =  array("KODE REKENING", "URAIAN (Program/Kegiatan/Sub Kegiatan/Rincian Belanja)", "ANGGARAN", "REALISASI", "SISA ANGGARAN")
         $datasheet[0] =  array("KODE REKENING", "URAIAN (Program/Kegiatan/Sub Kegiatan/Rincian Belanja)","Volume Anggaran", "Satuan Anggaran", "Harga Anggaran", "Total Anggaran", "Volume Realisasi", "Satuan Realisasi", "Harga Realisasi", "Total Realisasi", "Sisa Anggaran");
         $i=1;

        foreach ($datas as $data) {

           // $sheet->appendrow($data);
          $datasheet[$i] = array(
                        $data->kd_rincian,
                        $data->rincian,
                        $data->vol_anggaran,
                        $data->satuan_anggaran,
                        $data->harga_anggaran,
                        $data->total_anggaran,
                        $data->vol_realisasi,
                        $data->satuan_realisasi,
                        $data->harga_realisasi,
                        $data->total_realisasi,
                        $data->total_anggaran-$data->total_realisasi
                    );
          
          $i++;
        }

        $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }
}
