<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Administraci&oacute;n de Usuarios</h1>
    <span class="pagedesc">Administraci&oacute;n de Usuarios</span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/usuarios/create'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-user"></i> Agregar Usuario</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php if(Yii::app()->user->hasFlash('Usuarios.Success')):?>
		<div class="notibar msgsuccess">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Usuarios.Success'); ?></p>
        </div>
	<?php endif; ?>

	<?php if(Yii::app()->user->hasFlash('Usuarios.Error')):?>
		<div class="notibar msgerror">
            <a class="close"></a>
            <p><?php echo Yii::app()->user->getFlash('Usuarios.Error'); ?></p>
        </div>
	<?php endif; ?>	

	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'id'=>'usuarios-grid',
	    'dataProvider'=>$model->search(),
	    'itemsCssClass' => 'stdtable',
		'emptyText'=>'No se encontraron usuarios',
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
				'type'   => 'raw',
				'name'   => 'fecha_creacion',
				'header' => 'Creaci&oacute;n',
				'value'  => '$data->fecha_creacion',
				'htmlOptions' => array(
					'style' => 'width:15%;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
	        array(
				'type'   => 'raw',
				'name'   => 'username',
				'header' => 'Usuario',
				'value'  => '$data->username',
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
				'header' => 'Nombre',
				'value'  => '$data->nombre',
				'htmlOptions' => array(
					'style' => 'width:18%;'
				),
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
	        array(
				'type'   => 'raw',
				'name'   => 'correo',
				'header' => 'Email',
				'value'  => '$data->correo',
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
			array(
				'type'   => 'raw',
				'name'   => 'grupos_id',
				'header' => 'Grupo',
				'value'  => '((isset($data->grupo)) ? $data->grupo->nombre : "-")',
				'headerHtmlOptions'=>array(
					'class' => 'head1'
				),
			),
	        array(
				'type'   => 'raw',
				'name'   => 'estatus',
				'header' => 'Estatus',
				'value'  => '($data->estatus == Usuarios::ACTIVO) ? "Activo" : "Inactivo"',
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
						'url' => 'Yii::app()->controller->createUrl("usuarios/update", array("id"=>$data->id))',
					)
				)
			)
	    ),
	)); ?>
</div>