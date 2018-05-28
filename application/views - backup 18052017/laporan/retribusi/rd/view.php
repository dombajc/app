<div class="row">
	
	<div class="col-md-12">
		<!-- Default box -->
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title"><?= $title ?></h3>
			</div>
			<div class="box-body">
				<div class="form form-horizontal">
					<div class="form-group">
						<div class="col-sm-3">
							Jenis Laporan
							<select id="slctlaporan" class="form-control filter input-sm">
								<option value="01"> RD 01 </option>
								<option value="02"> RD 02 </option>
							</select>
						</div>
						<div class="col-sm-2">
							Target
							<select id="slcttarget" class="form-control input-sm">
								<option value="0"> MURNI </option>
								<option value="1"> PERUBAHAN </option>
							</select>
						</div>
						<div class="col-sm-2">
							Tahun
							<select id="slctth" class="form-control input-sm">
							<?= $this->Thanggaran->opsiByTahunAktif() ?>
							</select>
						</div>
						<div class="col-sm-2">
							Bulan
							<select id="slctbulan" class="form-control filter input-sm">
								<?= $this->Bulan->getBulanOrderByBulanAktif() ?>
							</select>
						</div>
						<div class="col-sm-3">
							Lokasi
							<select id="slctlokasi" class="form-control filter input-sm">
								<?= $this->Lokasi->optionAll() ?>
							</select>
						</div>
					</div><!-- /.box-body -->
					<div class="form-group">
						<div class="col-sm-3">
							<button type="button" class="btn btn-sm btn-success" id="btn-view"> <i class="fa fa-table"></i> Tampilkan </button> 
							<button type="button" class="btn btn-sm btn-success" id="btn-pdf"> <i class="fa fa-file-excel-o"></i> Pdf </button> 
							<button type="button" class="btn btn-sm btn-success" id="btn-excel"> <i class="fa fa-file-pdf-o"></i> Excel </button>
						</div>
					</div>
				</div><!-- /.box -->
			</div>
		</div>
		
	</div>
	<div class="col-md-12">
		<div id="show-laporan"></div>
	</div>
</div>

<script>
	$(function(){

		//load_display_laporan();

		$('#btn-view').click(function(){
			load_display_laporan();
        });

        $('#btn-pdf').click(function(){
			var id_laporan = $('#slctlaporan').val();
			var id_target = $('#slcttarget').val();
			var id_thn = $('#slctth').val();
			var id_lokasi = $('#slctlokasi').val();
			var id_bulan = $('#slctbulan').val();
			var ifr=$('<iframe/>', {
                id:'MainPopupIframe',
                frameborder:0,
                src:"<?= base_url('pdf_rd') ?>" + "/type/frame/lap/" + id_laporan + "/target/" + id_target + "/th/" + id_thn + "/bln/" + id_bulan + "/lok/" + id_lokasi,
                style:'display:none;width:100%;height:600px;overflow:scroll',
                load:function(){
                    $(this).show();
                }
            });
            $('#show-laporan').html(ifr); 
        });

        $('#btn-excel').click(function() {
			var id_laporan = $('#slctlaporan').val();
			var id_target = $('#slcttarget').val();
			var id_thn = $('#slctth').val();
			var id_lokasi = $('#slctlokasi').val();
			var id_bulan = $('#slctbulan').val();
            location.href = "<?= base_url('report_rd') ?>" + "/type/excel/lap/" + id_laporan + "/target/" + id_target + "/th/" + id_thn + "/bln/" + id_bulan + "/lok/" + id_lokasi;
        });
	});

	function load_display_laporan()
	{
		var id_laporan = $('#slctlaporan').val();
		var id_target = $('#slcttarget').val();
		var id_thn = $('#slctth').val();
		var id_lokasi = $('#slctlokasi').val();
		var id_bulan = $('#slctbulan').val();
		var ifr=$('<iframe/>', {
            id:'MainPopupIframe',
            frameborder:0,
            src:"<?= base_url('report_rd') ?>" + "/type/frame/lap/" + id_laporan + "/target/" + id_target + "/th/" + id_thn + "/bln/" + id_bulan + "/lok/" + id_lokasi,
            style:'display:none;width:100%;height:600px;overflow:scroll',
            load:function(){
                $(this).show();
            }
        });
        $('#show-laporan').html(ifr); 
	}
</script>