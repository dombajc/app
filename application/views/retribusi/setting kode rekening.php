<!-- CSS JQueryUI  -->
<link href="<?= base_url('css/jQueryUI/jquery-ui-1.10.3.custom.min.css') ?>" rel="stylesheet" />

<!-- CSS TreeGrid  -->
<link href="<?= base_url('plugins/jquery-tree-master/src/css/jquery.tree.css') ?>" rel="stylesheet" />

<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading" id="titleModal">
			<?= $title ?>	
			</header>
			<div class="panel-body form-horizontal">
				<div class="form-group">
					<label class="col-sm-1 control-label">Tahun </label>
					<div class="col-sm-2">
						<select id="slctth" class="form-control input-sm">
						<?= $this->Thanggaran->opsiByTahunAktif() ?>
						</select>
					</div>
					<div class="col-sm-3">
						<button type="button" class="btn btn-sm" id="btn-open-pengaturan"> Setting no rekening retribusi </button>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-sm-12" id="form-setting-kode-rekening" style="display:none;">
		<section class="panel">
			<header class="panel-heading">
				PILIH KODE RETRIBUSI yang DIGUNAKAN :
			</header>
			<div class="panel-body">
				<form class="form-horizontal">
					<div id="result-setting"></div>					
					<div class="text-center">
						<button type="submit" class="btn btn-sm btn-flat btn-success"><i class="glyphicon glyphicon-ok-circle"></i> SIMPAN </button>
						<button type="button" class="btn btn-sm btn-flat btn-danger" id="btn-batal"><i class="glyphicon glyphicon-remove-circle"></i> BATAL </button>
					</div>
				</form>
			</div>
		</section>
	</div>
</div>
<!-- CSS JQueryUI  -->
<script src="<?= base_url('plugins/jQueryUI/jquery-ui.min.js') ?>"></script>

<!-- CSS TreeGrid  -->
<script src="<?= base_url('plugins/jquery-tree-master/src/js/jquery.tree.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script type="text/javascript">
    $(document).ready(function () {
		$('#result-setting').trees({
			onCheck: {
				node: 'expand'
			},
			onUncheck: {
				node: 'collapse'
			}
		});
		
		$('#btn-open-pengaturan').click(function(){
			var id_thn = $('#slctth').val();
			$.ajax({
				type: 'POST',
				url: '<?= base_url("tampilkan_data_setting_kode_rekening") ?>',
				data: "post_thn="+ id_thn,
				success: function(data) {
					setTimeout(function() {
						$.unblockUI({
							onUnblock: function() {
								var divResult = $("#result-setting");
								divResult.empty();
								divResult.html(data);
								$('#slctth, #btn-open-pengaturan, #btn-cari-user').attr('disabled', true);
								$('#form-setting-kode-rekening').show('fast');
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
		});

        $('form').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			}
		})
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			
			var id_thn = $('#slctth').val();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_setting_kode_rekening") ?>',
					dataType: "JSON",
					data: $('form').serialize() +"&post_thn="+ id_thn,
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										pesanOK(html.msg);
										$('#btn-batal').click();
									} else {
										pesanError(html.error);
									}
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
            
        });
		
		$('#btn-batal').click(function(){
			$('#form-setting-kode-rekening').hide('fast');
			$('#slctth, #btn-open-pengaturan, #btn-cari-user').attr('disabled', false);
		});
    });
	
</script>