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
					  	<select class="form-control input-sm" id="slctbentuk" onchange="pilih_bentuk_pelaporan();">
					  		<option value="bln"> BULANAN </option>
							<option value="thn"> TAHUNAN </option>
							<option value="mthn"> MULTI YEARS </option>
					  	</select>
				  	</div>
				  	<label class="col-sm-1 control-label pilihbulanan">Bulan</label>
				 	<div class="col-sm-2 pilihbulanan">
						<select id="slctbulan" class="form-control input-sm">
							<?= $this->Bulan->getBulanOrderByBulanAktif() ?>
						</select>
				  	</div>
				  	<label class="col-sm-1 control-label pilihtahunan">Tahun</label>
			  		<div class="col-sm-3" id="">
				  		<div class="input-group">
				  			<select id="slctth" class="form-control input-sm">
								<?= $this->Thanggaran->opsiByTahunAktif() ?>
							</select>
				  			<span class="input-group-addon pilihmultiyears input-sm">+</span>
				  			<select id="slctmulti" class="form-control pilihmultiyears input-sm">
								<option value="1"> 1 </option>
								<option value="2"> 2 </option>
								<option value="3"> 3 </option>
								<option value="4"> 4 </option>
								<option value="5"> 5 </option>
							</select>
							<span class="input-group-addon pilihmultiyears input-sm">th</span>
				  		</div>
			  		</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Jenis</label>
					<div class="col-sm-2">
						<select id="slctjenis" class="form-control input-sm">
							<?= $this->Fungsi->getopsiJenisPelaporan() ?>
						</select>
				  	</div>
					<label class="col-sm-1 control-label">Pajak</label>
				  	<div class="col-sm-4">
				  		<div class="input-group">
				  			<select id="slctpd" class="form-control input-sm" onchange="load_opsi_tipe();">
								<?= $this->Pd->opsiJenis(" where disabled='1'") ?>
							</select>
							<span class="input-group-addon">Tipe</span>
							<select id="slcttipe" class="form-control input-sm">
							</select>
				  		</div>
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
		pilih_bentuk_pelaporan();
		load_opsi_tipe();

		$('#btn-view').click(function(){
			load_display_laporan();
        });

        $('#btn-pdf').click(function(){
        	var getbentuk = $('#slctbentuk').val();
			var getbulan = $('#slctbulan').val();
			var gettahun = $('#slctth').val();
			var getmultiyears = $('#slctmulti').val();
			var getjenis = $('#slctjenis').val();
			var getpd = $('#slctpd').val();
			var gettipe = $('#slcttipe').val();
			var param = 'getbentuk/'+ getbentuk +'/getbulan/'+ getbulan +'/gettahun/'+ gettahun +'/getmultiyears/'+ getmultiyears +'/getjenis/'+ getjenis +'/getpd/'+ getpd +'/gettipe/'+ gettipe;
    		var ifr=$('<iframe/>', {
                id:'MainPopupIframe',
                frameborder:0,
                src:"<?= base_url('pdflaporanrekappkbbbnkbsejateng') ?>" + "/type/frame/"+ param,
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
			var getmultiyears = $('#slctmulti').val();
			var getjenis = $('#slctjenis').val();
			var getpd = $('#slctpd').val();
			var gettipe = $('#slcttipe').val();
			var param = 'getbentuk/'+ getbentuk +'/getbulan/'+ getbulan +'/gettahun/'+ gettahun +'/getmultiyears/'+ getmultiyears +'/getjenis/'+ getjenis +'/getpd/'+ getpd +'/gettipe/'+ gettipe;
        	location.href = "<?= base_url('viewlaporanrekappkbbbnkbsejateng') ?>" + "/type/excel/"+ param;
        });
	});

	function load_display_laporan()
	{
		var getbentuk = $('#slctbentuk').val();
		var getbulan = $('#slctbulan').val();
		var gettahun = $('#slctth').val();
		var getmultiyears = $('#slctmulti').val();
		var getjenis = $('#slctjenis').val();
		var getpd = $('#slctpd').val();
		var gettipe = $('#slcttipe').val();
		var param = 'getbentuk/'+ getbentuk +'/getbulan/'+ getbulan +'/gettahun/'+ gettahun +'/getmultiyears/'+ getmultiyears +'/getjenis/'+ getjenis +'/getpd/'+ getpd +'/gettipe/'+ gettipe;
		var ifr=$('<iframe/>', {
            id:'MainPopupIframe',
            frameborder:0,
            src:"<?= base_url('viewlaporanrekappkbbbnkbsejateng') ?>" + "/type/frame/"+ param,
            style:'display:none;width:100%;height:600px;overflow:scroll;overflow-x: scroll;',
            load:function(){
                $(this).show();
            }
        });
        $('#show-laporan').html(ifr);
	}

	function pilih_bentuk_pelaporan()
	{
		var bentuk = $('#slctbentuk').val();
		if ( bentuk == 'bln' ) {
			$('.pilihbulanan, .pilihtahunan').show('fast');
			$('.pilihmultiyears').hide('fast');
		} else if ( bentuk == 'thn' ) {
			$('.pilihtahunan').show('fast');
			$('.pilihbulanan, .pilihmultiyears').hide('fast');
		} else if ( bentuk == 'mthn' ) {
			$('.pilihtahunan, .pilihmultiyears').show('fast');
			$('.pilihbulanan').hide('fast');
		}
	}

	function load_opsi_tipe()
	{
		var tipe = $('#slctpd').val();
		$.ajax({
	        type: 'POST',
	        url: '<?= base_url("get_array_tipe_rekening") ?>',
	        dataType: "JSON",
	        data: 'tipe=' + tipe,
	        success: function(html) {

	            setTimeout(function() {
	                $.unblockUI({
	                    onUnblock: function() {
	                        var $opsitipe = $("#slcttipe");
							$opsitipe.empty();
							$opsitipe.append('<option value="99"> Keseluruhan </option>');
							$.each(html.data, function(key, value) {
								$opsitipe.append('<option value="'+ value.id_rek_pd +'">'+ value.nama_rekening +'</option>');
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