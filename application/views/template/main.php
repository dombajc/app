<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $title ?> | <?= $this->Opsisite->getDataSite()['nama_site'] ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta name="description" content="Developed By M Abdur Rokib Promy">
        <meta name="keywords" content="Admin, Bootstrap 3, Template, Theme, Responsive">
        <!-- bootstrap 3.0.2 -->
        <!-- font Awesome -->
        <link href="<?= base_url('css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="<?= base_url('css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
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
				src: url('<?= base_url()."/fonts/Gotham Free/Gotham Book.ttf"; ?>');
			}
			body, .sidebar .sidebar-menu .treeview-menu > li > a {
				font-family:basicFont !important;
				font-size : 12px !important;
			}
			h1,h2,h3,h4,h5 {
				font-family:basicFont !important;
			}

            #loadingMessage {
                display : none;
            }
            
            .marquee {
              width: 100%;
              overflow: hidden;
            }
			
		</style>
		
        <!-- jQuery 2.0.2 -->
        <script src="<?= base_url('plugins/jQuery/jQuery-2.1.4.min.js') ?>" type="text/javascript"></script>

    </head>
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="<?= base_url() ?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <?= $this->Opsisite->getDataSite()['site_singkat'] ?>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                                <span><?= $this->Opsisite->getDataUser()['nama_user'] ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                <li class="dropdown-header text-center">Account</li>
                                <li class="divider"></li>

                                    <li>
                                        <a href="<?= base_url('ubahdatapengguna') ?>">
                                        <i class="fa fa-user fa-fw pull-right"></i>
                                            Ubah Data Pengguna
                                        </a>
                                        <a href="<?= base_url('ubahkatasandi') ?>">
                                        <i class="fa fa-cog fa-fw pull-right"></i>
                                            Ubah Kata Sandi
                                        </a>
                                        </li>

                                        <li class="divider"></li>

                                        <li>
                                            <a href="<?= base_url('logout') ?>"><i class="fa fa-ban fa-fw pull-right"></i> Logout</a>
                                        </li>
                                    </ul>

                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?= base_url('img/Logo-Jawa Tengah.png') ?>" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p>Hallo, <?= $this->Opsisite->getDataUser()['username'] ?></p>

                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    
                    <?= $this->Menu->ShowListMenu() ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                
                <!-- Main content -->
                <section class="content">
                    <?php if ( $this->Opsisite->getPegawaiUltah()['count'] > 0 ) { ?>
                    <div class="alert alert-warning">
                        <strong><i class="fa fa-birthday-cake"></i> INFOMASI : </strong>
                        <div class="marquee text-danger"> Ngaturaken Sugeng Tanggap Warsa kagem : <?= $this->Opsisite->getPegawaiUltah()['data_ultah'] ?>  Mugio panjenengan pinaringan wilujeng selamet lan lumebering rejeki kaliyan pinaringan kasihatan dening Ingkang Murbeng Dumadi Allah SWT.
                        </div>
                    </div>
                    <?php } ?>
					<?php $this->load->view($dinamisContent); ?>
                    
                </section><!-- /.content -->
                
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <div class="footer-main">
			<!-- Version APP Ver. II.05.2016 - Copyright &copy WaFe Developer, 2016 -->
            APP Ver. III.08.2016 - Copyright &copy WaFe Developer, 2016
        </div>

        <!-- Bootstrap -->
        <script src="<?= base_url('js/bootstrap.min.js') ?>" type="text/javascript"></script>
        <!-- Director App -->
        <script src="<?= base_url('js/Director/app.js') ?>" type="text/javascript"></script>
		<!-- Loading -->
		<script type="text/javascript" src="<?= base_url('js/jquery.blockUI.js'); ?>"></script>
		
		<!-- Notification Fx -->
		<script src="<?= base_url('plugins/notify/jquery.bootstrap-growl.min.js') ?>"></script>

        <!-- Marquee -->
        <script src="<?= base_url('plugins/Marquee-master/jquery.marquee.min.js') ?>"></script>
		
		<script>
			$(function(){
                $('.marquee').marquee({
                    //speed in milliseconds of the marquee
                    duration: 10000,
                    //gap in pixels between the tickers
                    gap: 50,
                    //time in milliseconds before the marquee will start animating
                    //delayBeforeStart: 0,
                    //'left' or 'right'
                    pauseOnHover: true,
                    direction: 'left',
                    //true or false - should the marquee be duplicated to show an effect of continues flow
                    duplicated: false
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
