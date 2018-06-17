<?php
$this->pageTitle = Yii::app()->name;
$this->brandHeader = 'Solicitud de Notas de Cr&eacute;dito - '.Yii::app()->user->getState('empresa_nombre');
?>

<article class="data-block">
	<div class="navbar">
		<div class="navbar-inner">
			<ul class="nav">
				<?php //if($grupo == 1): ?>
	      			<li <?php echo (Yii::app()->controller->id == 'solicitudes' && Yii::app()->controller->action->id == 'notasalmacen') ? 'class="active"' : null ;?>>
						<a href="<?php echo Yii::app()->createUrl('solicitudes/NotasAlmacen'); ?>">Notas Cr&eacute;dito de de Almac&eacute;n</a>
					</li>
					<li <?php echo (Yii::app()->controller->id == 'solicitudes' && Yii::app()->controller->action->id == 'descuentoscomerciales') ? 'class="active"' : null ;?>>
						<a href="<?php echo Yii::app()->createUrl('solicitudes/DescuentosComerciales'); ?>">Descuentos Comerciales</a>
					</li>
				<?php //endif; ?>

				<?php //if($grupo == 2): ?>
					<?php if((int)Yii::app()->user->getState('empresa_id') == 1): // Para Farma no se activa la cooperacion, solo para Dermo ?>
						<li <?php echo (Yii::app()->controller->id == 'solicitudes' && Yii::app()->controller->action->id == 'cooperacioncliente') ? 'class="active"' : null ;?>>
							<a href="<?php echo Yii::app()->createUrl('solicitudes/CooperacionCliente'); ?>">Cooperaci&oacute;n al Cliente</a>
						</li>
					<?php endif; ?>
				<?php //endif; ?>

				<li <?php echo (Yii::app()->controller->id == 'solicitudes' && Yii::app()->controller->action->id == 'refacturaciones') ? 'class="active"' : null ;?>>
					<a href="<?php echo Yii::app()->createUrl('solicitudes/Refacturaciones'); ?>">Refacturaciones</a>
				</li>
    		</ul>
  		</div>
	</div>
	<h2>
		Lista de mis solicitudes pendientes		
	</h2>
	<hr />
	<section>
		<?php
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'solicitudes-grid',
			'dataProvider'=>$model,
			'itemsCssClass' => 'table table-striped table-hover table-bordered',
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
					'name'   => 'fecha_cierre',
					'header' => 'Vigencia',
					'value' => 'Yii::app()->dateFormatter->format("d MMM y", strtotime($data->fecha_cierre))',
					'htmlOptions' => array(
						'style' => 'width:10%'
					),
				),
				array(
					'type'=>'raw',
					'name'=>'clientes_codigo',
					'header'=>'Cliente',
					'value'=>'$data->clientes->nombre',
				),
				array(
					'type'=>'raw',
					'name'=>'caracteristicas_tipo',
					'header'=>'Tipo de Nota de Cr&eacute;dito',
					'value'=>'$data->razones->caracteristicas_tipo->nombre',
				),
				array(
					'type'=>'raw',
					'name'=>'razones_id',
					'header'=>'Raz&oacute;n',
					'value'=>'$data->razones->nombre',
				),
				array(
					'header'=>'Estatus',
					'class' => 'PintaStatus',
		            'attribute' => '$data',
		            'htmlOptions' => array(
						'style' => 'width:10%; text-align:center'
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
						'style' => 'width:8%;'
					),
					'buttons' => array(
						'update' => array(
							'label' => '<i class="icon-pencil"></i>',
							'visible' => '(($data->estatus == Notas::ESTATUS_BORRADOR) ? true : false);',
							'options' => array(
								'title' => 'Editar',
								'style' => 'padding: 5px;'
							),
							'url' => 'Yii::app()->controller->createUrl("solicitudes/update", array("id"=>$data->id))',
						),
						'details' => array(
							'label' => '<i class="fa fa-search"></i>',
							'options' => array(
								'title' => 'Detalles',
								'style' => 'padding: 5px;'
							),
							'url' => 'Yii::app()->controller->createUrl("solicitudes/view", array("id"=>$data->id))',
						)
					)
				)
			)
		));
		?>
	</section>
</article>