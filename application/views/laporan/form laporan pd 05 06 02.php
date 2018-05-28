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
					<label class="col-sm-2 control-label">Bentuk Pelaporan</label>
				  	<div class="col-sm-2">
					  	<select class="form-control input-sm" id="slctbentuk">
					  		<option value="02"> PD 02 </option>
							<option value="03"> PD 03 </option>
							<option value="05"> PD 05 </option>
							<option value="06"> PD 06 </option>
					  	</select>
				  	</div>
				  	<label class="col-sm-2 control-label">Bulan</label>
				 	<div class="col-sm-2 pilihbulanan">
						<select id="slctbulan" class="form-control input-sm">
							<?= $this->Bulan->getBulanOrderByBulanAktif() ?>
						</select>
				  	</div>
				  	<label class="col-sm-1 control-label pilihtahunan">Tahun</label>
			  		<div class="col-sm-2">
			  			<select id="slctth" class="form-control input-sm">
							<?= $this->Thanggaran->opsiByTahunAktif() ?>
						</select>
			  		</div>
				</div>
				<div class="form-group">
				  	<label class="col-sm-2 control-label">INDUK LOKASI</label>
					<div class="col-sm-3">
						<select id="slctinduk" class="form-control filter input-sm" onchange="aksiPilihanInduk();">
							<?= $this->Lokasi->optionAll() ?>
						</select>
				  	</div>
				  	<label class="col-sm-1 control-label">LOKASI</label>
				  	<div class="col-sm-3">
						<select id="slctlokasi" class="form-control filter input-sm">
						<option value=""> -- Pilih dulu lokasi di samping -- </option>
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
		aksiPilihanInduk();

		$('#btn-view').click(function(){
			load_display_laporan();
        });

        $('#btn-pdf').click(function(){
        	var getbentuk = $('#slctbentuk').val();
			var getbulan = $('#slctbulan').val();
			var gettahun = $('#slctth').val();
			var getinduk = $('#slctinduk').val();
			var getlokasi = $('#slctlokasi').val();
			var param = 'getbentuk/'+ getbentuk +'/getbulan/'+ getbulan +'/gettahun/'+ gettahun +'/getinduk/'+ getinduk +'/getlokasi/'+ getlokasi;
        		
    		var ifr=$('<iframe/>', {
                id:'MainPopupIframe',
                frameborder:0,
                src:"<?= base_url('pdflaporanpd050602') ?>" + "/type/excel/"+ param,
                style:'display:none;width:100%;height:600px;overflow:scroll',
                load:function(){
                    $(this).show();
                }
            });
            $('#show-laporan').html(ifr); 
        });

        $('#btn-excel').click(function() {
            var getbentuk = $('#slctbentuk').val();
			var getbulan = $('#slctbulan').val();
			var gettahun = $('#slctth').val();
			var getinduk = $('#slctinduk').val();
			var getlokasi = $('#slctlokasi').val();
			var param = 'getbentuk/'+ getbentuk +'/getbulan/'+ getbulan +'/gettahun/'+ gettahun +'/getinduk/'+ getinduk +'/getlokasi/'+ getlokasi;
        	location.href = "<?= base_url('viewlaporanpd050602') ?>" + "/type/excel/"+ param;
        });
	});

	function load_display_laporan()
	{
		var getbentuk = $('#slctbentuk').val();
		var getbulan = $('#slctbulan').val();
		var gettahun = $('#slctth').val();
		var getinduk = $('#slctinduk').val();
		var getlokasi = $('#slctlokasi').val();
		var param = 'getbentuk/'+ getbentuk +'/getbulan/'+ getbulan +'/gettahun/'+ gettahun +'/getinduk/'+ getinduk +'/getlokasi/'+ getlokasi;
		var ifr=$('<iframe/>', {
            id:'MainPopupIframe',
            frameborder:0,
            src:"<?= base_url('viewlaporanpd050602') ?>" + "/type/frame/"+ param,
            style:'display:none;width:100%;height:600px;overflow:scroll;overflow-x: scroll;',
            load:function(){
                $(this).show();
            }
        });
        $('#show-laporan').html(ifr);
	}

	function aksiPilihanInduk()
	{
		var induk = $('#slctinduk').val();
		$.ajax({
	        type: 'POST',
	        url: '<?= base_url("get_array_lokasi_by_induk") ?>',
	        dataType: "JSON",
	        data: 'induk=' + induk,
	        success: function(html) {

	            setTimeout(function() {
	                $.unblockUI({
	                    onUnblock: function() {
	                        var $opsilokasi = $("#slctlokasi");
							$opsilokasi.empty();
							$.each(html.data, function(key, value) {
								$opsilokasi.append('<option value="'+ value.id_lokasi +'">'+ value.lokasi +'</option>');
							});
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