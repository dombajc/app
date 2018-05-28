<!-- CSS Bootgrid  -->
<link href="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.css') ?>" rel="stylesheet" />
<!-- CSS Datetime Picker  -->
<link href="<?= base_url('plugins/bootdatepicker/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet" />

<!-- Form Modal Edit -->
<div class="modal fade" id="modal-data-pegawai-aktif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<h4 class="modal-title">Data Pegawai Aktif</h4>
      	</div>
      	<div class="modal-body">
      		<table id="grid-pegawai-aktif" class="table table-condensed table-bordered table-hover">
				<thead>
					<tr>
						<th data-column-id="nama_pegawai" data-header-align="center">Nama Pegawai</th>
						<th data-column-id="nip" data-header-align="center" data-align="center">NIP</th>
						<th data-column-id="status_pegawai" data-header-align="center" data-align="center">Status</th>
						<th data-column-id="jabatan" data-header-align="center" data-align="center">Jabatan</th>
						<th data-column-id="nama_lokasi" data-header-align="center" data-align="center">Lokasi</th>
						<th data-column-id="nama_homebase" data-header-align="center" data-align="center">Homebase</th>
						<th data-column-id="" data-formatter="commands" data-header-align="center" data-sortable="false" data-align="center" data-width="40px">OPSI</th>
					</tr>
				</thead>
			</table>
		</div>
    </div>
  </div>
</div>

<!-- ******************************************************************************************************************* -->

<!-- Form Modal Edit -->
<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="titleModal"></h4>
      </div>
      <div class="modal-body">
		<form class="forms form-horizontal">
            <input type="hidden" id="haksi" name="haksi" value="edit">
            <input type="hidden" id="hID" name="hID">
            <input type="hidden" name="hIdMutasiSebelumnya" id="hIdMutasiSebelumnya">
            <input type="hidden" name="hIdPegawai" id="hIdPegawai">
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<!-- Default box -->
						<div class="box box-info">
							<div class="box-body">
								<div class="form-group">
									<label class="col-sm-4 control-label">Jenis Mutasi <span class="text-danger">*</span></label>
									<div class="col-sm-8">
										<select name="slctjenis_mutasi" id="slctjenis_mutasi" class="form-control jml_trx_0">
											<option value=""> -- Pilih salah satu -- </option>
											<?= $this->Mutasi->pilihan_jenis_mutasi() ?>
										</select>
									</div>
								</div>
								<div class="form-group dppad">
									<label class="col-sm-4 control-label">Lokasi Tujuan <span class="text-danger">*</span></label>
									<div class="col-sm-8">
										<select name="slctlokasi_mutasi" id="slctlokasi_mutasi" class="form-control jml_trx_0">
											<option value=""> -- Pilih salah satu -- </option>
											<?= $this->Lokasi->select_opotion_mutasi_keluar() ?>
										</select>
									</div>
								</div>
								<div class="form-group skpd">
									<label class="col-sm-4 control-label">Nama SKPD <span class="text-danger">*</span></label>
									<div class="col-sm-8">
										<input type="text" name="txtlokasi_lain" id="txtlokasi_lain" class="form-control jml_trx_0">
									</div>
								</div>
								<div class="form-group dppad">
									<label class="col-sm-4 control-label">Jabatan setelah Mutasi <span class="text-danger">*</span></label>
									<div class="col-sm-8">
										<select name="slctjabatan" id="slctjabatan" class="form-control">
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
								  	<div class='input-group date jml_trx_0' id='ambiltgl'>
					                    <input type='text' name="txttgl" id="txttgl" class="form-control jml_trx_0" value="<?= date('d-m-Y') ?>" />
					                    <span class="input-group-addon hide-btn">
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
		
		</div>
      
    </div>
  </div>
</div>

<!-- ******************************************************************************************************************* -->


