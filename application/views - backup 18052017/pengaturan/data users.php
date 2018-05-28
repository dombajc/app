<!-- CSS Bootgrid  -->
<link href="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.css') ?>" rel="stylesheet" />

<!-- CSS JQueryUI  -->
<link href="<?= base_url('css/jQueryUI/jquery-ui-1.10.3.custom.min.css') ?>" rel="stylesheet" />

<!-- CSS TreeGrid  -->
<link href="<?= base_url('plugins/jquery-tree-master/src/css/jquery.tree.css') ?>" rel="stylesheet" />

<!-- Form Modal Edit -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="titleModal"></h4>
      </div>
      <div class="modal-body">
		<form class="forms form-horizontal">
            <input type="hidden" id="haksi" name="haksi" value="add">
            <input type="hidden" id="hID" name="hID">
			<input type="hidden" id="hlokasi" name="hlokasi">
            <div class="box-body">
				<div class="row">
					<div class="col-md-7">
						<!-- Default box -->
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">DATA PERSONAL</h3>
							</div>
							<div class="box-body">
								<div class="form-group">
								  <label class="col-sm-4 control-label">AKSES sebagai <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
								  	<label class="radio-inline">
								  		<input type="radio" name="slctakses" id="slctakses_1" value="1"> ADMIN
								  	</label>
								  	<label class="radio-inline">
								  		<input type="radio" name="slctakses" id="slctakses_0" value="0"> USER
								  	</label>
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">STATUS <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
								  	<label class="radio-inline">
								  		<input type="radio" name="slctstatus" id="slctstatus_1" value="1" onchange="actGetStatus(this.value)"> PUSAT
								  	</label>
								  	<label class="radio-inline">
								  		<input type="radio" name="slctstatus" id="slctstatus_0" value="0" onchange="actGetStatus(this.value)"> DAERAH
								  	</label>
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">LOKASI <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<select name="slctlokasi" id="slctlokasi" class="form-control">
										<option value=""> -- Pilih Hak Akses dahulu -- </option>
									</select>
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">NAMA <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<input type="text" name="txtnama" id="txtnama" class="form-control">
								  </div>
								</div>
								<h3 class="box-title">DATA LOGIN APLIKASI</h3>
								<div class="form-group">
								  <label class="col-sm-4 control-label">USER LOGIN <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<input type="text" name="txtuser" id="txtuser" class="form-control">
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">KATA SANDI <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<input type="password" name="txtkatasandi" id="txtkatasandi" class="form-control">
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">ULANGI KATA SANDI <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<input type="password" name="txtulangkatasandi" id="txtulangkatasandi" class="form-control">
								  </div>
								</div>
							</div><!-- /.box-body -->
							
						</div><!-- /.box -->
					</div>
					
					<div class="col-md-5">
						<!-- Default box -->
						<div class="box box-warning">
							<div class="box-header with-border">
								<p>Silahkan klik menu yang akan di tampilkan di grup :
							</div>
						<div class="box-body">
							<div class="form-group">
							  <div class="col-sm-12">
								<button type="button" id="btn-check-semua" class="btn btn-sm btn-default"> Pilih semua </button>
								<button type="button" id="btn-hapus-semua-check" class="btn btn-sm btn-default"> Batalkan semua </button></p>
								<div id="pilihanmenu">
									<?= $this->Menu->ShowPilihanMenu() ?>
								</div>
							  </div>
							</div>
						</div><!-- /.box-body -->

						</div><!-- /.box -->
					</div>
				</div>
            </div>  
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Daftar </button>
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
					<label>NAMA</label>
					<input type="text" id="find-nama" class="form-control">
				</div>
				<div class="form-group">
					<label>USER</label>
					<input type="text" id="find-user" class="form-control">
				</div>
				<div class="form-group">
					<label>LOKASI</label>
					<select id="find-lokasi" class="form-control">
						<option value=""> SEMUA </option>
						<?= $this->Lokasi->optionAll() ?>
					</select>
				</div>
				<div class="form-group">
					<label>STATUS</label>
					<select id="find-status" class="form-control">
						<option value=""> SEMUA </option>
						<option value="1"> PUSAT </option>
						<option value="0"> DAERAH </option>
					</select>
				</div>
				<div class="form-group">
					<label>AKSES</label>
					<select id="find-akses" class="form-control">
						<option value=""> SEMUA </option>
						<option value="1"> ADMIN </option>
						<option value="0"> USER </option>
					</select>
				</div>
				<button type="button" class="btn btn-sm btn-info" id="btn-cari"> Cari Data </button>
			</div>
		</section>
	</div>
	<div class="col-lg-10">
		<section class="panel">
			<header class="panel-heading">
				<i class="fa fa-users"></i> <?= $title ?>
				<button type="button" class="btn btn-xs btn-info pull-right" id="btn-tambah" title="Tambah Data Satuan"> <i class="glyphicon glyphicon-plus-sign"></i> </button>
			</header>
			<div class="panel-body">
				<table id="grid" class="table table-condensed table-bordered table-hover">
					<thead>
						<tr>
							<th data-column-id="nama_user" data-header-align="center">Nama</th>
							<th data-column-id="username" data-header-align="center" data-align="center">User</th>
							<th data-column-id="lokasi" data-header-align="center" data-align="center">Lokasi</th>
							<th data-column-id="pusat" data-formatter="strStatus" data-header-align="center" data-align="center">Status</th>
							<th data-column-id="admin" data-formatter="strAkses" data-header-align="center" data-align="center">Akses</th>
							<th data-column-id="" data-formatter="commands" data-header-align="center" data-sortable="false" data-align="center" data-width="90px">OPSI</th>
						</tr>
					</thead>
				</table>
			</div>
		</section>
	</div>
