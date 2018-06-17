<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Agregar Tipo de Caracter&iacute;stica</h1>
    <span class="pagedesc">Agregar Tipo de Caracter&iacute;stica</span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/caracteristicastipo/index'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-home"></i> Regresar</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php $this->renderPartial('_form', array(
		'model'=>$model,
		'caracteristicas' => $caracteristicas,
	)); ?>
</div>

