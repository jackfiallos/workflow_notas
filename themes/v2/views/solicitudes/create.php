<?php
$this->pageTitle = Yii::app()->name;

switch ($model->tipo_nota)
{
	case Caracteristicas::ALMACEN:
		$this->brandHeader = 'Nota de Cr&eacute;dito de Almac&eacute;n';
		break;
	case Caracteristicas::DESCUENTOS:
		$this->brandHeader = 'Nota de Descuentos Comerciales';
		break;
	case Caracteristicas::COOPERACION:
		$this->brandHeader = 'Nota de Cooperaci&oacute;n al Cliente';
		break;
	case Caracteristicas::REFACTURACION:
		$this->brandHeader = 'Nota de Refacturaciones';
		break;
	case Caracteristicas::PROVISION:
		$this->brandHeader = 'Nota de Provisi&oacute;n';
		break;
	default:
		$this->brandHeader = 'Nota de Cr&eacute;dito de Almac&eacute;n';
		break;
}
?>

<div class="pageheader">
    <h1 class="pagetitle">Crear Solicitud</h1>
    <span class="pagedesc">Generar nueva <?php echo $this->brandHeader; ?></span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('solicitudes'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-home"></i> Regresar</a>
	</div>
    <ul class="hornav">
        <?php //if ((Yii::app()->user->Grupo == 'sac') || (Yii::app()->user->Grupo == 'cuentas clave')): ?>
            <li class="<?php echo (Yii::app()->controller->id == 'solicitudes' && Yii::app()->controller->action->id == 'notasalmacen') ? 'current' : null ;?>">
                <a href="<?php echo Yii::app()->createUrl('solicitudes/NotasAlmacen'); ?>" id="ncalamcen">NC Almacén</a>
            </li>
        <?php //endif; ?>
        <?php //if (Yii::app()->user->Grupo == 'cuentas clave'): ?>
            <li class="<?php echo (Yii::app()->controller->id == 'solicitudes' && Yii::app()->controller->action->id == 'descuentoscomerciales') ? 'current' : null ;?>">
             	<a href="<?php echo Yii::app()->createUrl('solicitudes/DescuentosComerciales'); ?>" id="ncfinanciera">NC Descuento</a>
            </li>
            <?php if((int)Yii::app()->user->getState('empresa_id') == 1): // Para Farma no se activa la cooperacion, solo para Dermo ?>
                <li class="<?php echo (Yii::app()->controller->id == 'solicitudes' && Yii::app()->controller->action->id == 'cooperacioncliente') ? 'current' : null ;?>">
                 	<a href="<?php echo Yii::app()->createUrl('solicitudes/CooperacionCliente'); ?>">NC Cooperaci&oacute;n</a>
                </li>
            <?php endif; ?>
        <?php //endif; ?>
        <?php //if ((Yii::app()->user->Grupo == 'sac') || (Yii::app()->user->Grupo == 'c&c')): ?>
            <li class="<?php echo (Yii::app()->controller->id == 'solicitudes' && Yii::app()->controller->action->id == 'refacturaciones') ? 'current' : null ;?>">
             	<a href="<?php echo Yii::app()->createUrl('solicitudes/Refacturaciones'); ?>">Refacturaci&oacute;n</a>
            </li>
        <?php //endif; ?>
        <li class="<?php echo (Yii::app()->controller->id == 'solicitudes' && Yii::app()->controller->action->id == 'provision') ? 'current' : null ;?>">
         	<a href="<?php echo Yii::app()->createUrl('solicitudes/Provision'); ?>">Provisiones</a>
        </li>
    </ul>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php echo $this->renderPartial('_form', array(
		'id' => 0,
		'model' => $model,
		'caracteristicas' => $caracteristicas,
		'razones' => $razones,
		//'sucursales' => $sucursales,
		'clientes' => $clientes,
		'anios' => $anios,
		'productos' => array(),
		'jsonData' => CJSON::encode(array()),
		'update' => false
	)); ?>
</div>
