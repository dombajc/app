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
				  <label class="col-xs-2 control-label">TAHUN </label>
				  <div class="col-xs-2">
					<select id="slctth" class="form-control input-sm">
						<?= $this->Thanggaran->opsiByTahunAktif() ?>
					</select>
				  </div>
				  <label class="col-xs-1 control-label">TRANSAKSI </label>
				  <div class="col-xs-2">
					<select id="slctjenispenerimaan" class="form-control input-sm">
						<?= $this->Esamsatjateng->get_opsi_jenis_penerimaan() ?>
					</select>
				  </div>
				  <label class="col-xs-1 control-label">JENIS </label>
				  <div class="col-xs-2">
					<select id="slcttrx" class="form-control input-sm">
						<option value="99"> Pokok + Denda </option>
						<option value="00"> Pokok </option>
						<option value="01"> Denda </option>
					</select>
				  </div>
				</div>
				<div class="form-group">
					<label class="col-xs-2 control-label">JENIS PELAPORAN </label>
					<div class="col-xs-2">
						<select id="scltjenispelaporan" class="form-control input-sm" onchange="opsitgl()">
							<option value="0"> REKAP </option>
							<option value="1"> JURNAL </option>
						</select>
					</div>
					<label class="col-xs-1 control-label">PENERIMAAN </label>
					<div class="col-xs-2">
						<select id="slcttipe" class="form-control input-sm">
							<option value="00"> Obyek </option>
							<option value="01"> Rupiah </option>
						</select>
					</div>
					<label class="col-xs-1 control-label">BANK </label>
					<div class="col-xs-2">
						<select id="slctbank" class="form-control input-sm">
							<option value=""> Keseluruhan </option>
							<?= $this->Esamsatjateng->get_opsi_bank() ?>
						</select>
					</div>
				</div>
				<div class="form-group">
				  	<label class="col-xs-2 control-label">LOKASI </label>
					<div class="col-xs-2">
						<select id="slctlokasi" class="form-control input-sm text-center">
						<option value="99"> Se-Jawa Tengah </option>
						<?= $this->Lokasi->optionarrUppd() ?>
						</select>
				  	</div>
				</div>
				<div class="form-group">
				  	<label class="col-xs-2 control-label">BULAN </label>
					<div class="col-xs-2">
						<select id="slctbulan" class="form-control input-sm text-center">
						<?= $this->Bulan->getBulanOrderByBulanAktif() ?>
						</select>
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

<script>
	$(function(){
		
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
        });
	});

	function load_display_laporan()
	{
				
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

	function get_param()
	{
		var tahun = $('#slctth').val();
		var bulan = $('#slctbulan').val();
		var laporan = $('#scltjenispelaporan').val();
		var lokasi = $('#slctlokasi').val();
		var jnsbyr = $('#slctjenispenerimaan').val();
		var jnstrx = $('#slcttrx').val();
		var jnstipe = $('#slcttipe').val();
		var bank = $('#slctbank').val();

		return '&y=' + tahun + '&m=' + bulan + '&lok=' + lokasi + '&jnsbyr=' + jnsbyr + '&jnstipe=' + jnstipe + '&jnstrx=' + jnstrx + '&jnslaporan=' + laporan + '&b=' + bank;
	}
</script>