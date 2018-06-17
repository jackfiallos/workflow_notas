<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Actualizar solicitud (<?php echo $title; ?>)</h1>
	<span class="pagedesc">Revisi&oacute;n de detalles, revise con detenimiento la solicitud.</span>
	<div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('logistica/index'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-home"></i> Regresar</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php if(Yii::app()->user->hasFlash('Logistica.Success')):?>
		<div class="notibar msgsuccess">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Logistica.Guardar'); ?></p>
        </div>
	<?php endif; ?>

	<?php if(Yii::app()->user->hasFlash('Logistica.Error')):?>
		<div class="notibar msgerror">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Logistica.Error'); ?></p>
        </div>
	<?php endif; ?>

	<?php echo $this->renderPartial('_form', array(
		'id' => $id,
		'model' => $model,
		'modelform' => $modelform,
		'jsonData' => $jsonData,
		'archivo' => $archivo,
		'documentos' => $documentos,
		'historial' => $historial,
		'update' => true
	)); ?>
</div>