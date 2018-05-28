<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
				<?= $title ?>
			</header>
			<div class="panel-body form-horizontal">
				<div class="col-lg-6">
					<form class="form-horizontal" id="form-filter">
						<div class="form-group">
						  	<label class="col-sm-5 control-label">Kota / UP3AD <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
						  		<select name="slctlokasi" id="slctlokasi" class="form-control filter">
								<?= $this->Lokasi->optionAllUpad() ?>
								</select>
						  	</div>
						</div>
						<div class="form-group">
						  	<label class="col-sm-5 control-label">Jenis Penyetor <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
						  		<?= $this->Jenispenyalur->printRadioButton() ?>
						  	</div>
						</div>
						<div class="form-group">
						  	<label class="col-sm-5 control-label">SPBU / Nama Perusahaan <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
						  		<select name="slctspbu" id="slctspbu" class="form-control filter">
								<option value=""> -- Pilih salah satu -- </option>
								</select>
						  	</div>
						</div>
						<div class="form-group">
						<label class="col-sm-5 control-label">Tahun <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
						  		<select name="slcttahun" id="slcttahun" class="form-control filter">
								<?= $this->Thanggaran->opsiByTahunAktif() ?>
								</select>
						  	</div>
						</div>
						<div class="form-group">
						<label class="col-sm-5 control-label">Bulan <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
						  		<select name="slctbulan" id="slctbulan" class="form-control filter">
								<?= $this->Bulan->getBulanOrderByBulanAktif() ?>
								</select>
						  	</div>
						</div>
						<div class="form-group">
						<label class="col-sm-5 control-label">Dasar <span class="text-danger">*</span></label>
						  	<div class="col-sm-7" id="radio-btn-dasar">
						  		<?= $this->Dasar->printRadioButton() ?>
						  	</div>
						</div>
						<div class="form-group" id="only-bu" style="display:none;">
						  	<label class="col-sm-5 control-label">Tanggal DO <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
								<select name="slcttgl" id="slcttgl" class="form-control filter">
								</select>
						  	</div>
						</div>
						<button type="submit" class="btn btn-sm btn-info pull-right filter"> Selanjutnya </button>
					</form>
				</div>
				<div class="col-lg-6" id="div-entry-pbbkb" style="display:none;">
					<form id="form-submit-pbbkb" class="form-horizontal">
						<input type="hidden" id="getlokasi" name="getlokasi">
						<input type="hidden" id="getspbu" name="getspbu">
						<input type="hidden" id="gettahun" name="gettahun">
						<input type="hidden" id="getbulan" name="getbulan">
						<input type="hidden" id="getdasar" name="getdasar">
						<input type="hidden" id="gettgl" name="gettgl">
						<div id="form-entry-pbbkb">

						</div>
						<button type="button" class="btn btn-sm btn-danger btn-flat pull-right" id="btn-batal"> Batalkan </button><button type="submit" class="btn btn-sm btn-success btn-flat pull-right"> Simpan Data </button>
					</form>
				</div>
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
		load_opsi_kecamatan_by_lokasi();

		$('#slctlokasi').change(function(){
			load_opsi_kecamatan_by_lokasi();
		});

		$('#slctbulan').change(function(){
			load_opsi_tanggal_per_bulan();
		});

		$("input[name=radJenis]:radio").change(function() {
			var getJenis = $('input[name=radJenis]:checked').val();
			var htmlOpsiDasar = $('#radio-btn-dasar');
			htmlOpsiDasar.empty();
			if ( getJenis == '11' ) {
				htmlOpsiDasar.append('<?= $this->Dasar->printRadioButton() ?>');
				$('#only-bu').hide('fast');
			} else if ( getJenis == '33' ) {
				htmlOpsiDasar.append('<?= $this->Dasar->getEntryOnlyBadanUsaha() ?>');
				$('#only-bu').show('fast');
			}
			load_opsi_kecamatan_by_lokasi();
		});

		$('#form-filter').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			},
			fields: {
				slctlokasi: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slctspbu: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slcttahun: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slctbulan: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slcttgl: {
					validators: {
						callback: {
                            message: 'Pilih salah satu !',
                            callback: function(value, validator, $field) {
                                var sts = $('#form-filter').find('[name="radJenis"]:checked').val();
                                return (sts !== '11') ? true : (value !== '');
                            }
                        }
					}
				}
			}
		})
		.on('change', '[name="slctlokasi"]', function() {
            $('#form-filter')
                    .formValidation('resetField', 'slctspbu');
        })
        .on('change', '[name="radJenis"]', function() {
            $('#form-filter')
            		.formValidation('resetField', 'slcttgl')
                    .formValidation('revalidateField', 'slcttgl');
        })
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			$.ajax({
				type: 'POST',
				url: '<?= base_url("view_rekam_pbbkb") ?>',
				dataType: "JSON",
				data: $('#form-filter').serialize(),
				success: function(html) {
					setTimeout(function() {
						$.unblockUI({
							onUnblock: function() {
								$('#div-entry-pbbkb').show('fast');
								$('#form-submit-pbbkb')[0].reset();
								$('#form-submit-pbbkb').formValidation('resetForm', true);
								var opsiPbbkb = $('#form-entry-pbbkb');
								opsiPbbkb.empty();
								$.each(html.arr_jenis_pbbkb, function(key, value) {
                            		opsiPbbkb.append('<div class="form-group">'+
										'<label class="col-sm-5 control-label">'+ value.item_pbbkb +'</label>'+
										  	'<div class="col-sm-7">'+
										  		'<input type="hidden" name="idpbbkb[]" value="'+ value.id_item_pbbkb +'">'+
										  		'<div class="input-group">'+
													'<input type="text" name="txtliter[]" class="form-control nominal" value="'+ html.arr_trx[value.id_item_pbbkb] +'">'+
													'<span class="input-group-addon"><i>Liter<i></span>'+
												'</div>'+
										  	'</div>'+
										'</div>');
                            	});
								$('.nominal').number( true, 0 );
								$('.filter').attr('disabled',true);
								$('#getlokasi').val(html.getLokasi);
								$('#getspbu').val(html.getSpbu);
								$('#gettahun').val(html.getTahun);
								$('#getbulan').val(html.getBulan);
								$('#getdasar').val(html.getDasar);
								$('#gettgl').val(html.getTgl);
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

		$('#btn-batal').click(function(){
			$('#div-entry-pbbkb').hide('fast');
			$('.filter').attr('disabled', false);
		});

		$('#form-submit-pbbkb').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			},
			fields: {
				'txtliter[]': {
					validators: {
						notEmpty: {
							message: 'Isikan jumlah !'
						}
					}
				}
			}
		})
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_rekam_pbbkb") ?>',
					dataType: "JSON",
					data: $('#form-submit-pbbkb').serialize(),
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										pesanOK(html.msg);
										$('#btn-batal').click();
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

	function load_opsi_kecamatan_by_lokasi()
	{
		var getLokasi = $('#slctlokasi').val();
		var getJenis = $('input[name=radJenis]:checked').val();

		$.ajax({
            type: 'POST',
            url: '<?= base_url("get_json_penyetor_by_id_lokasi") ?>',
            dataType: "JSON",
            data: 'postIdLokasi=' + getLokasi +'&postJenis='+ getJenis,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            var opsiSpbu = $('#slctspbu');
                            opsiSpbu.empty();
                            if ( html.opsi.length === 0 ) {
                            	opsiSpbu.append('<option value=""> -- Data Penyetor Kosong -- </option>');
                            } else {
                            	opsiSpbu.append('<option value=""> -- Pilih salah satu -- </option>');
                            	$.each(html.opsi, function(key, value) {
                            		if ( getJenis == '11' ) {
                            			opsiSpbu.append('<option value="'+ value.id_lokasi_pbbkb +'">'+ value.no_spbu +' - '+ value.nama_spbu +'</option>');
                            		} else {
                            			opsiSpbu.append('<option value="'+ value.id_lokasi_pbbkb +'">'+ value.nama_spbu +'</option>');
                            		}
                            		
                            	});
                            }
                            if ( getJenis == '33' ) {
                            	load_opsi_tanggal_per_bulan();
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

	function load_opsi_tanggal_per_bulan()
	{
		var getBulan = $('#slctbulan').val();
		var getTahun = $('#slcttahun').val();

		$.ajax({
            type: 'POST',
            url: '<?= base_url("get_json_opsi_tanggal") ?>',
            dataType: "JSON",
            data: 'postBulan=' + getBulan +'&postTahun='+ getTahun,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            var opsiTgl = $('#slcttgl');
                            opsiTgl.empty();
                        	//opsiTgl.append('<option value=""> -- Pilih salah satu -- </option>');
                        	$.each(html.tgl, function(key, value) {
                        		opsiTgl.append('<option value="'+ value +'">'+ value +'</option>');
                        	});
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