<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
			<?= $title ?>
			</header>
			<div class="panel-body">
				<form class="forms form-horizontal">
					
					<div class="form-group">
					  <label class="col-sm-4 control-label">NAMA <span class="text-danger">*</span></label>
					  <div class="col-sm-8">
						<input type="text" name="txtnama" id="txtnama" class="form-control" value="<?= $this->Opsisite->getDataUser()['nama_user'] ?>">
					  </div>
					</div>
					<center>
						<button type="submit" class="btn btn-sm btn-flat btn-success"> PERBAHARUI </button>
					</center>
				</form>

			</div>
		</section>
	</div>
</div>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script>
	$(function(){
		$('form').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			},
			fields: {
				txtnama: {
					validators: {
						notEmpty: {
							message: 'Harap di isi nama pengguna !'
						}
					}
				}
			}
		})
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_ubah_datapengguna") ?>',
					dataType: "JSON",
					data: $('form').serialize(),
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										pesanOK(html.msg);
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
	});
</script>