@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      "iDisplayLength": 12
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
                  <h4 class="card-title">Data Keuangan - Berdasarkan Bulan</h4>
                  
                  <div class="table-responsive">
                    <table class="table table-striped" id="table">
                      <thead>
                        <tr>
                          <th>
                            No
                          </th>
                          <th>
                            Bulan
                          </th>
                          <th>
                            Anggaran
                          </th>
                          <th>
                            % Anggaran
                          </th>
                          <th>
                            Realisasi Realtime
                          </th>
                          <th>
                            % Realisasi
                          </th>
                          <th>
                            Action
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($dataKeuangan as $data)
                        <tr>
                          <td class="py-1">
                            {{ $data['no'] }}
                          </td>
                          <td>
                            {{ $data['bulan'] }}
                          </td>
                          <td>
                            {{"Rp. ".number_format($data['anggaran'])}}
                          </td>
                          <td>
                            {{ $data['persenAnggaran'] }}
                          </td>
                          <td>
                            {{"Rp. ".number_format($data['realisasi'])}}
                          </td>
                          <td>
                            {{ $data['persenRealisasi'] }}
                          </td>
                          <td>
                          <div class="btn-group dropdown">
                          <button type="button" class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                          </button>
                          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                            <form action="{{route('transaksi.index')}}" method="get" enctype="multipart/form-data">
                              {{ csrf_field() }}
                              {{ method_field('get') }}
                              <input style="display:none" type="text" id="bulan" name="bulan" value="{{ $data['no'] }}">
                              <input style="display:none" type="text" id="id_jabatan" name="id_jabatan" value="{{ $getUserJabatan }}">
                              <button class="dropdown-item"> Lihat Anggaran</button>
                            </form>  
                          </div>
                        </div>
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
               {{--  {!! $dataKeuangan->links() !!} --}}
                </div>
              </div>
            </div>
          </div>
@endsection