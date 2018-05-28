<!-- CSS Serialize  -->
<link href="<?= base_url('plugins/select2/select2.min.css') ?>" rel="stylesheet" />
<link href="<?= base_url('plugins/select2/select2-bootstrap.min.css') ?>" rel="stylesheet" />

<div class="row">
	<div class="col-lg-6">
		<section class="panel">
			<header class="panel-heading">
				Rekam Data Obyek Pemungutan Pajak Daerah :
			</header>
			<div class="panel-body form-horizontal">
				<div class="form-group">
				  	<label class="col-sm-2 control-label">Tahun</label>
				  	<div class="col-sm-4">
						<select name="slctth" id="slctth" class="form-control filter">
							<?= $this->Thanggaran->opsiByTahunAktif() ?>
						</select>
				  	</div>
				  	<label class="col-sm-2 control-label">Bulan</label>
				  	<div class="col-sm-4">
						<select name="slctbulan" id="slctbulan" class="form-control filter">
							<?= $this->Bulan->opsiSemuaBulan() ?>
						</select>
				  	</div>
				</div>
				<div class="form-group">
				  	<label class="col-sm-2 control-label">Lokasi</label>
				  	<div class="col-sm-4">
						<select name="slctlokasi" id="slctlokasi" class="form-control filter">
							<?= $this->Lokasi->optionAll() ?>
						</select>
				  	</div>
				</div>
				<button type="button" class="btn btn-sm btn-info btn-block filter" id="btn-tampilkan"> <i class="fa fa-share"></i> Lanjutkan Proses </button>
			</div>
		</section>
	</div>
	<div class="col-lg-6" id="div-form-inputan" style="display:none;">
		<section class="panel">
			<header class="panel-heading">
				ENTRY LAPORAN JUMLAH OBYEK PAJAK DAERAH :
			</header>
			<div class="panel-body">
				<form class="form-horizontal">
					<div id="dinamis-input">
					</div>					
					<div class="text-center">
						<button type="submit" class="btn btn-sm btn-flat btn-success"><i class="glyphicon glyphicon-ok-circle"></i> SIMPAN </button>
						<button type="button" class="btn btn-sm btn-flat btn-danger" id="btn-batal"><i class="glyphicon glyphicon-remove-circle"></i> BATAL </button>
					</div>
				</form>
			</div>
		</section>
	</div>
</div>

