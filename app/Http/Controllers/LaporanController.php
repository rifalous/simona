<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Buku;
use App\Anggota;
use App\Transaksi;
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
        return view('laporan.realisasi_anggaran_global', compact('datas'));
        //    $pdf = PDF::loadView('laporan.transaksi_pdf', compact('datas'));
        //    return $pdf->download('laporan_transaksi_'.date('Y-m-d_H-i-s').'.pdf');
    }


    public function realisasiAnggaranExcel(Request $request)
    {
        $nama = 'realisasi_anggaran_'.date('Y-m-d_H-i-s');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Laporan Data Transaksi', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:H1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('LAPORAN DATA TRANSAKSI'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $q = Transaksi::query();

        if($request->get('status')) 
        {
             if($request->get('status') == 'pinjam') {
                $q->where('status', 'pinjam');
            } else {
                $q->where('status', 'kembali');
            }
        }

        if(Auth::user()->level == 'user')
        {
            $q->where('anggota_id', Auth::user()->anggota->id);
        }

        $datas = $q->get();

       // $sheet->appendRow(array_keys($datas[0]));
        $sheet->row($sheet->getHighestRow(), function ($row) {
            $row->setFontWeight('bold');
        });

         $datasheet = array();
         $datasheet[0]  =   array("NO", "KODE TRANSAKSI", "BUKU", "PEMINJAM",  "TGL PINJAM","TGL KEMBALI","STATUS", "KET");
         $i=1;

        foreach ($datas as $data) {

           // $sheet->appendrow($data);
          $datasheet[$i] = array($i,
                        $data['kode_transaksi'],
                        $data->buku->judul,
                        $data->anggota->nama,
                        date('d/m/y', strtotime($data['tgl_pinjam'])),
                        date('d/m/y', strtotime($data['tgl_kembali'])),
                        $data['status'],
                        $data['ket']
                    );
          
          $i++;
        }

        $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }
}
