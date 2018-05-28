<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.base.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.metro.css') ?>" type="text/css" />

<!-- Form Modal Edit -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="title-form"></h4>
      	</div>
      	<div class="modal-body">
      		<form class="form-horizontal">
      			<input type="hidden" id="haksi" name="haksi" value="add">
	            <input type="hidden" id="hID" name="hID">
	            <input type="hidden" id="pastSpbu">
	            <input type="hidden" id="pastTgl">
	            <input type="hidden" id="pastKota">
	            <input type="hidden" id="pastPenyalur">
	            <div class="row">
	            	<div class="col-lg-6">
						<div class="form-group">
						  	<label class="col-sm-5 control-label">Kota / UP3AD <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
						  		<select name="slctlokasi" id="slctlokasi" class="form-control">
								<?= $this->Lokasi->optionAllUpad() ?>
								</select>
						  	</div>
						</div>
						<div class="form-group">
						  	<label class="col-sm-5 control-label">Nama Perusahaan <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
						  		<select name="slctspbu" id="slctspbu" class="form-control">
								<option value=""> -- Pilih salah satu -- </option>
								</select>
						  	</div>
						</div>
						<div class="form-group">
						<label class="col-sm-5 control-label">Tahun <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
						  		<select name="slcttahun" id="slcttahun" class="form-control">
								<?= $this->Thanggaran->opsiByTahunAktif() ?>
								</select>
						  	</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Bulan <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
						  		<select name="slctbulan" id="slctbulan" class="form-control">
								<?= $this->Bulan->getBulanOrderByBulanAktif() ?>
								</select>
						  	</div>
						</div>
						<div class="form-group">
						  	<label class="col-sm-5 control-label">Tanggal DO <span class="text-danger">*</span></label>
						  	<div class="col-sm-3">
								<select name="slcttgl" id="slcttgl" class="form-control">
								</select>
						  	</div>
						</div>
						<h4>Data Penyalur / Penyedia Bahan Bakar</h4>
						<div class="form-group">
							<label class="col-sm-5 control-label">Provinsi <span class="text-danger">*</span></label>
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
									<option value=""> -- Pilih Salah satu -- </option>
									<?= $this->Kotaasal->printOpsi() ?>
								</select>
						  	</div>
						</div>
						<div class="form-group">
						  	<label class="col-sm-5 control-label">Nama Penyedia BBM <span class="text-danger">*</span></label>
						  	<div class="col-sm-7">
						  		<select name="slctpenyedia" id="slctpenyedia" class="form-control">
									<option value=""> -- Pilih Kota Asal Dahulu -- </option>
								</select>
						  	</div>
						</div>
					</div>
					<div class="col-lg-6">
						<?php
							foreach( $this->Pbbkb->getAllData() as $row ) {
								echo '<div class="form-group">
								<label class="col-sm-5 control-label">'. $row['item_pbbkb'] .'</label>
								  	<div class="col-sm-7">
								  		<input type="hidden" name="idpbbkb[]" value="'. $row['id_item_pbbkb'] .'">
								  		<div class="input-group">
											<input type="text" name="txtliter[]" id="txtliter_'. $row['id_item_pbbkb'] .'" class="form-control nominal">
											<span class="input-group-addon"><i>Liter</i></span>
										</div>
								  	</div>
								</div>';
							}
						?>
					</div>
	            </div>
				
				<div class="box-footer text-right">
					<button type="submit" class="btn btn-sm btn-info"> Simpan </button>
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
			<div class="panel-body form-horizontal">
				<table width="100%">
				<tr>
				<td align="right">Tahun : </td>
				<td><select id="sTh" class="form-control"><?= $this->Thanggaran->opsiByTahunAktif() ?></select></td>
				<td align="right">Bulan : </td>
				<td><select id="sBulan" class="form-control"><?= $this->Bulan->getBulanOrderByBulanAktif() ?></select></td>
				<td align="right">Penyalur : </td>
				<td><input type="text" id="sPenyalur" class="form-control"></td>
				<td align="right">Perusahaan : </td>
				<td><input type="text" id="sBU" class="form-control"></td>
				<td><button type="button" id="btn-cari" class="btn btn-sm btn-default btn-flat"><i class="fa fa-search"></i> Cari</button></td>
				<td align="right"><button type="button" id="btn-tambah" class="btn btn-sm btn-info btn-flat"><i class="fa fa-plus"></i> Tambah</button></td>
				</tr>
				</table>
				
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

