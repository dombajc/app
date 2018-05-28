<!-- CSS Serialize  -->
<link href="<?= base_url('plugins/select2/select2.min.css') ?>" rel="stylesheet" />
<link href="<?= base_url('plugins/select2/select2-bootstrap.min.css') ?>" rel="stylesheet" />

<!-- CSS Bootgrid  -->
<link href="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.css') ?>" rel="stylesheet" />

<div class="row">
	<div class="col-lg-6">
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">
						Pencarian Riwayat berdasarkan :
					</header>
					<div class="panel-body form-horizontal">
						<div class="form-group">
						  	<label class="col-sm-3 control-label">Nama Pegawai</label>
						  	<div class="col-sm-9">
								<select name="slctpegawai" id="slctpegawai" class="form-control">
								</select>
						  	</div>
						</div>
					</div>
				</section>
			</div>
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">
						<?= $title ?>
						<button type="button" class="btn btn-xs btn-info pull-right" id="btn-tambah" disabled> <i class="glyphicon glyphicon-plus-sign"></i> </button>
					</header>
					<div class="panel-body">
						<table id="grid" class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th data-column-id="lokasi" data-header-align="center">Lokasi</th>
									<th data-column-id="" data-formatter="commands" data-header-align="center" data-sortable="false" data-align="center" data-width="90px">OPSI</th>
								</tr>
							</thead>
						</table>
					</div>
				</section>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="row" id="div-form-riwayat" style="display:none;">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">
						FORM RIWAYAT LOKASI PEGAWAI :
					</header>
					<div class="panel-body">
						<form class="form-horizontal">
							<input type="hidden" name="haksi" id="haksi">
							<input type="hidden" name="hID" id="hID">
							<div class="form-group">
							  	<label class="col-sm-3 control-label">Lokasi</label>
							  	<div class="col-sm-9">
									<select name="slctlokasi" id="slctlokasi" class="form-control">
										<?= $this->Lokasi->optionAll() ?>
									</select>
							  	</div>
							</div>
							<center>
								<button type="submit" class="btn btn-sm btn-flat btn-success"><i class="glyphicon glyphicon-ok-circle"></i> SIMPAN </button>
								<button type="button" class="btn btn-sm btn-flat btn-danger" id="btn-batal"><i class="glyphicon glyphicon-remove-circle"></i> BATAL </button>
							</center>
						</form>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>

<!-- Serialize -->
<script type="text/javascript" src="<?= base_url('plugins/select2/select2.min.js') ?>"></script>

<!-- Boot Grid -->
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.js') ?>"></script>
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.fa.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>
		
<script>
	$(function(){
		$("#slctpegawai").select2({
			  theme: "bootstrap",
			  ajax: {
				url: "<?= base_url('get_select_by_nama_pegawai') ?>",
				dataType: 'json',
				delay: 250,
				data: function (params) {
				  return {
					q: params.term, // search term
					page: params.page
				  };
				},
				processResults: function (data, params) {
					// parse the results into the format expected by Select2.
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			  },
			  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			  minimumInputLength: 1,
			  //formatResult: format,
	          //formatSelection: format
			  templateResult: formatRepo, // omitted for brevity, see the source of this page
			  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
		}).on("select2:select", function(e) {
			$('#grid').bootgrid('reload');
			$('#btn-tambah').attr('disabled', false);
        });

        $("#grid").bootgrid({
			ajax: true,
			post: function ()
			{
				/* To accumulate custom parameter with the request object */
				return {
					sIDPegawai : $('#slctpegawai').val(),
					//sNama : $('#fNmObat').val(),
					//sKategori : $('#fKatObat').val()
				};
			},
			url: "<?= base_url('load_grid_per_pegawai') ?>",
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
						var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default text-green\" onClick=\"aksivalid('" + row.id_riwayat_lokasi + "',0)\"><span class=\"fa fa-check\"></span></button> ";
					} else {
						var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default text-red\" onClick=\"aksivalid('" + row.id_riwayat_lokasi + "',1)\"><span class=\"fa fa-ban\"></span></button> ";
					}
					
					showButton += "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id_riwayat_lokasi + "\"><span class=\"fa fa-pencil text-blue\"></span></button> " + 
					"<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id_riwayat_lokasi + "\"><span class=\"fa fa-trash-o text-red\"></span></button>";
					
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

		$('form').formValidation({
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
				}
			}
		})
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			
			var getIDPegawai = $('#slctpegawai').val();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_riwayat_lokasi") ?>',
					dataType: "JSON",
					data: $('form').serialize() +'&slctpegawai='+ getIDPegawai,
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										pesanOK(html.msg);
										$('#div-form-riwayat').hide('fast');
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
            
        });
		
        $('#btn-tambah').click(function(){
        	$('#div-form-riwayat').show('fast');
        	$('#haksi').val('add');
        	$('form')[0].reset();
			$('form').formValidation('resetForm', true);
        });

		$('#btn-batal').click(function(){
			$('#div-form-riwayat').hide('fast');
		});
	});

	function formatRepo (repo) {
		if (repo.loading) return repo.text;

		var markup = "<div class='select2-result-repository clearfix'>" +
				"<div class='select2-result-repository__avatar'>" + repo.nama_pegawai + "</div>" +
					"<div class='select2-result-repository__meta'>" +
					"<div class='select2-result-repository__title'>NIP : " + repo.nip + "</div>"+
				"</div>"+
			"</div>";

		  return markup;
    }

    function formatRepoSelection (repo) {
		return repo.nama_pegawai || repo.text;
    }

	function getDetilData(ID)
	{
		$.ajax({
            type: 'POST',
            url: '<?= base_url("detil_data_riwayat") ?>',
            dataType: "JSON",
            data: 'hID=' + ID,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
								$('#div-form-riwayat').show('fast');
								$('form')[0].reset();
								$('form').formValidation('resetForm', true);
								$('#hID').val(ID);
								$('#haksi').val('edit');
								$('#slctlokasi').val(html.id_lokasi);	
								
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
            url: '<?= base_url("aksi_riwayat_lokasi") ?>',
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

    function actDelete(ID) {
    	var konfirm = confirm('Data akan di hapus !');
        if (konfirm == 1) {
            $.ajax({
                type: 'POST',
                url: '<?= base_url("aksi_riwayat_lokasi") ?>',
                dataType: "JSON",
                data: 'haksi=delete&hID=' + ID,
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