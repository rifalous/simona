<!DOCTYPE html>
<html>
<head>
	<title>Laporan Rekap Pra Rencana Kerja Anggaran</title>
</head>
<body>
<style type="text/css">

	body {
		color: #333;
	}

	table {
	    border-collapse: collapse;
	    font-size: 12px;
	}

	p {
		font-size: 13px;
	}

	.custom-table thead {
		background-color: #e1e1e1;
	}

	.custom-table tr > th, .custom-table tr > td {
		border: 1px solid #ccc;
		box-shadow: none;
		padding: 5px;
	}

	.text-center {
		text-align: center;
	}

	.top-table {
		margin-bottom: 10px;
	}

	.top-table tr > td {
		padding: 3px 10px;
	}

</style>
<!-- <center><p><strong>Rekap Data Rencana Kerja Anggaran</strong></p></center> -->
<table style="width: 100%; border:1px #ccc solid;">
	<tr>
		<td><img src="{{ url('assets/images/logo-kab.png') }}" alt="" style="padding:10px; width: 80px;height: 60px;"></td>
		<td style="padding-right:130px"><center><p><strong>REKAP DATA PRA RENCANA KERJA ANGGARAN</strong></p></center></td>
	</tr>
<table>

<table style="width: 100%; border:1px #ccc solid;">
	<tr>
		<td style="padding-left:10px;padding-top:10px;padding-bottom:5px" width="12%"><b>Urusan Pemerintahan</b></td>
		<td width="3%" align="center"><b>:</b></td>
		<td width="13%">7</td>
		<td width="72%">UNSUR KEWILAYAHAN</td>
	</tr>
	<tr>
		<td style="padding-left:10px;padding-top:5px;padding-bottom:5px"><b>Bidang Urusan </b></td>
		<td align="center"><b>:</b></td>
		<td>7 . 01</td>
		<td>Administrasi Pemerintahan (Kecamatan)</td>
	</tr>
	<tr>
		<td style="padding-left:10px;padding-top:5px;padding-bottom:5px"><b>Organisasi</b></td>
		<td align="center"><b>:</b></td>
		<td>7-01.0-00.0-00.00</td>
		<td>KECAMATAN RANCABALI</td>
	</tr>
	<tr>
		<td style="padding-left:10px;padding-top:5px;padding-bottom:5px"><b>Sub Organisasi</b></td>
		<td align="center"><b>:</b></td>
		<td>7-01.0-00.0-00.00 . 01</td>
		<td>KECAMATAN RANCABALI</td>
	</tr>
<table>

<br>

<table class="custom-table" style="width: 100%">
	<thead>
		
        <tr>
            <th>KODE REKENING</th>
            <!-- <th>Urusan/Bidang/Program/Kegiatan/Sub Kegiatan</th> -->
            <th>URAIAN (Program/Kegiatan/Sub Kegiatan/Rincian Belanja)</th>
            <th>VOLUME</th>
            <th>SATUAN</th>
            <th>HARGA</th>
            <th>JUMLAH</th>
        </tr>
	</thead>
	<tbody>
        <!-- @php ($i = 1) -->
        @php ($kode = '')
		@foreach($anggarans as $anggaran)
			@if($anggaran->sub_activity_kode == $kode)
				@if(!empty($anggaran->keterangan))
				<tr>
					<td style="text-indent: 20px;">{{ $anggaran->rincian_kode }}</td>
					<td style="text-indent: 20px;">{{ $anggaran->rincian_name }}<br></td>
					<td></td> 
					<td></td> 
					<td></td> 
					<td></td> 
				</tr>
				<tr>
					<td></td> 
					<td style="text-indent: 25px;">{{ $anggaran->keterangan }}</td>
					<td>{{ number_format($anggaran->volume) }}</td>
					<td>{{ $anggaran->satuan }}</td>
					<td>Rp {{ number_format($anggaran->harga) }}</td>
					<td>Rp {{ number_format($anggaran->jumlah) }}</td> 
				</tr>
				@else
				<tr>
					<td style="text-indent: 20px;">{{ $anggaran->rincian_kode }}</td>
					<td style="text-indent: 20px;">{{ $anggaran->rincian_name }}<br></td>
					<td>{{ number_format($anggaran->volume) }}</td>
					<td>{{ $anggaran->satuan }}</td>
					<td>Rp {{ number_format($anggaran->harga) }}</td>
					<td>Rp {{ number_format($anggaran->jumlah) }}</td> 
				</tr>
				@endif
			@else
			<tr>
				<!-- <td><center>{{ $i }}</td></center> -->
				<td style="text-indent: 5px;"><b><i>{{ $anggaran->program_kode }}</i></b></td>
				<td style="text-indent: 5px;"><b><i>{{ $anggaran->program_name }}</i></b</td>
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
			</tr>
			<tr>
				<td style="text-indent: 10px;"><i>{{ $anggaran->activity_kode }}</i></td>
				<td style="text-indent: 10px;"><i>{{ $anggaran->activity_name }}</i></td>
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
			</tr>
			<tr>
				<td style="text-indent: 15px;">{{ $anggaran->sub_activity_kode }}</td>
				<td style="text-indent: 15px;">{{ $anggaran->sub_activity_name }}</td>
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
			</tr>
			@if(!empty($anggaran->keterangan))
			<tr>
				<td style="text-indent: 20px;">{{ $anggaran->rincian_kode }}</td>
				<td style="text-indent: 20px;">{{ $anggaran->rincian_name }}<br></td>
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
			</tr>
			<tr>
				<td></td> 
				<td style="text-indent: 25px;">{{ $anggaran->keterangan }}</td>
				<td>{{ number_format($anggaran->volume) }}</td>
				<td>{{ $anggaran->satuan }}</td>
				<td>Rp {{ number_format($anggaran->harga) }}</td>
				<td>Rp {{ number_format($anggaran->jumlah) }}</td> 
			</tr>
			@else
			<tr>
				<td style="text-indent: 20px;">{{ $anggaran->rincian_kode }}</td>
				<td style="text-indent: 20px;">{{ $anggaran->rincian_name }}<br></td>
				<td>{{ number_format($anggaran->volume) }}</td>
				<td>{{ $anggaran->satuan }}</td>
				<td>Rp {{ number_format($anggaran->harga) }}</td>
				<td>Rp {{ number_format($anggaran->jumlah) }}</td> 
			</tr>
			@endif
			@endif
        	@php ($kode = $anggaran->sub_activity_kode)
        <!-- @php($i++) -->
		@endforeach
	</tbody>
</table>
<br>
<br>
<br>
<div>
    <table style="width: 100%;">
        <tr>
            <th>Mengetahui,</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th>CAMAT</th>
            <th style="visibility:hidden">Checked By</th>
            <th>{{ $user->position_name }}</th>
        </tr>
        <tr>
            <td style="height: 80px"></td>
            <td style="height: 80px"></td>
            <td style="height: 80px"></td>
        </tr>
        <tr>
            <th class="text-center">DADANG HERMAWAN S S.IP., MAP</th>
            <th style="visibility:hidden" class="text-center">{{ Auth::user()->name }}</th>
            <th class="text-center">{{ $user->name }}</th>
        </tr>
        <tr>
            <th class="text-center">NIP. 19640827 199103 1 006</th>
            <td></td>
            <th class="text-center">NIP. {{ $user->nip }}</th>
        </tr>
    </table>
</div>
</body>
</html>