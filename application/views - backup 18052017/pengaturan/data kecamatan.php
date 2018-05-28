<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.base.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.metro.css') ?>" type="text/css" />

<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading" id="titleModal">
			<?= $title ?>	
			</header>
			<div class="panel-body">
				<table width="100%">
				<tr>
				<td width="25%">
				<select id="sLokasi" class="form-control input-sm">
					<?= $this->Lokasi->opsiAllUpadViewInputanRekamD2D() ?>
				</select>
				</td>
				<td width="30%">
				<div class="input-group">
			      	<input type="text" class="form-control input-sm" id="sNama" placeholder="Ketikan kecamatan">
			      	<span class="input-group-btn">
			        	<button class="btn btn-sm btn-default" type="button" id="btn-refresh">Cari</button>
			      	</span>
			    </div>
				</td>
				<td width="45%">
				<button type="button" id="btn-add-item" class="btn btn-sm btn-info btn-flat pull-right"><i class="fa fa-plus"></i> Tambah</button>
				</td>
				</tr>
				</table>
			</div>
		</section>
	</div>
	<div class="col-sm-7">
		<section class="panel">
			<div class="panel-body">
				<div id="jqxgrid"></div>
			</div>
		</section>
	</div>
	<div class="col-sm-5">
		<section class="panel">
			<header class="panel-heading" id="title-form">
			Tambah Nama Kecamatan
			</header>
			<div class="panel-body">
				<form class="forms form-horizontal">
		            <input type="hidden" id="haksi" name="haksi" value="add">
		            <input type="hidden" id="hID" name="hID">
		            <div class="box-body">
		            	<div class="form-group">
						  	<label class="col-sm-5 control-label">Kota / UP3AD <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
								<select name="slctlokasi" id="slctlokasi" class="form-control">
								<?= $this->Lokasi->optionAllUpad() ?>
								</select>
						  	</div>
						</div>
						<div class="form-group">
						  	<label class="col-sm-5 control-label">Nama Kecamatan <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
								<input type="text" name="txtkecamatan" id="txtkecamatan" class="form-control">
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
            url: "<?= base_url('load_data_kecamatan') ?>",
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
		                cariNama: $('#sNama').val(),
		                cariLokasi : $('#sLokasi').val()
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
            deferreddatafields: ['kecamatan', 'lokasi'],
            columns: [
        	  {
                  text: '#', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false,
                  datafield: '', columntype: 'number', width: 40, cellsalign: 'right', align: 'center',
                  cellsrenderer: function (row, column, value) {
                      return "<div style='margin:4px;'>" + (value + 1) + "</div>";
                  }
              },
              { text: 'Nama Kecamatan', datafield: 'kecamatan', width: '40%', cellsalign: 'left', align: 'center' },
              { text: 'Kota / UP3AD', datafield: 'lokasi', width: '34%', cellsalign: 'center', align: 'center' },
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
                  datafield: 'id_kecamatan', width: 60,
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
        	$('#title-form').text('Tambah Nama Kecamatan');
        	$('form')[0].reset();
			$('form').formValidation('resetForm', true);
        	$('#haksi').val('add');
        	var opsiLokasi = $('#slctlokasi');
        	opsiLokasi.empty();
        	opsiLokasi.append('<?= $this->Lokasi->optionAllUpad() ?>');
        	$('#txtkecamatan').focus();
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
				txtkecamatan: {
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
					url: '<?= base_url("aksi_kecamatan") ?>',
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
										$('#btn-add-item').click();
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
            url: '<?= base_url("view_kecamatan") ?>',
            dataType: "JSON",
            data: 'id=' + IDclick,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
                                $('form')[0].reset();
								$('form').formValidation('resetForm', true);
					        	$('#title-form').text('Perbaharui Nama Kecamatan');
					        	$('#haksi').val('edit');
					        	$('#hID').val(html.item.id_kecamatan);
					        	$('#slctlokasi').val(html.item.id_lokasi);
					        	$('#txtkecamatan').val(html.item.kecamatan);
					        	$('#slctlokasi').focus();
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
	            url: '<?= base_url("aksi_kecamatan") ?>',
	            dataType: "JSON",
	            data: 'haksi=valid&aktif='+ active +'&hID=' + getid.id_kecamatan,
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
		var konfirm = confirm('Hapus Data Kecamatan ?');
		if (konfirm == 1) {
			$.ajax({
	            type: 'POST',
	            url: '<?= base_url("aksi_kecamatan") ?>',
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
</script>