<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
				<?= $title ?>
				<button class="btn btn-xs pull-right btn-info" type="button" id="btn-add-mutasi" data-width="100%" data-height="360" data-video-fullscreen=""><span class="glyphicon glyphicon-transfer"></span> Tambahkan Mutasi Pegawai</button>
			</header>
			<div class="panel-body">
				
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label"><strong>Pencarian Data Cepat :</strong></label>
						<label class="col-sm-2 control-label">Nama Pegawai</label>
						<div class="col-sm-2">
							<input type="text" id="find-nama" class="form-control">
						</div>
						<label class="col-sm-1 control-label">No.SK</label>
						<div class="col-sm-2">
							<input type="text" id="find-no-sk" class="form-control">
						</div>
						
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">LOKASI ASAL</label>
						<div class="col-sm-5">
							<select id="find-lokasi" class="form-control">
								<?= $this->Lokasi->opsiLaporan() ?>
							</select>
						</div>
					</div>
				</div>
				<button type="button" class="btn btn-sm btn-info btn-block" id="btn-cari"> Cari Data </button>
				<br>
				<table id="grid" class="table table-condensed table-bordered table-hover">
					<thead>
						<tr>
							<th data-column-id="nama_pegawai" data-header-align="center">Nama Pegawai</th>
							<th data-column-id="lokasi_sebelumnya" data-header-align="center" data-align="center">Lokasi Asal</th>
							<th data-column-id="jenis_mutasi" data-header-align="center" data-align="center">Status</th>
							<th data-column-id="lokasi_tujuan_mutasi" data-header-align="center" data-align="center">MUtasi Tujuan</th>
							<th data-column-id="no_sk" data-header-align="center" data-align="center">No.SK</th>
							<th data-column-id="tgl_sk" data-header-align="center" data-align="center">TMT</th>
							<th data-column-id="" data-formatter="commands" data-header-align="center" data-sortable="false" data-align="center" data-width="60px">OPSI</th>
						</tr>
					</thead>
				</table>
			</div>
		</section>
	</div>
</div>

<!-- Boot Grid -->
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.js') ?>"></script>
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.fa.js') ?>"></script>

