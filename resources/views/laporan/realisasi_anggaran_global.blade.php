<!DOCTYPE html>
<html>
<head>
	<title>Laporan Realisasi Anggaran</title>
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
		<td><img src="{{ url('images/logo-kab.png') }}" alt="" style="padding:10px; width: 80px;height: 60px;"></td>
		<td style="padding-right:130px"><center><p><strong>REKAP DATA REALISASI ANGGARAN</strong></p></center></td>
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
            <th rowspan="2">KODE REKENING</th>
            <!-- <th>Urusan/Bidang/Program/Kegiatan/Sub Kegiatan</th> -->
            <th rowspan="2">URAIAN (Program/Kegiatan/Sub Kegiatan/Rincian Belanja)</th>
            <th colspan="4">ANGGARAN</th>
            <th colspan="4">REALISASI</th>
            <th rowspan="2">SISA ANGGARAN</th>
        </tr>
        <tr>
            <th>VOLUME</th>
            <th>SATUAN</th>
            <th>HARGA</th>
            <th>JUMLAH</th>
            <th>VOLUME</th>
            <th>SATUAN</th>
            <th>HARGA</th>
            <th>JUMLAH</th>
        </tr>
	</thead>
	<tbody>
    	@foreach($dataAnggaran as $data)
			<tr>
				<td style="text-indent: 5px;"><b><i>{{ $data->kd_prog }}</i></b></td>
				<td style="text-indent: 5px;"><b><i>{{ $data->prog }}</i></b</td>
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
			</tr>
			<tr>
				<td style="text-indent: 10px;"><i>{{ $data->kd_keg }}</i></td>
				<td style="text-indent: 10px;"><i>{{ $data->keg }}</i></td>
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
			</tr>
			<tr>
				<td style="text-indent: 15px;">{{ $data->kd_subkeg }}</td>
				<td style="text-indent: 15px;">{{ $data->subkeg }}</td>
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
				<td></td> 
			</tr>
            <tr>
              	<td style="text-indent: 15px;">
                	{{ $data->kd_rincian }}
            	</td>
              	<td style="text-indent: 15px;">
				  	{{ $data->rincian }} 
            	</td>
                <td>
                	{{ $data->vol_anggaran }}
            	</td>
                <td>
                	{{ $data->satuan_anggaran }}
            	</td>
                <td>
					{{"Rp. ".number_format($data->harga_anggaran)}}
            	</td>
                <td>
					{{"Rp. ".number_format($data->total_anggaran)}}
            	</td>
                <td>
                	{{ $data->vol_realisasi }}
            	</td>
                <td>
                	{{ $data->satuan_realisasi }}
            	</td>
                <td>
					{{"Rp. ".number_format($data->harga_realisasi)}}
            	</td>
                <td>
					{{"Rp. ".number_format($data->total_realisasi)}}
            	</td>
                <td>
                	{{ "Rp. ".number_format($data->total_anggaran - $data->total_realisasi) }}
            	</td>
            </tr>
        @endforeach
	</tbody>
</table>
<br>
<br>
<br>
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
            <th>POSITION NAME</th>
        </tr>
        <tr>
            <td style="height: 80px"></td>
            <td style="height: 80px"></td>
            <td style="height: 80px"></td>
        </tr>
        <tr>
            <th class="text-center">DADANG HERMAWAN S S.IP., MAP</th>
            <th style="visibility:hidden" class="text-center">USER NAME</th>
            <th class="text-center">USER NAME</th>
        </tr>
        <tr>
            <th class="text-center">NIP. 196408271991031006</th>
            <td></td>
            <th class="text-center">NIP. USER NIP</th>
        </tr>
    </table>
</div>
</body>
</html>