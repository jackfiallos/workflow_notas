<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!doctype html>
<!--[if IE 7]>
<html class="ie ie7" xmlns="http://www.w3.org/1999/xhtml">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" xmlns="http://www.w3.org/1999/xhtml">
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html xmlns="http://www.w3.org/1999/xhtml">
<!--<![endif]-->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Pierre Fabre Pedidos</title>
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.default.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pure-min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.custom.css" type="text/css" />
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" />
        <!--[if IE 9]>
        <link rel="stylesheet" media="screen" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.ie9.css"/>
        <![endif]-->
        <!--[if IE 8]>
        <link rel="stylesheet" media="screen" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.ie8.css"/>
        <![endif]-->
        <!--[if lt IE 9]>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <![endif]-->
        <link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl; ?>/favicon.ico" type="image/x-icon">
    </head>
    <body class="withvernav">
        <div class="bodywrapper">
            <div class="topheader">
                <div class="left">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo_web_inter.png" width="450" height="50" alt="Pierre Fabre" />
                    <br clear="all">
                </div>
                <div class="right">
                    <div class="userinfo">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/avatar.png" alt="" />
                        <span><?php echo Yii::app()->user->getState('username'); ?></span>
                    </div>
                    <div class="userinfodrop">
                        <div class="avatar">
                            <a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/avatarbig.png" alt="" /></a>
                        </div>
                        <div class="userdata">
                            <h4><?php echo Yii::app()->user->getState('username'); ?></h4>
                            <br />
                            <span class="email"></span>
                            <ul>
                                <li>
                                    <a href="<?php echo Yii::app()->createUrl('site/logout'); ?>" title="Salir">Salir</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vernav2 iconmenu">
            <ul>
                <?php if(Yii::app()->user->verifyRole(Usuarios::SOLICITUD)): ?>
                <li>
                    <a href="#solicitud" class="elements">SOLICITUDES</a>
                    <span class="arrow"></span>
                    <ul id="solicitud" style="display: block;">
                        <li>
                            <a href="<?php echo Yii::app()->createUrl('solicitudes'); ?>">Lista de Solicitudes</a>
                        </li>
                        <li>
                            <a href="<?php echo Yii::app()->createUrl('solicitudes/crear'); ?>">Crear Solicitud</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if(Yii::app()->user->verifyRole(Usuarios::APROBADOR)): ?>
                <li>
                    <a href="#listaaprobacion" class="elements">APROBACI&Oacute;N</a>
                    <span class="arrow"></span>
                    <ul id="listaaprobacion" style="display: block;">
                        <li>
                            <a href="<?php echo Yii::app()->createUrl('aprobadores'); ?>">Lista de Solicitudes</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if(Yii::app()->user->verifyRole(Usuarios::LOGISTICA)): ?>
                <li>
                    <a href="#listalogistica" class="elements">LOG&Iacute;STICA</a>
                    <span class="arrow"></span>
                    <ul id="listalogistica" style="display: block;">
                        <li>
                            <a href="<?php echo Yii::app()->createUrl('logistica'); ?>">Lista de Solicitudes</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if(Yii::app()->user->verifyRole(Usuarios::SAC)): ?>
                <li>
                    <a href="#listasac" class="elements">Servicio a Clientes</a>
                    <span class="arrow"></span>
                    <ul id="listasac" style="display: block;">
                        <li>
                            <a href="<?php echo Yii::app()->createUrl('sac'); ?>">Lista de Solicitudes</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if(Yii::app()->user->verifyRole(Usuarios::SUPERVISOR)): ?>
                <li>
                    <a href="#super" class="elements">Supervisar</a>
                    <span class="arrow"></span>
                    <ul id="super" style="display: block;">
                        <li>
                            <a href="<?php echo Yii::app()->createUrl('supervisor/default/index'); ?>">Dashboard</a>
                        </li>
                        <li>
                            <a href="<?php echo Yii::app()->createUrl('supervisor/default/pendientes'); ?>">Solicitudes Pendientes</a>
                        </li>
                        <li>
                            <a href="<?php echo Yii::app()->createUrl('supervisor/default/revisar'); ?>">Todas las Solicitudes</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(Yii::app()->user->verifyRole(Usuarios::ADMIN)): ?>
                <li>
                    <a href="#admin" class="elements">Cat&aacute;logos</a>
                    <span class="arrow"></span>
                    <ul id="admin" style="display: block;">
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
                    <a href="#users" class="elements">Administraci&oacute;n</a>
                    <span class="arrow"></span>
                    <ul id="users" style="display: block;">
                        <li>
                            <a href="<?php echo Yii::app()->createUrl('admin/usuarios/index'); ?>">Usuarios</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <li>
                    <a href="#ayuda" class="elements">Ayuda</a>
                    <span class="arrow"></span>
                    <ul id="ayuda" style="display: block;">
                        <li><a href="?modulo=ayuda&a=manuales">Manual de Usuario</a></li>
                        <li><a href="?modulo=ayuda&a=video">Video Instruccional</a></li>
                    </ul>
                </li>
            </ul>
            <a class="togglemenu"></a>
            <br /><br />
        </div>
        <div class="centercontent">
            <?php echo $content; ?>
            <br clear="all">
        </div>
    </body>
</html>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/general.js',CClientScript::POS_END);
$cs->registerScript('jquery.main', "
    $(document).on('keydown', function (e) {
        if (e.which === 8 && !$(e.target).is('input[type=\"text\"]:not([readonly]), input[type=\"number\"], input[type=\"password\"], input[type=\"search\"], textarea')) {
            e.preventDefault();
        }
    });
", CClientScript::POS_READY);
?>
