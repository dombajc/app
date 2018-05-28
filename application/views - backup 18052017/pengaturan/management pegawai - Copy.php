<!-- CSS Bootgrid  -->
<link href="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.css') ?>" rel="stylesheet" />

<!-- Form Modal Edit -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="titleModal"></h4>
      </div>
      <div class="modal-body">
		<form class="forms form-horizontal">
            <input type="hidden" id="haksi" name="haksi" value="add">
            <input type="hidden" id="hID" name="hID">
            <input type="hidden" id="hIdPangkat">
            <input type="hidden" id="hIdJabatan">
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<!-- Default box -->
						<div class="box box-info">
							<div class="box-body">
								<div class="form-group">
								  <label class="col-sm-4 control-label">STATUS <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<?= $this->Statuspegawai->printRadioButton() ?>
								  </div>
								</div>
								<div class="form-group" id="div-nip" style="display:none;">
								  <label class="col-sm-4 control-label">NIP <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<input type="text" name="txtnip" id="txtnip" class="form-control">
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">NAMA <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<input type="text" name="txtnama" id="txtnama" class="form-control kodebesar" style="text-transform:uppercase">
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">Pangkat / Golongan <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<select name="slctpangkat" id="slctpangkat" class="form-control">
									</select>
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">Jabatan <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<select name="slctjabatan" id="slctjabatan" class="form-control">
									</select>
								  </div>
								</div>
								<div id="show-only-add">
									<div class="form-group">
									  <label class="col-sm-4 control-label">LOKASI <span class="text-danger">*</span></label>
									  <div class="col-sm-8">
										<select name="slctlokasi" id="slctlokasi" class="form-control">
											<?= $this->Lokasi->optionAll() ?>
										</select>
									  </div>
									</div>
									<div class="form-group">
									  <label class="col-sm-4 control-label">HomeBase <span class="text-danger">*</span></label>
									  <div class="col-sm-8">
										<select name="slcthomebase" id="slcthomebase" class="form-control">
										</select>
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
		
		</div>
      
    </div>
  </div>
</div>

<!-- ******************************************************************************************************************* -->

<div class="row">
	<div class="col-lg-2">
		<section class="panel">
			<header class="panel-heading">
			Pencarian Data
			</header>
			<div class="panel-body">
				<div class="form-group">
					<label>NAMA PEGAWAI</label>
					<input type="text" id="find-nama" class="form-control">
				</div>
				<div class="form-group">
					<label>NIP</label>
					<input type="text" id="find-nip" class="form-control">
				</div>
				<div class="form-group">
					<label>LOKASI</label>
					<select id="find-lokasi" class="form-control">
					<?= $this->Lokasi->opsiAllUpadViewInputanRekamD2D() ?>
					</select>
				</div>
				<button type="button" class="btn btn-sm btn-info" id="btn-cari"> Cari Data </button>
			</div>
		</section>
	</div>
	<div class="col-lg-10">
		<section class="panel">
			<header class="panel-heading">
				</i> <?= $title ?>
				<button type="button" class="btn btn-xs btn-info pull-right" id="btn-tambah" title="Tambah Data Satuan"> <i class="glyphicon glyphicon-plus-sign"></i> </button>
			</header>
			<div class="panel-body">
				<table id="grid" class="table table-condensed table-bordered table-hover">
					<thead>
						<tr>
							<th data-column-id="nama_pegawai" data-header-align="center">Nama Pegawai</th>
							<th data-column-id="nip" data-header-align="center" data-align="center">NIP</th>
							<th data-column-id="status_pegawai" data-header-align="center" data-align="center">Status</th>
							<th data-column-id="jabatan" data-header-align="center" data-align="center">Jabatan</th>
							<th data-column-id="nama_lokasi" data-header-align="center" data-align="center">Lokasi</th>
							<th data-column-id="nama_homebase" data-header-align="center" data-align="center">Homebase</th>
							<th data-column-id="" data-formatter="commands" data-header-align="center" data-sortable="false" data-align="center" data-width="90px">OPSI</th>
						</tr>
					</thead>
				</table>
			</div>
		</section>
	</div>
</div>

<script src="<?= base_url('plugins/jquery.inputmask-3.x/dist/min/jquery.inputmask.bundle.min.js') ?>"></script>

<!-- Boot Grid -->
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.js') ?>"></script>
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.fa.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>
		
