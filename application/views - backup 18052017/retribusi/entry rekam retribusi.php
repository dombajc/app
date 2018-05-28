<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading" id="titleModal">
			<?= $title ?>	
			</header>
			<div class="panel-body form-horizontal">
				<div class="form-group">
					<label class="col-sm-1 control-label">Tahun </label>
					<div class="col-sm-2">
						<select id="slctth" class="form-control input-sm">
						<?= $this->Thanggaran->opsiByTahunAktif() ?>
						</select>
					</div>
					<label class="col-sm-1 control-label">Bulan </label>
					<div class="col-sm-2">
						<select id="slctbulan" class="form-control filter input-sm">
							<?= $this->Bulan->getBulanOrderByBulanAktif() ?>
						</select>
					</div>
					<label class="col-sm-1 control-label">Lokasi </label>
					<div class="col-sm-3">
						<select id="slctlokasi" class="form-control filter input-sm">
							<?= $this->Lokasi->optionAll() ?>
						</select>
					</div>
					<div class="col-sm-2">
						<button type="button" class="btn btn-sm" id="btn-open-form"> Lanjutkan </button>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-sm-12" id="form-setting-kode-rekening" style="display:none;">
		<section class="panel">
			<header class="panel-heading">
				ENTRY OBYEK dan PENERIMAAN RETRIBUSI :
			</header>
			<div class="panel-body">
				<form class="form form-horizontal">
					<div id="result-setting"></div>					
					<div class="text-center">
						<button type="submit" class="btn btn-sm btn-flat btn-success" id="btn-simpan"><i class="glyphicon glyphicon-ok-circle"></i> S I M P A N </button>
						<button type="button" class="btn btn-sm btn-flat btn-danger" id="btn-hapus"><i class="glyphicon glyphicon-trash"></i> H A P U S </button>
						<button type="button" class="btn btn-sm btn-flat btn-warning" id="btn-batal"><i class="glyphicon glyphicon-remove-circle"></i> B A T A L </button>
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

<script type="text/javascript">
    $(document).ready(function () {
		
		$('#btn-open-form').click(function(){
			var id_thn = $('#slctth').val();
			var id_lokasi = $('#slctlokasi').val();
			var id_bulan = $('#slctbulan').val();
			$.ajax({
				type: 'POST',
				url: '<?= base_url("form_entry_retribusi") ?>',
				dataType: "JSON",
				data: "post_thn="+ id_thn +"&postbulan="+ id_bulan +"&postlokasi="+ id_lokasi,
				success: function(data) {
					setTimeout(function() {
						$.unblockUI({
							onUnblock: function() {
								var divResult = $("#result-setting");
								divResult.empty();
								var writeHtml = '';
								var i = 1;
								
								if ( data.sts == 1 )
								{
									var row_transaksi = data.rowtransaksi;
									writeHtml += '<div class="form-group"><div class="col-sm-12"><div class="alert alert-success">Data sudah di simpan pada '+ data.tgl_rekam +'</div></div></div>';
								}
								
								$.each(data.getrekening, function(key, value){
									
									//if ( value.anakan == 0 ) {
										var value_input = data.sts == 1 ? value.getapp_penerimaan : value.getonline;
										if ( data.sts == 1 )
										{
											val = value.getapp_penerimaan;
										}
										else
										{
											val = value.getonline;
										}
										
										writeHtml += '<div class="form-group">'+
										'<label class="col-sm-2 control-label">'+ value.no_rekening +'</label>'+
										'<label class="col-sm-4 control-label">'+ value.nm_rekening +'</label>';
										if ( value.anakan == 0 ) {
											writeHtml += '<div class="col-sm-2">'+
											'<div class="input-group">'+
											'<span class="input-group-addon">Oby</span>'+
											'<input type="text" class="form-control input-sm nominal text-right nextinput" name="txtoby[]" id="oby_'+ value.kd_rekening +'" tabIndex="'+ i +'" value="'+ value.getapp_oby +'">'+
											'<input type="hidden" name="postkode[]" value="'+ value.kd_rekening +'">'+
											'</div>'+
											'</div>'+
											'<div class="col-sm-4">'+
											'<div class="input-group">'+
											'<span class="input-group-addon">Rp. </span>'+
											'<input type="text" class="form-control input-sm nominal text-right nextinput" name="txtrealisasi[]" id="realisasi_'+ value.kd_rekening +'" tabIndex="'+ parseInt(i+1) +'" value="'+ val +'">'+
											'</div>'+
											'</div>';
											i = parseInt(i+2);
										}
										writeHtml += '</div>';
										
										
									//} else {
										//writeHtml += '<div class="form-group alert-info">'+
										//'<label class="col-sm-2 control-label">'+ value.no_rekening +'</label>'+
										//'<label class="col-sm-4 control-label">'+ value.nm_rekening +'</label>'+
										//'</div>';
									//}
									
								});
								writeHtml += '';
								divResult.html(writeHtml);
								$('#slctth, #slctbulan, #slctlokasi, #btn-open-form').attr('disabled', true);
								$('#form-setting-kode-rekening').show('fast');
								$('.nominal').number( true, 0 );
								
								$('[tabIndex=1]').focus();
								
								$('.nextinput').on('keypress', function (e) {
									if (e.which == 13) {
										e.preventDefault();
										var $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
										console.log($next.length);
										if (!$next.length) {
											$('#btn-simpan').click();
										}
										$next.focus();
									}
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
			
			var id_thn = $('#slctth').val();
			var id_lokasi = $('#slctlokasi').val();
			var id_bulan = $('#slctbulan').val();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("simpan_entrian_retribusi") ?>',
					dataType: "JSON",
					data: $('form').serialize() +"&post_thn="+ id_thn +"&postbulan="+ id_bulan +"&postlokasi="+ id_lokasi,
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
		
		$('#btn-batal').click(function(){
			$('#form-setting-kode-rekening').hide('fast');
			$('#slctth, #slctbulan, #slctlokasi, #btn-open-form').attr('disabled', false);
		});
		
		$('#btn-hapus').click(function() {
			var id_thn = $('#slctth').val();
			var id_lokasi = $('#slctlokasi').val();
			var id_bulan = $('#slctbulan').val();
			var konfirm = confirm("HAPUS REKAM DATA");
			if ( konfirm == 1 )
			{
				$.ajax({
		            type: 'POST',
		            url: '<?= base_url("hapus_entrian_retribusi") ?>',
		            dataType: "JSON",
					data: "post_thn="+ id_thn +"&postbulan="+ id_bulan +"&postlokasi="+ id_lokasi,
		            success: function(html) {

		                setTimeout(function() {
		                    $.unblockUI({
		                        onUnblock: function() {
		                            if (html.error == '')
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
	
</script>