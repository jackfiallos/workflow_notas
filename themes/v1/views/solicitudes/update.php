<?php
$this->pageTitle = Yii::app()->name;

switch ($model->tipo_nota)
{
	case Caracteristicas::ALMACEN:
		$this->brandHeader = 'Notas de Almac&eacute;n - '.Yii::app()->user->getState('empresa_nombre');
		break;
	case Caracteristicas::DESCUENTOS:
		$this->brandHeader = 'Notas de Descuentos - '.Yii::app()->user->getState('empresa_nombre');
		break;
	case Caracteristicas::COOPERACION:
		$this->brandHeader = 'Notas de Cooperaci&oacute;n - '.Yii::app()->user->getState('empresa_nombre');
		break;
	case Caracteristicas::REFACTURACION:
		$this->brandHeader = 'Notas de Refacturaci&oacute;n - '.Yii::app()->user->getState('empresa_nombre');
		break;
	default:
		$this->brandHeader = 'Workflow Notas - '.Yii::app()->user->getState('empresa_nombre');
		break;
}
?>

<article class="data-block">
	<div class="pull-right">
		<a href="<?php echo Yii::app()->createUrl('solicitudes'); ?>" class="btn btn-info">Regresar</a>
	</div>
	<h2>
		Actualizar solicitud
	</h2>
	<hr />
	<?php if(Yii::app()->user->hasFlash('Solicitudes.Guardar')):?>
		<div class="evtMessage alert alert-success">
			<a class="close" data-dismiss="alert" href="#">&times;</a>
			<?php echo Yii::app()->user->getFlash('Solicitudes.Guardar'); ?>
		</div>
	<?php endif; ?>
	<section>
		<?php if(Yii::app()->user->hasFlash('Solicitudes.Error')):?>
			<div class="evtMessage alert alert-danger">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<h4>Errores de captura</h4>
				<?php echo Yii::app()->user->getFlash('Solicitudes.Error'); ?>
			</div>
		<?php endif; ?>

		<?php if(Yii::app()->user->hasFlash('Solicitudes.Warning')):?>
			<div class="evtMessage alert alert-warning">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<h4>Campos requeridos</h4>
				<?php echo Yii::app()->user->getFlash('Solicitudes.Warning'); ?>
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
			'seleccion' => $seleccion,
			'jsonData' => $jsonData,
			'archivo' => $archivo,
			'documentos' => $documentos,
			'update' => true
		)); ?>
	</section>
</article>