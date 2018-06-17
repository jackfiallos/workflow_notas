<?php $this->pageTitle = Yii::app()->name; ?>

<div class="pageheader notab">
    <h1 class="pagetitle">Revisi&oacute;n de Solicitudes</h1>
    <span class="pagedesc">Lista de solicitudes enviadas para su revisi&oacute;n.</span>
</div>
<div id="contentwrapper" class="contentwrapper">

	<?php if(Yii::app()->user->hasFlash('Aprobadores.Success')):?>
		<div class="notibar msgsuccess">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Aprobadores.Success'); ?></p>
        </div>
	<?php endif; ?>

	<?php if(Yii::app()->user->hasFlash('Aprobadores.Error')):?>
		<div class="notibar msgerror">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Aprobadores.Error'); ?></p>
        </div>
	<?php endif; ?>

	<?php if(Yii::app()->user->hasFlash('Aprobadores.Warning')):?>
		<div class="notibar msgalert">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Aprobadores.Warning'); ?></p>
        </div>
	<?php endif; ?>

	<?php $this->renderPartial('_search',array(
	    'model' => $model,
	    'clientes' => $clientes
	)); ?>
	<hr />

	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'id'=>'aprobadores-grid',
	    'dataProvider'=>$model->searchAprobadores(),
	    'itemsCssClass' => 'stdtable',
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
				'name'   => 'fecha_vencimiento',
				'header' => 'Vigencia',
				'value' => 'Yii::app()->dateFormatter->format("d MMM y", strtotime($data->fecha_vencimiento))',
				'htmlOptions' => array(
					'style' => 'width:10%'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
	    	array(
				'type'   => 'raw',
				'name'   => 'cliente_nombre',
				'header' => 'Cliente',
				'value'  => '$data->clientes->nombre',
				'htmlOptions' => array(
					'style' => 'width:20%;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
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
				'headerHtmlOptions'=>array(
					'class' => 'head1'
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
				'template' => '{update}',
				'updateButtonImageUrl' => false,
				'deleteButtonImageUrl'=>false,
				'htmlOptions' => array(
					'style' => 'width:5%;text-align:center;'
				), 
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
				'buttons' => array(
					'update' => array(
						'label' => '<i class="fa fa-search"></i>',
						'options' => array(
							'title' => 'Editar',
							'style' => 'padding: 5px;',
							'class' => 'btn'
						),
						'url' => 'Yii::app()->controller->createUrl("aprobadores/update", array("id"=>$data->id))',
					)
				)
			)
	    )
	)); ?>
</div>	