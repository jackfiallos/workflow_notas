<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Administraci&oacute;n de Razones</h1>
    <span class="pagedesc">Administraci&oacute;n de Razones</span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/razones/create'); ?>" class="button-large button-secondary pure-button">Crear Raz&oacute;n</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'id'=>'razones-grid',
	    'dataProvider'=>$model->search(),
	    'itemsCssClass' => 'stdtable',
		'emptyText'=>'No se encontraron razones',
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
				'name'   => 'caracteristica_id',
				'header' => 'Tipo de Nota',
				'value'  => '$data->caracteristicas_tipo->caracteristicas->nombre',
				'htmlOptions' => array(
					'style' => 'width:20%;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'caracteristicasTipo_id',
				'header' => 'Tipo de Caracter&iacute;stica',
				'value'  => '$data->caracteristicas_tipo->nombre',
				'htmlOptions' => array(
					'style' => 'width:20%;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'nombre',
				'header' => 'Nombre de la Raz&oacute;n',
				'value'  => '$data->nombre',
				'htmlOptions' => array(
					'style' => 'width:20%;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),		    	
			array(
				'type'   => 'raw',
				'name'   => 'codigo',
				'header' => 'C&oacute;digo',
				'value'  => '$data->codigo',
				'htmlOptions' => array(
					'style' => 'width:10%; text-align:center'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'Cuenta',
				'header' => 'Cuenta',
				'value'  => '$data->cuenta',
				'htmlOptions' => array(
					'style' => 'width:10%; text-align:center'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'class'=>'CButtonColumn',
				'template' => '{update}',
				'updateButtonImageUrl' => false,
				'htmlOptions' => array(
					'style' => 'width:5%;'
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
						'url' => 'Yii::app()->controller->createUrl("razones/update", array("id"=>$data->id))',
					)
				)
			)				
	    ),
	)); ?>
</div>