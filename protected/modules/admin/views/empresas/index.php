<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Administraci&oacute;n de Empresas</h1>
    <span class="pagedesc">Administraci&oacute;n de Empresas</span>
</div>
<div id="contentwrapper" class="contentwrapper">
	
	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'id'=>'empresas-grid',
	    'dataProvider'=>$model->search(),
	    'itemsCssClass' => 'stdtable',
		'emptyText'=>'No se encontraron empresas',
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
				'name'   => 'id',
				'header' => 'ID',
				'value'  => '$data->id',
				'htmlOptions' => array(
					'style' => 'width:5%'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'nombre',
				'header' => 'Nombre de la Empresa',
				'value'  => '$data->nombre',
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'class'=>'CButtonColumn',
				'template' => '{update}',
				'updateButtonImageUrl' => false,
				'htmlOptions' => array(
					'style' => 'width:5%'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
				'buttons' => array(
					'update' => array(
						'label' => '<i class="fa fa-pencil"></i>',
						'options' => array(
							'title' => 'Editar',
							'style' => 'padding: 5px;',
							'class' => 'btn'
						),
						'url' => 'Yii::app()->controller->createUrl("empresas/update", array("id"=>$data->id))',
					)
				)
			)
	    ),
	)); ?>
</div>