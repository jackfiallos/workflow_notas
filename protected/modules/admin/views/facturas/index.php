<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Administraci&oacute;n de Facturas</h1>
    <span class="pagedesc">Administraci&oacute;n de Facturas</span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/facturas/importar'); ?>" class="button-large button-secondary pure-button"> Importar Archivo</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php if(Yii::app()->user->hasFlash('Facturas.Success')):?>
		<div class="notibar msgsuccess">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Facturas.Success'); ?></p>
        </div>
	<?php endif; ?>

	<fieldset class="search-form">
		<legend>Filtrar Resultados</legend>
		<?php $this->renderPartial('_search',array(
		    'model' => $model,
		    'clientes' => $clientes
		)); ?>
	</fieldset>
	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'id'=>'facturas-grid',
	    'dataProvider'=>$model->search(),
	    'itemsCssClass' => 'stdtable',
		'emptyText'=>'No se encontraron facturas',
		'enableSorting' => false,
		'ajaxUpdate' => false,
		'summaryText'=>'Mostrando {start} al {end} de {count} registro(s)',
		'pager'=>array(
			'header'         => '',
			'firstPageLabel' => '&lt;&lt;',
			'prevPageLabel'  => '&lt; Anterior',
			'nextPageLabel'  => 'Siguiente &gt;',
			'lastPageLabel'  => '&gt;&gt;',
		),
	    'columns' => array(
			array(
				'type'   => 'raw',
				'name'   => 'orden_compra',
				'header' => 'No. Orden',
				'value'  => '$data->orden_compra',
				'htmlOptions' => array(
					'style' => 'width:15%;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'folio',
				'header' => 'No. Factura',
				'value'  => '$data->folio',
				'htmlOptions' => array(
					'style' => 'width:15%;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'clientes_codigo',
				'header' => 'Nombre del Cliente',
				'value'  => '(isset($data->clientes) ? $data->clientes->nombre : "Cliente no encontrado")',
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'fecha',
				'header' => 'Fecha de Emisi&oacute;n',
				'value'  => '$data->fecha',
				'htmlOptions' => array(
					'style' => 'width:15%;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'        => 'raw',
				'name'        => 'monto',
				'header'      => 'Importe',
				'value'       => 'Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($data->monto), "")',
				'htmlOptions' => array(
					'style' => 'text-align:right; width:10%;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
	    ),
	)); ?>
</div>

<?php
Yii::app()->clientScript->registerScript('admin.facturas.search', "
	$('.search-form form').submit(function(){
	    $('#facturas-grid').yiiGridView('update', {
	        data: $(this).serialize()
	    });
	    return false;
	});
");
?>