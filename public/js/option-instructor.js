function getKegiatan(value) {
    var SITE_URL = "{{ url('/') }}";
	var res = $.ajax({

		url: '/transaksi/get_kegiatan/'+value,
		dataType: 'json',
		type: 'get',
		async: false
	
	});

	$('[name="id_kegiatan"]').find('option').remove();
	$('[name="id_subkegiatan"]').find('option').remove();

	$('[name="id_kegiatan"]').select2({
		data: res.responseJSON
	});
}

function getSubKegiatan(value) {
    var SITE_URL = "{{ url('/') }}";
	var res = $.ajax({

		url: '/transaksi/get_subkegiatan/'+value,
		dataType: 'json',
		type: 'get',
		async: false
	
	});

	$('[name="id_subkegiatan"]').find('option').remove();

	$('[name="id_subkegiatan"]').select2({
		data: res.responseJSON
	});
}