<?php
$this->pageTitle = Yii::app()->name;
?>

<article class="data-block">
	<div class="pull-right">
		<a href="<?php echo Yii::app()->createUrl('logistica'); ?>" class="btn btn-info">Regresar</a>
	</div>
	<h2>
		Actualizar solicitud (<?php echo $title; ?>)
	</h2>
	<hr />
	<section>
		<?php if(Yii::app()->user->hasFlash('Logistica.Error')):?>
			<div class="evtMessage alert alert-danger">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<h4>Errores de captura</h4>
				<?php echo Yii::app()->user->getFlash('Logistica.Error'); ?>
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
	</section>
</article>