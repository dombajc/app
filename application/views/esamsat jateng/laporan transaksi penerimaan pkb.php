<link href="<?= base_url('plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet" />
<div class="row">
	
	<div class="col-md-12">
		<!-- Default box -->
		<div class="box box-warning">
			<div class="box-header with-border">
				<h3 class="box-title"><?= $title ?></h3>
			</div>
		<div class="box-body">
			<div class="form form-horizontal">
				<div class="form-group">
				  <label class="col-xs-1 control-label">TAHUN </label>
				  <div class="col-xs-2">
					<select id="slctth" class="form-control input-sm">
						<?= $this->Thanggaran->opsiByTahunAktif() ?>
					</select>
				  </div>
				  <label class="col-xs-3 control-label">TRANSAKSI e-SAMSAT </label>
				  <div class="col-xs-2">
					<select id="slctjenistransaksi" class="form-control input-sm">
						<option value=""> Keseluruhan </option>
						<?= $this->Esamsatjateng->get_opsi_jenis_transaksi() ?>
					</select>
				  </div>
				  <label class="col-xs-2 control-label">TRANSAKSI </label>
				  <div class="col-xs-2">
					<select id="slctjenispenerimaan" class="form-control input-sm">
						<?= $this->Esamsatjateng->get_opsi_jenis_penerimaan() ?>
					</select>
				  </div>
				</div>
				<div class="form-group">
					<label class="col-xs-1 control-label">BANK </label>
					<div class="col-xs-2">
						<select id="slctbank" class="form-control input-sm">
							<option value=""> Keseluruhan </option>
							<?= $this->Esamsatjateng->get_opsi_bank() ?>
						</select>
					</div>
					<label class="col-xs-3 control-label">JENIS PELAPORAN </label>
					<div class="col-xs-2">
						<select id="scltjenispelaporan" class="form-control input-sm" onchange="opsitgl()">
							<option value="0"> REKAP </option>
							<option value="1"> JURNAL </option>
						</select>
					</div>
				</div>
				<div class="form-group">
				  	<label class="col-xs-1 control-label">PILIH </label>
					<div class="col-xs-2">
						<select id="slct_tgl_tr" class="form-control input-sm" onchange="opsitgl()">
							<option value="0"> BULANAN </option>
							<option value="1"> PERIODIK </option>
						</select>
					</div>
					<div class="col-xs-2 opsibulanan">
						<select id="slctbulan" class="form-control input-sm text-center">
						<?= $this->Bulan->getBulanOrderByBulanAktif() ?>
						</select>
				  	</div>
					<div class="col-xs-3 opsiperiode">
						<div class='input-group date' id='datetimepicker6'>
			                <input type='text' class="form-control input-sm text-center" id="txttglawal" />
			                <span class="input-group-addon">
			                    <span class="glyphicon glyphicon-calendar"></span>
			                </span>
			            </div>
					</div>
					<label class="col-xs-1 control-label text-center opsiperiode">s.d </label>
					<div class="col-xs-3 opsiperiode">
						<div class='input-group date' id='datetimepicker7'>
			                <input type='text' class="form-control input-sm text-center" id="txttglakhir" />
			                <span class="input-group-addon">
			                    <span class="glyphicon glyphicon-calendar"></span>
			                </span>
			            </div>
					</div>
				</div>
				<div class="form-group">
				  	<div class="col-sm-12 text-right">
				  		<button type="button" class="btn btn-sm btn-success" id="btn-view"> <i class="fa fa-table"></i> Tampilkan </button> 
						<button type="button" class="btn btn-sm btn-success" id="btn-pdf"> <i class="fa fa-file-excel-o"></i> Pdf </button> 
						<button type="button" class="btn btn-sm btn-success" id="btn-excel"> <i class="fa fa-file-pdf-o"></i> Excel </button>
				  	</div>
				</div>
			</div>
		</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
	
	<div class="col-md-12">
		<div id="loadingMessage"><center><img src="<?= base_url('img/load.gif') ?>" /></center></div>
		<div id="show-laporan"></div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url('plugins/moment-develop/moment.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js') ?>"></script>

