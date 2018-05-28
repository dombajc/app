<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.base.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.metro.css') ?>" type="text/css" />

<style>
	.jqx-cell { padding: 0px 4px; }
	
	.jqx-widget-content-metro { font-family:basicFont !important; }
	
	.jqx-tree-grid-indent { border-bottom: 1px #ccc dashed; }
	
	.jqx-tree-grid-title { padding-left: 10px; }
</style>

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
				<input type="hidden" id="h-edit-skpd">
				<input type="hidden" id="h-edit-sub-rekening">
				<input type="hidden" id="h-edit-no-rekening">
	            <div class="box-body">
					<div class="form-group">
						<label class="col-sm-4 control-label">UP3AD pengampu <span class="text-danger">*</span></label>
						<div class="col-sm-8">
							<select name="slctlokasi" id="slctlokasi" class="form-control input-sm nextinput" tabIndex="1">
							<option value=""> -- Pilih salah satu -- </option>
							<?= $this->Lokasi->optionAll() ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Nama SKPD <span class="text-danger">*</span></label>
						<div class="col-sm-8">
							<select name="slctskpd" id="slctskpd" class="form-control input-sm nextinput" tabIndex="2">
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Sub Rekening <span class="text-danger">*</span></label>
						<div class="col-sm-8">
							<select name="slctsub" id="slctsub" class="form-control input-sm nextinput" tabIndex="3">
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">No Rekening <span class="text-danger">*</span></label>
						<div class="col-sm-8" id="input-no-rekening">
							
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Nama Rekening <span class="text-danger">*</span></label>
						<div class="col-sm-8">
							<input type="text" name="txtnamarekening" id="txtnamarekening" class="form-control input-sm nextinput" tabIndex="5">
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
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxdata.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxdatatable.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxtreegrid.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script type="text/javascript">
    $(document).ready(function () {
		
    	var source =
		{
        	datatype: "json",
            url: "<?= base_url('grid_rekening_skpd_lain') ?>",
		    type : 'post',
            sort: function () {
                // update the grid and send a request to the server.
                $("#jqxgrid").jqxGrid('updatebounddata', 'sort');
            },
			hierarchy:
			{
				keyDataField: { name: 'id_rekening' },
				parentDataField: { name: 'sub_rekening' }
			}
        };
		var dataAdapter = new $.jqx.dataAdapter(source,
		    {
		        formatData: function (data) {
		            $.extend(data, {
		                //cariNama : $('#filter-skpd').val(),
		                //cariLokasi : $('#filter-lokasi').val()
		            });
		            return data;
		        }
		    }
		);

        $("#jqxgrid").jqxTreeGrid(
        {
            theme: 'metro',
            width: '100%',
            height: '400',
            source: dataAdapter,
            sortable: true,
            //groupable: true,
            //columnsresize: true,
            //altrows: true,
            //scrollmode: 'deferred',
            //virtualmode: true,
            //deferreddatafields: ['no_spbu', 'nama_spbu', 'alamat_spbu'],
            columns: [
              { text: 'No. REKENING', datafield: 'no_rekening2', width: '32.5%', cellsalign: 'left', align: 'center' },
              { text: 'NAMA REKENING', datafield: 'nama_rekening', width: '60%', cellsalign: 'left', align: 'center' },
              {
                  text: '--', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false, cellsalign: 'center', align: 'center',
                  datafield: 'aktif', width: 30,
                  cellsrenderer: function (row, column, value) {
                  	
                  	if ( value == 1 ) {
                  		return '<button class="btn btn-xs btn-default btn-flat text-center" onclick="jsValidation(0)"><i class="fa fa-check text-success"></i></button>';
                  	} else {
                    	return '<button class="btn btn-xs btn-default btn-flat text-center" onclick="jsValidation(1)"><i class="fa fa-ban text-danger"></i></button>';
                  	}
                  }
              },
              {
                  text: '...', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false, cellsalign: 'center', align: 'center',
                  datafield: 'id_rekening', width: 51,
                  cellsrenderer: function (row, column, value) {
                      return '<button class="btn btn-xs btn-info btn-flat" onclick="jsDetil(\''+ value +'\')"><i class="fa fa-edit"></i></button><button class="btn btn-xs btn-danger btn-flat" onclick="jsDelete(\''+ value +'\')"><i class="fa fa-trash"></i></button>';
                  }
              },
          ]
        });
		
		$('#jqxgrid').on('bindingComplete', function() {
			$("#jqxgrid").jqxTreeGrid('expandAll');
		});

        $("#btn-filter").click(function () {
            $("#jqxgrid").jqxTreeGrid('updateBoundData');
        });
		
		$('#modal-form').on('shown.bs.modal', function () {
			$('#slctlokasi').focus();
		});

        $('#btn-add-item').click(function(){
        	$('#modal-form').modal('show');
        	$('form')[0].reset();
			$('form').formValidation('resetForm', true);
        	$('#title-form').text('TAMBAH REKENING BARU');
        	$('#haksi').val('add');
			option_sub();
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
				txtnorekening: {
					validators: {
						notEmpty: {
							message: 'Harap diisi !'
						}
					}
				},
				txtnamarekening: {
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
				slctskpd: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slctsub: {
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
			
			var konfirm = confirm('SIMPAN DATA ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_rekening_skpd_lain") ?>',
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
		
		$('#slctlokasi').change(function(){
			option_sub();
		});
		
		$('#slctskpd').change(function(){
			option_skpd();
		});
		
		$('#slctsub').change(function(){
			action_select_sub_rekening();
		});
    });

	function jsDetil(IDclick)
	{
		$.ajax({
            type: 'POST',
            url: '<?= base_url("get_no_rekening_by_id") ?>',
            dataType: "JSON",
            data: 'postid=' + IDclick,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
                                $('#modal-form').modal('show');
                                $('form')[0].reset();
								$('form').formValidation('resetForm', true);
					        	$('#title-form').text('UPDATE DATA REKENING SKPD LAIN');
					        	$('#haksi').val('edit');
					        	$('#hid').val(html.data.id_rekening);
								$('#slctlokasi').val(html.data.id_lokasi);
								$('#h-edit-skpd').val(html.data.id_skpd);
								$('#h-edit-sub-rekening').val(html.data.sub_rekening);
								$('#h-edit-no-rekening').val(html.data.no_rekening);
								$('#txtnamarekening').val(html.data.nama_rekening);
								$('#txtmurni').val(html.data.murni);
								$('#txtperubahan').val(html.data.perubahan);
								option_sub();
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
		var getselectedrowindexes = $('#jqxgrid').jqxTreeGrid('getSelection');
        if (getselectedrowindexes.length > 0)
        {
            // returns the selected row's data.
            var getid = getselectedrowindexes[0].id_rekening;

            $.ajax({
	            type: 'POST',
	            url: '<?= base_url("aksi_rekening_skpd_lain") ?>',
	            dataType: "JSON",
	            data: 'haksi=valid&aktif='+ active +'&hid=' + getid,
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

	function jsDelete(IDclick)
	{
		var konfirm = confirm('HAPUS DATA ?');
		if (konfirm == 1) {
			$.ajax({
	            type: 'POST',
	            url: '<?= base_url("aksi_rekening_skpd_lain") ?>',
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
	
	function option_sub()
	{
		var get_id_lokasi = $('#slctlokasi').val();
		
		var inputselect = $('#slctskpd');
		inputselect.empty();
		if ( get_id_lokasi == "" )
		{
			inputselect.append('<option value=""> -- Pilih salah satu UP3AD pengampu dahulu -- </option>');
			option_skpd();
		}
		else
		{
			$.ajax({
				type: 'POST',
				url: '<?= base_url("get_option_skpd_lain") ?>',
				dataType: "JSON",
				data: 'postlokasi=' + get_id_lokasi,
				success: function(data) {
					inputselect.append('<option value=""> -- Pilih salah satu -- </option>');
					$.each(data, function(key, value){
						inputselect.append('<option value="'+ value.id_skpd +'"> '+ value.nama_skpd +' </option>');
					});
					inputselect.val($('#h-edit-skpd').val());
					option_skpd();
				},
				error: function(xhr, ajaxOptions, thrownError) {
					pesanError(xhr.responseText);
				}
			});
		}
		
	}
	
	function option_skpd()
	{
		var get_id_skpd = $('#slctskpd').val();
		
		var inputselect = $('#slctsub');
		inputselect.empty();
		if ( get_id_skpd == "" )
		{
			inputselect.append('<option value=""> -- Pilih salah satu SKPD dahulu -- </option>');
		}
		else
		{
			$.ajax({
				type: 'POST',
				url: '<?= base_url("get_option_sub_rekening_skpd_lain") ?>',
				dataType: "JSON",
				data: 'postskpd=' + get_id_skpd,
				success: function(data) {
					inputselect.append('<option value="0"> INDUK </option>');
					$.each(data, function(key, value){
						inputselect.append('<option value="'+ value.id_rekening +'"> '+ value.nama_rekening +' </option>');
					});
					
					var get = $('#h-edit-sub-rekening').val() == '' ? 0 : $('#h-edit-sub-rekening').val();
					inputselect.val(get);
					action_select_sub_rekening();
				},
				error: function(xhr, ajaxOptions, thrownError) {
					pesanError(xhr.responseText);
				}
			});
		}
	}
	
	function action_select_sub_rekening()
	{
		var value = $('#slctsub').val();
		var input = $('#input-no-rekening');
		input.empty();
		var writeHTML = '';
		
		if ( value !== '0' && value !== '' ) {
			$.ajax({
				type: 'POST',
				url: '<?= base_url('get_no_rekening_by_id') ?>',
				dataType: "JSON",
				data: 'postid=' + value,
				success: function(result) {
					if ( result.error == '' )
					{
						writeHTML += '<div class="input-group">'+
						'<span class="input-group-addon">'+ result.data['no_rekening'] +'</span>'+
						'<input type="text" class="form-control input-sm nextinput" name="txtnorekening" id="txtnorekening" tabIndex="4">'+
						'</div>';
						input.append(writeHTML);
						$('#txtnorekening').val($('#h-edit-no-rekening').val());
					} else {
						alert(result.error);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					pesanError(xhr.responseText);
				}
			});
		} else{
			writeHTML += '<input type="text" name="txtnorekening" id="txtnorekening" class="form-control input-sm nextinput" tabIndex="3">';
			input.append(writeHTML);
			$('#txtnorekening').val($('#h-edit-no-rekening').val());
		}
		
		
		
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
	}

</script>