<!-- CSS Datetime Picker  -->
<link href="<?= base_url('plugins/bootdatepicker/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet" />

<form class="forms form-horizontal">
	<input type="hidden" id="hID" name="hID" value="<?= $pegawai->id_pegawai ?>">
	<input type="hidden" id="haksi" name="haksi" value="add">
	<input type="hidden" id="hIdJabatan" value="<?= $pegawai->id_jabatan ?>">
	<input type="hidden" name="hIdMutasiSebelumnya" id="hIdMutasiSebelumnya" value="<?= $pegawai->id_mutasi ?>">
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<!-- Default box -->
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-4 control-label">Jenis Mutasi <span class="text-danger">*</span></label>
							<div class="col-sm-8">
								<select name="slctjenis_mutasi" id="slctjenis_mutasi" class="form-control">
									<?= $this->Mutasi->pilihan_jenis_mutasi() ?>
								</select>
							</div>
						</div>
						<div class="form-group dppad">
							<label class="col-sm-4 control-label">Lokasi Mutasi <span class="text-danger">*</span></label>
							<div class="col-sm-8">
								<select name="slctlokasi_mutasi" id="slctlokasi_mutasi" class="form-control">
									<option value=""> Pilih salah satu Lokasi </option>
									<?= $this->Lokasi->pilihanLokasiHomebase() ?>
								</select>
							</div>
						</div>
						<div class="form-group skpd">
							<label class="col-sm-4 control-label">Nama SKPD <span class="text-danger">*</span></label>
							<div class="col-sm-8">
								<input type="text" name="txtlokasi_lain" id="txtlokasi_lain" class="form-control">
							</div>
						</div>
						<div class="form-group dppad">
							<label class="col-sm-4 control-label">Lokasi D2D <span class="text-danger">*</span></label>
							<div class="col-sm-8">
								<select name="slctlokasi_d2d" id="slctlokasi_d2d" class="form-control">
								<!-- Get from JSON -->
								</select>
							</div>
						</div>
						<div class="form-group dppad">
							<label class="col-sm-4 control-label">Jabatan setelah Mutasi <span class="text-danger">*</span></label>
							<div class="col-sm-8">
								<select name="slctjabatan" id="slctjabatan" class="form-control">
								<?php
									if ( $getjabatan == '33' ) {
										echo $this->Jabatan->opsiASN();
									} else {
										echo $this->Jabatan->opsiNonASN();
									}
								?>
								</select>
							</div>
						</div>
						<div class="form-group">
						  <label class="col-sm-4 control-label">Dasar Surat Keputusan <span class="text-danger">*</span></label>
						  <div class="col-sm-8">
							<input type="text" name="txtdasarsurat" id="txtdasarsurat" class="form-control">
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-sm-4 control-label">TMT <span class="text-danger">*</span></label>
						  <div class="col-sm-8">
						  	<div class='input-group date' id='ambiltgl'>
			                    <input type='text' name="txttgl" id="txttgl" class="form-control" value="<?= date('d-m-Y') ?>" />
			                    <span class="input-group-addon">
			                        <span class="glyphicon glyphicon-calendar"></span>
			                    </span>
			                </div>
						  </div>
						</div>
						
					</div><!-- /.box-body -->
					
				</div><!-- /.box -->
			</div>
			
		</div>
	</div>  
	<div class="box-footer text-right">
		<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Simpan </button>
	</div>                        
</form>

<!-- Datetime Picker -->
<script type="text/javascript" src="<?= base_url('plugins/bootdatepicker/js/bootstrap-datepicker.min.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>
		
<script>
	$(function(){
		loadBeginForm();
		
		$('#slctjenis_mutasi').change(function(){
			actChangeJenisMutasi();
		});
		
		$('#slctlokasi_mutasi').change(function(){
			aksiUbahLokasiHomebase();
		});
		
		$('#ambiltgl').datepicker({
			format: "dd-mm-yyyy",
		    language: "id",
		    autoclose: true,
		    todayHighlight: true
		});

		$('form').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			framework: 'bootstrap',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			},
			fields: {
				slctjenis_mutasi: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu jenis mutasi !'
						}
					}
				},
				slctlokasi_mutasi: {
					validators: {
						callback: {
                            message: 'Pilih salah satu',
                            callback: function(value, validator, $field) {
                                var jenis_mutasi = $('form').find('[name="slctjenis_mutasi"]').val();
                                return (jenis_mutasi !== '333333') ? true : (value !== '');
                            }
                        }
					}
				},
				slctlokasi_d2d: {
					validators: {
						callback: {
                            message: 'Pilih salah satu',
                            callback: function(value, validator, $field) {
                                var jenis_mutasi = $('form').find('[name="slctjenis_mutasi"]').val();
                                return (jenis_mutasi !== '333333') ? true : (value !== '');
                            }
                        }
					}
				},
				txtlokasi_lain: {
					validators: {
						callback: {
                            message: 'Isikan lokasi SKPD Lainnya !',
                            callback: function(value, validator, $field) {
                                var jenis_mutasi = $('form').find('[name="slctjenis_mutasi"]').val();
                                return (jenis_mutasi !== '555555') ? true : (value !== '');
                            }
                        }
					}
				},
				slctjabatan: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu jabatan !'
						}
					}
				},
				txtdasarsurat: {
					validators: {
						notEmpty: {
							message: 'Isikan dasar surat keterangan mutasi !'
						}
					}
				},
				txttgl: {
					validators: {
						notEmpty: {
							message: 'Tanggal aktif mutasi harap di isi !'
						},
	                    date: {
	                        format: 'DD-MM-YYYY',
	                        message: 'Penulisan tanggal salah !'
	                    }
					}
				}
			}
		})
		.on('change', '[name="slctjenis_mutasi"]', function() {
            $('form').formValidation('revalidateField', 'slctlokasi_mutasi');
            $('form').formValidation('revalidateField', 'slctlokasi_d2d');
            $('form').formValidation('revalidateField', 'txtlokasi_lain');
        })
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_mutasi") ?>',
					dataType: "JSON",
					data: $('form').serialize(),
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										pesanOK(html.msg);
										window.opener.reloadTabel();
										window.close();
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
		
	});

	function  loadBeginForm()
	{
		actChangeJenisMutasi();
		var defaultJabatan = $('#hIdJabatan').val();
		$('#slctjabatan').val(defaultJabatan);
		$('#slctlokasi_asal').val("<?= $pegawai->id_homebase ?>");
	}
	
	function actChangeJenisMutasi()
	{
		var value = $('#slctjenis_mutasi').val();

		if ( value == '555555' ) {
			$('.skpd').show('fast');
			$('.dppad').hide('fast');
			$('#txtlokasi_lain').focus();
		} else if ( value == '999999' ) {
			$('.dppad, .skpd').hide('fast');
		} else if ( value == '333333' ) {
			$('.skpd').hide('fast');
			$('.dppad').show('fast');
		}
	}
	
	function aksiUbahLokasiHomebase()
	{
		var lokasiD2D = $('#slctlokasi_d2d');
		var value = $('#slctlokasi_mutasi').val();
		lokasiD2D.empty();
		$.ajax({
			type: 'POST',
			url: '<?= base_url("getlokasid2dfrommutasi") ?>',
			dataType: "JSON",
			data: "postLokasiMutasi="+ value,
			success: function(html) {
				setTimeout(function() {
					$.unblockUI({
						onUnblock: function() {
							if (html.error == "")
							{
								$.each(html.opsi, function (key, data) {
									lokasiD2D.append('<option value="'+ data.id_lokasi +'">'+ data.lokasi +'</option>');
								});
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
</script>