<!-- Number -->
<script type="text/javascript" src="<?= base_url('plugins/jquery-number-master/jquery.number.min.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script>

	$(function(){
		$('#slctbulan').val("<?= date('n') ?>");

		$('#slctlokasi').change(function(){
			$('#div-form-inputan').hide('fast');
			$('btn-tampilkan').click();
		});

		$('#btn-tampilkan').click(function(){
			var bulan = $('#slctbulan').val();
			var lokasi = $('#slctlokasi').val();
			var ia = $('#slctth').val();
			$.ajax({
	            type: 'POST',
	            url: '<?= base_url("load_pungutpd") ?>',
	            dataType: "JSON",
	            data: 'bulan='+ bulan +'&lokasi='+ lokasi +'&ia='+ ia,
	            success: function(html) {

	                setTimeout(function() {
	                    $.unblockUI({
	                        onUnblock: function() {
	                            if (html.error == '')
	                            {
									$('#div-form-inputan').show('fast');
									$('form')[0].reset();
									$('form').formValidation('resetForm', true);
									var $divinputan = $("#dinamis-input");
									$divinputan.empty();

									var getHtml = '<table class="table">'+
										'<thead><tr>'+
										'<th width="60%">Jenis Pajak Daerah</th>'+
										'<th>Jumlah Obyek</th>';
									getHtml += '</tr></thead>'+
									'<tbody>';

									$.each(html.arrPd, function(key, value) {

										if ( value.disabled == 1 ) {
											getHtml += '<tr>'+
												'<td class="text-right" valign="middle">'+
													'<strong>TOTAL OBYEK '+ value.nama_rekening +'</strong>'+
												'</td><td>'+
													'<input type="text" class="form-control nominal text-right '+ value.nama_class +'" disabled>'+
												'</td>'+
											'</tr>';
										} else {
											getHtml += '<tr>'+
												'<td>'+
													'<input type="hidden" name="txtpd[]" value="'+ value.id_rek_pd +'" class="form-control">'+ value.nama_rekening +
												'</td><td>'+
													'<input type="text" name="txtjumlah[]" value="'+ value.jumlah +'" class="form-control nominal text-right '+ value.nama_class +'">'+
												'</td>'+
											'</tr>';
										}

										
									});

									getHtml += '</tbody>'+
									'</table>';

									var $divinputan = $("#dinamis-input");
									$divinputan.empty();
									$divinputan.append(getHtml);

									$('.filter').attr('disabled', true);
									$('.nominal').number( true, 0 );
									
									var totalpkb = 0;
									$('.pkb').each(function(){
										totalpkb += parseInt(this.value.replace(/,/g,''));
									});
									$('.totalpkb').val(totalpkb);
									var totalbbnkb = 0;
									$('.bbnkb').each(function(){
										totalbbnkb += parseInt(this.value.replace(/,/g,''));
									});
									$('.totalbbnkb').val(totalbbnkb);

									$('.pkb').keyup(function(){
										var total = 0;
										$('.pkb').each(function(){
											if ( this.value == '' ) {
												var val = 0;
											} else {
												var val = this.value.replace(/,/g,'');
											}
											total += parseInt(val);
										});
										$('.totalpkb').val(total);
									});

									$('.bbnkb').keyup(function(){
										var total = 0;
										$('.bbnkb').each(function(){
											if ( this.value == '' ) {
												var val = 0;
											} else {
												var val = this.value.replace(/,/g,'');
											}
											total += parseInt(val);
										});
										$('.totalbbnkb').val(total);
									});
	                            } else {
	                                pesanError(html.error);
	                                $('.filter').attr('disabled', false);
	                                $('#div-form-inputan').hide('fast');
	                            }
	                        }
	                    });
	                }, 1000);
	            },
	            beforeSend: function() {
	                loadoverlay();
	            },
	            error: function(xhr, ajaxOptions, thrownError) {
	                setTimeout(function() {
	                    $.unblockUI({
	                        onUnblock: function() {
	                            pesanError(xhr.responseText);
	                        }
	                    });
	                }, 1000);
	            }
	        });
		});

		$('form').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			}
		})
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			var bulan = $('#slctbulan').val();
			var lokasi = $('#slctlokasi').val();
			var ia = $('#slctth').val();
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_pungutpd") ?>',
					dataType: "JSON",
					data: $('form').serialize() +'&bulan='+ bulan +'&lokasi='+ lokasi +'&ia='+ ia,
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										$('#div-form-inputan').hide('fast');
										$('.filter').attr('disabled', false);
										pesanOK(html.msg);
									} else {
										pesanError(html.error);
									}
								}
							});
						}, 1000);
					},
					beforeSend: function() {
						loadoverlay();
					},
					error: function(xhr, ajaxOptions, thrownError) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									pesanError(xhr.responseText);
								}
							});
						}, 1000);
					}
				});
			}
            
        });
        
		$('#btn-batal').click(function(){
			$('#div-form-inputan').hide('fast');
			$('.filter').attr('disabled', false);
		});

		$('.pkb').keyup(function(){
			//getTotalPKB()
			alert('GOOD');
		});

	});

	function formatRepo (repo) {
		if (repo.loading) return repo.text;

		var markup = "<div class='select2-result-repository clearfix'>" +
				"<div class='select2-result-repository__avatar'>" + repo.nama_pegawai + "</div>" +
					"<div class='select2-result-repository__meta'>" +
					"<div class='select2-result-repository__title'>NIP : " + repo.nip + "</div>"+
				"</div>"+
			"</div>";

		  return markup;
    }

    function formatRepoSelection (repo) {
		return repo.nama_pegawai || repo.text;
    }

    function getDetilData(ID)
	{
		$.ajax({
            type: 'POST',
            url: '<?= base_url("detil_data_pegawai") ?>',
            dataType: "JSON",
            data: 'hID=' + ID,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
								$('#id_jabatan').val(html.id_jabatan);
								$('#id_sts_pegawai').val(html.id_sts_pegawai);
								$('#btn-tampilkan').attr('disabled', false);
								$('#btn-tampilkan').click();
                            } else {
                                pesanError(html.error);
                                $('#btn-tampilkan').attr('disabled', true);
                                $('#div-form-inputan').hide('fast');
                            }
                        }
                    });
                }, 1000);
            },
            beforeSend: function() {
                loadoverlay();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            pesanError(xhr.responseText);
                        }
                    });
                }, 1000);
            }
        });
	}

</script>