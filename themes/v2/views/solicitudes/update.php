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
	default:
		$this->brandHeader = 'Notas de Cr&eacute;dito de Almac&eacute;n';
		break;
}
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Actualizar Solicitud de <?php echo $this->brandHeader; ?></h1>
    <span class="pagedesc">Actualizaci&oacute;n de la informaci&oacute;n para la <?php echo $this->brandHeader; ?></span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('solicitudes'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-home"></i> Regresar</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">

	<?php if(Yii::app()->user->hasFlash('Solicitudes.Guardar')):?>
		<div class="notibar msgsuccess">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Solicitudes.Guardar'); ?></p>
        </div>
	<?php endif; ?>

	<?php if(Yii::app()->user->hasFlash('Solicitudes.Error')):?>
		<div class="notibar msgerror">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Solicitudes.Error'); ?></p>
        </div>
	<?php endif; ?>

	<?php if(Yii::app()->user->hasFlash('Solicitudes.Warning')):?>
		<div class="notibar msgalert">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Solicitudes.Warning'); ?></p>
        </div>
	<?php endif; ?>

	<?php echo $this->renderPartial('_form', array(
		'id' => $id,
		'model' => $model,
		'notas' => $notas,
		'caracteristicas' => $caracteristicas,
		'razones' => $razones,
		//'sucursales' => $sucursales,
		'clientes' => $clientes,
		'anios' => $anios,
		//'listaPrecios' => $listaPrecios,
		'seleccion' => $seleccion,
		'jsonData' => $jsonData,
		'jsonDataMarcas' => $jsonDataMarcas,
		'archivo' => $archivo,
		'documentos' => $documentos,
		'update' => true
	)); ?>

</div>
