<!-- CSS Serialize  -->
<link href="<?= base_url('plugins/select2/select2.min.css') ?>" rel="stylesheet" />
<link href="<?= base_url('plugins/select2/select2-bootstrap.min.css') ?>" rel="stylesheet" />

<div class="row">
	<div class="col-lg-6">
		<section class="panel">
			<header class="panel-heading">
				Pencarian Riwayat berdasarkan :
			</header>
			<div class="panel-body form-horizontal">
				<div class="form-group">
				  	<label class="col-sm-4 control-label">Pilih Tahun Anggaran</label>
				  	<div class="col-sm-3">
						<select name="slctth" id="slctth" class="form-control filter">
							<?= $this->Thanggaran->opsiByTahunAktif() ?>
						</select>
				  	</div>
				  	<label class="col-sm-3 control-label">Triwulan</label>
				  	<div class="col-sm-2">
						<select name="slcttriwulan" id="slcttriwulan" class="form-control filter">
							<?= $this->Triwulan->optionSelect() ?>
						</select>
				  	</div>
				</div>
				<div class="form-group">
				  	<label class="col-sm-4 control-label">Cari Pegawai</label>
				  	<div class="col-sm-8">
						<select name="slctpegawai" id="slctpegawai" class="form-control filter">
						</select>
				  	</div>
				</div>
				<div class="form-group">
				  	<label class="col-sm-4 control-label">Lokasi Aktif</label>
				  	<div class="col-sm-8">
						<select name="slctlokasi" id="slctlokasi" class="form-control filter">
							<option value=""> Silahkan pilih Pegawai dahulu ! </option>
						</select>
				  	</div>
				</div>
				<button type="button" class="btn btn-sm btn-default btn-block filter" id="btn-tampilkan" disabled> Tampilkan Form Pengisian Rekap D2D </button>
			</div>
		</section>
	</div>

	<div class="col-lg-6" id="div-form-inputan" style="display:none;">
		<section class="panel">
			<header class="panel-heading">
				FORM INPUTAN REKAM D2D :
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

<!-- Serialize -->
<script type="text/javascript" src="<?= base_url('plugins/select2/select2.min.js') ?>"></script>

<!-- Number -->
<script type="text/javascript" src="<?= base_url('plugins/jquery-number-master/jquery.number.min.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script>

	$(function(){
		

		$("#slctpegawai").select2({
			  theme: "bootstrap",
			  ajax: {
				url: "<?= base_url('get_select_by_nama_pegawai') ?>",
				dataType: 'json',
				delay: 250,
				data: function (params) {
				  return {
					q: params.term, // search term
					page: params.page
				  };
				},
				processResults: function (data, params) {
					// parse the results into the format expected by Select2.
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			  },
			  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			  minimumInputLength: 1,
			  //formatResult: format,
	          //formatSelection: format
			  templateResult: formatRepo, // omitted for brevity, see the source of this page
			  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
		}).on("select2:select", function(e) {
			getDetilData(this.value);
			//$('#btn-tambah').attr('disabled', false);
        });

		$('#btn-tampilkan').click(function(){
			var idt = $('#slcttriwulan').val();
			var irl = $('#slctlokasi').val();
			var ia = $('#slctth').val();
			var ip = $('#slctpegawai').val();
			$.ajax({
	            type: 'POST',
	            url: '<?= base_url("show_form_inputan") ?>',
	            dataType: "JSON",
	            data: 'idt='+ idt +'&irl='+ irl +'&ia='+ ia +'&ip='+ ip,
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
									$.each(html.arrBulan, function(key, value) {
										$divinputan.append('<div class="form-group">'+
										  	'<label class="col-sm-6 control-label">'+ value.bulan +'</label>'+
										  	'<div class="col-sm-4">'+
										  		'<input type="hidden" name="txtbulan[]" value="'+ value.id_bulan +'">'+
												'<input type="text" name="txtjumlah[]" id="jml_'+ value.id_bulan +'" class="form-control nominal" value="'+ value.jumlah +'">'+
										  	'</div>'+
										'</div>');
									});
									$('.filter').attr('disabled', true);
									$('.nominal').number( true, 0 );
									
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
			var idt = $('#slcttriwulan').val();
			var irl = $('#slctlokasi').val();
			var ia = $('#slctth').val();
			var ip = $('#slctpegawai').val();
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_simpan_rekam") ?>',
					dataType: "JSON",
					data: $('form').serialize() +'&haksi=add&idt='+ idt +'&irl='+ irl +'&ia='+ ia +'&ip='+ ip,
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
								
								var $opsilokasi = $("#slctlokasi");
								$opsilokasi.empty();
								$.each(html.arrLokasiAktif, function(key, value) {
									$opsilokasi.append('<option value="'+ value.id_riwayat_lokasi +'"> '+ value.lokasi +' </option>');
								});
								$('#btn-tampilkan').attr('disabled', false);
								
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