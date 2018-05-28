<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
				<?= $title ?>
				<div class="pull-right">
					Tahun Anggaran : 
					<select name="slctth" id="slctth" class="form-control">
						<?= $this->Thanggaran->opsiByTahunAktif() ?>
					</select>
				</div>
			</header>
			<div class="panel-body">
				<form class="forms form-horizontal">
		            <input type="hidden" id="haksi" name="haksi" value="add">
		            <input type="hidden" id="hID" name="hID">
		            <div class="box-body">
		            	<table class="table">
		            		<thead>
		            			<tr>
			            			<th rowspan="2" width="30%" class="text-center">NAMA PAJAK DAERAH</th>
			            			<th colspan="4" class="text-center">TRIWULAN</th>
			            		</tr>
			            		<tr>
			            			<?php
			            				foreach ( $this->Triwulan->getAllData()->result() as $tri ) {
			            					echo '<th class="text-center">'. $tri->triwulan .'</th>';
			            				}
			            			?>
			            		</tr>
		            		</thead>
		            		<tbody>
		            			<?php
				            		foreach( $this->Pd->getRekeningInputTarget()->result() as $pd ) {
				            			echo '<tr>
				            				<td>
				            				<div class="form-group">
											  	<label class="col-sm-12 control-label">'. $pd->nama_rekening .'</label>
											</div>
											</td>';
										
										foreach ( $this->Triwulan->getAllData()->result() as $inpertri ) {
			            					echo '<td>
			            					<input type="hidden" name="hIdRekPD[]" value="'. $pd->id_rek_pd .'">
			            					<input type="hidden" name="hIdtriwulan[]" value="'. $inpertri->id_triwulan .'">
			            					<div class="col-sm-12">
												<input type="text" name="txtjml[]" id="txtjml_'. $pd->id_rek_pd .'_'. $inpertri->id_triwulan. '" class="form-control nominal">
											</div>
											</td>';
			            				}

										echo '</tr>';
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
<!-- Number -->
<script type="text/javascript" src="<?= base_url('plugins/jquery-number-master/jquery.number.min.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>
		
<script>
	$(function(){
		getDetilData();

		$('.nominal').number( true, 0 );

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
			
			var getThAnggaran = $('#slctth').val();
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_targetpelypd") ?>',
					dataType: "JSON",
					data: $('form').serialize() +'&slctth='+ getThAnggaran,
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

        $('#slctth').change(function(){
        	getDetilData();
        });

        $('#btn-hapus').click(function(){
        	actDelete();
        });
		
	});

	function getDetilData()
	{
		var id_th_anggaran = $('#slctth').val();
		$.ajax({
            type: 'POST',
            url: '<?= base_url("detil_data_targetpelypd") ?>',
            dataType: "JSON",
            data: 'hID=' + id_th_anggaran,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
								$('form')[0].reset();
								$('form').formValidation('resetForm', true);
									$.each(html.resultArr, function(key, value) {
										$('#txtjml_'+ value.forID).val(value.total);
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

    function actDelete() {
    	var getID = $('#slctth').val();
    	var konfirm = confirm('Data akan di hapus !');
        if (konfirm == 1) {
            $.ajax({
                type: 'POST',
                url: '<?= base_url("aksi_targetpelypd") ?>',
                dataType: "JSON",
                data: 'haksi=delete&hID=' + getID,
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
    }
</script>