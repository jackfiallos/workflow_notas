<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Agregar Usuario</h1>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/usuarios/index'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-home"></i> Regresar</a>
	</div>
	<span class="pagedesc">Agregar Usuario</span>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php $this->renderPartial('_form', array(
		'model'=>$model,
		'permisosDisponibles' =>  $permisosDisponibles,
        'permisosSeleccionados' => $permisosSeleccionados,
        'empresasDisponibles' => $empresasDisponibles,
        'empresasSeleccionadas' => $empresasSeleccionadas,
		'flujos' =>  $flujos,
		'grupos' => $grupos,
		'lstClientes' => $lstClientes,
		'lstClientesAsignados' => $lstClientesAsignados,
		'lstSolicitantes' => $lstSolicitantes,
        'lstSolicitantesAsignados' => $lstSolicitantesAsignados,
	)); ?>
</div>

