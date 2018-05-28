<!-- CSS Bootgrid  -->
<link href="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.css') ?>" rel="stylesheet" />

<div class="row">
	<div class="col-lg-6">
		<section class="panel">
			<header class="panel-heading">
				<i class="fa fa-users"></i> <?= $title ?>
				<button type="button" class="btn btn-xs btn-info pull-right" id="btn-tambah" title="Tambah Data Satuan"> <i class="glyphicon glyphicon-plus-sign"></i> </button>
			</header>
			<div class="panel-body">
				<table id="grid" class="table table-condensed table-bordered table-hover">
					<thead>
						<tr>
							<th data-column-id="th_anggaran" data-header-align="center">Tahun</th>
							<th data-column-id="keterangan" data-header-align="center">Keterangan</th>
							<th data-column-id="" data-formatter="commands" data-header-align="center" data-sortable="false" data-align="center" data-width="90px">OPSI</th>
						</tr>
					</thead>
				</table>
			</div>
		</section>
	</div>

	<div class="col-lg-6" id="form-th-anggaran" style="display:none;">
		<section class="panel">
			<header class="panel-heading" id="titleModal">
				
			</header>
			<div class="panel-body">
				<form class="forms form-horizontal">
		            <input type="hidden" id="haksi" name="haksi" value="add">
		            <input type="hidden" id="hID" name="hID">
		            <div class="box-body">
		            	<div class="form-group">
						  <label class="col-sm-3 control-label">Pilih Tahun <span class="text-danger">*</span></label>
						  <div class="col-sm-4">
							<select name="slctth" id="slctth" class="form-control">
								<option value=""> -- Pilih Tahun Anggaran -- </option>
							</select>
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-sm-3 control-label">Keterangan</label>
						  <div class="col-sm-9">
							<input type="text" name="txtketerangan" id="txtketerangan" class="form-control">
						  </div>
						</div>
		            </div>  
		            <div class="box-footer text-right">
		                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Simpan </button>
		            </div>                        

		        </form>
			</div>
		</section>
	</div>
</div>

<!-- Boot Grid -->
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.js') ?>"></script>
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.fa.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>
		
<script>
	$(function(){
		
		$('#btn-tambah').click(function() {
            $('#form-th-anggaran').show('fast');
			$('form')[0].reset();
			$('form').formValidation('resetForm', true);
			$('#titleModal').html('DATA TAHUN ANGGARAN BARU');
			$('#haksi').val('add');
			$('#slctth').focus();
			var $opsith = $("#slctth");
			$opsith.empty();
			$opsith.append('<?= $this->Thanggaran->optionAll() ?>');
        });
		
		$('form').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			},
			fields: {
				slctth: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
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
					url: '<?= base_url("aksi_th_anggaran") ?>',
					dataType: "JSON",
					data: $('form').serialize(),
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										$('#form-th-anggaran').hide('fast');
										$('#grid').bootgrid('reload');
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
		
		$("#grid").bootgrid({
			ajax: true,
			post: function ()
			{
				/* To accumulate custom parameter with the request object */
				return {
					//sKode : $('#fKdObat').val(),
					//sNama : $('#fNmObat').val(),
					//sKategori : $('#fKatObat').val()
				};
			},
			url: "<?= base_url('load_data_th_anggaran') ?>",
			selection: true,
			multiSelect: false,
			multiSort : true,
			rowSelect: true,
			keepSelection: true,
			templates: {
				header: "<div id=\"{{ctx.id}}\" class=\"{{css.header}}\"><div class=\"row\"><div class=\"col-sm-12 actionBar\"><p class=\"{{css.actions}}\"></p></div></div></div>"       
			},
			formatters: {
				"commands": function(column, row)
				{
					if ( row.aktif == 1 ){
						var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default text-green\" onClick=\"aksivalid('" + row.id_anggaran + "',0)\"><span class=\"fa fa-check\"></span></button> ";
					} else {
						var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default text-red\" onClick=\"aksivalid('" + row.id_anggaran + "',1)\"><span class=\"fa fa-ban\"></span></button> ";
					}
					
					showButton += "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id_anggaran + "\"><span class=\"fa fa-pencil text-blue\"></span></button> " + 
					"<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id_anggaran + "\"><span class=\"fa fa-trash-o text-red\"></span></button>";
					
					return showButton;
				}
			}
		}).on("loaded.rs.jquery.bootgrid", function()
		{
			/* Executes after data is loaded and rendered */
			$("#grid").find(".command-edit").on("click", function(e)
			{
				getDetilData($(this).data("row-id"));
			}).end().find(".command-delete").on("click", function(e)
			{
				actDelete($(this).data("row-id"));
			});
		});
		
	});

	function getDetilData( ID )
	{
		$.ajax({
            type: 'POST',
            url: '<?= base_url("detil_data_th_anggaran") ?>',
            dataType: "JSON",
            data: 'hID=' + ID,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
                                $('#form-th-anggaran').show('fast');
								$('form')[0].reset();
								$('form').formValidation('resetForm', true);
								$('#titleModal').html('PERBAHARUI DATA TAHUN ANGGARAN');
								$('#haksi').val('edit');
								$('#hID').val(ID);
								$('#slctth').focus();
								var $opsith = $("#slctth");
								$opsith.empty();
								$opsith.append('<option value="'+ html.th_anggaran +'"> '+ html.th_anggaran +' </option>');
								$opsith.append('<?= $this->Thanggaran->optionAll() ?>');
								$('#slctth').val(html.th_anggaran);
								$('#txtketerangan').val(html.keterangan);

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
	
	function aksivalid(id, valid) {
        $.ajax({
            type: 'POST',
            url: '<?= base_url("aksi_th_anggaran") ?>',
            dataType: "JSON",
            data: 'haksi=validasi&hID=' + id + '&value=' + valid,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == "")
                            {
                                $('#grid').bootgrid('reload');
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

    function actDelete(id) {
    	var konfirm = confirm('Data akan di hapus !');
        if (konfirm == 1) {
            $.ajax({
                type: 'POST',
                url: '<?= base_url("aksi_th_anggaran") ?>',
                dataType: "JSON",
                data: 'haksi=delete&hID=' + id,
                success: function(html) {

                    setTimeout(function() {
                        $.unblockUI({
                            onUnblock: function() {
                                if (html.error == "")
                                {
                                   	$('#grid').bootgrid('reload');
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