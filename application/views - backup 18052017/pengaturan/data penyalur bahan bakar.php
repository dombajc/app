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
	            <input type="hidden" id="hid_prov">
	            <input type="hidden" id="hid_kota">
	            <div class="box-body">
	            	<div class="form-group">
					  	<label class="col-sm-5 control-label">Provinsi Asal <span class="text-danger">*</span></label>
					  	<div class="col-sm-7">
							<select name="slctprov" id="slctprov" class="form-control">
							<?= $this->Provinsi->printOpsi() ?>
							</select>
					  	</div>
					</div>
					<div class="form-group">
					  	<label class="col-sm-5 control-label">Kota Asal <span class="text-danger">*</span></label>
					  	<div class="col-sm-7">
							<select name="slctkota" id="slctkota" class="form-control">
							</select>
					  	</div>
					</div>
					<div class="form-group">
					  	<label class="col-sm-5 control-label">Nama Perusahaan Penyedia BBM <span class="text-danger">*</span></label>
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
					  	<label class="col-sm-5 control-label">Telp</label>
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
				<table width="100%">
				<tr>
				<td width="15%">
				Nama Perusahaan
				</td>
				<td width="20%">
				<input type="text" id="sPerusahaan" class="form-control input-sm">
				</td>
				<td width="10%" align="center">
				Provinsi
				</td>
				<td width="20%">
				<div class="input-group">
			      	<select id="sProvinsi" class="form-control input-sm">
					<option value=""> -- Keseluruhan -- </option>
						<?= $this->Provinsi->printOpsi() ?>
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
		
    	var source =
		{
        	datatype: "json",
            url: "<?= base_url('load_data_penyalur_bbm') ?>",
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
		                cariNama : $('#sPerusahaan').val(),
		                cariProv : $('#sProvinsi').val()
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
              { text: 'Perusahaan / Pemilik', datafield: 'nama_penyalur', width: '15%', cellsalign: 'left', align: 'center' },
              { text: 'Alamat', datafield: 'alamat', width: '23%', cellsalign: 'left', align: 'center' },
              { text: 'Provinsi', datafield: 'provinsi', width: '15%', cellsalign: 'center', align: 'center' },
              { text: 'Kota', datafield: 'kota_asal', width: '12%', cellsalign: 'center', align: 'center' },
              { text: 'Telp', datafield: 'telp', width: '13%', cellsalign: 'center', align: 'center' },
			  { text: 'User', datafield: 'username', width: '10%', cellsalign: 'center', align: 'center' },
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
                  datafield: 'id_penyalur', width: 60,
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
        	$('#title-form').text('Tambah Data Penyalur/Penyedia Bahan Bakar');
        	$('#haksi').val('add');
        	$('#slctprov').prop('selectedIndex',0);
        	load_opsi_kota_asal_by_provinsi();
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
				slctprov: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slctkota: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				}
			}
		})
		.on('change', '[name="slctprov"]', function() {
			$('form').formValidation('revalidateField', 'slctkota');
        })
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_penyalur_bbm") ?>',
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

		$('#slctprov').change(function(){
			load_opsi_kota_asal_by_provinsi();
		});
    });

	function jsDetil(IDclick)
	{
		$.ajax({
            type: 'POST',
            url: '<?= base_url("view_penyalur_bbm") ?>',
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
					        	$('#hID').val(html.item.id_penyalur);
					        	$('#txtnama').val(html.item.nama_penyalur);
					        	$('#txtalamat').val(html.item.alamat);
					        	$('#slctprov').val(html.item.id_provinsi);
					        	$('#hid_prov').val(html.item.id_provinsi);
					        	$('#hid_kota').val(html.item.id_kota_asal);
					        	$('#txttelp').val(html.item.telp);
					        	load_opsi_kota_asal_by_provinsi();
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
	            url: '<?= base_url("aksi_penyalur_bbm") ?>',
	            dataType: "JSON",
	            data: 'haksi=valid&aktif='+ active +'&hID=' + getid.id_penyalur,
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

	function jsDelete(IDclick)
	{
		var konfirm = confirm('Hapus Data Penyalur BBM ?');
		if (konfirm == 1) {
			$.ajax({
	            type: 'POST',
	            url: '<?= base_url("aksi_penyalur_bbm") ?>',
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

	function load_opsi_kota_asal_by_provinsi()
	{
		var getProvinsi = $('#slctprov').val();
		var getHidProv = $('#hid_prov').val();
		$.ajax({
            type: 'POST',
            url: '<?= base_url("get_json_kota_asal_by_provinsi") ?>',
            dataType: "JSON",
            data: 'postIdProvinsi=' + getProvinsi,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            var opsiKota = $('#slctkota');
                            var getAksi = $('#haksi').val();
                            opsiKota.empty();
                            if ( html.opsi.length === 0 ) {
                            	opsiKota.append('<option value=""> -- Data Kota Asal Kosong -- </option>');
                            } else {
                            	opsiKota.append('<option value=""> -- Pilih salah satu -- </option>');
                            	$.each(html.opsi, function(key, value) {
                            		opsiKota.append('<option value="'+ value.id_kota_asal +'">'+ value.kota_asal +'</option>');
                            	});
                            }

                            if ( getAksi === 'edit' && getProvinsi === getHidProv ) {
                            	opsiKota.val($('#hid_kota').val());
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