<script>
	$(function(){

		$('#txtnip').inputmask('99999999 999999 9 999',
			{ "onincomplete": function(){ 
				$("#txtnip").val("");
				$('form').formValidation('revalidateField', 'txtnip');
			}
		});

		$('#btn-tambah').click(function() {
            $('#formModal').modal('show');
			$('#titleModal').html('ENTRY / MENAMBAH DATA PEGAWAI');
			$('#haksi').val('add');
			$('#txtnip, .stspegawai').attr('disabled', false);
			$('#show-only-add').show('fast');
			$('form')[0].reset();
			$('form').formValidation('resetForm', true);
			$('form').formValidation('enableFieldValidators', 'slctlokasi');
			var $opsipangkat = $("#slctpangkat");
			$opsipangkat.empty();
			$opsipangkat.append('<option value=""> -- Silahkan pilih status pegawai -- </option>');
			var $opsijabatan = $("#slctjabatan");
			$opsijabatan.empty();
			$opsijabatan.append('<option value=""> -- Silahkan pilih status pegawai -- </option>');
			$('#slctlokasi').val("<?= $this->Opsisite->getDataUser()['id_lokasi'] ?>");
			aksiUbahLokasi();
        });

        $('#slctlokasi').change(function(){
        	aksiUbahLokasi();
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
				txtnama: {
					validators: {
						notEmpty: {
							message: 'Isikan nama !'
						}
					}
				},
				txtnip: {
					enabled : false,
					validators: {
						notEmpty: {
							message: 'Isikan nip pegawai !'
						}
					}
				},
				slctpangkat: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slctjabatan: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slctstatus: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slctlokasi: {
					enabled: false,
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				}
			}
		})
		.on('change', '[name="slctstatus"]', function() {
            var isEmpty = $(this).val() == '99';
            $('form').formValidation('enableFieldValidators', 'txtnip', !isEmpty);
            $('form').formValidation('revalidateField', 'slctpangkat');
            $('form').formValidation('revalidateField', 'slctjabatan');
        })
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_pegawai") ?>',
					dataType: "JSON",
					data: $('form').serialize(),
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										$('#formModal').modal('hide');
										$('#btn-cari').click();
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
		
		$('#btn-cari').click(function(){
			$('#grid').bootgrid('reload');
		});

		$("#grid").bootgrid({
			ajax: true,
			post: function ()
			{
				/* To accumulate custom parameter with the request object */
				return {
					postLokasi : $('#find-lokasi').val(),
					postNIP : $('#find-nip').val(),
					postNama : $('#find-nama').val()
				};
			},
			url: "<?= base_url('load_data_pegawai') ?>",
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
					//if ( row.aktif == 1 ){
						//var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default text-green\" onClick=\"aksivalid('" + row.id_pegawai + "',0)\"><span class=\"fa fa-check\"></span></button> ";
					//} else {
						//var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default text-red\" onClick=\"aksivalid('" + row.id_pegawai + "',1)\"><span class=\"fa fa-ban\"></span></button> ";
					//}
					
					var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id_pegawai + "\"><span class=\"fa fa-pencil text-blue\"></span></button> " + 
					"<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id_pegawai + "\"><span class=\"fa fa-trash-o text-red\"></span></button>";
					
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
            url: '<?= base_url("detil_data_pegawai") ?>',
            dataType: "JSON",
            data: 'hID=' + ID,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
                                $('#formModal').modal('show');
								$('#show-only-add').hide('fast');
								$('#titleModal').html('EDIT / MEMPERBAHARUI DATA PEGAWAI');
								$('#haksi').val('edit');
								$('#hID').val(ID);
								$('form')[0].reset();
								$('form').formValidation('resetForm', true);
								$('form').formValidation('enableFieldValidators', 'slctlokasi', false);
								$('#hIdPangkat').val(html.id_pangkat);
								$('#hIdJabatan').val(html.id_jabatan);
								$('#slctstatus_'+ html.id_sts_pegawai).prop('checked',true);
								actGetStatus(html.id_sts_pegawai);
                                $('#txtnip').val(html.nip);
								$('#txtnama').val(html.nama_pegawai);
								//$('#txtnip, .stspegawai').attr('disabled', true);

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
            url: '<?= base_url("aksi_pegawai") ?>',
            dataType: "JSON",
            data: 'haksi=validasi&hID=' + id + '&value=' + valid,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == "")
                            {
                                $('#btn-cari').click();
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
                url: '<?= base_url("aksi_pegawai") ?>',
                dataType: "JSON",
                data: 'haksi=delete&hID=' + id,
                success: function(html) {

                    setTimeout(function() {
                        $.unblockUI({
                            onUnblock: function() {
                                if (html.error == "")
                                {
                                   	$('#btn-cari').click();
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

    function actGetStatus(value) {
    	
    	if ( value === '33' ){
    		$('#div-nip').show('fast');
    		var $opsipangkat = $("#slctpangkat");
			$opsipangkat.empty();
			$opsipangkat.append('<?= $this->Pangkat->opsiAsn() ?>');
			var $opsijabatan = $("#slctjabatan");
			$opsijabatan.empty();
			$opsijabatan.append('<?= $this->Jabatan->opsiAsn() ?>');
    	}else if ( value === '99' ){
    		$('#div-nip').hide('fast');
    		var $opsipangkat = $("#slctpangkat");
			$opsipangkat.empty();
			$opsipangkat.append('<?= $this->Pangkat->opsiNonAsn() ?>');
			var $opsijabatan = $("#slctjabatan");
			$opsijabatan.empty();
			$opsijabatan.append('<?= $this->Jabatan->opsiNonAsn() ?>');
    	}

    	var act = $('#haksi').val();
    	if ( act == 'edit' ) {
			$('#slctpangkat').val($('#hIdPangkat').val());
			$('#slctjabatan').val($('#hIdJabatan').val());
    	}
    }

    function aksiUbahLokasi()
    {
    	var lokasi = $('#slctlokasi').val();
    	var $homebase = $("#slcthomebase");
    	$homebase.empty();
    	$.ajax({
	        type: 'POST',
	        url: '<?= base_url("get_lokasi_homebase") ?>',
	        dataType: "JSON",
	        data: 'postLokasi=' + lokasi,
	        success: function(html) {

	            setTimeout(function() {
	                $.unblockUI({
	                    onUnblock: function() {
	                        if (html.error == "")
	                        {
								$.each(html.arrHomebase, function(key, value) {
									$homebase.append('<option value="'+ value.id_lokasi +'"> '+ value.lokasi +' </option>');
								});
								$homebase.val("<?= $this->Opsisite->getDataUser()['id_lokasi'] ?>");
	                            
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