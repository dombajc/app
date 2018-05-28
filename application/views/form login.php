<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title><?= $this->Opsisite->getDataSite()['nama_site'] ?></title>
    	<link rel="stylesheet" href="<?= base_url('css/login/style.css') ?>">
  	</head>
  	<body>
  	
    <div class="wrapper">
	<div class="container">
		
		<h1><img src="<?= base_url('img/Logo-Jawa Tengah.png') ?>" width="15%" height="15%"><br>Aplikasi Pelaporan Online (e-LA)<br><span>BPPD Provinsi Jawa Tengah</span></h1>
		<div id="messageBox" style="color:#fff;background-color:#FF6666;border-radius:8px;list-style:none;">

		</div>
		<form class="form">
			<input type="text" name="loguser" id="loguser" placeholder="User pengguna">
			<input type="password" name="logpass" placeholder="Kata Sandi">
			<button type="submit" id="login-button">Log In</button>
		</form>
	</div>


	
	<ul class="bg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
	</div>
    	<script src="<?= base_url('plugins/jQuery/jQuery-2.1.4.min.js') ?>" type="text/javascript"></script>
    	<!-- Form Validation -->
		<script src="<?= base_url('plugins/validation/dist/jquery.validate.min.js') ?>"></script>
		<script src="<?= base_url('plugins/validation/dist/additional-methods.min.js') ?>"></script>

		<!-- Loading -->
		<script type="text/javascript" src="<?= base_url('js/jquery.blockUI.js'); ?>"></script>
		
		<!-- Notification Fx -->
		<script src="<?= base_url('plugins/noty-2.3.8/js/noty/packaged/jquery.noty.packaged.min.js') ?>"></script>
		<script src="<?= base_url('plugins/noty-2.3.8/js/noty/themes/bootstrap.js') ?>"></script>
    	
		<script>
			$(function(){

				$("form").validate({
					rules: {
				    	loguser: {
				      		required: true
				    	},
				    	logpass: {
				      		required: true
				    	}
				  	},
				  	messages: {
				    	loguser: {
				      		required: "User pengguna tidak boleh kosong !"
				    	},
				    	logpass: {
				      		required: "Kata sandi tidak boleh kosong !"
				    	}
				  	},
				  	errorLabelContainer: "#messageBox",
  					wrapper: "li",
				  	submitHandler: function(form) {
				    	$.ajax({
							type: 'POST',
							url: '<?= base_url("checkinglogin") ?>',
							dataType: "JSON",
							data: $('form').serialize(),
							success: function(html) {
								setTimeout(function() {
									$.unblockUI({
										onUnblock: function() {
											if (html.error == "")
											{
												$('form').fadeOut(500);
	 											$('.wrapper').addClass('form-success');
												location.href = '<?= base_url() ?>';
												pesanOK(html.msg);
											} else {
												pesanError(html.error);
												$('form')[0].reset();
												$('#loguser').focus();
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

			function loadoverlay()
			{
				$.blockUI({
					message: '<img src="<?= base_url('img/loading45.gif'); ?>">',
					css: {
						border: '',
						color: '#8EC64E',
						top: '40%',
						left: '0',
						backgroundColor: '',
						width: '100%'
					},
					overlayCSS: {
						backgroundColor: '#FFF', 
						opacity: 0.9, 
						cursor: 'wait' }
				});
			}
			
			function pesanOK(text){
	          	var n = noty({
				    text: text,
				    timeout: 5000,
				    type: 'success',
				    animation: {
				        open: {height: 'toggle'}, // jQuery animate function property object
				        close: {height: 'toggle'}, // jQuery animate function property object
				        easing: 'swing', // easing
				        speed: 500 // opening & closing animation speed
				    }
				});
	        }

	        function pesanError(text){
	            var n = noty({
				    text: text,
				    type: 'error',
				    timeout: 5000,
				    animation: {
				        open: {height: 'toggle'}, // jQuery animate function property object
				        close: {height: 'toggle'}, // jQuery animate function property object
				        easing: 'swing', // easing
				        speed: 500 // opening & closing animation speed
				    }
				});
	        }
		</script>
  	</body>
</html>


