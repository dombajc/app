<link href="<?= base_url('plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet" />

<!-- Modal -->
<div class="modal fade" id="form-modal-report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div id="show-laporan">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
	
	<div class="col-md-4">
		<!-- Default box -->
		<div class="box box-warning">
			<div class="box-header with-border">
				<h3 class="box-title"><?= $title ?></h3>
			</div>
		<div class="box-body">
			<form class="form form-horizontal" id="form-post-param">
				<div class="form-group">
				  <label class="col-xs-7 control-label">TAHUN </label>
				  <div class="col-xs-5">
					<select id="slctth" name="slctth" class="form-control input-sm">
						<?= $this->Thanggaran->opsiByTahunAktif() ?>
					</select>
				  </div>
				</div>
				<div class="form-group">
				  <label class="col-xs-7 control-label">TRANSAKSI e-SAMSAT </label>
				  <div class="col-xs-5">
					<select id="slctjenistransaksi" name="slctjenistransaksi" class="form-control input-sm">
						<?= $this->Esamsatjateng->get_opsi_jenis_transaksi() ?>
					</select>
				  </div>
				</div>
				<div class="form-group">
				  <label class="col-xs-7 control-label">JENIS PENERIMAAN </label>
				  <div class="col-xs-5">
					<select id="slctjenispenerimaan" name="slctjenispenerimaan" class="form-control input-sm">
						<?= $this->Esamsatjateng->option_jenis_penerimaan() ?>
					</select>
				  </div>
				</div>
				<div class="form-group">
					<label class="col-xs-7 control-label">BANK </label>
					<div class="col-xs-5">
						<select id="slctbank" name="slctbank" class="form-control input-sm">
							<?= $this->Esamsatjateng->get_opsi_bank() ?>
						</select>
					</div>
				</div>
				<div class="form-group">
				  	<label class="col-xs-4 control-label">TANGGAL AKHIR </label>
					<div class="col-xs-8">
						<div class="input-group date" id="datetimepicker6">
			                <input type="text" class="form-control input-sm text-center" id="txttglawal" name="txttglawal" />
			                <span class="input-group-addon">
			                    <span class="glyphicon glyphicon-calendar"></span>
			                </span>
			            </div>
					</div>
				</div>
				<div class="form-group">
				  	<div class="col-sm-12 text-right">
				  		<button type="submit" class="btn btn-sm btn-success btn-block"> DAPATKAN No. STS </button> 
				  	</div>
				</div>
			</form>
		</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>

	<div class="col-md-8" id="preview-no-sts" style="display: none;">
		<!-- Default box -->
		<div class="box box-warning">
			<div class="box-header with-border">
				<h3 class="box-title"></h3>
			</div>
			<div class="box-body">
				<input type="hidden" id="get-post-param">
				<table class="table" id="show-list-rekening">
				<thead>
				<tr>
					<td>No.Rekening</td>
					<td>Nama Rekening</td>
					<td>Jumlah</td>
				</tr>
				</thead>
				<tbody>
					<!-- JSON -->
				</tbody>
				<tfoot>
					<!-- JSON -->
				</tfoot>
				</table>
				<center>
					<button type="button" class="btn btn-sm btn-success" id="get-no-sts"> Dapatkan No. STS </button>
					<button type="button" class="btn btn-sm btn-danger" id="btn-batal"> Batal </button>
				</center>
				
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
	
	
</div>

<script type="text/javascript" src="<?= base_url('plugins/moment-develop/moment.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script type="text/javascript" src="<?= base_url('js/accounting.min.js') ?>"></script>

<script>
	var modalreport = $( '#form-modal-report' );
	$(document).ajaxStart(function(){
    	loadoverlay();
  	});
  	$(document).ajaxStop(function(){
    	setTimeout(function() {
            $.unblockUI();
        }, 1000);
  	});
	$(function(){

		$('#datetimepicker6').datetimepicker({
			format : 'DD-MM-YYYY HH:mm:ss',
			sideBySide : true,
			showClear : true,
			keepOpen : true,
			defaultDate: moment().set({ 'date' : moment().date()-1 , 'hour' : 14, 'minute' : 00, 'second' : 00, 'milisecond' : 000  })
		});

		$('#form-post-param').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			},
			fields: {
                slctth: {
                    validators: {
                        notEmpty: {}
                    }
                },
                txttglawal: {
                    validators: {
                        notEmpty: {
                        	message: 'Isikan tanggal akhir setoran !'
                        }
                    }
                }
            }
		})
		.on('success.field.fv', function(e, data) {
            if (data.fv.getSubmitButton()) {
                data.fv.disableSubmitButtons(false);
            }
        })
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();

            $.post( location.href + '/checkinput', $('#form-post-param').serialize(), function( result ) {

            	if ( result.Status == 1 ) {
            		$( '#get-post-param' ).val( $('#form-post-param').serialize() );
	            	$( '#preview-no-sts' ).show('fast');
	            	$( '#form-post-param input, #form-post-param select, #form-post-param button' ).prop( 'disabled', true );

	            	var trTabel = $( '#show-list-rekening tbody' );
	            	var trFoot = $( '#show-list-rekening tfoot' )
	            	var totalPenerimaan = 0;

	            	trTabel.empty();
	            	trFoot.empty();
	            	$.each( result.get_rekening_from_pad, function(key, value) {
	            		trTabel.append( '<tr>'+
	            			'<td>'+ value.no_rekening +'</td>'+
	            			'<td>'+ value.nama_rekening +'</td>'+
	            			'<td align="right">'+ accounting.formatNumber(value.jumlah) +'</td>'+
	            			'</tr>' );
	            		totalPenerimaan += value.jumlah;
	            	});
	            	trFoot.append( '<tr><td colspan="2"><b>Total Penerimaan</b></td><td align="right"><b>'+ accounting.formatNumber(totalPenerimaan) +'</b></td></tr>' );
            	} else {
            		pesanError( result.Error );
            	}

            }, 'json' )
            .fail( function( xhr, ajaxOptions, thrownError ) {
    			pesanError(xhr.responseText);
  			});

        });

        $( '#btn-batal' ).click( function(){
        	$( '#preview-no-sts' ).hide('fast');
            $( '#form-post-param input, #form-post-param select, #form-post-param button' ).prop( 'disabled', false );
        });

        $( '#get-no-sts' ).click( function(){
        	var konfirm = confirm( "Pastikan data sudah sesuai." );
        	if ( konfirm == 1 ) {

        		$.post( location.href + '/validation_no_sts', $( '#get-post-param' ).val(), function( result ) {
        			if ( result.Status == 1 ) {
        				clickPdf( result.IdSts );
        			} else {
        				pesanError(result.Error);
        				$( '#btn-batal' ).click();
        			}
        		}, 'json');

        	}
        });

	});

	function clickPdf( val ) {
		loadoverlay();
    	var url = location.href +'/pdf/'+ val;
	    var ifr=$('<iframe/>', {
	        id:'MainPopupIframe',
	        frameborder:0,
	        src: url,
	        style:'display:none;width:100%;height:550px;overflow:scroll;background:#fff;',
	        load:function(){
	            //$(this).show();
	            $(this).show(function () {
	                modalreport.modal('show');
        			$( '#btn-batal' ).click();
        			$.unblockUI();
	            });
	        }
	    });
	    $('#show-laporan').html(ifr);
    }

</script>