<!-- CSS Bootgrid  -->
<link href="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.css') ?>" rel="stylesheet" />

<!-- Form Modal Edit -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="titleModal">Entry Laporan jumlah pengiriman dokumen/obyek D2D :</h4>
      </div>
      <div class="modal-body">
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<!-- Default box -->
						<div class="box box-info">
							<div class="box-body">
								<form class="form-horizontal">
									<div class="form-group">
									  	<label class="col-sm-4 control-label">Nama Pegawai :</label>
									  	<div class="col-sm-8">
											<p class="form-control-static" id="pNama"></p>
									  	</div>
									  	
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">NIP :</label>
									  	<div class="col-sm-8">
											<p class="form-control-static" id="pNIP"></p>
									  	</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">Lokasi Pelaksanaan D2D :</label>
									  	<div class="col-sm-8">
											<p class="form-control-static" id="pLokasid2d"></p>
									  	</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">Jabatan :</label>
									  	<div class="col-sm-8">
											<p class="form-control-static" id="pJabatan"></p>
									  	</div>
									</div>
									
									<input type="hidden" name="hID" id="hID">
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
									  			<label class="col-sm-8 control-label">Bulan</label>
									  			<label class="col-sm-4 control-label">Jumlah Obyek</label>
									  		</div>
										</div>
										<hr>
										<div class="col-lg-12" id="dinamis-input">

										</div>
									</div>
									<div class="text-center">
										<button type="submit" class="btn btn-sm btn-flat btn-success btn-block"><i class="glyphicon glyphicon-ok-circle"></i> SIMPAN </button>
									</div>
								</form>
							</div><!-- /.box-body -->
							
						</div><!-- /.box -->
					</div>
					
				</div>
            </div>  		
		</div>
      
    </div>
  </div>
</div>

<!-- ******************************************************************************************************************* -->

<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
				Rekam Data Kegiatan Pengiriman Dok. D2D (SKPDPJ, STPD, SP1, SP2, SP3) :
			</header>
			<div class="panel-body form-horizontal">
				<input type="hidden" id="id_jabatan">
				<input type="hidden" id="id_sts_pegawai">
				<div class="form-group">
				  	<label class="col-sm-2 control-label">Pilih Tahun Anggaran</label>
				  	<div class="col-sm-2">
						<select name="slctth" id="slctth" class="form-control filter">
							<?= $this->Thanggaran->opsiByTahunAktif() ?>
						</select>
				  	</div>
				  	<label class="col-sm-1 control-label">Triwulan</label>
				  	<div class="col-sm-1">
						<select name="slcttriwulan" id="slcttriwulan" class="form-control filter">
							<?= $this->Triwulan->opsiOrderByBulanSekarang() ?>
						</select>
				  	</div>
				  	<label class="col-sm-1 control-label">Lokasi</label>
				  	<div class="col-sm-5">
						<select name="slctlokasi" id="slctlokasi" class="form-control filter">
							<?= $this->Lokasi->opsiAllUpadViewInputanRekamD2D() ?>
						</select>
				  	</div>
				</div>
				<button type="button" class="btn btn-sm btn-default btn-block filter" id="btn-proses"> Lanjutkan Proses </button>
			</div>
		</section>
	</div>
</div>
<div class="row" id="div-pegawai">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
				Hasil Pencarian Data Pegawai
				<button type="button" class="btn btn-sm btn-danger pull-right" id="btn-batal"> Filter Ulang Data Pegawai </button>
			</header>
			<div class="panel-body">
				<table id="grid" class="table table-condensed table-bordered table-hover">
					<thead>
						<tr>
							<th data-column-id="nama_pegawai" data-header-align="center">Nama Pegawai</th>
							<th data-column-id="nip" data-header-align="center" data-align="center">NIP</th>
							<th data-column-id="status_pegawai" data-header-align="center" data-align="center">Status</th>
							<th data-column-id="jabatan" data-header-align="center" data-align="center">Jabatan</th>
							<th data-column-id="nama_lokasi" data-header-align="center" data-align="center">Lokasi</th>
							<th data-column-id="nama_homebase" data-header-align="center" data-align="center">Homebase</th>
							<th data-column-id="" data-formatter="commands" data-header-align="center" data-sortable="false" data-align="center" data-width="30px">OPSI</th>
						</tr>
					</thead>
				</table>
			</div>
		</section>
	</div>
