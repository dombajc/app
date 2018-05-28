<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
			FORM UBAH KATA SANDI
			</header>
			<div class="panel-body">
			<p class="alert alert-danger"><strong>PERHATIAN !!!</strong><br>Setelah kata sandi baru berhasil disimpan maka halaman akan otomatis keluar dan anda di wajibkan untuk login kembali !<br>Terima Kasih.</p>
				<form class="forms form-horizontal">
					
					<div class="form-group">
					  <label class="col-sm-6 control-label">KATA SANDI LAMA <span class="text-danger">*</span></label>
					  <div class="col-sm-3">
						<input type="password" name="txtkatasandilama" id="txtkatasandilama" class="form-control">
					  </div>
					</div>
					<div class="form-group">
					  <label class="col-sm-6 control-label">KATA SANDI <span class="text-danger">*</span></label>
					  <div class="col-sm-3">
						<input type="password" name="txtkatasandi" id="txtkatasandi" class="form-control">
					  </div>
					</div>
					<div class="form-group">
					  <label class="col-sm-6 control-label">ULANGI KATA SANDI <span class="text-danger">*</span></label>
					  <div class="col-sm-3">
						<input type="password" name="txtulangkatasandi" id="txtulangkatasandi" class="form-control">
					  </div>
					</div>
					<center>
						<button type="submit" class="btn btn-sm btn-flat btn-success"> PERBAHARUI KATA SANDI </button>
						<button type="reset" class="btn btn-sm btn-flat btn-warning"> ULANGI </button>
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
				txtkatasandilama: {
					validators: {
						notEmpty: {
							message: 'Isikan kata sandi lama !'
						}
					}
				},
				txtkatasandi: {
					validators: {
						notEmpty: {
							message: 'Isikan kata sandi yang baru !'
						}
					}
				},
				txtulangkatasandi: {
					validators: {
						notEmpty: {
							message: 'Ulangi Kata Sandi yang baru !'
						},
                        identical: {
                            field: 'txtkatasandi',
                            message: 'Pengulangan kata sandi baru tidak sama !'
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
					url: '<?= base_url("aksi_ubah_password") ?>',
					dataType: "JSON",
					data: $('form').serialize(),
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										pesanOK(html.msg);
										location.href= "<?= base_url('logout') ?>";
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