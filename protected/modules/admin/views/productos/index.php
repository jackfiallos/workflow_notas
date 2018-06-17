<?php
$this->pageTitle = Yii::app()->name;
$dataProvider = $model->search();
$data = $dataProvider->getData();
$years = ( empty($data) ) ? 0 : (count($data[0]->precios));

$baseColumns = array(
	array(
		'type'   => 'raw',
		'name'   => 'codigo',
		'header' => 'C&oacute;digo',
		'value'  => '$data->codigo',
		'htmlOptions' => array(
			'style' => 'width:10%;'
		),
		'headerHtmlOptions'=>array(
			'class' => 'head1'
		),
	),
	array(
		'type'   => 'raw',
		'name'   => 'descripcion',
		'header' => 'Descripci&oacute;n',
		'value'  => '$data->descripcion',
		'headerHtmlOptions'=>array(
			'class' => 'head1'
		),
	),
	array(
		'type'   => 'raw',
		'header' => 'Empresa',
		'value'  => '$data->empresas->nombre',
		'htmlOptions' => array(
			'style' => 'width:20%;'
		),
		'headerHtmlOptions'=>array(
			'class' => 'head1'
		),
	)
);

$priceColumns = array();
for($i=0; $i<$years; $i++)
{
	array_push($priceColumns, array(
		'type'   => 'raw',
		'name'   => 'precio',
		'header' => 'Precio '.(2014+$i),
		'value'  => 'Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($data->precios[0]->precio), "")',
		'htmlOptions' => array(
			'style' => 'text-align:right; width:10%;'
		),
		'headerHtmlOptions'=>array(
			'class' => 'head1'
		),
	));
}

$columns = array_merge($baseColumns, $priceColumns);
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Administraci&oacute;n de Productos</h1>
    <span class="pagedesc">Administraci&oacute;n de Productos</span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/productos/importar'); ?>" class="button-large button-secondary pure-button"> Importar Archivo</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php if(Yii::app()->user->hasFlash('Productos.Success')):?>
		<div class="notibar msgsuccess">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Productos.Success'); ?></p>
        </div>
	<?php endif; ?>

	<fieldset class="search-form">
		<legend>Filtrar Resultados</legend>
		<?php $this->renderPartial('_search',array(
		    'model' => $model,
		    'empresas' => $empresas
		)); ?>
	</fieldset>

	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'id'=>'productos-grid',
	    'dataProvider'=>$dataProvider,
	    'itemsCssClass' => 'stdtable',
		'emptyText'=>'No se encontraron productos',
		'enableSorting' => false,
		'ajaxUpdate' => false,
		'summaryText'=>'Mostrando {start} al {end} de {count} registro(s)',
		'pager'=>array(
			'header'         => '',
			'hiddenPageCssClass' => 'disabled',
        	'maxButtonCount' => 3,
        	'cssFile' => false,
			'firstPageLabel' => '&lt;&lt;',
			'prevPageLabel'  => '&lt; Anterior',
			'nextPageLabel'  => 'Siguiente &gt;',
			'lastPageLabel'  => '&gt;&gt;',
		),
	    'columns' => $columns,
	)); ?>
</div>