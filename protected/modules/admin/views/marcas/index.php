<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Administraci&oacute;n de Marcas</h1>
    <span class="pagedesc">Administraci&oacute;n de Marcas</span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/marcas/importar'); ?>" class="button-large button-secondary pure-button">Importar Archivo</a>
		<a href="<?php echo Yii::app()->createUrl('admin/marcas/create'); ?>" class="button-large button-secondary pure-button">Crear nueva marca</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php if(Yii::app()->user->hasFlash('Marcas.Success')):?>
		<div class="notibar msgsuccess">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Marcas.Success'); ?></p>
        </div>
	<?php endif; ?>

	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'id'=>'marcass-grid',
	    'dataProvider'=>$model->search(),
	    'itemsCssClass' => 'stdtable',
		'emptyText'=>'No se encontraron marcas',
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
				'name'   => 'empresas_id',
				'header' => 'Nombre de la Empresa',
				'value'  => '$data->empresas->nombre',
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
				'header' => 'Nombre de la Marca',
				'value'  => '$data->marca',
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'es_gama',
				'header' => 'Es Gama',
				'value'  => '((bool)$data->es_gama) ? "Si" : "No"',
				'htmlOptions' => array(
					'style' => 'text-align:center;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'cuenta_cooperacion',
				'header' => 'Dimensi&oacute;n en Cooperaci&oacute;n',
				'value'  => '$data->cuenta_cooperacion',
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'cuenta_descuento',
				'header' => 'Dimensi&oacute;n en Descuento',
				'value'  => '$data->cuenta_descuento',
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'identificador',
				'header' => 'Identificador',
				'value'  => '$data->identificador',
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
						'url' => 'Yii::app()->controller->createUrl("marcas/update", array("id"=>$data->id))',
					)
				)
			)				
	    ),
	)); ?>
</div>