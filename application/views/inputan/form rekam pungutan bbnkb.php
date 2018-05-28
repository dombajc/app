<!-- CSS Serialize  -->
<link href="<?= base_url('plugins/select2/select2.min.css') ?>" rel="stylesheet" />
<link href="<?= base_url('plugins/select2/select2-bootstrap.min.css') ?>" rel="stylesheet" />

<div class="row">
	<div class="col-md-4">
		<section class="panel">
			<header class="panel-heading">
				<?= $title ?>
			</header>
			<div class="panel-body form-horizontal">
				<div class="form-group">
					<div class="col-sm-4">
				  		Tahun
						<select id="slctth" class="form-control filter input-sm">
							<?= $this->Thanggaran->opsiByTahunAktif() ?>
						</select>
				  	</div>
				  	<div class="col-sm-8">
						Bulan
						<select id="slctbulan" class="form-control filter input-sm">
							<?= $this->Bulan->opsiSemuaBulan() ?>
						</select>
				  	</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
				  		INDUK LOKASI
						<select id="slctinduk" class="form-control filter input-sm" onchange="aksiPilihanInduk();">
							<?= $this->Lokasi->optionAll() ?>
						</select>
				  	</div>
				</div>
				<div class="form-group">
				  	<div class="col-sm-12">
				  		LOKASI SAMSAT
						<select id="slctlokasi" class="form-control filter input-sm">
						<option value=""> -- Pilih dulu lokasi di samping -- </option>
						</select>
				  	</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						JENIS
						<select id="slctstatus" class="form-control filter input-sm">
							<?= $this->Pd->opsiJenis(" where sub_rek_pd='02'") ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
				  		<button type="button" class="btn btn-sm btn-info btn-block filter" id="btn-tampilkan">Lanjutkan Proses perekaman Obyek dan Realisasi</button>  
				  	</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-md-8" id="div-form-inputan" style="display:none;">
		<section class="panel">
			<header class="panel-heading">
				FORM PENGISIAN DATA OBYEK DAN REALISASI
			</header>
			<div class="panel-body">
				<form class="form-horizontal">
					<input type="hidden" name="hth" id="hth">
					<input type="hidden" name="hbulan" id="hbulan">
					<input type="hidden" name="hlokasi" id="hlokasi">
					<input type="hidden" name="hjenis" id="hjenis">					
					<div class="row" id="dinamis-input">

					</div>
					<div class="row">
						<div class="col-md-12 text-center">
							<button type="submit" class="btn btn-sm btn-flat btn-success"><i class="glyphicon glyphicon-ok-circle"></i> S I M P A N </button>
							<button type="button" class="btn btn-sm btn-flat btn-danger" id="btn-hapus"><i class="glyphicon glyphicon-trash"></i> H A P U S </button>
							<button type="button" class="btn btn-sm btn-flat btn-warning" id="btn-batal"><i class="glyphicon glyphicon-remove-circle"></i> B A T A L </button>
						</div>
					</div>
				</form>
			</div>
		</section>
	</div>
</div>

