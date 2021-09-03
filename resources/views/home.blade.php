@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table1').DataTable({
      "iDisplayLength": 10
    });
    $('#table2').DataTable({
      "iDisplayLength": 10
    });
    $('#table3').DataTable({
      "iDisplayLength": 10
    });
    $('#table4').DataTable({
      "iDisplayLength": 10
    });

} );
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
@stop
@extends('layouts.app')

@section('content')

<div class="row">

<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-coin text-success icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Real Time Realisasi Keuangan</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$RTRealisasiKeuangan}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Real Time Realisasi Keuangan
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-coin text-warning icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Realisasi Keuangan by Simda</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">Coming Soon</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Realisasi Keuangan by Simda
                  </p>
                </div>
              </div>
            </div>
</div>
@if(Auth::user()->level == 'admin')
<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-account-location text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">User</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$user->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Total User
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-book text-warning icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Program</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$program->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Total Program
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-receipt text-success icon-lg" style="width: 40px;height: 40px;"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Kegiatan</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$kegiatan->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Total Kegiatan
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-book-open text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Sub Kegiatan</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$subkegiatan->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="" aria-hidden="true"></i> Total Sub Kegiatan
                  </p>
                </div>
              </div>
            </div>
</div>
@endif

<div class="row" >
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div style="width:100%;">
            {!! $chart1->render() !!}
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div style="width:100%;">
            {!! $chart2->render() !!}
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Anggaran Kegiatan Bulan Sebelumnya B-1</h4>
          <div class="table-responsive">
            <table class="table table-striped" id="table1">
              <thead>
              <tr>
                <th>
                  Kode
                </th>
                <th>
                  Sub Kegiatan
                </th>
                <th>
                  Rincian
                </th>
                <th>
                  Anggaran
                </th>
              </tr>
              </thead>
              <tbody>
              @foreach($prevs as $prev)
              <tr>
                <td class="py-1">
                  {{$prev->kd_subkeg}}
                </td>
                <td class="py-1">
                  {{$prev->nama_subkeg}}
                </td>
                <td class="py-1">
                  {{$prev->nama_rincian}}
                </td>
                <td class="py-1">
                  {{"Rp. ".number_format($prev->anggaran)}}
                </td>
              </tr>
              @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>

  
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Anggaran Kegiatan Bulan Ini</h4>
          <div class="table-responsive">
            <table class="table table-striped" id="table2">
              <thead>
              <tr>
                <th>
                  Kode
                </th>
                <th>
                  Sub Kegiatan
                </th>
                <th>
                  Rincian
                </th>
                <th>
                  Anggaran
                </th>
              </tr>
              </thead>
              <tbody>
              @foreach($currents as $current)
              <tr>
                <td class="py-1">
                  {{$current->kd_subkeg}}
                </td>
                <td class="py-1">
                  {{$current->nama_subkeg}}
                </td>
                <td class="py-1">
                  {{$current->nama_rincian}}
                </td>
                <td class="py-1">
                  {{"Rp. ".number_format($current->anggaran)}}
                </td>
              </tr>
              @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>

  
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Anggaran Kegiatan Bulan Selanjutnya B+1</h4>
          <div class="table-responsive">
            <table class="table table-striped" id="table3">
              <thead>
              <tr>
                <th>
                  Kode
                </th>
                <th>
                  Sub Kegiatan
                </th>
                <th>
                  Rincian
                </th>
                <th>
                  Anggaran
                </th>
              </tr>
              </thead>
              <tbody>
              @foreach($nexts as $next)
              <tr>
                <td class="py-1">
                  {{$next->kd_subkeg}}
                </td>
                <td class="py-1">
                  {{$next->nama_subkeg}}
                </td>
                <td class="py-1">
                  {{$next->nama_rincian}}
                </td>
                <td class="py-1">
                  {{"Rp. ".number_format($next->anggaran)}}
                </td>
              </tr>
              @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>

  
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Realisasi Penyerapan By PPTK</h4>
          <div class="table-responsive">
            <table class="table table-striped" id="table4">
              <thead>
              <tr>
                <th>
                  Nomor
                </th>
                <th>
                  PPTK
                </th>
                <th>
                  Anggaran
                </th>
                <th>
                  Realisasi
                </th>
              </tr>
              </thead>
              <tbody>
              @php 
                $i=0;
                $no=1;
              @endphp
              @foreach($pptks as $pptk)
              <tr>
                <td class="py-1">
                  {{$no}}
                </td>
                <td class="py-1">
                  {{$pptk->jabatan}}
                </td>
                <td class="py-1">
                  {{"Rp. ".number_format($pptkAnggaran[$i])}} 
                </td>
                <td class="py-1">
                  {{"Rp. ".number_format($pptkRealisasi[$i])}} 
                </td>
              </tr>
              @php 
                $i++;
                $no++; 
              @endphp
              @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
</div>



@endsection
