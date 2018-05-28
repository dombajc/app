<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.base.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.metro.css') ?>" type="text/css" />

<!-- Form Modal Edit -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="title-form"></h4>
      	</div>
      	<div class="modal-body">
			<form class="forms form-horizontal">
	            <input type="hidden" id="haksi" name="haksi" value="add">
	            <input type="hidden" id="hID" name="hID">
	            <input type="hidden" id="hid_kecamatan">
	            <div class="box-body">
	            	<div class="form-group">
					  	<label class="col-sm-5 control-label">Jenis Penyalur <span class="text-danger">*</span></label>
					  	<div class="col-sm-7">
							<?= $this->Jenispenyalur->printRadioButton() ?>
					  	</div>
					</div>
	            	<div class="form-group" id="only-spbu">
					  	<label class="col-sm-5 control-label">No.SPBU <span class="text-danger">*</span></label>
					  	<div class="col-sm-7">
							<input type="text" name="txtnospbu" id="txtnospbu" class="form-control">
					  	</div>
					</div>
					<div class="form-group">
					  	<label class="col-sm-5 control-label">Nama Perusahaan / Pemilik <span class="text-danger">*</span></label>
					  	<div class="col-sm-7">
							<input type="text" name="txtnama" id="txtnama" class="form-control">
					  	</div>
					</div>
					<div class="form-group">
					  	<label class="col-sm-5 control-label">Alamat <span class="text-danger">*</span></label>
					  	<div class="col-sm-7">
							<input type="text" name="txtalamat" id="txtalamat" class="form-control">
					  	</div>
					</div>
	            	<div class="form-group">
					  	<label class="col-sm-5 control-label">UP3AD <span class="text-danger">*</span></label>
					  	<div class="col-sm-7">
							<select name="slctlokasi" id="slctlokasi" class="form-control">
							<?= $this->Lokasi->optionAllUpad() ?>
							</select>
					  	</div>
					</div>
					<div class="form-group">
					  	<label class="col-sm-5 control-label">Kecamatan <span class="text-danger">*</span></label>
					  	<div class="col-sm-7">
							<select name="slctkecamatan" id="slctkecamatan" class="form-control">
							<option value=""> -- Pilih Kota dahulu -- </option>
							</select>
					  	</div>
					</div>
					<div class="form-group">
					  	<label class="col-sm-5 control-label">Telp <span class="text-danger">*</span></label>
					  	<div class="col-sm-7">
							<input type="text" name="txttelp" id="txttelp" class="form-control">
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
			<header class="panel-heading" id="titleModal">
			<?= $title ?>	
			</header>
			<div class="panel-body">
				<table>
				<tr>
					<td>Pengelompokan </td>
					<td><select id="sKelompok" class="form-control input-sm"><?= $this->Jenispenyalur->opsiPrint() ?></select></td>
					<td align="center">
					No.Spbu
					</td>
					<td width="10%">
					<input type="text" id="sNo" class="form-control input-sm">
					</td>
					<td align="center">
					Perusahaan
					</td>
					<td>
					<input type="text" id="sPerusahaan" class="form-control input-sm">
					</td>
				</tr>
				<tr>
				
				<td>
				Kota / UP3AD
				</td>
				<td>
				<select id="sLokasi" class="form-control input-sm">
					<?= $this->Lokasi->opsiAllUpadViewInputanRekamD2D() ?>
				</select>
				</td>
				<td colspan="3">
				<div class="input-group">
			      	<select id="sKecamatan" class="form-control input-sm">
					</select>
			      	<span class="input-group-btn">
			        	<button class="btn btn-sm btn-default" type="button" id="btn-refresh">Cari</button>
			      	</span>
			    </div>
				</td>
				<td align="right">
				<button type="button" id="btn-add-item" class="btn btn-sm btn-info btn-flat"><i class="fa fa-plus"></i> Tambah</button>
				</td>
				</tr>
				</table>
			</div>
		</section>
	</div>
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div id="jqxgrid"></div>
			</div>
		</section>
	</div>
</div>


