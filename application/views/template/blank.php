<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $title ?> | <?= $this->Opsisite->getDataSite()['nama_site'] ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta name="description" content="Developed By M Abdur Rokib Promy">
        <meta name="keywords" content="Admin, Bootstrap 3, Template, Theme, Responsive">
        <!-- bootstrap 3.0.2 -->
        <link href="<?= base_url('css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?= base_url('css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="<?= base_url('css/ionicons.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?= base_url('css/style.css') ?>" rel="stylesheet" type="text/css" />
		<!-- Notif style -->
		<link rel="stylesheet" type="text/css" href="<?= base_url('plugins/NotificationStyles-master/css/ns-default.css') ?>" />
		<link rel="stylesheet" type="text/css" href="<?= base_url('plugins/NotificationStyles-master/css/ns-style-growl.css') ?>" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
		<style type="text/css">
			@font-face {
				font-family: basicFont;
				src: url('<?= base_url()."/fonts/droid/DroidSerif-Regular.ttf"; ?>');
			}
			body, .sidebar .sidebar-menu .treeview-menu > li > a {
				font-family:basicFont !important;
				font-size : 12px !important;
			}
			h1,h2,h3,h4,h5 {
				font-family:basicFont !important;
			}
			
		</style>
		
        <!-- jQuery 2.0.2 -->
        <script src="<?= base_url('plugins/jQuery/jQuery-2.1.4.min.js') ?>" type="text/javascript"></script>

    </head>
    <body class="skin-black">
        
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Main content -->
            <section class="content">
                
				<?php $this->load->view($dinamisContent); ?>
                
            </section><!-- /.content -->
        </div><!-- ./wrapper -->
        <div class="footer-main">
            Copyright &copy WaFe Developer, 2016
        </div>

        <!-- Bootstrap -->
        <script src="<?= base_url('js/bootstrap.min.js') ?>" type="text/javascript"></script>
        <!-- Director App -->
        <script src="<?= base_url('js/Director/app.js') ?>" type="text/javascript"></script>
		<!-- Loading -->
		<script type="text/javascript" src="<?= base_url('js/jquery.blockUI.js'); ?>"></script>
		
		<!-- Notification Fx -->
		<script src="<?= base_url('plugins/notify/jquery.bootstrap-growl.min.js') ?>"></script>
		
		<script>
			
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
          $.bootstrapGrowl('<i class="glyphicon glyphicon-ok-circle"></i> ' + text, {
              type: 'success',
              align: 'center',
              //allow_dismiss: true,
              width: 'auto'
              
          });
        }

        function pesanError(text){
            $.bootstrapGrowl('<i class="glyphicon glyphicon-remove-circle"></i> ' + text, {
              type: 'danger',
              align: 'center',
              width: 'auto',
              //allow_dismiss: true,
              delay: 60000
              
          });
        }
			
		</script>
    </body>
</html>