<!-- Datetime Picker -->
<script type="text/javascript" src="<?= base_url('plugins/bootdatepicker/js/bootstrap-datepicker.min.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script>
	$(function(){
		$('#btn-cari').click(function(){
			$('#grid').bootgrid('reload');
		});

		$('#btn-add-mutasi').click(function(){
			$('#modal-data-pegawai-aktif').modal('show');
			$("#grid-pegawai-aktif").bootgrid('reload');
		});

		$("#grid").bootgrid({
			ajax: true,
			post: function ()
			{
				/* To accumulate custom parameter with the request object */
				return {
					postLokasi : $('#find-lokasi').val(),
					postNoSK : $('#find-no-sk').val(),
					postNama : $('#find-nama').val()
				};
			},
			url: "<?= base_url('load_data_mutasi_keluar') ?>",
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
					
					
					showButton = "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id_mutasi + "\" title=\"Ubah Mutasi\"><span class=\"fa fa-pencil text-blue\"></span></button> " + 
					"<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id_mutasi + "\" title=\"Hapus Mutasi\"><span class=\"fa fa-trash-o text-red\"></span></button>&nbsp;";
					
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

		$("#grid-pegawai-aktif").bootgrid({
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
			url: "<?= base_url('load_data_pegawai_lama') ?>",
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
					var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default command-mutasi\" data-row-id=\"" + row.id_mutasi + "\" title=\"Form Mutasi Pegawai\"><span class=\"glyphicon glyphicon-transfer\"></span></button>";
					
					return showButton;
				}
			}
		}).on("loaded.rs.jquery.bootgrid", function()
		{
			/* Executes after data is loaded and rendered */
			$("#grid-pegawai-aktif").find(".command-mutasi").on("click", function(e)
			{
				OpenFormMutasi($(this).data("row-id"));
			});
		});
		
		$('#slctjenis_mutasi').change(function(){
			actChangeJenisMutasi();
		});
		
		$('#txttgl').datepicker({
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
						callback: {
                            message: 'Pilih salah satu jabatan !',
                            callback: function(value, validator, $field) {
                                var jenis_mutasi = $('form').find('[name="slctjenis_mutasi"]').val();
                                return (jenis_mutasi !== '333333') ? true : (value !== '');
                            }
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
            $('form').formValidation('revalidateField', 'slctjabatan');
        })
        .on('change', '[name="txttgl"]', function() {
            $('form').formValidation('revalidateField', 'txttgl');
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
										$('#btn-cari').click();
										$('#ModalEdit, #modal-data-pegawai-aktif').modal('hide');
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

	function getDetilData( ID )
	{
		$.ajax({
            type: 'POST',
            url: '<?= base_url("detil_mutasi_pegawai") ?>',
            dataType: "JSON",
            data: 'postID=' + ID,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
                                $('#ModalEdit').modal('show');
                                $('#titleModal').html('Edit Data Mutasi Keluar Pegawai');
                                var opsiJabatan = $('#slctjabatan');
                                opsiJabatan.empty();
                                opsiJabatan.append(html.opsi);
								$('form')[0].reset();
								$('form').formValidation('resetForm', true);
								$('#haksi').val('edit');
								$('#slctlokasi_asal').val(html.data['id_lokasi_asal']);
								$('#hIdPegawai').val(html.data['id_pegawai']);
                                $('#slctjenis_mutasi').val(html.data['id_jenis_mutasi']);
                                $('#slctlokasi_mutasi').val(html.data['id_tujuan_mutasi']);
                                $('#txtlokasi_lain').val(html.data['lokasi_lain']);
                                $('#txtdasarsurat').val(html.data['no_sk']);
                                $('#txttgl').val(html.tgl);
                                $('#hID').val(html.data['id_mutasi']);
                                $('#slctjabatan').val(html.data['id_jabatan']);
                                $('#slctjenis_mutasi').change();
                                $('#slctlokasi_mutasi').change();
                                if ( html.jml_transaksi > 0 ) {
                                	$('.jml_trx_0').attr('disabled', true);
                                	$('.hide-btn').hide('fast');
                                } else {
                                	$('.jml_trx_0').attr('disabled', false);
                                	$('.hide-btn').show('fast');
                                }
								
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

	function actChangeJenisMutasi()
	{
		var value = $('#slctjenis_mutasi').val();

		if ( value == '555555' ) {
			$('.skpd').show('fast');
			$('.dppad').hide('fast');
			$('#txtlokasi_lain').focus();
		} else if ( value == '333333' ) {
			$('.skpd').hide('fast');
			$('.dppad').show('fast');
		} else {
			$('.dppad, .skpd').hide('fast');
		}
	}

	function actDelete(id) {
    	var konfirm = confirm('Mutasi Pegawai akan di hapus ?');
        if (konfirm == 1) {
            $.ajax({
                type: 'POST',
                url: '<?= base_url("aksi_mutasi") ?>',
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

    function OpenFormMutasi(ID)
    {
    	$.ajax({
            type: 'POST',
            url: '<?= base_url("detil_tambah_mutasi_pegawai") ?>',
            dataType: "JSON",
            data: 'postID=' + ID,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
                                $('#ModalEdit').modal('show');
                                $('#titleModal').html('FORM TAMBAH DATA MUTASI PEGAWAI');
                                $('form')[0].reset();
								$('form').formValidation('resetForm', true);
                                var opsiJabatan = $('#slctjabatan');
                                opsiJabatan.empty();
                                opsiJabatan.append(html.opsi);
								$('#haksi').val('add');
								$('#hIdMutasiSebelumnya').val(ID);
								$('#hIdPegawai').val(html.data['id_pegawai']);
								opsiJabatan.val(html.data['id_jabatan']);

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