</div>

<!-- Number -->
<script type="text/javascript" src="<?= base_url('plugins/jquery-number-master/jquery.number.min.js') ?>"></script>

<!-- Boot Grid -->
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.js') ?>"></script>
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.fa.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script>

	$(function(){

		$('.filter').attr('disabled', true);
		
		$("#grid").bootgrid({
			ajax: true,
			post: function ()
			{
				/* To accumulate custom parameter with the request object */
				return {
					postLokasi : $('#slctlokasi').val(),
					postNama : $('#find-nama').val(),
					postTahun : $('#slctth').val(),
					postTriwulan : $('#slcttriwulan').val()
				};
			},
			url: "<?= base_url('grid_rekam_pegawai') ?>",
			selection: true,
			multiSelect: false,
			multiSort : true,
			rowSelect: true,
			keepSelection: true,
			templates: {
				header: "<div class=\"input-group\"><input type=\"text\" class=\"form-control\" id=\"find-nama\" placeholder=\"cari nama pegawai ...\"><span class=\"input-group-btn\"><button class=\"btn btn-secondary\" type=\"button\" id=\"btn-cari\"> Cari nama pegawai </button></span></div><div id=\"{{ctx.id}}\" class=\"{{css.header}}\"><div class=\"row\"><div class=\"col-sm-12 actionBar\"><p class=\"{{css.actions}}\"></p></div></div></div>"       
			},
			formatters: {
				"commands": function(column, row)
				{					
					var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" onClick=\"getDetilData('"+ row.id_mutasi +"')\"><span class=\"fa fa-pencil text-blue\"></span></button>";
					
					return showButton;
				}
			}
		});

		$('#btn-proses').click(function(){
			$('#grid').bootgrid('reload');
			$('#find-nama').val('');
			$('#div-pegawai').show('fast');
			$('.filter').attr('disabled', true);
		});

		$('#btn-cari').click(function(){
			$('#grid').bootgrid('reload');
		});

		$('form').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			}
		})
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			var idt = $('#slcttriwulan').val();
			var ia = $('#slctth').val();
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_simpan_rekam") ?>',
					dataType: "JSON",
					data: $('form').serialize() +'&haksi=add&idt='+ idt +'&ia='+ ia,
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										$('#formModal').modal('hide');
										$('.filter').attr('disabled', false);
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
        
		$('#btn-batal').click(function(){
			$('#div-pegawai').hide('fast');
			$('.filter').attr('disabled', false);
		});

	});

    function getDetilData(ID)
	{
		var idt = $('#slcttriwulan').val();
		var ia = $('#slctth').val();
		var ip = ID;
		$.ajax({
            type: 'POST',
            url: '<?= base_url("show_form_inputan") ?>',
            dataType: "JSON",
            data: 'idt='+ idt +'&ia='+ ia +'&ip='+ ID,
            success: function(html) {

                setTimeout(function() {
                    $.unblockUI({
                        onUnblock: function() {
                            if (html.error == '')
                            {
								$('#formModal').modal('show');
								$('form')[0].reset();
								$('form').formValidation('resetForm', true);
								$('#hID').val(ID);
								$('#pNama').text(html.nama_pegawai);
								$('#pNIP').text(html.nip);
								$('#pLokasid2d').text(html.lokasi_d2d);
								$('#pJabatan').text(html.jabatan);
								var $divinputan = $("#dinamis-input");
								$divinputan.empty();
								$.each(html.arrBulan, function(key, value) {
									var getHtml = '<div class="form-group">'+
									  	'<label class="col-sm-8 control-label">'+ value.bulan +'</label>'+
									  	'<div class="col-sm-4">'+
									  		'<input type="hidden" name="txtbulan[]" value="'+ value.id_bulan +'" '+ value.disabled +'>'+
											'<input type="text" name="txtjumlah[]" id="jml_'+ value.id_bulan +'" class="form-control nominal text-right" value="'+ value.jumlah +'" '+ value.disabled +'>'+
									  	'</div>';

									getHtml += '</div>';
									$divinputan.append(getHtml);

								});
								$('.filter').attr('disabled', true);
								$('.nominal').number( true, 0 );
								
                            } else {
                                pesanError(html.error);
                                $('.filter').attr('disabled', false);
                                $('#formModal').modal('hide');
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