<!-- CSS Bootgrid  -->
<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.base.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.metro.css') ?>" type="text/css" />

<link rel="stylesheet" href="<?= base_url('plugins/datepicker/datepicker3.css') ?>" type="text/css" />

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
									<input type="text" name="txtnip" id="txtnip" class="form-control input-sm">
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">NAMA <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<input type="text" name="txtnama" id="txtnama" class="form-control kodebesar input-sm" style="text-transform:uppercase">
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">Pangkat / Golongan <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<select name="slctpangkat" id="slctpangkat" class="form-control input-sm">
									</select>
								  </div>
								</div>
								<div id="show-only-add">
									<div class="form-group">
									  <label class="col-sm-4 control-label">Jabatan <span class="text-danger">*</span></label>
									  <div class="col-sm-8">
										<select name="slctjabatan" id="slctjabatan" class="form-control input-sm">
										</select>
									  </div>
									</div>
									<div class="form-group">
									  <label class="col-sm-4 control-label">LOKASI <span class="text-danger">*</span></label>
									  <div class="col-sm-8">
										<select name="slctlokasi" id="slctlokasi" class="form-control input-sm">
											<?= $this->Lokasi->optionAllUpad() ?>
										</select>
									  </div>
									</div>
									<div class="form-group">
									  <label class="col-sm-4 control-label">HomeBase <span class="text-danger">*</span></label>
									  <div class="col-sm-8">
										<select name="slcthomebase" id="slcthomebase" class="form-control input-sm">
										</select>
									  </div>
									</div>
								</div>
								<div class="form-group" id="div-tgl-lahir">
								  <label class="col-sm-4 control-label">Tgl Lahir <span class="text-danger">*</span></label>
								  <div class="col-sm-4">
									<div class="input-group date">
										<input type="text" class="form-control input-sm" id="txttgllahir" name="txttgllahir"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
									</div>
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-4 control-label">No.HP <span class="text-danger">*</span></label>
								  <div class="col-sm-8">
									<input type="text" name="txtnohp" id="txtnohp" class="form-control input-sm">
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
			</header>
			<div class="panel-body">
				<table width="100%">
					<tr>
						<td>NAMA PEGAWAI </td>
						<td><input type="text" id="find-nama" class="form-control input-sm"></td>
						<td align="right">NIP </td>
						<td><input type="text" id="find-nip" class="form-control input-sm"></td>
						<td align="right">STATUS PEGAWAI </td>
						<td><select id="find-status" class="form-control input-sm">
						<?= $this->Statuspegawai->selectoption() ?>
						</select></td>
						<td>LOKASI </td>
						<td>
						<select id="find-lokasi" class="form-control input-sm">
						<?= $this->Lokasi->opsiAllUpadViewInputanRekamD2D() ?>
						</select>
						</td>
						<td>
						<button type="button" class="btn btn-sm btn-info pull-right" id="btn-cari"> Cari Data </button>
						</td>
					</tr>
				</table>
					
			</div>
		</section>
	</div>
	<div class="col-lg-12">
		<section class="panel">
			<div class="panel-body">
				<div class="text-right">
				<button type="button" class="btn btn-sm btn-success" id="btn-tambah"> <i class="glyphicon glyphicon-plus-sign"></i> Tambah Pegawai </button>
				</div><br>
				<div id="jqxgrid"></div>
			</div>
		</section>
	</div>
</div>

<script src="<?= base_url('plugins/jquery.inputmask-3.x/dist/min/jquery.inputmask.bundle.min.js') ?>"></script>

<!-- Boot Grid -->
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

<!-- Formatter -->
<script type="text/javascript" src="<?= base_url('plugins/formatter/dist/jquery.formatter.min.js') ?>"></script>

