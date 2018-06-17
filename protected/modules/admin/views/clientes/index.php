<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Administraci&oacute;n de Clientes</h1>
    <span class="pagedesc">Administraci&oacute;n de Clientes</span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/clientes/importar'); ?>" class="button-large button-secondary pure-button">Importar Clientes</a>
		<a href="<?php echo Yii::app()->createUrl('admin/clientes/create'); ?>" class="button-large button-secondary pure-button">Crear nuevo cliente</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php if(Yii::app()->user->hasFlash('Clientes.Success')):?>
		<div class="notibar msgsuccess">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Clientes.Success'); ?></p>
        </div>
	<?php endif; ?>

	<?php if(Yii::app()->user->hasFlash('Clientes.Error')):?>
		<div class="notibar msgerror">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Clientes.Error'); ?></p>
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
	    'id'=>'clientes-grid',
	    'dataProvider'=>$model->search(),
	    'itemsCssClass' => 'stdtable',
		'emptyText'=>'No se encontraron clientes',
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
				'name'   => 'nombre',
				'header' => 'Nombre del Cliente',
				'value'  => '$data->nombre',
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'header'=>'Empresa',
				'class' => 'PintaEmpresa',
	            'attribute' => '$data',
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
						'url' => 'Yii::app()->controller->createUrl("clientes/update", array("id"=>$data->codigo))',
					)
				)
			)
	    ),
	)); ?>
</div>

<?php
Yii::app()->clientScript->registerScript('admin.clientes.search', "
	$('.search-form form').submit(function(){
	    $('#clientes-grid').yiiGridView('update', {
	        data: $(this).serialize()
	    });
	    return false;
	});

	$('.evtMessage').delay(5000).fadeOut('slow');
");
?>