<!-- Number -->
<script type="text/javascript" src="<?= base_url('plugins/jquery-number-master/jquery.number.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/accounting.min.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script>

	$(function(){
		
		$('.nominal').number( true, 0 );

		var source =
		{
        	datatype: "json",
            url: "<?= base_url('load_data_entrian_distribusi_bbm') ?>",
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
		                cariTh: $('#sTh').val(),
		                cariBulan : $('#sBulan').val(),
		                cariPenyalur : $('#sPenyalur').val(),
		                cariBU : $('#sBU').val()
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
                      return "<div style='margin:6px; text-align:right;'>" + (value + 1) + "</div>";
                  }
              },
              { text: 'Tgl DO', datafield: 'tgl_input', width: '8%', cellsalign: 'center', align: 'center' },
              { text: 'Dari Penyalur', datafield: 'nama_penyalur', width: '15%', cellsalign: 'left', align: 'center' },
              { text: 'Provinsi', datafield: 'provinsi', width: '12%', cellsalign: 'center', align: 'center' },
              { text: 'Kota', datafield: 'kota_asal', width: '13%', cellsalign: 'center', align: 'center' },
              { text: 'Ke Perusahaan', datafield: 'nama_spbu', width: '15%', cellsalign: 'left', align: 'center' },
              { text: 'UP3AD', datafield: 'lokasi', width: '18%', cellsalign: 'center', align: 'center' },
              { text: 'Total DO (Liter)', datafield: 'total', width: '10%', cellsalign: 'right', align: 'center',
              	cellsrenderer: function (row, column, value) {
                      return "<div style='margin:6px; text-align:right;'>" + accounting.formatMoney(value, "", 0) + "</div>";;
                  } 
              },
              {
                  text: '...', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false, cellsalign: 'center', align: 'center',
                  datafield: 'id_distribusi_bbm', width: 60,
                  cellsrenderer: function (row, column, value) {
                      return '<button class="btn btn-sm btn-info btn-flat" onclick="getOpenFormEdit(\''+ value +'\')"><i class="fa fa-edit"></i></button><button class="btn btn-sm btn-danger btn-flat" onclick="jsDelete(\''+ value +'\')"><i class="fa fa-trash"></i></button>';
                  }
              },
          ]
        });
		
		$('#slctbulan').change(function(){
			load_opsi_tanggal_per_bulan();
		});

		$('#slctlokasi').change(function(){
			load_opsi_perusahaan_by_lokasi();
		});

		$('#slctprov').change(function(){
			load_opsi_kota_asal_by_provinsi();
		});

		$('#slctkota').change(function(){
			load_opsi_penyalur_by_kota();
		});

		$('#btn-cari').click(function(){
			$("#jqxgrid").jqxGrid('updatebounddata');
		});

		$('#btn-tambah').click(function(){
			$('#modal-form').modal('show');
        	$('form')[0].reset();
			$('form').formValidation('resetForm', true);
        	$('#title-form').text('Tambah Entrian Distribusi Bahan Bakar');
        	$('#haksi').val('add');
        	$('#slctlokasi, #slcttahun, #slctbulan, #slctprov').prop('selectedIndex',0);
        	load_opsi_perusahaan_by_lokasi();
			
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
				},
				slctspbu: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slcttahun: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slctbulan: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slcttgl: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
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
				},
				slctpenyedia: {
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
					url: '<?= base_url("aksi_penyaluran_bbm") ?>',
					dataType: "JSON",
					data: $('form').serialize(),
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										pesanOK(html.msg);
										$('#modal-form').modal('hide');
										$("#jqxgrid").jqxGrid('updatebounddata');
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

	function load_opsi_kota_asal_by_provinsi()
	{
		var getProvinsi = $('#slctprov').val();
		var getAksi = $('#haksi').val();

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

                            opsiKota.empty();
                            if ( html.opsi.length === 0 ) {
                            	opsiKota.append('<option value=""> -- Data Kota Asal Kosong -- </option>');
                            } else {
                            	opsiKota.append('<option value=""> -- Pilih salah satu -- </option>');
                            	$.each(html.opsi, function(key, value) {
                            		opsiKota.append('<option value="'+ value.id_kota_asal +'">'+ value.kota_asal +'</option>');
                            	});
                            }

                            if ( getAksi == 'edit' ) {
                        		opsiKota.val($('#pastKota').val());
                        		load_opsi_penyalur_by_kota();
                        	} else {
                        		var opsiPenyalur = $('#slctpenyedia');
	                            opsiPenyalur.empty();
	                            opsiPenyalur.append('<option value=""> -- Pilih Kota Asal Dahulu -- </option>');
                        	}

                            //Reset validasi untuk lokasi Kota dan Penyalur
                            $('form')
                            	.formValidation('revalidateField', 'slctkota')
                            	.formValidation('revalidateField', 'slctpenyedia');
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

	function load_opsi_penyalur_by_kota()
	{
		var pilihkotaasal = $('#slctkota').val();
		var getAksi = $('#haksi').val();

		$.ajax({
            type: 'POST',
            url: '<?= base_url("get_opsi_penyalur_by_kota") ?>',
            dataType: "JSON",
            data: 'postIdKotaAsal=' + pilihkotaasal,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            var opsiPenyalur = $('#slctpenyedia');
                            opsiPenyalur.empty();
                            if ( html.opsi.length === 0 ) {
                            	opsiPenyalur.append('<option value=""> -- Data Penyalur Kosong -- </option>');
                            } else {
                            	opsiPenyalur.append('<option value=""> -- Pilih salah satu -- </option>');
                            	$.each(html.opsi, function(key, value) {
                            		opsiPenyalur.append('<option value="'+ value.id_penyalur +'">'+ value.nama_penyalur +'</option>');
                            	});

                            	if ( getAksi == 'edit' ) {
	                        		opsiPenyalur.val($('#pastPenyalur').val());
	                        	}

	                        	//Reset validasi untuk lokasi Kota dan Penyalur
	                            $('form')
	                            	.formValidation('revalidateField', 'slctpenyedia');

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

	function load_opsi_perusahaan_by_lokasi()
	{
		var getLokasi = $('#slctlokasi').val();
		var getJenis = '33';
		var getAksi = $('#haksi').val();

		$.ajax({
            type: 'POST',
            url: '<?= base_url("get_json_penyetor_by_id_lokasi") ?>',
            dataType: "JSON",
            data: 'postIdLokasi=' + getLokasi +'&postJenis='+ getJenis,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            var opsiSpbu = $('#slctspbu');
                            opsiSpbu.empty();
                            if ( html.opsi.length === 0 ) {
                            	opsiSpbu.append('<option value=""> -- Data Badan Usaha Kosong -- </option>');
                            } else {
                            	opsiSpbu.append('<option value=""> -- Pilih salah satu -- </option>');
                            	$.each(html.opsi, function(key, value) {
                            		opsiSpbu.append('<option value="'+ value.id_lokasi_pbbkb +'">'+ value.nama_spbu +'</option>');
                            	});
                            	if ( getAksi == 'edit' ) {
                            		opsiSpbu.val($('#pastSpbu').val());
                            	}
								load_opsi_tanggal_per_bulan();
                            }

                            //Reset validasi untuk lokasi SPBU
                            $('form').formValidation('revalidateField', 'slctspbu');
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

	function load_opsi_tanggal_per_bulan()
	{
		var getBulan = $('#slctbulan').val();
		var getTahun = $('#slcttahun').val();
		var getAksi = $('#haksi').val();

		$.ajax({
            type: 'POST',
            url: '<?= base_url("get_json_opsi_tanggal") ?>',
            dataType: "JSON",
            data: 'postBulan=' + getBulan +'&postTahun='+ getTahun,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            var opsiTgl = $('#slcttgl');
                            opsiTgl.empty();
                        	//opsiTgl.append('<option value=""> -- Pilih salah satu -- </option>');
                        	$.each(html.tgl, function(key, value) {
                        		opsiTgl.append('<option value="'+ value +'">'+ value +'</option>');
                        	});
                        	if ( getAksi == 'edit' ) {
                        		opsiTgl.val($('#pastTgl').val());
                        		load_opsi_kota_asal_by_provinsi();
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

	function getOpenFormEdit(getID)
	{
		$.ajax({
            type: 'POST',
            url: '<?= base_url("view_entrian_distribusi") ?>',
            dataType: "JSON",
            data: 'postid=' + getID,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
                                $('#modal-form').modal('show');
                                $('form')[0].reset();
								$('form').formValidation('resetForm', true);
					        	$('#title-form').text('Perbaharui Entrian Distribusi Bahan Bakar');
					        	$('#haksi').val('edit');
					        	$('#hID').val(html.getEntrian.id_distribusi_bbm);
					        	$('#slctlokasi').val(html.getEntrian.id_lokasi);
					        	$('#pastSpbu').val(html.getEntrian.id_lokasi_pbbkb);
					        	$('#slcttahun').val(html.getEntrian.id_anggaran);
					        	$('#slctbulan').val(html.getEntrian.id_bulan);
					        	$('#pastTgl').val(html.getEntrian.tgl_entry);
					        	$('#slctprov').val(html.getEntrian.id_provinsi);
					        	$('#pastKota').val(html.getEntrian.id_kota_asal);
					        	$('#pastPenyalur').val(html.getEntrian.id_penyalur);

					        	$.each(html.itemEntrian, function(key, value) {
					        		$('#txtliter_'+ value.id_item_pbbkb).val(value.jumlah);
					        	});

					        	load_opsi_perusahaan_by_lokasi();
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
		var konfirm = confirm('Hapus Data Entrian ?');
		if (konfirm == 1) {
			$.ajax({
	            type: 'POST',
	            url: '<?= base_url("hapus_entrian_distribusi_bbm") ?>',
	            dataType: "JSON",
	            data: 'id=' + IDclick,
	            success: function(html) {

	                setTimeout(function() {
	                    $.unblockUI({
	                        onUnblock: function() {
	                            if (html.error == '')
	                            {
	                            	pesanOK(html.msg);
									$("#jqxgrid").jqxGrid('updatebounddata');
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