<script>
	$(function(){
		opsitgl();

		$('#datetimepicker6').datetimepicker({
			format : 'DD-MM-YYYY HH:mm:ss',
			sideBySide : true,
			showClear : true,
			keepOpen : true,
			defaultDate: moment().set({ 'date' : moment().date()-1 , 'hour' : 14, 'minute' : 00, 'second' : 01, 'milisecond' : 000  })
		});
        $('#datetimepicker7').datetimepicker({
        	format : 'DD-MM-YYYY HH:mm:ss',
			sideBySide : true,
			showClear : true,
			keepOpen : true,
			defaultDate: moment().set({ 'hour' : 14, 'minute' : 00, 'second' : 00, 'milisecond' : 000  }),
            useCurrent: false //Important! See issue #1075
        });
        $("#datetimepicker6").on("dp.change", function (e) {
            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker7").on("dp.change", function (e) {
            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        });
		
		$('#btn-view').click(function(){
			load_display_laporan();
        });
		
		$('#btn-excel').click(function() {
			var periodeawal = $('#txttglawal').val();
			var periodeakhir = $('#txttglakhir').val();

			if ( periodeawal == '' ) {
				alert('Tanggal Periode Awal tidak boleh kosong !');
				$('#txttglawal').focus();
			} else {
				if ( periodeakhir == '' ) {
					alert('Tanggal Periode Akhir tidak boleh kosong !');
					$('#txttglakhir').focus();
				} else {
					
					var param = '?type=excel'+ get_param();

					location.href = location.href + "/cetak" + param;
				}
			} 
        });

        $('#btn-pdf').click(function(){

        	var periodeawal = $('#txttglawal').val();
			var periodeakhir = $('#txttglakhir').val();

			if ( periodeawal == '' ) {
				alert('Tanggal Periode Awal tidak boleh kosong !');
				$('#txttglawal').focus();
			} else {
				if ( periodeakhir == '' ) {
					alert('Tanggal Periode Akhir tidak boleh kosong !');
					$('#txttglakhir').focus();
				} else {
					
					var param = '?type=pdf'+ get_param();

					$('#loadingMessage').show('fast');
					var ifr=$('<iframe/>', {
			            id:'MainPopupIframe',
			            frameborder:0,
			            src: location.href + "/cetak" + param,
			            style:'display:none;width:100%;height:600px;overflow:scroll',
			            load:function(){
			                //$(this).show();
							$(this).show(function () {
								$('#loadingMessage').css('display', 'none');
							});
			            }
			        });
			        $('#show-laporan').html(ifr);
				}
			} 
        });
	});

	function load_display_laporan()
	{
		var periodeawal = $('#txttglawal').val();
		var periodeakhir = $('#txttglakhir').val();

		if ( periodeawal == '' ) {
			alert('Tanggal Periode Awal tidak boleh kosong !');
			$('#txttglawal').focus();
		} else {
			if ( periodeakhir == '' ) {
				alert('Tanggal Periode Akhir tidak boleh kosong !');
				$('#txttglakhir').focus();
			} else {
				
				var param = '?type=printout'+ this.get_param();

				$('#loadingMessage').show('fast');
				var ifr=$('<iframe/>', {
		            id:'MainPopupIframe',
		            frameborder:0,
		            src: location.href + "/cetak" + param,
		            style:'display:none;width:100%;height:600px;overflow:scroll',
		            load:function(){
		                //$(this).show();
						$(this).show(function () {
							$('#loadingMessage').css('display', 'none');
						});
		            }
		        });
		        $('#show-laporan').html(ifr);
			}
		} 
		
	}

	function get_param()
	{
		var tahun = $('#slctth').val();
		var bulan = $('#slctbulan').val();
		var jnstr = $('#slctjenistransaksi').val();
		var jnsbyr = $('#slctjenispenerimaan').val();
		var bank = $('#slctbank').val();
		var laporan = $('#scltjenispelaporan').val();
		var opsitgl = $('#slct_tgl_tr').val();
		var periodeawal = $('#txttglawal').val();
		var periodeakhir = $('#txttglakhir').val();

		return '&y=' + tahun + '&m=' + bulan + '&jnstrx=' + jnstr + '&jnsbyr=' + jnsbyr + '&bank=' + bank + '&opsitgl=' + opsitgl + '&paw=' + periodeawal + '&pak=' + periodeakhir + '&jnslaporan=' + laporan;
	}

	function opsitgl() {
		var value = $('#slct_tgl_tr').val();

		if ( value == 0 ) {
			$('.opsibulanan').show('fast');
			$('.opsiperiode').hide('fast');
		} else {
			$('.opsibulanan').hide('fast');
			$('.opsiperiode').show('fast');
		}
	}
</script>