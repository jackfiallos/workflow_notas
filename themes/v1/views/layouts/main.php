<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Qbit Mexhico">
	<meta name="application-name" content="<?php echo Yii::app()->name; ?>"/>
	<meta name="application-url" content="<?php echo Yii::app()->getBaseUrl(true).'/'; ?>"/>
	<meta name="google" content="notranslate" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<!-- Stylesheets -->
	<?php if (!(bool)YII_DEBUG): ?>
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap/jquery-ui-1.9.2.custom.min.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.min.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.min.css" rel="stylesheet">
	<?php else: ?>
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css" rel="stylesheet">
	<?php endif; ?>
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/fontello.min.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/animation.min.css">
	<!--[if IE 7]><link rel="stylesheet" href="css/styler/fontello-ie7.css"><![endif]-->
	<!-- Custom Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300,500' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl; ?>/favicon.ico" type="image/x-icon">
	<style>
	</style>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
        	<div class="container">
          		<a class="brand" href="<?php echo Yii::app()->createUrl('site/index'); ?>">
          			<?php if (Yii::app()->user->getState('empresa_id') == 1): // Dermo ?>
          			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/logo-pfd-dermo.jpg" alt="Dermo" />
          			<?php else: ?>
					<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/logo-pierre-fabre.jpg" alt="Farma" />
          			<?php endif; ?>
          		</a>
          		<?php if (!Yii::app()->user->isGuest): ?>
          		<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          			<span class="icon-menu"></span>
          		</button>
          		<div class="nav-collapse collapse">
            		<ul class="nav">
            			<?php if(Yii::app()->user->verifyRole(Usuarios::SOLICITUD)): ?>
						<li <?php echo (Yii::app()->controller->id == 'solicitudes' && Yii::app()->controller->action->id == 'index') ? 'class="active"' : null ;?>>
							<a href="<?php echo Yii::app()->createUrl('solicitudes'); ?>">Solicitudes</a>
						</li>
						<?php endif; ?>
						<?php if(Yii::app()->user->verifyRole(Usuarios::APROBADOR)): ?>
						<li <?php echo (Yii::app()->controller->id == 'aprobadores') ? 'class="active"' : null ;?>>
							<a href="<?php echo Yii::app()->createUrl('aprobadores'); ?>">Aprobadores</a>
						</li>
						<?php endif; ?>
						<?php if(Yii::app()->user->verifyRole(Usuarios::LOGISTICA)): ?>
						<li <?php echo (Yii::app()->controller->id == 'logistica') ? 'class="active"' : null ;?>>
							<a href="<?php echo Yii::app()->createUrl('logistica'); ?>">Log&iacute;stica</a>
						</li>
						<?php endif; ?>
						<?php if(Yii::app()->user->verifyRole(Usuarios::SAC)): ?>
						<li <?php echo (Yii::app()->controller->id == 'sac') ? 'class="active"' : null ;?>>
							<a href="<?php echo Yii::app()->createUrl('sac'); ?>">SAC</a>
						</li>
						<?php endif; ?>
						<!-- -->
						<?php if(Yii::app()->user->verifyRole(Usuarios::SUPERVISOR)): ?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								Supervisar
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo Yii::app()->createUrl('supervisor/default/index'); ?>">Dashboard</a>
								</li>
								<li>
									<a href="<?php echo Yii::app()->createUrl('supervisor/default/pendientes'); ?>">Solicitudes Pendientes</a>
								</li>
								<li>
									<a href="<?php echo Yii::app()->createUrl('supervisor/default/revisar'); ?>">Totas las Solicitudes</a>
								</li>
							</ul>
						</li>
						<?php endif; ?>
						<!-- -->
						<?php if(Yii::app()->user->verifyRole(Usuarios::ADMIN)): ?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								Cat&aacute;logos
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo Yii::app()->createUrl('admin/clientes/index'); ?>">Clientes</a>
								</li>
								<li>
									<a href="<?php echo Yii::app()->createUrl('admin/facturas/index'); ?>">Facturas</a>
								</li>
								<li>
									<a href="<?php echo Yii::app()->createUrl('admin/empresas/index'); ?>">Empresas</a>
								</li>
								<li>
									<a href="<?php echo Yii::app()->createUrl('admin/sucursales/index'); ?>">Sucursales</a>
								</li>
								<li>
									<a href="<?php echo Yii::app()->createUrl('admin/productos/index'); ?>">Productos</a>
								</li>
								<li>
									<a href="<?php echo Yii::app()->createUrl('admin/marcas/index'); ?>">Marcas</a>
								</li>
								<li>
									<a href="<?php echo Yii::app()->createUrl('admin/caracteristicas/index'); ?>">Caracter&iacute;sticas</a>
								</li>
								<li>
									<a href="<?php echo Yii::app()->createUrl('admin/caracteristicastipo/index'); ?>">Tipo de Caracter&iacute;sticas</a>
								</li>
								<li>
									<a href="<?php echo Yii::app()->createUrl('admin/razones/index'); ?>">Razones</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="<?php echo Yii::app()->createUrl('admin/usuarios/index'); ?>">Usuarios</a>
						</li>
						<?php endif; ?>
						<!-- -->
					</ul>
					<ul class="pull-right nav">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo Yii::app()->user->getState('username'); ?> <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo Yii::app()->createUrl('site/logout'); ?>" title="Salir">Salir</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<section class="container">
		<?php echo $content; ?>
	</section>

	<!--<div id="footer" class="bg-color dark-blue">
		<div class="container">
			<div class="box-padding">
				<p>
					<?php echo Yii::app()->name; ?> - Desarrollado por 
					<a href="http://qbit.com.mx" title="Qbit Mexhico" target="_blank">Qbit Mexhico</a>.
				</p>
			</div>
		</div>
	</div>-->
</body>
</html>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap.min.js',CClientScript::POS_END);
$cs->registerScript('jquery.main', "
	
", CClientScript::POS_READY);
?>