</div>

<!-- CSS JQueryUI  -->
<script src="<?= base_url('plugins/jQueryUI/jquery-ui.min.js') ?>"></script>

<!-- CSS TreeGrid  -->
<script src="<?= base_url('plugins/jquery-tree-master/src/js/jquery.tree.js') ?>"></script>

<!-- Boot Grid -->
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.js') ?>"></script>
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.fa.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>
		
<script>
	$(function(){
		
		$('#pilihanmenu').trees({
			onCheck: {
				node: 'expand'
			},
			onUncheck: {
				node: 'collapse'
			}
		});

		$('#btn-check-semua').click(function(){
			$('#pilihanmenu').trees('checkAll');
		});

		$('#btn-hapus-semua-check').click(function(){
			$('#pilihanmenu').trees('uncheckAll');
		});

		$('#btn-tambah').click(function() {
            $('#formModal').modal('show');
			$('form')[0].reset();
			$('form').formValidation('resetForm', true);
			$('#titleModal').html('DATA USER PENGGUNA BARU');
			$('#haksi').val('add');
			$('#slctakses').focus();
			var $opsilokasi = $("#slctlokasi");
			$opsilokasi.empty();
			$opsilokasi.append('<option value=""> -- Pilih Hak Akses dahulu -- </option>');
			$('#txtuser').attr('disabled',false);
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
				txtuser: {
					validators: {
						notEmpty: {
							message: 'Isikan username !'
						}
					}
				},
				txtkatasandi: {
					validators: {
						notEmpty: {
							message: 'Isikan kata sandi !'
						}
					}
				},
				txtulangkatasandi: {
					validators: {
						notEmpty: {
							message: 'Ulangi Kata Sandi !'
						},
                        identical: {
                            field: 'txtkatasandi',
                            message: 'Pengulangan kata sandi tidak sama !'
                        }
					}
				},
				slctakses: {
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
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				}
			}
		})
		.on('keyup', '[name="txtkatasandi"]', function() {
			var isEmpty = $(this).val() == '' && $('#haksi').val() == 'edit';
            $('form')
                .formValidation('enableFieldValidators', 'txtkatasandi', !isEmpty)
                .formValidation('enableFieldValidators', 'txtulangkatasandi', !isEmpty);
				
            // Revalidate the field when user start typing in the password field
            if ($(this).val().length == 1) {
                $('form')
					.formValidation('validateField', 'txtkatasandi')
                    .formValidation('validateField', 'txtulangkatasandi');
            }
        })
		.on('change', '[name="slctakses"]', function() {
            var isEmpty = $(this).val() == '';
            $('form')
                    .formValidation('enableFieldValidators', 'slctlokasi', !isEmpty);
        })
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_users") ?>',
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
					sByNama : $('#find-nama').val(),
					sByUser : $('#find-user').val(),
					sByLokasi : $('#find-lokasi').val(),
					sByStatus : $('#find-status').val(),
					sByAkses : $('#find-akses').val()
				};
			},
			url: "<?= base_url('load_data_users') ?>",
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
						var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default text-green\" onClick=\"aksivalid('" + row.id_user + "',0)\"><span class=\"fa fa-check\"></span></button> ";
					} else {
						var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default text-red\" onClick=\"aksivalid('" + row.id_user + "',1)\"><span class=\"fa fa-ban\"></span></button> ";
					}
					
					showButton += "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id_user + "\"><span class=\"fa fa-pencil text-blue\"></span></button> " + 
					"<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id_user + "\"><span class=\"fa fa-trash-o text-red\"></span></button>";
					
					return showButton;
				},
				"strStatus": function(column, row)
				{
					if ( row.pusat == 1 ) {
						return 'PUSAT';
					} else {
						return 'DAERAH';
					}
				},
				"strAkses": function(column, row)
				{
					if ( row.admin == 1 ) {
						return 'ADMIN';
					} else {
						return 'USER';
					}
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
            url: '<?= base_url("detil_data_user") ?>',
            dataType: "JSON",
            data: 'hID=' + ID,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
                                $('#formModal').modal('show');
								$('form')[0].reset();
								$('form').formValidation('resetForm', true);
								$('#titleModal').html('PERBAHARUI DATA PENGGUNA');
								$('#haksi').val('edit');
								$('#hID').val(ID);
								$('#hlokasi').val(html.id_lokasi);
								$('#slctakses_'+ html.admin).prop('checked',true);
								$('#slctstatus_'+ html.pusat).prop('checked',true);
								actGetStatus(html.pusat);
                                $('#txtuser').val(html.username);
								$('#txtnama').val(html.nama_user);
								$('#txtkatasandi, #txtulangkatasandi').val(html.katakunci);
								var exp = html.menuakses.split(',');
								$.each(exp, function(key, value) {
									$('#chk_'+ value).prop('checked',true);
								});
								$('#txtuser').attr('disabled',true);

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
            url: '<?= base_url("aksi_users") ?>',
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
                url: '<?= base_url("aksi_users") ?>',
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
    	var $opsilokasi = $("#slctlokasi");
		$opsilokasi.empty();
    	if ( value === '1' ){
    		$opsilokasi.append('<?= $this->Lokasi->optionAllPusat() ?>');
    	}else if ( value === '0' ){
    		$opsilokasi.append('<?= $this->Lokasi->optionAllUpad() ?>');
    	}

		if( $('#haksi').val() == 'edit' ){
			$('#slctlokasi').val($('#hlokasi').val());
		}
    }
</script>