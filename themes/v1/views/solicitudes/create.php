<?php
$this->pageTitle = Yii::app()->name;

switch ($model->tipo_nota)
{
	case Caracteristicas::ALMACEN:
		$this->brandHeader = 'Notas de Cr&eacute;dito de Almac&eacute;n';
		break;
	case Caracteristicas::DESCUENTOS:
		$this->brandHeader = 'Descuentos Comerciales';
		break;
	case Caracteristicas::COOPERACION:
		$this->brandHeader = 'Cooperaci&oacute;n al Cliente';
		break;
	case Caracteristicas::REFACTURACION:
		$this->brandHeader = 'Refacturaciones';
		break;
	default:
		$this->brandHeader = 'Solicitud de Notas de Cr&eacute;dito';
		break;
}
?>

<article class="data-block">
	<div class="pull-right">
		<a href="<?php echo Yii::app()->createUrl('solicitudes'); ?>" class="btn btn-info">Regresar</a>
	</div>
	<h2>
		Generaci&oacute;n de <?php echo $this->brandHeader; ?>
	</h2>
	<hr />
	<section>
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
	</section>
</article>