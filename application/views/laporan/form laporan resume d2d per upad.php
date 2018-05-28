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
					<select id="slct_th" class="form-control">
						<?= $this->Thanggaran->opsiByTahunAktif() ?>
					</select>
				  </div>
				  <label class="col-sm-1 control-label">Triwulan </label>
				  <div class="col-sm-1">
					<select id="slct_triwulan" class="form-control">
						<?= $this->Triwulan->opsiOrderByBulanSekarang() ?>
					</select>
				  </div>
				  <div class="col-sm-3">
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
		<div id="show-laporan"></div>
	</div>
</div>

<script>
	$(function(){

		load_display_laporan();

		$('#btn-view').click(function(){
			load_display_laporan();
        });

        $('#btn-pdf').click(function(){
			var ifr=$('<iframe/>', {
                id:'MainPopupIframe',
                frameborder:0,
                src:"<?= base_url('viewpdfresumed2d') ?>" + "/type/frame/ta/" + $('#slct_th').val() + "/tw/" + $('#slct_triwulan').val(),
                style:'display:none;width:100%;height:600px;overflow:scroll',
                load:function(){
                    $(this).show();
                }
            });
            $('#show-laporan').html(ifr); 
        });

        $('#btn-excel').click(function() {
            location.href = "<?= base_url('viewresumed2dperupad') ?>" + "/type/excel/ta/" + $('#slct_th').val() + "/tw/" + $('#slct_triwulan').val();
        });
	});

	function load_display_laporan()
	{
		var ifr=$('<iframe/>', {
            id:'MainPopupIframe',
            frameborder:0,
            src:"<?= base_url('viewresumed2dperupad') ?>" + "/type/frame/ta/" + $('#slct_th').val() + "/tw/" + $('#slct_triwulan').val(),
            style:'display:none;width:100%;height:600px;overflow:scroll',
            load:function(){
                $(this).show();
            }
        });
        $('#show-laporan').html(ifr); 
	}
</script>