<!-- Number -->
<script type="text/javascript" src="<?= base_url('plugins/Easy-Numbers-Currency-Formatting-Plugin-autoNumeric/autoNumeric-min.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script>

	$(function(){
		$('#slctbulan').val("<?= date('n') ?>");
		aksiPilihanInduk();

		$('#btn-hapus').click(function(){
			var id = $('#hid').val();
			var konfirm = confirm("HAPUS REKAM DATA");
			if ( konfirm == 1 )
			{
				$.ajax({
		            type: 'POST',
		            url: '<?= base_url("hapus_rekam_bbnkb") ?>',
		            dataType: "JSON",
		            data: 'id='+ id,
		            success: function(html) {

		                setTimeout(function() {
		                    $.unblockUI({
		                        onUnblock: function() {
		                            if (html.error == '')
		                            {
										pesanOK(html.msg);
										$('#btn-tampilkan').click();
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
			}
		});

		$('#btn-tampilkan').click(function(){
			var bulan = $('#slctbulan').val();
			var lokasi = $('#slctlokasi').val();
			var ia = $('#slctth').val();
			var jenis = $('#slctstatus').val();
			$.ajax({
	            type: 'POST',
	            url: '<?= base_url("get_inputan_bbnkb") ?>',
	            dataType: "JSON",
	            data: 'bulan='+ bulan +'&lokasi='+ lokasi +'&ia='+ ia +'&jenis='+ jenis,
	            success: function(html) {

	                setTimeout(function() {
	                    $.unblockUI({
	                        onUnblock: function() {
	                            if (html.error == '')
	                            {
									var tabindex = 1;
									$('#div-form-inputan').show('fast');
									$('form')[0].reset();
									$('form').formValidation('resetForm', true);
									$('#hth').val(ia);
									$('#hbulan').val(bulan);
									$('#hlokasi').val(lokasi);
									$('#hjenis').val(jenis);
									var $divinputan = $("#dinamis-input");
									$divinputan.empty();

									var getHtml = '';

									if ( html.cek == 0 ) {
										$('#btn-hapus').attr('disabled', true);
									} else {
										$('#btn-hapus').attr('disabled', false);
										var getHtml = '<input type="hidden" id="hid" value="'+ html.rekam.id_rekam_pkb +'">'+
											'<div class="col-md-12"><div class="alert alert-warning">Telah di rekam oleh : '+ html.rekam.nama_user +' pada '+ html.rekam.last_update +'</div></div>';
									}

									getHtml += '<div class="col-md-12">'+
										'<h4>'+ html.arrPkb.detil.nama_rekening + '</h4>'+
										'<label class="text-info">* Tekan [ENTER] untuk berpindah tiap kolom input.</label>'+
										'<div class="form-group">'+
			  								'<div class="col-sm-2 text-center">'+
			  								'JENIS'+
			  								'</div>'+
			  								'<div class="col-sm-2 text-center">'+
			  								'OBYEK'+
			  								'</div>'+
			  								'<div class="col-sm-4 text-center">'+
			  								'POKOK'+
			  								'</div>'+
			  								'<div class="col-sm-4 text-center">'+
			  								'SANKSI'+
			  								'</div>'+
										'</div>';
									$.each(html.arrPkb.list, function(key, value2) {
										getHtml += '<div class="form-group">'+
			  								'<label class="col-sm-2 control-label">'+ value2.kode_jenis +'</label>'+
			  								'<div class="col-sm-2">'+
			  								'<input type="hidden" value="'+ value2.id_jenis +'" name="postidjenis[]">'+
			  								'<input type="text" class="form-control input-sm nominal text-right suboby nextinput" name="postoby[]" value="'+ value2.obyek +'" tabindex="'+ tabindex +'">'+
			  								'</div>'+
			  								'<div class="col-sm-4">'+
			  									'<div class="input-group">'+
			  									'<span class="input-group-addon">Rp. </span>'+
												'<input type="text" class="form-control input-sm nominal text-right subpokok nextinput" name="postpokok[]" value="'+ value2.pokok +'" tabindex="'+ parseInt(tabindex + 1) +'">'+
												'</div>'+				  								
			  								'</div>'+
			  								'<div class="col-sm-4">'+
			  									'<div class="input-group">'+
			  									'<span class="input-group-addon">Rp. </span>'+
												'<input type="text" class="form-control input-sm nominal text-right subsanksi nextinput" name="postsanksi[]" value="'+ value2.sanksi +'" tabindex="'+ parseInt(tabindex + 2) +'">'+
												'</div>'+				  								
			  								'</div>'+
										'</div>';
										tabindex = parseInt(tabindex) + 3;
									});

									getHtml += '<div class="form-group">'+
		  								'<label class="col-sm-2 control-label">TOTAL</label>'+
		  								'<div class="col-sm-2">'+
		  								'<input type="text" class="form-control input-sm nominal text-right" id="totaloby" disabled>'+
		  								'</div>'+
		  								'<div class="col-sm-4">'+
		  									'<div class="input-group">'+
		  									'<span class="input-group-addon">Rp. </span>'+
											'<input type="text" class="form-control input-sm nominal text-right" id="totalpokok" disabled>'+
											'</div>'+				  								
		  								'</div>'+
		  								'<div class="col-sm-4">'+
		  									'<div class="input-group">'+
		  									'<span class="input-group-addon">Rp. </span>'+
											'<input type="text" class="form-control input-sm nominal text-right" id="totalsanksi" disabled>'+
											'</div>'+				  								
		  								'</div>'+
									'</div>';

									getHtml += '</div>';

									$('.nominal').autoNumeric('init', {
										//aSign: 'Rp. '
										mDec : 0
									});

									$divinputan.append(getHtml);
									hitungOby();
									hitungPokok();
									hitungSanksi();
									
									$('.nominal').autoNumeric('update', {lZero: 'deny'});
									
									$('.nominal').focus(function(){
										if ( this.value == 0 )
										{
											$(this).val('');
										}
									});
									
									$('.nominal').focusout(function(){
										if ( this.value == '' )
										{
											$(this).val(0);
										}
									})

									$(".suboby").keyup(function(){
										hitungOby();
									});

									$(".subpokok").keyup(function(){
										hitungPokok();
									});
									$(".subsanksi").keyup(function(){
										hitungSanksi();
									});

									$('.filter').attr('disabled', true);
									$('.nextinput').on('keypress', function (e) {
									    if (e.which == 13 && this.tabIndex < parseInt(tabindex-1)) {
								    		e.preventDefault();
									        var $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
									        console.log($next.length);
									        if (!$next.length) {
									            $next = $('[tabIndex=1]');
									        }
									        $next.focus();
									    } else if (e.which == 115) {
									    	$('#btn-batal').click();
									    }
									});
									$('[tabIndex=1]').focus();
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
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_rekam_bbnkb") ?>',
					dataType: "JSON",
					data: $('form').serialize(),
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

	function aksiPilihanInduk()
	{
		var induk = $('#slctinduk').val();
		$.ajax({
	        type: 'POST',
	        url: '<?= base_url("get_array_lokasi_by_induk") ?>',
	        dataType: "JSON",
	        data: 'induk=' + induk,
	        success: function(html) {

	            setTimeout(function() {
	                $.unblockUI({
	                    onUnblock: function() {
	                        var $opsilokasi = $("#slctlokasi");
							$opsilokasi.empty();
							$.each(html.data, function(key, value) {
								$opsilokasi.append('<option value="'+ value.id_lokasi +'">'+ value.lokasi +'</option>');
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

	function hitungOby()
	{
		$('.nominal').autoNumeric('init', {
			//aSign: 'Rp. '
			mDec : 0
		});
		var totaloby = 0;
		$(".suboby").each(function(){
		if ( this.value == '' ) {
			var val = 0;
		} else {
			var val = this.value.replace(/,/g,'');
		}
			totaloby += parseInt(val);
		});
		$('#totaloby').autoNumeric('set', totaloby);
	}

	function hitungPokok()
	{
		$('.nominal').autoNumeric('init', {
			//aSign: 'Rp. '
			mDec : 0
		});
		var totalpokok = 0;
		$(".subpokok").each(function(){
			if ( this.value == '' ) {
				var val = 0;
			} else {
				var val = this.value.replace(/,/g,'');
			}
			totalpokok += parseInt(val);
		});
		$('#totalpokok').autoNumeric('set', totalpokok);
	}

	function hitungSanksi()
	{
		$('.nominal').autoNumeric('init', {
			//aSign: 'Rp. '
			mDec : 0
		});
		var totalsanksi = 0;
		$(".subsanksi").each(function(){
			if ( this.value == '' ) {
				var val = 0;
			} else {
				var val = this.value.replace(/,/g,'');
			}
			totalsanksi += parseInt(val);
		});
		$('#totalsanksi').autoNumeric('set', totalsanksi);
	}

</script>