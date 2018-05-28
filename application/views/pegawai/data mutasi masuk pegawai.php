<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.base.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.metro.css') ?>" type="text/css" />

<!-- CSS Datepicker Bootgrid  -->
<link href="<?= base_url('plugins/bootdatepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet" />

<!-- Form Modal Edit -->
<div class="modal fade" id="modal-edit-mutasi-pegawai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<h4 class="modal-title">VALIDASI MUTASI MASUK PEGAWAI</h4>
      	</div>
      	<div class="modal-body">
      		<p class="text-warning">
      		Form ini bertujuan untuk validasi data pegawai masuk setelah dilakukan mutasi keluar oleh pihak asal. Dengan ini dimohon pengguna dapat melakukan pengecekan sebelum melakukan validasi mutasi. Karena setelah di validasi dan pegawai melakukan rekam door to door maka validasi tidak dapat di ubah.
      		</p>
      		<form class="forms form-horizontal">
      			<input type="hidden" name="hId" id="hId">
      			<div class="form-group">
					<label class="col-sm-4 control-label">Lokasi Homebase <span class="text-danger">*</span></label>
					<div class="col-sm-8">
						<select name="slctlokasi_mutasi" id="slctlokasi_mutasi" class="form-control">
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Lokasi D2D <span class="text-danger">*</span></label>
					<div class="col-sm-8">
						<select name="slctlokasi_d2d" id="slctlokasi_d2d" class="form-control">
						<!-- Get from JSON -->
						</select>
					</div>
				</div>
				<button type="submit" class="btn btn-sm btn-success pull-right">SIMPAN dan AKTIFKAN</button>
				<br>
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
				<div class="form-horizontal">
					<div class="form-group">
						<div class="col-sm-3">
							Tanggal TMT
							<div class="input-group input-daterange" id="btntxttgl">
								<input type="text" class="form-control input-sm" id="startDate" value="<?= date('01-m-Y') ?>" readonly>
								<span class="input-group-addon">s.d</span>
								<input type="text" class="form-control input-sm" id="endDate" value="<?= date('d-m-Y') ?>" readonly>
							</div>
						</div>
						<div class="col-sm-3">
							Nama Pegawai
							<input type="text" id="cari-nama" class="form-control input-sm">
						</div>
						<div class="col-sm-3">
							Lokasi
							<div class="input-group">
						      	<select class="form-control input-sm" id="cari-lokasi">
									<?= $this->Lokasi->opsiLokasiMutasiMasuk() ?>
								</select>
						      	<span class="input-group-btn">
						        	<button class="btn btn-sm btn-default" type="button" id="btn-refresh">Cari</button>
						      	</span>
						    </div>
						</div>
					</div>
				</div>
				
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

<!-- Date Picker -->
<script type="text/javascript" src="<?= base_url('plugins/bootdatepicker/js/bootstrap-datepicker.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/bootdatepicker/locales/bootstrap-datepicker.id.min.js') ?>"></script>

<script type="text/javascript">
	$(function(){
		$('.input-daterange').datepicker({
			format: "dd-mm-yyyy",
			language: "id",
			autoclose: true,
			todayBtn: "linked"
		});

		var source =
		{
        	datatype: "json",
            url: "<?= base_url('load_data_mutasi_masuk') ?>",
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
		                cariNama: $('#cari-nama').val(),
		                cariLokasi : $('#cari-lokasi').val(),
		                cariTglAwal : $('#startDate').val(),
		                cariTglAkhir : $('#endDate').val()
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
              { text: 'Nama Pegawai', datafield: 'nama_pegawai', width: '30%', cellsalign: 'left', align: 'center' },
              { text: 'NIP', datafield: 'nip', width: '15%', cellsalign: 'center', align: 'center' },
              { text: 'Lokasi Asal', datafield: 'lokasi_sebelumnya', width: '20%', cellsalign: 'center', align: 'center' },
              { text: 'Bukti No. SK', datafield: 'no_sk', width: '20%', cellsalign: 'center', align: 'center' },
              { text: 'TMT', datafield: 'tgl_sk', width: '8%', cellsalign: 'center', align: 'center' },
              {
                  text: '...', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false, cellsalign: 'center', align: 'center',
                  datafield: 'id_mutasi', width: 30,
                  cellsrenderer: function (row, column, value) {
                      return '<button class="btn btn-sm btn-info btn-flat" onclick="getDetilData(\''+ value +'\')"><i class="fa fa-edit"></i></button>';
                  }
              },
          ]
        });

        $("#btn-refresh").click(function () {
            $("#jqxgrid").jqxGrid('updatebounddata');
        });

        $('#slctlokasi_mutasi').change(function(){
			aksiUbahLokasiHomebase();
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
				slctlokasi_mutasi: {
					validators: {
						notEmpty: {
							message: 'Pilih salah satu !'
						}
					}
				},
				slctlokasi_d2d: {
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
					url: '<?= base_url("aksi_mutasi_masuk") ?>',
					dataType: "JSON",
					data: $('form').serialize(),
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										$('#modal-edit-mutasi-pegawai').modal('hide');
										$("#grid").bootgrid('reload');
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

	function getDetilData(getId)
	{
		$.ajax({
			type: 'POST',
			url: '<?= base_url("get_detil_lokasi_pegawai") ?>',
			dataType: "JSON",
			data: 'id='+ getId,
			success: function(html) {
				setTimeout(function() {
					$.unblockUI({
						onUnblock: function() {
							if (html.error == "")
							{
								$('#modal-edit-mutasi-pegawai').modal('show');
								$('form')[0].reset();
								$('form').formValidation('resetForm', true);
								$('#hId').val(html.id_mutasi);
								var slctHomebase = $('#slctlokasi_mutasi');
								var slctD2D = $('#slctlokasi_d2d');
								slctHomebase.empty();
								slctD2D.empty();
								$.each(html.opsiHomebase, function (key, data) {
									slctHomebase.append('<option value="'+ data.id_lokasi +'">'+ data.lokasi +'</option>');
								});
								$.each(html.opsiD2D, function (key, data) {
									slctD2D.append('<option value="'+ data.id_lokasi +'">'+ data.lokasi +'</option>');
								});
								slctHomebase.val(html.id_homebase);
								slctD2D.val(html.id_lokasi_d2d);
								
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

	function aksiUbahLokasiHomebase()
	{
		var lokasiD2D = $('#slctlokasi_d2d');
		var value = $('#slctlokasi_mutasi').val();
		lokasiD2D.empty();
		$.ajax({
			type: 'POST',
			url: '<?= base_url("getlokasid2dfrommutasi") ?>',
			dataType: "JSON",
			data: "postLokasiMutasi="+ value,
			success: function(html) {
				setTimeout(function() {
					$.unblockUI({
						onUnblock: function() {
							if (html.error == "")
							{
								$.each(html.opsi, function (key, data) {
									lokasiD2D.append('<option value="'+ data.id_lokasi +'">'+ data.lokasi +'</option>');
								});
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