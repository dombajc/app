<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.base.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.metro.css') ?>" type="text/css" />

<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading" id="titleModal">
			<?= $title ?>	
			</header>
			<div class="panel-body">
				
			</div>
		</section>
	</div>
	<div class="col-sm-6">
		<section class="panel">
			<div class="panel-body">
				<button type="button" id="btn-add-item" class="btn btn-xs btn-info btn-flat"><i class="fa fa-plus"></i> Tambah</button><button type="button" id="btn-refresh" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-refresh"></i> Segarkan</button>
				<div id="jqxgrid"></div>
			</div>
		</section>
	</div>
	<div class="col-sm-6">
		<section class="panel">
			<header class="panel-heading" id="title-form">
			Tambah Jenis Bahan Bakar
			</header>
			<div class="panel-body">
				<form class="forms form-horizontal">
		            <input type="hidden" id="haksi" name="haksi" value="add">
		            <input type="hidden" id="hID" name="hID">
		            <div class="box-body">
						<div class="form-group">
						  	<label class="col-sm-5 control-label">Nama Bahan Bakar <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
								<input type="text" name="txtitem" id="txtitem" class="form-control">
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
            url: "<?= base_url('dataitempbbkb') ?>",
            data: {
		        featureClass: "P",
		        style: "full",
		        maxRows: 50
		    },
		    type : 'post',
		    root: 'Rows',
		    id : 'id_item_pbbkb',
		    async : 'false',
		    cache : 'false',
			beforeprocessing: function(data) {
                if (data != null && data.length > 0) {
                    source.totalrecords = data[0].TotalRows;
                }
            },
            sort: function () {
                // update the grid and send a request to the server.
                $("#jqxgrid").jqxGrid('updatebounddata', 'sort');
            }
        };
        var dataadapter = new $.jqx.dataAdapter(source);

        $("#jqxgrid").jqxGrid(
        {
            theme: 'metro',
            width: '100%',
            height: '100%',
            source: dataadapter,
            sortable: true,
            columnsresize: true,
            pageable: true,
            virtualmode: true,
            pagermode: 'simple',
            pagesize: 2,
            rendergridrows: function () {
                return dataadapter.records;
                ///var data = generatedata(params.startindex, params.endindex);
                //return data;
            },
            ready : function(){

            },
            columns: [
        	  {
                  text: '#', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false,
                  datafield: '', columntype: 'number', width: 40, cellsalign: 'right', align: 'center',
                  cellsrenderer: function (row, column, value) {
                      return "<div style='margin:4px;'>" + (value + 1) + "</div>";
                  }
              },
              { text: 'Jenis Bahan Bakar', datafield: 'item_pbbkb', width: '80%', cellsalign: 'left', align: 'center' },
              {
                  text: '...', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false, cellsalign: 'center', align: 'center',
                  datafield: 'id_item_pbbkb', width: 60,
                  cellsrenderer: function (row, column, value) {
                      return '<button class="btn btn-sm btn-info btn-flat" onclick="jsDetil(\''+ value +'\')"><i class="fa fa-edit"></i></button><button class="btn btn-sm btn-danger btn-flat" onclick="jsDelete(\''+ value +'\')"><i class="fa fa-trash"></i></button>';
                  }
              },
          ]
        });

        $("#btn-refresh").click(function () {
        	//$('#jqxgrid').jqxGrid({ source: dataadapter });
            //$("#jqxgrid").jqxGrid('source', dataadapter);
            //source.url = "<?= base_url('dataitempbbkb') ?>";
            $("#jqxgrid").jqxGrid('clear');
            $("#jqxgrid").jqxGrid('updatebounddata');
        });

        $('#btn-add-item').click(function(){
        	$('form')[0].reset();
			$('form').formValidation('resetForm', true);
        	$('#title-form').text('Tambah Jenis Bahan Bakar');
        	$('#haksi').val('add');
        	$('#txtitem').focus();
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
				txtitem: {
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
					url: '<?= base_url("aksi_item_pbbkb") ?>',
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
            url: '<?= base_url("select_item_pbbkb") ?>',
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
					        	$('#title-form').text('Perbaharui Jenis Bahan Bakar');
					        	$('#haksi').val('edit');
					        	$('#hID').val(html.item.id_item_pbbkb);
					        	$('#txtitem').val(html.item.item_pbbkb);
					        	$('#txtitem').focus();
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

	function jsDelete(IDclick)
	{
		$.ajax({
            type: 'POST',
            url: '<?= base_url("aksi_item_pbbkb") ?>',
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
</script>