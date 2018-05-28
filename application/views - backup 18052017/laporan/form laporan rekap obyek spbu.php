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
					<label class="col-sm-1 control-label">Obyek </label>
					<div class="col-sm-2">
						<select class="form-control input-sm" id="slctoby">
							<option value="0"> SPBU </option>
							<option value="1"> Badan Usaha </option>
						</select>
					</div>
				  <label class="col-sm-1 control-label oby">Dasar </label>
				  <div class="col-sm-2 oby">
						<?= $this->Dasar->printRadioButton() ?>
				  </div>
				  <label class="col-sm-1 control-label">Tahun </label>
				  <div class="col-sm-2">
					<select id="slctth" class="form-control input-sm">
						<?= $this->Thanggaran->opsiByTahunAktif() ?>
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
		<div id="loadingMessage"><center><img src="<?= base_url('img/load.gif') ?>" /></center></div>
		<div id="show-laporan"></div>
	</div>
</div>


<script>
	$(function(){
		$('#slctoby').change(function(){
			pilih_oby();
		});
		
		$('#btn-view').click(function(){
			load_display_laporan();
        });
		
		$('#btn-excel').click(function() {
			var valOby = $('#slctoby').val();
			var valDasar = $('input[name=radDasar]:checked').val();
            location.href = "<?= base_url('view_rekap_obyk_spbu') ?>" + "/type/excel/getoby/"+ valOby +"/getdasar/" + valDasar + "/getta/" + $('#slctth').val();
        });

        $('#btn-pdf').click(function(){
			var valOby = $('#slctoby').val();
        	var valDasar = $('input[name=radDasar]:checked').val();
			var ifr=$('<iframe/>', {
                id:'MainPopupIframe',
                frameborder:0,
                src:"<?= base_url('pdf_rekap_obyk_spbu') ?>" + "/type/frame/getoby/"+ valOby +"/getdasar/" + valDasar + "/getta/" + $('#slctth').val(),
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
    	var valOby = $('#slctoby').val();
		var valDasar = $('input[name=radDasar]:checked').val();
    	if ( $('input[name=radDasar]:checked').is(':checked') ) {
    		$('#loadingMessage').show('fast');
			var ifr=$('<iframe/>', {
	            id:'MainPopupIframe',
	            frameborder:0,
	            src:"<?= base_url('view_rekap_obyk_spbu') ?>" + "/type/frame/getoby/"+ valOby +"/getdasar/" + valDasar + "/getta/" + $('#slctth').val(),
	            style:'display:none;width:100%;height:600px;overflow:scroll',
	            load:function(){
	                //$(this).show();
					$(this).show(function () {
						$('#loadingMessage').css('display', 'none');
					});
	            }
	        });
	        $('#show-laporan').html(ifr);
    	} else {
    		 alert('Silahkan pilih jenis penyetoran dahulu !!!');
    	}
	}
	
	function pilih_oby()
	{
		var value = $('#slctoby').val();
		if ( value == 1 )
		{
			$('.oby').hide('fast');
		}
		else
		{
			$('.oby').show('fast');
		}
	}

</script>