<?php
$form=$this->beginWidget('CActiveForm', array(
	'id' => 'solicitudes-form',
  	'htmlOptions' => array(
  		'class' => '',
  		'autocomplete' => 'off'
  	),
	'enableClientValidation'=>false
));
?>

<fieldset>
	<legend>Detalles de la Solicitud</legend>
	<div class="row-fluid">
		<div class="span4">
			<div class="control-group">
				<strong>Caracter&iacute;stica</strong>
				<div class="controls">
					<?php echo $model->razones->caracteristicas_tipo->caracteristicas->nombre; ?>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="control-group">
				<strong>Raz&oacute;n</strong>
				<div class="controls">
					<?php echo $model->razones->nombre; ?>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="control-group">
				<strong>Cliente</strong>
				<div class="controls">
					<?php echo $model->clientes->nombre; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span4">
			<div class="control-group">
				<strong>Sucursal</strong>
				<div class="controls">
					<?php echo (!empty($model->clientes->sucursal->nombre)) ? $model->clientes->sucursal->nombre : ''; ?>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="control-group">
				<strong>Fecha de Vencimiento</strong>
				<div class="controls">
					<?php echo $model->fecha_vencimiento; ?>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="control-group">
				<strong>Cat&aacute;logo del a&ntilde;o</strong>
				<div class="controls">
					<?php echo $model->anio->anio; ?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span4">
			<div class="control-group">
				<strong>No. de Factura</strong>
				<div class="controls">
					<?php echo $model->num_factura; ?>
				</div>
			</div>
		</div>
	</div>
</fieldset>

<hr />

<?php
echo $this->renderPartial('_formAlmacen', array(
	'form' => $form,
	'update' => $update,
	'jsonData' => $jsonData,
	'id' => $id
));
?>
<?php $this->endWidget(); ?>

<hr />

<div class="row-fluid">
	<div class="span6">
		<fieldset>
			<legend>Documentos Adjuntos</legend>
			<div class="row-fluid">
				<div class="span12">	
					<?php
					$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'documentos-grid',
						'dataProvider'=>$documentos,
						'htmlOptions' => array(
							'class' => ''
						),
						'itemsCssClass' => 'table table-striped table-hover table-bordered',
						'emptyText'=>'No se encontraron documentos',
						'enableSorting' => false,
						'summaryText'=>'Mostrando {start} al {end} de {count} registro(s)',
						'columns' => array(
							array(
								'type'=>'raw',
								'name'=>'fecha_creacion',
								'header'=>'Fecha de Carga',
								'value'=>'$data->fecha_creacion',
								'htmlOptions' => array(
									'style' => 'width:20%'
								),
							),
							array(
								'type'=>'raw',
								'name'=>'archivo',
								'header'=>'Nombre del Archivo',
								'value'=>'CHtml::link($data->archivo, Yii::app()->baseUrl."/protected/files/".$data->descripcion)',
								'htmlOptions' => array(
									'target' => '_blank'
								),
							)
						)
					));
					?>
				</div>
			</div>
		</fieldset>
	</div>
	<div class="span6">
		<fieldset>
			<legend>Historial de Cambios</legend>
			<div class="row-fluid">
				<div class="span12">
			        <div style="overflow-y:auto; height:250px; background-color:#fff; padding: 5px 6px 5px 2px;">
			        	<ul>
							<?php foreach($historial as $registro): ?>
								<li>
									Usuario: <?php echo $registro->usuarios->nombre; ?><br />
									Descripci&oacute;n: <?php echo nl2br(CHtml::encode($registro->descripcion)); ?><br />
									Fecha: <?php echo $registro->fecha_creacion; ?><br />
									<hr />
								</li>
							<?php endforeach; ?>
						</ul>
			        </div>
				</div>
			</div>
		</fieldset>
	</div>
</div>

<hr />

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'solicitud-form',
    'focus' => array($modelform, 'estatus'),
    'htmlOptions' => array(
        'class' => 'well',
    ),
    'enableClientValidation'=>false
)); ?>
<div class="row-fluid">
	<div class="span12">
		<h3>Modificaci&oacute;n de estatus</h3>
		<div class="control-group <?php echo (!is_null($model->getError('estatus')) ? 'error' : ''); ?>">
            <div class="controls">
                <?php  echo $form->radioButtonList($modelform,'estatus',
					array( 
	                	Notas::REV_SAC => 'Aceptado', 
	                	Notas::REV_RECHAZADO => 'Rechazado'
                	),
                	array(
                		'labelOptions'=>array('style'=>'display:inline;margin-right: 5px;'), 
						'separator'=>' ',
						'class' => 'validar'
                	)
                ); ?>
                 <span class="help-inline">
                	<?php echo $form->error($modelform,'estatus'); ?>
            	</span>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="control-group <?php echo (!is_null($modelform->getError('comentario')) ? 'error' : ''); ?>">
			<label for="<?php echo CHtml::activeId($modelform,'comentario'); ?>" class="control-label">Escriba un comentario, acerca del cambio de estatus realizado.</label>
            <div class="controls">
				<?php echo $form->textArea($modelform, 'comentario', 
						array('class'=>'span12 validar','rows' => 6, 'cols' => 50, 'value'=>'')); 
				?>
				 <span class="help-inline">
                	<?php echo $form->error($modelform,'comentario'); ?>
            	</span>
            </div>
        </div>
        <div class="form-actions">
    		<button class="btn btn-primary" type="submit" value="Guardar">Guardar</button>
    		<a href="<?php echo Yii::app()->createUrl('logistica')?>" class="btn">Regresar</a>
		</div>
    </div>
</div>
<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.inputmask.js',CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.inputmask.extensions.js',CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.inputmask.numeric.extensions.js',CClientScript::POS_END);
$cs->registerScript('solicitudes.jquery.main.create', "
	var specialKeys = new Array();
    specialKeys.push(8); //Backspace
    specialKeys.push(46); //Punto

	$('.numeric').on('keypress', function (e) {
		var keyCode = e.which ? e.which : e.keyCode;
		var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
		return ret;
	}).on('paste', function (e) { return false; }).on('drop', function (e) { return false; }).inputmask('decimal', { 
		autoGroup: true,
		groupSeparator: ','
	});

	$('.disallownumeric').on('keypress', function (e) { return false; });

", CClientScript::POS_READY);
?>