<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxcore.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxbuttons.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxscrollbar.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxmenu.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxgrid.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxgrid.sort.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxgrid.pager.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxlistbox.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxdropdownlist.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxgrid.selection.js') ?>"></script> 
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxgrid.columnsresize.js') ?>"></script> 
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxdata.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script type="text/javascript">
    $(document).ready(function () {
    	load_opsi_kecamatan_by_filter();

    	var source =
		{
        	datatype: "json",
            url: "<?= base_url('load_data_spbu') ?>",
		    type : 'post',
            sort: function () {
                // update the grid and send a request to the server.
                $("#jqxgrid").jqxGrid('updatebounddata', 'sort');
            }
        };
		var dataAdapter = new $.jqx.dataAdapter(source,
		    {
		        formatData: function (data) {
		            $.extend(data, {
		            	cariKelompok : $('#sKelompok').val(),
		                cariNo: $('#sNo').val(),
		                cariNama : $('#sPerusahaan').val(),
		                cariLokasi : $('#sLokasi').val(),
		                cariKecamatan : $('#sKecamatan').val()
		            });
		            return data;
		        }
		    }
		);

        $("#jqxgrid").jqxGrid(
        {
            theme: 'metro',
            width: '100%',
            height: '400',
            source: dataAdapter,
            sortable: true,
            //groupable: true,
            columnsresize: true,
            //altrows: true,
            scrollmode: 'deferred',
            //virtualmode: true,
            deferreddatafields: ['no_spbu', 'nama_spbu', 'alamat_spbu'],
            columns: [
        	  {
                  text: '#', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false,
                  datafield: '', columntype: 'number', width: 35, cellsalign: 'right', align: 'center',
                  cellsrenderer: function (row, column, value) {
                      return "<div style='margin:4px;'>" + (value + 1) + "</div>";
                  }
              },
              { text: 'No.SPBU', datafield: 'no_spbu', width: '8%', cellsalign: 'center', align: 'center' },
              { text: 'Perusahaan / Pemilik', datafield: 'nama_spbu', width: '12%', cellsalign: 'left', align: 'center' },
              { text: 'Alamat', datafield: 'alamat_spbu', width: '20%', cellsalign: 'left', align: 'center' },
              { text: 'UP3AD', datafield: 'lokasi', width: '15%', cellsalign: 'center', align: 'center' },
              { text: 'KOTA', datafield: 'kota', width: '12%', cellsalign: 'center', align: 'center' },
              { text: 'Kecamatan', datafield: 'kecamatan', width: '12%', cellsalign: 'center', align: 'center' },
              { text: 'Telp', datafield: 'telp', width: '9%', cellsalign: 'center', align: 'center' },
              {
                  text: '--', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false, cellsalign: 'center', align: 'center',
                  datafield: 'aktif', width: 30,
                  cellsrenderer: function (row, column, value) {
                  	
                  	if ( value == 1 ) {
                  		return '<button class="btn btn-sm btn-default btn-flat" onclick="jsValidation(0)"><i class="fa fa-check text-success"></i></button>';
                  	} else {
                    	return '<button class="btn btn-sm btn-default btn-flat" onclick="jsValidation(1)"><i class="fa fa-ban text-danger"></i></button>';
                  	}
                  }
              },
              {
                  text: '...', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false, cellsalign: 'center', align: 'center',
                  datafield: 'id_lokasi_pbbkb', width: 60,
                  cellsrenderer: function (row, column, value) {
                      return '<button class="btn btn-sm btn-info btn-flat" onclick="jsDetil(\''+ value +'\')"><i class="fa fa-edit"></i></button><button class="btn btn-sm btn-danger btn-flat" onclick="jsDelete(\''+ value +'\')"><i class="fa fa-trash"></i></button>';
                  }
              },
          ]
        });

        $("#btn-refresh").click(function () {
            $("#jqxgrid").jqxGrid('updatebounddata');
        });

        $('#btn-add-item').click(function(){
        	$('#modal-form').modal('show');
        	$('form')[0].reset();
			$('form').formValidation('resetForm', true);
        	$('#title-form').text('Tambah Perusahaan Penyalur/Pengguna Bahan Bakar');
        	$('#haksi').val('add');
        	$('#radJenis_11').prop('checked', true);
        	$('#slctlokasi').empty();
        	$('#slctlokasi').append('<?= $this->Lokasi->optionAllUpad() ?>');
        	load_opsi_kecamatan_by_lokasi();
        });

        $("input[name=radJenis]:radio").change(function () {
        	var value = $('input[name=radJenis]:checked').val();
        	if ( value == '11' ) {
        		$('#only-spbu').show('fast');
        	} else if ( value == '33' ) {
        		$('#only-spbu').hide('fast');
        	}
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
				radJenis: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				txtnospbu: {
					validators: {
						callback: {
                            message: 'Harap di isi !',
                            callback: function(value, validator, $field) {
                                var sts = $('form').find('[name="radJenis"]:checked').val();
                                return (sts !== '11') ? true : (value !== '');
                            }
                        }
					}
				},
				txtnama: {
					validators: {
						notEmpty: {
							message: 'Harap diisi !'
						}
					}
				},
				txtalamat: {
					validators: {
						notEmpty: {
							message: 'Harap diisi !'
						}
					}
				},
				slctkecamatan: {
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
				},
				txttelp: {
					validators: {
						notEmpty: {
							message: 'Harap diisi !'
						}
					}
				}
			}
		})
		.on('change', '[name="radJenis"]', function() {
			$('form').formValidation('revalidateField', 'txtnospbu');
        })
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_spbu") ?>',
					dataType: "JSON",
					data: $('form').serialize(),
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										pesanOK(html.msg);
										$('#btn-refresh').click();
										$('#modal-form').modal('hide');
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

		$('#slctlokasi').change(function(){
			load_opsi_kecamatan_by_lokasi();
		});

		$('#sLokasi').change(function(){
			load_opsi_kecamatan_by_filter();
		});
    });

	function jsDetil(IDclick)
	{
		$.ajax({
            type: 'POST',
            url: '<?= base_url("view_spbu") ?>',
            dataType: "JSON",
            data: 'id=' + IDclick,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
                                $('#modal-form').modal('show');
                                $('form')[0].reset();
								$('form').formValidation('resetForm', true);
					        	$('#title-form').text('Perbaharui Perusahaan Penyalur / Pengguna Bahan Bakar');
					        	$('#haksi').val('edit');
					        	$('#hID').val(html.item.id_lokasi_pbbkb);
					        	$('#radJenis_'+ html.item.id_jenis_penyalur_pbbkb).prop('checked', true);
					        	$('#txtnospbu').val(html.item.no_spbu);
					        	$('#txtnama').val(html.item.nama_spbu);
					        	$('#txtalamat').val(html.item.alamat_spbu);
					        	$('#slctlokasi').val(html.item.id_lokasi);
					        	$('#hid_kecamatan').val(html.item.id_kecamatan);
					        	$('#txttelp').val(html.item.telp);
					        	load_opsi_kecamatan_by_lokasi();
					        	$("input[name=radJenis]:radio").change();
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

	function jsValidation(active)
	{
		var getselectedrowindexes = $('#jqxgrid').jqxGrid('getselectedrowindexes');
        if (getselectedrowindexes.length > 0)
        {
            // returns the selected row's data.
            var getid = $('#jqxgrid').jqxGrid('getrowdata', getselectedrowindexes[0]);
            $.ajax({
	            type: 'POST',
	            url: '<?= base_url("aksi_spbu") ?>',
	            dataType: "JSON",
	            data: 'haksi=valid&aktif='+ active +'&hID=' + getid.id_lokasi_pbbkb,
	            success: function(html) {

	                setTimeout(function() {
	                    $.unblockUI({
	                        onUnblock: function() {
	                            if (html.error == '')
	                            {
	                            	$('#modal-form').modal('hide');
	                            	pesanOK(html.msg);
									$('#btn-refresh').click();
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

	function jsDelete(IDclick)
	{
		var konfirm = confirm('Hapus Data SPBU / Perusahaan ?');
		if (konfirm == 1) {
			$.ajax({
	            type: 'POST',
	            url: '<?= base_url("aksi_spbu") ?>',
	            dataType: "JSON",
	            data: 'haksi=delete&hID=' + IDclick,
	            success: function(html) {

	                setTimeout(function() {
	                    $.unblockUI({
	                        onUnblock: function() {
	                            if (html.error == '')
	                            {
	                            	pesanOK(html.msg);
									$('#btn-refresh').click();
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

	function load_opsi_kecamatan_by_lokasi()
	{
		var getLokasi = $('#slctlokasi').val();
		$.ajax({
            type: 'POST',
            url: '<?= base_url("get_json_kecamatan_by_lokasi") ?>',
            dataType: "JSON",
            data: 'postIdLokasi=' + getLokasi,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            var opsiKecamatan = $('#slctkecamatan');
                            var getAksi = $('#haksi').val();
                            opsiKecamatan.empty();
                            if ( html.opsi.length === 0 ) {
                            	opsiKecamatan.append('<option value=""> -- Data Kecamatan Kosong -- </option>');
                            } else {
                            	opsiKecamatan.append('<option value=""> -- Pilih salah satu -- </option>');
                            	$.each(html.opsi, function(key, value) {
                            		opsiKecamatan.append('<option value="'+ value.id_kecamatan +'">'+ value.kecamatan +'</option>');
                            	});
                            }

                            if ( getAksi === 'edit' ) {
                            	opsiKecamatan.val($('#hid_kecamatan').val());
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

	function load_opsi_kecamatan_by_filter()
	{
		var getLokasi = $('#sLokasi').val();
		$.ajax({
            type: 'POST',
            url: '<?= base_url("get_json_kecamatan_by_lokasi") ?>',
            dataType: "JSON",
            data: 'postIdLokasi=' + getLokasi,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            var opsiKecamatan = $('#sKecamatan');
                            opsiKecamatan.empty();
                            if ( html.opsi.length === 0 ) {
                            	opsiKecamatan.append('<option value=""> -- Data Kecamatan Kosong -- </option>');
                            } else {
                            	opsiKecamatan.append('<option value=""> -- Keseluruhan -- </option>');
                            	$.each(html.opsi, function(key, value) {
                            		opsiKecamatan.append('<option value="'+ value.id_kecamatan +'">'+ value.kecamatan +'</option>');
                            	});
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