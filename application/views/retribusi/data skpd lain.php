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
	            <input type="hidden" id="hid" name="hid">
	            <div class="box-body">
					<div class="form-group">
					  	<label class="col-sm-3 control-label">UP3AD <span class="text-danger">*</span></label>
					  	<div class="col-sm-9">
							<select name="slctlokasi" id="slctlokasi" class="form-control input-sm nextinput" tabIndex="1">
							<option value=""> -- Pilih salah satu -- </option>
							<?= $this->Lokasi->optionAll() ?>
							</select>
					  	</div>
					</div>
	            	<div class="form-group">
					  	<label class="col-sm-3 control-label">Nama SKPD <span class="text-danger">*</span></label>
					  	<div class="col-sm-9">
							<input type="text" name="txtnamaskpd" id="txtnamaskpd" class="form-control input-sm nextinput" tabIndex="2">
					  	</div>
					</div>
					<div class="form-group">
					  	<label class="col-sm-3 control-label">Alamat <span class="text-danger">*</span></label>
					  	<div class="col-sm-9">
							<input type="text" name="txtalamat" id="txtalamat" class="form-control input-sm nextinput" tabIndex="3">
					  	</div>
					</div>
					<div class="form-group">
					  	<label class="col-sm-3 control-label">Telp <span class="text-danger">*</span></label>
					  	<div class="col-sm-9">
							<input type="text" name="txttelp" id="txttelp" class="form-control input-sm nextinput" tabIndex="4">
					  	</div>
					</div>
	            </div>  
	            <div class="box-footer text-right">
	                <button type="submit" class="btn btn-sm btn-success" id="btn-simpan"><i class="fa fa-save"></i> Simpan </button>
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
			<div class="panel-body form-horizontal">
				<label>Filter :</label>
				<div class="form-group">
					<label class="col-sm-1 control-label">SKPD</label>
					<div class="col-sm-3">
						<input type="text" id="filter-skpd" class="form-control input-sm">
					</div>
					<label class="col-sm-1 control-label">Lokasi </label>
					<div class="col-sm-4">
						<div class="input-group">
							<select id="filter-lokasi" class="form-control filter input-sm">
								<option value="">-- Keseluruhan --</option>
								<?= $this->Lokasi->optionAll() ?>
							</select>
							<span class="input-group-btn">
								<button class="btn btn-sm btn-default" type="button" id="btn-filter">Cari</button>
							</span>
						</div>
					</div>
					<div class="col-sm-3">
						<button type="button" id="btn-add-item" class="btn btn-sm btn-info btn-flat pull-right"><i class="fa fa-plus"></i> Tambah</button>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-sm-12">
		<div id="jqxgrid"></div>
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
            url: "<?= base_url('grid_data_skpd_lain') ?>",
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
		                cariNama : $('#filter-skpd').val(),
		                cariLokasi : $('#filter-lokasi').val()
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
                      return "<div style='margin:5.5px;'>" + (value + 1) + "</div>";
                  }
              },
              { text: 'NAMA SKPD', datafield: 'nama_skpd', width: '25%', cellsalign: 'left', align: 'center' },
              { text: 'ALAMAT', datafield: 'alamat_skpd', width: '28.5%', cellsalign: 'left', align: 'center' },
              { text: 'UP3AD', datafield: 'lokasi', width: '20%', cellsalign: 'center', align: 'center' },
              { text: 'Telp', datafield: 'no_telp', width: '15%', cellsalign: 'center', align: 'center' },
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
                  datafield: 'id_skpd', width: 60,
                  cellsrenderer: function (row, column, value) {
                      return '<button class="btn btn-sm btn-info btn-flat" onclick="jsDetil(\''+ value +'\')"><i class="fa fa-edit"></i></button><button class="btn btn-sm btn-danger btn-flat" onclick="jsDelete(\''+ value +'\')"><i class="fa fa-trash"></i></button>';
                  }
              },
          ]
        });

        $("#btn-filter").click(function () {
            $("#jqxgrid").jqxGrid('updatebounddata');
        });
		
		$('#modal-form').on('shown.bs.modal', function () {
			$('#slctlokasi').focus();
		});

        $('#btn-add-item').click(function(){
        	$('#modal-form').modal('show');
        	$('form')[0].reset();
			$('form').formValidation('resetForm', true);
        	$('#title-form').text('TAMBAH DATA  SKPD LAIN');
        	$('#haksi').val('add');
        });
		
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

        $('form').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			},
			fields: {
				txtnamaskpd: {
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
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("act_data_skpd_lain") ?>',
					dataType: "JSON",
					data: $('form').serialize(),
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										pesanOK(html.msg);
										$('#btn-filter').click();
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
		
    });

	function jsDetil(IDclick)
	{
		$.ajax({
            type: 'POST',
            url: '<?= base_url("row_data_skpd_lain") ?>',
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
					        	$('#title-form').text('UPDATE DATA SKPD LAIN');
					        	$('#haksi').val('edit');
					        	$('#hid').val(html.item.id_skpd);
					        	$('#txtnamaskpd').val(html.item.nama_skpd);
					        	$('#txtalamat').val(html.item.alamat_skpd);
					        	$('#slctlokasi').val(html.item.id_lokasi);
					        	$('#txttelp').val(html.item.no_telp);
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
	            url: '<?= base_url("act_data_skpd_lain") ?>',
	            dataType: "JSON",
	            data: 'haksi=valid&aktif='+ active +'&hid=' + getid.id_skpd,
	            success: function(html) {

	                setTimeout(function() {
	                    $.unblockUI({
	                        onUnblock: function() {
	                            if (html.error == '')
	                            {
	                            	$('#modal-form').modal('hide');
	                            	pesanOK(html.msg);
									$('#btn-filter').click();
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
		var konfirm = confirm('Hapus Data ?');
		if (konfirm == 1) {
			$.ajax({
	            type: 'POST',
	            url: '<?= base_url("act_data_skpd_lain") ?>',
	            dataType: "JSON",
	            data: 'haksi=delete&hid=' + IDclick,
	            success: function(html) {

	                setTimeout(function() {
	                    $.unblockUI({
	                        onUnblock: function() {
	                            if (html.error == '')
	                            {
	                            	pesanOK(html.msg);
									$('#btn-filter').click();
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