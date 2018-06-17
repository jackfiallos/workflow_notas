<?php $this->pageTitle = Yii::app()->name; ?>

<article class="data-block">
	<h2>Lista de Solicitudes Pendientes</h2>
	<hr />
	<section>
		<?php if(Yii::app()->user->hasFlash('Logistica.Success')):?>
			<div class="evtMessage alert alert-success">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<?php echo Yii::app()->user->getFlash('Logistica.Success'); ?>
			</div>
		<?php endif; ?>

		<?php if(Yii::app()->user->hasFlash('Logistica.Error')):?>
			<div class="evtMessage alert alert-danger">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<?php echo Yii::app()->user->getFlash('Logistica.Error'); ?>
			</div>
		<?php endif; ?>

		<?php $this->widget('zii.widgets.grid.CGridView', array(
		    'id'=>'aprobadores-grid',
		    'dataProvider'=>$model,
		    'itemsCssClass' => 'table table-striped table-hover table-bordered',
			'emptyText'=>'No se encontraron solicitudes pendientes.',
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
			'htmlOptions' => array('style' => 'table-layout: fixed;'),
		    'columns'=>array(
		    	array(
					'type'=>'raw',
					'name'=>'folio',
					'htmlOptions' => array(
						'style' => 'width:10%;'
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
				),
				array(
					'type'   => 'raw',
					'name'   => 'fecha_vencimiento',
					'header' => 'Vigencia',
					'value' => 'Yii::app()->dateFormatter->format("d MMM y", strtotime($data->fecha_vencimiento))',
					'htmlOptions' => array(
						'style' => 'width:10%'
					),
				),
		    	array(
					'type'   => 'raw',
					'name'   => 'cliente_nombre',
					'header' => 'Cliente',
					'value'  => '$data->clientes->nombre',
					'htmlOptions' => array(
						'style' => 'width:15%;'
					),
				),
				array(
					'type'=>'raw',
					'name'=>'caracteristicas_tipo',
					'header'=>'Tipo de Nota de Cr&eacute;dito',
					'value'=>'$data->razones->caracteristicas_tipo->nombre',
					'htmlOptions' => array(
						'style' => 'width:15%;'
					),
				),
				array(
					'type'=>'raw',
					'name'=>'usuarios_id',
					'header'=>'Solicitante',
					'value'=>'$data->usuarios->nombre',
					'htmlOptions' => array(
						'style' => 'width:10%;'
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
				),
				array(
					'header'=>'Revisi&oacute;n',
					'class' => 'PintaRevision',
		            'attribute' => '$data',
		            'htmlOptions' => array(
						'style' => 'width:10%; text-align:center'
					),
				),
				array(
					'class'=>'CButtonColumn',
					'template' => '{details}&nbsp;{update}',
					'updateButtonImageUrl' => false,
					'htmlOptions' => array(
						'style' => 'width:10%;text-align:center'
					),
					'buttons' => array(
						'update' => array(
							'label' => '<i class="icon-pencil"></i>',
							'options' => array(
								'title' => 'Editar',
								'style' => 'padding: 5px;'
							),
							'url' => 'Yii::app()->controller->createUrl("logistica/update", array("id"=>$data->id))',
						),
						'details' => array(
							'label' => '<i class="fa fa-search"></i>',
							'options' => array(
								'title' => 'Detalles',
								'style' => 'padding: 5px;'
							),
							'url' => 'Yii::app()->controller->createUrl("logistica/view", array("id"=>$data->id))',
						)
					)
				)
		    )
		)); ?>
	</section>
</article>