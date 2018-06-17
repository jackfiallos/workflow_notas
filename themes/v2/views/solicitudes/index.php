<?php
$this->pageTitle = Yii::app()->name;
$this->brandHeader = 'Solicitud de Notas de Cr&eacute;dito - '.Yii::app()->user->getState('empresa_nombre');
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Lista de mis solicitudes</h1>
    <span class="pagedesc">Lista de solicitudes pendientes por enviar y enviadas a los supervisores para su revisi&oacute;n.</span>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php $this->renderPartial('_search',array(
	    'model' => $model,
	    'clientes' => $clientes
	)); ?>
	<hr />
	<?php
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'solicitudes-grid',
			'dataProvider'=>$model->searchSolicitantes(),
			'itemsCssClass' => 'stdtable',
			'emptyText'=>'No se encontraron solicitudes',
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
			'columns'=>array(
				array(
					'type'=>'raw',
					'name'=>'folio',
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
				),
				array(
					'type' => 'raw',
					'name' => 'fecha_creacion',
					'header' => 'Creaci&oacute;n',
					'value' => 'Yii::app()->dateFormatter->format("d MMM y", strtotime($data->fecha_creacion))',
					'htmlOptions' => array(
						'style' => 'width:10%;'
					),
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
				),
				array(
					'type'   => 'raw',
					'name'   => 'fecha_cierre',
					'header' => 'Vigencia',
					'value' => 'Yii::app()->dateFormatter->format("d MMM y", strtotime($data->fecha_cierre))',
					'htmlOptions' => array(
						'style' => 'width:10%'
					),
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
				),
				array(
					'type'=>'raw',
					'name'=>'clientes_codigo',
					'header'=>'Cliente',
					'value'=>'$data->clientes->nombre',
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
				),
				array(
					'type'=>'raw',
					'name'=>'caracteristicas_tipo',
					'header'=>'Tipo de Nota',
					'value'=>'$data->razones->caracteristicas_tipo->nombre',
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
				),
				array(
					'header' => 'Total',
					'name'   => 'total',
	        		'class'     => 'TotalColumn',
	        		'attribute' => '$data',
	        		'htmlOptions' => array(
						'style' => 'width:10%;text-align:right;'
					), 
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
				),
				array(
					'header'=>'Estatus',
					'class' => 'PintaStatus',
		            'attribute' => '$data',
		            'htmlOptions' => array(
						'style' => 'width:10%; text-align:center'
					),
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
				),
				array(
					'header'=>'Revisi&oacute;n',
					'class' => 'PintaRevision',
		            'attribute' => '$data',
		            'htmlOptions' => array(
						'style' => 'width:10%; text-align:center'
					),
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
				),
				array(
					'class'=>'CButtonColumn',
					'template' => '{details}&nbsp;{update}',
					'updateButtonImageUrl' => false,
					'htmlOptions' => array(
						'style' => 'width:8%;'
					),
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
					'buttons' => array(
						'update' => array(
							'label' => '<i class="fa fa-pencil"></i>',
							'visible' => '(($data->estatus == Notas::ESTATUS_BORRADOR) ? true : false);',
							'options' => array(
								'title' => 'Editar',
								'style' => 'padding: 5px;',
								'class' => 'btn'
							),
							'url' => 'Yii::app()->controller->createUrl("solicitudes/update", array("id"=>$data->id))',
						),
						'details' => array(
							'label' => '<i class="fa fa-search"></i>',
							'options' => array(
								'title' => 'Detalles',
								'style' => 'padding: 5px;',
								'class' => 'btn'
							),
							'url' => 'Yii::app()->controller->createUrl("solicitudes/view", array("id"=>$data->id))',
						)
					)
				)
			)
		));
	?>
</div>