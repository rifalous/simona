@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      "iDisplayLength": 10
    });

} );
</script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">

  <!-- <div class="col-lg-2">
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i> Tambah Transaksi</a>
  </div> -->
    <div class="col-lg-12">
                  @if (Session::has('message'))
                  <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">{{ Session::get('message') }}</div>
                  @endif
                  </div>
</div>
<div class="row" style="margin-top: 20px;">
<div class="col-lg-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Data Keuangan - Berdasarkan Kegiatan</h4>
                  
                  <div class="table-responsive">
                    <table class="table table-striped" id="table">
                      <thead>
                        <tr>
                          <th rowspan="2">
                            ID Master
                          </th>
                          <th rowspan="2">
                            Uraian
                          </th>
                          <th colspan="4" align="center">
                            Anggaran
                          </th>
                          <th colspan="4" align="center">
                            Realisasi
                          </th>
                          <th rowspan="2">
                            Bulan & Tahun
                          </th>
                        </tr>
                        <tr>
                          <th>
                            Volume
                          </th>
                          <th>
                            Satuan
                          </th>
                          <th>
                            Harga
                          </th>
                          <th>
                            Total
                          </th>
                          <th>
                            Volume
                          </th>
                          <th>
                            Satuan
                          </th>
                          <th>
                            Harga
                          </th>
                          <th>
                            Total
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($getDataAnggaran as $dataAnggaran)
                        <tr>
                          <td class="py-1">
                            {{ $dataAnggaran->id_master }}
                          </td>
                          <td>
                            {{ $dataAnggaran->kd_rincian.' - '.$dataAnggaran->det_rincian }}
                          </td>
                          <td>
                            {{ $dataAnggaran->vol_anggaran }}
                          </td>
                          <td>
                            {{ $dataAnggaran->satuan_anggaran }}
                          </td>
                          <td>
                            {{"Rp. ".number_format($dataAnggaran->harga_anggaran)}}
                          </td>
                          <td>
                            {{"Rp. ".number_format($dataAnggaran->total_anggaran)}}
                          </td>
                          <td>
                            {{ $dataAnggaran->vol_realisasi }}
                          </td>
                          <td>
                            {{ $dataAnggaran->satuan_realisasi }}
                          </td>
                          <td>
                            {{"Rp. ".number_format($dataAnggaran->harga_realisasi)}}
                          </td>
                          <td>
                            {{"Rp. ".number_format($dataAnggaran->total_realisasi)}}
                          </td>
                          <td>
                          @php
                            if ($dataAnggaran->bulan == 1) 
                              echo "Januari";
                            else if ($dataAnggaran->bulan == 2)
                              echo "Februari";
                            else if ($dataAnggaran->bulan == 3)
                              echo "Maret";
                            else if ($dataAnggaran->bulan == 4)
                              echo "April";
                            else if ($dataAnggaran->bulan == 5)
                              echo "Mei";
                            else if ($dataAnggaran->bulan == 6)
                              echo "Juni";
                            else if ($dataAnggaran->bulan == 7)
                              echo "Juli";
                            else if ($dataAnggaran->bulan == 8)
                              echo "Agustus";
                            else if ($dataAnggaran->bulan == 9)
                              echo "September";
                            else if ($dataAnggaran->bulan == 10)
                              echo "Oktober";
                            else if ($dataAnggaran->bulan == 11)
                              echo "November";
                            else if ($dataAnggaran->bulan == 12)
                              echo "Desember";
                          @endphp
                            {{ ' '.$dataAnggaran->tahun }}
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
               {{--  {!! $getDataAnggaran->links() !!} --}}
                </div>
              </div>
            </div>
          </div>
@endsection