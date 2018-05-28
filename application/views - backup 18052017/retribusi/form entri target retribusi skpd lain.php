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
					<label class="col-sm-1 control-label">Target </label>
					<div class="col-sm-2">
						<select id="slcttarget" class="form-control filter input-sm">
							<option value="0"> MURNI </option>
							<option value="1"> PERUBAHAN </option>
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
				TARGET RETRIBUSI :
			</header>
			<div class="panel-body">
				<form class="form form-horizontal">
					<div id="result-setting"></div>					
					<div class="text-center">
						<button type="submit" class="btn btn-sm btn-flat btn-success" id="btn-simpan"><i class="glyphicon glyphicon-ok-circle"></i> S I M P A N </button>
						<button type="button" class="btn btn-sm btn-flat btn-danger" id="btn-hapus" disabled><i class="glyphicon glyphicon-trash"></i> H A P U S </button>
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
			var id_target = $('#slcttarget').val();
			$.ajax({
				type: 'POST',
				url: '<?= base_url("view_entri_target_rekening_skpd_lain") ?>',
				dataType: "JSON",
				data: "post_thn="+ id_thn +"&post_target="+ id_target +"&post_lokasi="+ id_lokasi,
				success: function(data) {
					setTimeout(function() {
						$.unblockUI({
							onUnblock: function() {
								if ( data.error == '' )
								{
									var divResult = $("#result-setting");
									divResult.empty();
									var writeHtml = '';
									var i = 1;
									
									if ( data.sts_entri == 1 )
									{
										var row_transaksi = data.row_entri;
										writeHtml += '<div class="form-group"><div class="col-sm-12"><div class="alert alert-success">Data sudah di simpan pada '+ row_transaksi.last_update +' oleh '+ row_transaksi.nama_user +'</div></div></div>';
										$('#btn-hapus').attr('disabled', false);
									}
									else
									{
										$('#btn-hapus').attr('disabled', true);
									}
									
									$.each(data.arr, function(key, value){
										writeHtml += '<div class="form-group">'+
										'<label class="col-sm-8 control-label text-info">'+ value[0] +'</label>';
										writeHtml += '</div>';
										if ( value.node != null )
										{
											$.each(value.node, function(key2, value2){
												
												
													if ( value2.anakan == 0 )
													{
														writeHtml += '<div class="form-group">'+
														'<input type="hidden" name="postkode[]" value="'+ value2.id_rekening +'">'+
														'<label class="col-sm-4 control-label">'+ value2.no_rekening2 +'</label>'+
														'<label class="col-sm-4 control-label">'+ value2.nama_rekening +'</label>'+
														'<div class="col-sm-4">'+
														'<div class="input-group">'+
														'<span class="input-group-addon">Rp. </span>'+
														'<input type="text" class="form-control input-sm nominal text-right nextinput" name="postjumlah[]" tabIndex="'+ i +'" value="'+ value2.jumlah +'">'+
														'</div>'+
														'</div>';
														i++;
													} else {
														writeHtml += '<div class="form-group">'+
														'<label class="col-sm-4 control-label text-warning">'+ value2.no_rekening2 +'</label>'+
														'<label class="col-sm-4 control-label text-warning">'+ value2.nama_rekening +'</label>';
													}
													
													writeHtml += '</div>';
													
											});
										}
									});
									writeHtml += '';
									divResult.html(writeHtml);
									$('#slctth, #slcttarget, #slctlokasi, #btn-open-form').attr('disabled', true);
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
								else
								{
									pesanError(data.error);
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
			
			var id_thn = $('#slctth').val();
			var id_lokasi = $('#slctlokasi').val();
			var id_target= $('#slcttarget').val();
			
			var konfirm = confirm('SIMPAN DATA ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("simpan_target_retribusi_skpd_lain") ?>',
					dataType: "JSON",
					data: $('form').serialize() +"&post_thn="+ id_thn +"&posttarget="+ id_target +"&postlokasi="+ id_lokasi,
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
			$('#slctth, #slcttarget, #slctlokasi, #btn-open-form').attr('disabled', false);
		});
		
		$('#btn-hapus').click(function() {
			var id_thn = $('#slctth').val();
			var id_lokasi = $('#slctlokasi').val();
			var id_target = $('#slcttarget').val();
			var konfirm = confirm("HAPUS DATA ?");
			if ( konfirm == 1 )
			{
				$.ajax({
		            type: 'POST',
		            url: '<?= base_url("hapus_target_retribusi_skpd_lain") ?>',
		            dataType: "JSON",
					data: "post_thn="+ id_thn +"&posttarget="+ id_target +"&postlokasi="+ id_lokasi,
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