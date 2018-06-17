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
	body { margin: 0px; }
	body p { color: #5F5F5F; }
	h1 { font-size: 36px; line-height: 42px; color: #2E2E2E; }
	h2 { font-size: 26px; color: #2E2E2E; border-bottom: 1px solid #ddd; }
	h4 { color: #2E2E2E; }
	h6 { margin-bottom: 6px; margin-bottom: 0.42857143rem; color: #9799a7; }
	.toc { margin-top: 20px; display: block; padding: 29px 30px 5px 30px; border-radius: 6px; border: 1px solid #cecfd5; background-color: #fff; }
	.section { padding-left: 30px; }
	p { margin-bottom:30px; }
	</style>
</head>
<body>
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