<?php $this->pageTitle = Yii::app()->name; ?>

<style>
	table { table-layout: fixed; }
</style>			

<article class="data-block">
	<h2>Revisi&oacute;n de Solicitudes</h2>
	<hr />
	<section>
		<?php if(Yii::app()->user->hasFlash('Aprobadores.Success')):?>
			<div class="evtMessage alert alert-success">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<?php echo Yii::app()->user->getFlash('Aprobadores.Success'); ?>
			</div>
		<?php endif; ?>

		<?php if(Yii::app()->user->hasFlash('Aprobadores.Error')):?>
			<div class="evtMessage alert alert-warning">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<?php echo Yii::app()->user->getFlash('Aprobadores.Error'); ?>
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
					'type'   => 'raw',
					'name'   => 'cliente_nombre',
					'header' => 'Cliente',
					'value'  => '$data->clientes->nombre',
					'htmlOptions' => array(
						'style' => 'width:20%;'
					),
				),
				array(
					'type'   => 'raw',
					'name'   => 'caracteristica',
					'header' => 'Tipo de Nota de Cr&eacute;dito',
					'value'  => '$data->razones->caracteristicas_tipo->caracteristicas->nombre',
					'htmlOptions' => array(
						'style' => 'width:25%;'
					),
				),
				array(
					'type'   => 'raw',
					'name'   => 'fecha_creacion',
					'header' => 'Fecha de Creaci&oacute;n',
					'value'  => '$data->fecha_creacion',
					'htmlOptions' => array(
						'style' => 'width:15%'
					),
				),
				array(
					'type'   => 'raw',
					'name'   => 'fecha_vencimiento',
					'header' => 'Fecha Vencimiento',
					'value'  => '$data->fecha_vencimiento',
					'htmlOptions' => array(
						'style' => 'width:15%'
					),
				),
				array(
					'header' => 'Total',
					'name'   => 'total',
            		'class'     => 'TotalColumn',
            		'attribute' => '$data',
            		'htmlOptions' => array(
						'style' => 'width:20%;text-align:right;'
					), 
				),
				array(
					'header'=>'Revisi&oacute;n',
					'class' => 'PintaRevision',
		            'attribute' => '$data',
		            'htmlOptions' => array(
						'style' => 'width:10%;text-align:center;'
					), 
				),
				array(
					'class'=>'CButtonColumn',
					'template' => '{update}',
					'updateButtonImageUrl' => false,
					'deleteButtonImageUrl'=>false,
					'buttons' => array(
						'update' => array(
							'label' => '<i class="icon-pencil"></i>',
							'options' => array(
								'title' => 'Editar',
								'style' => 'padding: 5px;'
							),
							'url' => 'Yii::app()->controller->createUrl("aprobadores/update", array("id"=>$data->id))',
						)
					)
				)
		    )
		)); ?>
	</section>
</article>