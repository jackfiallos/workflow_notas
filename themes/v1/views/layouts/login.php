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
	<?php else: ?>
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" rel="stylesheet">
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
<body class="login">
	<section class="container">
		<?php echo $content; ?>
	</section>
</body>
</html>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap.min.js',CClientScript::POS_END);
$cs->registerScript('jquery.login', "
	
", CClientScript::POS_READY);
?>