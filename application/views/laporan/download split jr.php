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
					<label class="col-xs-2 control-label">TRANSAKSI e-SAMSAT </label>
					<div class="col-xs-2">
						<select id="slctjenistransaksi" class="form-control input-sm">
							<option value="99"> Keseluruhan </option>
							<?= $this->Esamsatjateng->get_opsi_jenis_transaksi() ?>
						</select>
					</div>
					<label class="col-xs-1 control-label">BANK </label>
					<div class="col-xs-2">
						<select id="slctbank" class="form-control input-sm">
							<option value="99"> Keseluruhan </option>
							<?= $this->Esamsatjateng->get_opsi_bank() ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Tanggal </label>
					<div class="col-xs-2 opsiperiode">
						<div class='input-group date' id='datetimepicker6'>
							<input type='text' class="form-control input-sm text-center" id="txttglawal" />
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
					<label class="col-xs-1 control-label text-center opsiperiode">s.d </label>
					<div class="col-xs-2 opsiperiode">
						<div class='input-group date' id='datetimepicker7'>
							<input type='text' class="form-control input-sm text-center" id="txttglakhir" />
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
				  <div class="col-sm-6">
				  	<button type="button" class="btn btn-sm btn-success" id="btn-download"> <i class="glyphicon glyphicon-download-alt"></i> Download .DAT </button>
				  </div>
				</div>
			</div>
		</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
	
</div>

<script type="text/javascript" src="<?= base_url('plugins/moment-develop/moment.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js') ?>"></script>
<script>
	var getTglAwal = $( '#txttglawal' );
	var getTglAkhir = $( '#txttglakhir' );
	var getJenis = $('#slctjenistransaksi');

	$(function(){

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

	    $( '#btn-download' ).click( function(){
			var valBank = $('#slctbank').val();
	    	if ( getTglAwal.val() == '' || getTglAkhir.val() == '' ) {
	    		alert( 'Isikan range tanggal dengan benar !' );
	    	} else {
	    		location.href = location.href + '/download/'+ getJenis.val() + '/' + getTglAwal.val() +'/'+ getTglAkhir.val() +'/'+ valBank;
	    	}
	    });
	});
</script>