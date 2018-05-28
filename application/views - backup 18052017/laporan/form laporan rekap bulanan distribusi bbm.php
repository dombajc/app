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
				  <label class="col-sm-1 control-label">Tahun </label>
				  <div class="col-sm-2">
					<select id="slctth" class="form-control">
						<?= $this->Thanggaran->opsiByTahunAktif() ?>
					</select>
				  </div>
				  <label class="col-sm-1 control-label">Bulan </label>
				  <div class="col-sm-2">
					<select id="slctbulan" class="form-control">
						<?= $this->Bulan->getBulanOrderByBulanAktif() ?>
					</select>
				  </div>
				  <label class="col-sm-1 control-label">UP3AD </label>
				  <div class="col-sm-5">
					<select id="slctlokasi" class="form-control">
						<?= $this->Lokasi->opsiLaporanOnlyDaerah() ?>
					</select>
				  </div>
				</div>
				<div class="form-group">
					<label class="col-sm-1 control-label">Satuan </label>
					<div class="col-sm-2">
						<label class="radio-inline">
					  		<input type="radio" name="radSatuan" value="ltr" checked> Liter
					  	</label>
					  	<label class="radio-inline">
					  		<input type="radio" name="radSatuan" value="kilo"> Kilo Liter
					  	</label>
					</div>
				  	<div class="col-sm-9 text-right">
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
			var valDasar = $('input[name=radDasar]:checked').val();
			var valSatuan = $('input[name=radSatuan]:checked').val();
            location.href = "<?= base_url('viewreportmontlydistributionbbm') ?>" + "/type/excel/getta/" + $('#slctth').val() + "/getbulan/" + $('#slctbulan').val() + "/getlokasi/" + $('#slctlokasi').val() + "/getsatuan/" + valSatuan;
        });

        $('#btn-pdf').click(function(){
        	var valDasar = $('input[name=radDasar]:checked').val();
        	var valSatuan = $('input[name=radSatuan]:checked').val();
			var ifr=$('<iframe/>', {
                id:'MainPopupIframe',
                frameborder:0,
                src:"<?= base_url('viewpdfmontlydistributionbbm') ?>" + "/type/frame/getta/" + $('#slctth').val() + "/getbulan/" + $('#slctbulan').val() + "/getlokasi/" + $('#slctlokasi').val() + "/getsatuan/" + valSatuan,
                style:'display:none;width:100%;height:600px;overflow:scroll',
                load:function(){
                    $(this).show();
                }
            });
            $('#show-laporan').html(ifr); 
        });
	});

	function load_display_laporan()
	{
		var valSatuan = $('input[name=radSatuan]:checked').val();
		$('#loadingMessage').show('fast');
		var ifr=$('<iframe/>', {
            id:'MainPopupIframe',
            frameborder:0,
            src:"<?= base_url('viewreportmontlydistributionbbm') ?>" + "/type/frame/getta/" + $('#slctth').val() + "/getbulan/" + $('#slctbulan').val() + "/getlokasi/" + $('#slctlokasi').val() + "/getsatuan/" + valSatuan,
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

</script>