<link rel="stylesheet" type="text/css" href="<?= base_url('plugins/bootdatepicker/css/bootstrap-datepicker.min.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('plugins/bootdatepicker/css/bootstrap-datepicker3.min.css') ?>" />
<style type="text/css"> .periode-tgl{ display: none; } </style>
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
						<select id="slct_th" class="form-control input-sm">
							<?= $this->Thanggaran->opsiByTahunAktif() ?>
						</select>
					</div>
				  	<label class="col-sm-2 control-label">Jenis Pelaporan </label>
				  	<div class="col-sm-2">
						<select class="form-control input-sm" id="slct-jenis-laporan">
						<option value="0"> REKAP </option>
						<option value="1"> JURNAL </option>
						</select>
				  	</div>
				  	<label class="col-sm-2 control-label">Transaksi </label>
				  	<div class="col-sm-2">
						<select class="form-control input-sm" id="slct-range-laporan">
						<option value="0"> BULANAN </option>
						<option value="1"> PERIODIK </option>
						</select>
				  	</div>
				</div>
				<div class="form-group periode-bulan">
				  <label class="col-sm-1 control-label">Bulan </label>
				  <div class="col-sm-2">
					<select id="slct-bulan" class="form-control input-sm">
						<?= $this->Bulan->getBulanOrderByBulanAktif() ?>
					</select>
				  </div>
				</div>
				<div class="form-group periode-tgl">
				  <label class="col-sm-1 control-label">Tgl </label>
				  <div class="col-sm-4" id="pilih-range-tanggal">
					<div class="input-group input-daterange">
					    <input type="text" id="txt-tgl-awal" class="form-control input-sm pilihan text-center">
					    <div class="input-group-addon">s.d</div>
					    <input type="text" id="txt-tgl-akhir" class="form-control input-sm pilihan text-center">
					</div>
				  </div>
				</div>
				<div class="form-group">
				  <div class="col-sm-6">
				  	<button type="button" class="btn btn-sm btn-success" id="btn-view"> <i class="fa fa-table"></i> Lihat </button> 
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

<script src="<?= base_url('plugins/bootdatepicker/js/bootstrap-datepicker.min.js') ?>"></script>
<script src="<?= base_url('plugins/bootdatepicker/locales/bootstrap-datepicker.id.min.js') ?>"></script>
<script src="<?= base_url('js/actiondatepicker.js') ?>"></script>
<script>
	$(function(){

		//load_display_laporan();

		$('#btn-view').click(function(){
			load_display_laporan();
        });

		$('#btn-pdf').click(function(){
	    	var param = get_parameter();

	    	var ifr=$('<iframe/>', {
	            id:'MainPopupIframe',
	            frameborder:0,
	            src:"<?= base_url('download_report_transaksi_e_samsat_nasional') ?>" + "/type/pdf"+ param,
	            style:'display:none;width:100%;height:600px;overflow:scroll',
	            load:function(){
	                $(this).show();
	            }
	        });
	        $('#show-laporan').html(ifr);
	    });

        $('#btn-excel').click(function() {
            var cekRadio = $('input[name=slctpd]:checked').val();
        	if ( $('input[name=slctpd]:checked').is(':checked') ) {
        		location.href = "<?= base_url('download_report_transaksi_e_samsat_nasional') ?>" + "/type/excel/ta/" + $('#slct_th').val() + "/tw/" + $('#slct_triwulan').val() + "/lapjenis/" + cekRadio;
        	} else {
        		alert('Silahkan pilih jenis laporan dahulu !!!');
        	}
            
        });
	});



	function load_display_laporan()
	{
		var param = this.get_parameter();

    	var ifr=$('<iframe/>', {
            id:'MainPopupIframe',
            frameborder:0,
            src:"<?= base_url('download_report_transaksi_e_samsat_nasional') ?>" + "/type/frame"+ param,
            style:'display:none;width:100%;height:600px;overflow:scroll',
            load:function(){
                $(this).show();
            }
        });
        $('#show-laporan').html(ifr);
	}

	function get_parameter()
	{
		var param_th = $('#slct_th').val();
		var param_jenis = $('#slct-jenis-laporan').val();
		var param_trx = $('#slct-range-laporan').val();
		var param_bln = $('#slct-bulan').val();
		var param_tgl_mulai = $('#txt-tgl-awal').val() == '' ? '-' : $('#txt-tgl-awal').val();
		var param_tgl_selesai = $('#txt-tgl-akhir').val() == '' ? '-' : $('#txt-tgl-akhir').val();

		return '/ta/' + param_th +'/jenis/' + param_jenis +'/range/'+ param_trx +'/bln/'+ param_bln +'/tgl_awal/'+ param_tgl_mulai +'/tgl_akhir/'+ param_tgl_selesai;
	}
</script>