<!-- Date Picker -->
<script type="text/javascript" src="<?= base_url('plugins/datepicker/bootstrap-datepicker.js') ?>"></script>



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

		$('.input-group.date').datepicker({
			format: "dd-mm-yyyy",
		    	autoclose: true
		});

		$('#txtnohp').formatter({
		  	'pattern': '{{999999999999}}',
		  	'persistent': true
		});

		var source =
			{
			datatype: "json",
		    url: "<?= base_url('load_data_pegawai') ?>",
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
					postLokasi : $('#find-lokasi').val(),
					postNIP : $('#find-nip').val(),
					postNama : $('#find-nama').val(),
					postStatus : $('#find-status').val()
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
		    deferreddatafields: ['nama_pegawai'],
		    columns: [
			  {
		          text: '#', sortable: false, filterable: false, editable: false,
		          groupable: false, draggable: false, resizable: false,
		          datafield: '', columntype: 'number', width: 40, cellsalign: 'right', align: 'center',
		          cellsrenderer: function (row, column, value) {
		              return "<div style='margin:4px;'>" + (value + 1) + "</div>";
		          }
		      },
		      { text: 'Nama Pegawai', datafield: 'nama_pegawai', width: '20%', cellsalign: 'left', align: 'center' },
		      { text: 'NIP', datafield: 'nip', width: '15%', cellsalign: 'center', align: 'center' },
		      { text: 'Status', datafield: 'status_pegawai', width: '8%', cellsalign: 'center', align: 'center' },
		      { text: 'Jabatan', datafield: 'jabatan', width: '10%', cellsalign: 'center', align: 'center' },
		      { text: 'Lokasi', datafield: 'nama_lokasi', width: '19%', cellsalign: 'center', align: 'center' },
		      { text: 'Homebase', datafield: 'nama_homebase', width: '18%', cellsalign: 'center', align: 'center' },		      
		      {
		          text: '...', sortable: false, filterable: false, editable: false,
		          groupable: false, draggable: false, resizable: false, cellsalign: 'center', align: 'center',
		          datafield: 'id_pegawai', width: 60,
		          cellsrenderer: function (row, column, value) {
		              return '<button class="btn btn-sm btn-info btn-flat" onclick="getDetilData(\''+ value +'\')"><i class="fa fa-edit"></i></button><button class="btn btn-sm btn-danger btn-flat" onclick="actDelete(\''+ value +'\')"><i class="fa fa-trash"></i></button>';
		          }
		      },
		  ]
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
			var $homebase = $("#slcthomebase");
    		$homebase.empty();
			//$('#slctlokasi').val("<?= $this->Opsisite->getDataUser()['id_lokasi'] ?>");
			//aksiUbahLokasi();
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
					validators: {
						callback: {
                            message: 'NIP harap di isi !',
                            callback: function(value, validator, $field) {
                                var sts = $('form').find('[name="slctstatus"]:checked').val();
                                return (sts !== '33') ? true : (value !== '');
                            }
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
						callback: {
                            message: 'Pilih salah satu !',
                            callback: function(value, validator, $field) {
                                var sts = $('form').find('[name="haksi"]').val();
                                return (sts !== 'add') ? true : (value !== '');
                            }
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
						callback: {
                            message: 'Pilih salah satu !',
                            callback: function(value, validator, $field) {
                                var sts = $('form').find('[name="haksi"]').val();
                                return (sts !== 'add') ? true : (value !== '');
                            }
                        }
					}
				},
				slcthomebase: {
					validators: {
						callback: {
                            message: 'Pilih salah satu !',
                            callback: function(value, validator, $field) {
                                var sts = $('form').find('[name="haksi"]').val();
                                return (sts !== 'add') ? true : (value !== '');
                            }
                        }
					}
				},
				txttgllahir: {
					validators: {
						callback: {
                            message: 'Tanggal Lahir harap di isi !',
                            callback: function(value, validator, $field) {
                                var sts = $('form').find('[name="slctstatus"]:checked').val();
                                return (sts !== '99') ? true : (value !== '') ;
                            }
                        },
                        date: {
	                        format: 'DD-MM-YYYY',
	                        message: 'Penulisan tanggal salah. Contoh (dd-mm-yyyy)'
	                    }
					}
				},
				txtnohp: {
					validators: {
						notEmpty: {
							message: 'Isikan no.HP. Contoh : 08178373676 !'
						}
					}
				}
			}
		})
		.on('change', '[name="slctstatus"]', function() {
			$('form').formValidation('revalidateField', 'txtnip');
            $('form').formValidation('revalidateField', 'slctpangkat');
            $('form').formValidation('revalidateField', 'slctjabatan');
            $('form').formValidation('revalidateField', 'txttgllahir');
        })
        .on('keyup', '[name="txtnohp"]', function() {
			$('form').formValidation('revalidateField', 'txtnohp');
        })
        .on('change', '[name="txttgllahir"]', function() {
			$('form').formValidation('revalidateField', 'txttgllahir');
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
			$("#jqxgrid").jqxGrid('updatebounddata');
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
								$('#txttgllahir').val(html.tgl_lahir);
								$('#txtnohp').val(html.no_hp);
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
		$('#div-tgl-lahir').hide('fast');
    		var $opsipangkat = $("#slctpangkat");
			$opsipangkat.empty();
			$opsipangkat.append('<?= $this->Pangkat->opsiAsn() ?>');
			var $opsijabatan = $("#slctjabatan");
			$opsijabatan.empty();
			$opsijabatan.append('<?= $this->Jabatan->opsiAsn() ?>');
    	}else if ( value === '99' ){
    		$('#div-nip').hide('fast');
		$('#div-tgl-lahir').show('fast');
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
								//$homebase.val("<?= $this->Opsisite->getDataUser()['id_lokasi'] ?>");
	                            
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
