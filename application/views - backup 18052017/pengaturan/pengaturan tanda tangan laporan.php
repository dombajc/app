<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
				<?= $title ?>
				<div class="pull-right">
					Lokasi : 
					<select name="slctlokasi" id="slctlokasi" class="form-control">
						<?= $this->Lokasi->optionAllUpad() ?>
					</select>
				</div>
			</header>
			<div class="panel-body">
				<form class="forms form-horizontal">
		            <div class="box-body">
		            	<table class="table">
		            		<thead>
		            			<tr>
			            			<th width="50%" class="text-center">Nama Jabatan Penandatangan</th>
			            			<th class="text-center">Nama Pejabat</th>
			            		</tr>
		            		</thead>
		            		<tbody>
		            			<?php
				            		foreach( $this->Paraf->getAllByNamaLaporan()->result() as $paraf ) {
				            			echo '<tr>
				            				<td>
				            				<div class="form-group">
											  	<label class="col-sm-12 control-label">'. $paraf->nama_paraf .'</label>
											</div>
											</td>
											<td>
												<div class="form-group">
												  	<div class="col-sm-12">
												  		<input type="hidden" name="txtparaf[]" value="'. $paraf->id_paraf .'">
												  		<select id="slctpegawai_'. $paraf->id_paraf .'" class="form-control slctpegawai" name="slctpegawai[]">
												  	</div>
												</div>
											</td>
										</tr>';
				            		}
				            	?>
		            		</tbody>
		            	</table>
		            	
		            </div>  
		            <div class="box-footer text-right">
		                <button type="submit" class="btn btn-sm btn-success btn-flat"><i class="fa fa-save"></i> Simpan </button>
		                <button type="button" class="btn btn-sm btn-danger btn-flat" id="btn-hapus"><i class="fa fa-trash"></i> Hapus </button>
		            </div>                        

		        </form>
			</div>
		</section>
	</div>
</div>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>


<script>
	$(function(){

		aksiChangePegawai();

		$('#slctlokasi').change(function(){
			aksiChangePegawai();
		});

		$('#btn-hapus').click(function(){
			var lokasi = $('#slctlokasi').val();
	    	var konfirm = confirm('Pengaturan Tanda Tangan akan di hapus !');
	        if (konfirm == 1) {
	            $.ajax({
	                type: 'POST',
	                url: '<?= base_url("aksi_setparaf") ?>',
	                dataType: "JSON",
	                data: 'haksi=delete&lokasi=' + lokasi,
	                success: function(html) {

	                    setTimeout(function() {
	                        $.unblockUI({
	                            onUnblock: function() {
	                                if (html.error == "")
	                                {
	                                    $('form')[0].reset();
										$('form').formValidation('resetForm', true);
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
			
			var lokasi = $('#slctlokasi').val();
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_setparaf") ?>',
					dataType: "JSON",
					data: $('form').serialize() +'&haksi=add&postLokasi='+ lokasi,
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
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
	});

	function aksiChangePegawai()
	{
		var lokasi = $('#slctlokasi').val();
		var $opsipegawai = $(".slctpegawai");
		//$opsijabatan.empty();
		//$opsijabatan.append('<?= $this->Jabatan->opsiNonAsn() ?>');

		$.ajax({
            type: 'POST',
            url: '<?= base_url("get_setparaf") ?>',
            dataType: "JSON",
            data: 'lokasi=' + lokasi,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
								$opsipegawai.empty();
								
								//$('form')[0].reset();
								//$('form').formValidation('resetForm', true);
								$opsipegawai.append('<option value=""> -- Pilih salah satu pegawai -- </option>');
								$.each(html.arrPegawai, function(key, value) {
									$opsipegawai.append('<option value="'+ value.id_pegawai +'">'+ value.nama_pegawai +'</option>');
								});

								$.each(html.arrParaf, function(key, value) {
									$('#slctpegawai_'+ value.id_paraf).val( value.id_pegawai );
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