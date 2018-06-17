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
	<legend>Tipo de Solicitud</legend>
	<div class="row-fluid">
		<div class="span4">
			<div class="control-group <?php echo (!is_null($model->getError('caracteristica')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'caracteristica', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->dropDownList($model, 'caracteristica', $caracteristicas, array('class'=>'span12', 'empty'=>'Seleccionar',
					'ajax' => array(
						'type'=>'POST',
						'url'=>CController::createUrl('solicitudes/SelectRazon'),
						'update'=>'#'.CHtml::activeId($model, 'razon'),
						'data'=>'js:$("#solicitudes-form").serialize()'
					))); ?>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="control-group <?php echo (!is_null($model->getError('razon')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'razon', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->dropDownList($model, 'razon', $razones, array('class'=>'span12', 'empty'=>'Seleccionar')); ?>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="control-group <?php echo (!is_null($model->getError('cliente')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'cliente', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->dropDownList($model, 'cliente', $clientes, array('class'=>'span12 chosen-select', 'empty'=>'Seleccionar', 'data-placeholder'=>'Selecciona un Cliente')); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span4">
			<div class="control-group <?php echo (!is_null($model->getError('sucursal')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'sucursal', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->dropDownList($model, 'sucursal', array(), array('class'=>'span12', 'empty'=>'Seleccionar Sucursal')); ?>
				</div>
			</div>
		</div>
		<div class="span4">
			<?php if($model->tipo_nota != Caracteristicas::DESCUENTOS): ?>
			<div class="control-group <?php echo (!is_null($model->getError('fecha_vencimiento')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'fecha_vencimiento', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'options'=>array(
								'showAnim'=>'fold',
							),
							'model'=>$model,
							'attribute'=>'fecha_vencimiento',
							'htmlOptions'=>array(
								'class'=>'span12',
								'value'=>$model->fecha_vencimiento,
							),
							'options'=>array(
								'dateFormat'=>'yy-mm-dd',
								'showButtonPanel'=>true,
								'beforeShowDay' => 'js:function(date){
									var day = date.getDate();
									if ($.inArray(day, unavailableDates) < 0) {
										return [true,""];
									} else {
										return [false,""];
									}
								}',
								'minDate'=>0,
								'changeMonth'=>true,
								'changeYear'=>true,
								'yearRange'=>'nnnn:+5nn'
							)
						));
					?>
				</div>
			</div>
			<?php else: ?>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group <?php echo (!is_null($model->getError('fecha_vencimiento')) ? 'error' : ''); ?>">
						<?php echo $form->labelEx($model,'fecha_vencimiento', array('class'=>'control-label')); ?>
						<div class="controls">
							<?php
								$this->widget('zii.widgets.jui.CJuiDatePicker', array(
									'options'=>array(
										'showAnim'=>'fold',
									),
									'model'=>$model,
									'attribute'=>'fecha_vencimiento',
									'htmlOptions'=>array(
										'class'=>'span12',
										'value'=>$model->fecha_vencimiento,
									),
									'options'=>array(
										'dateFormat'=>'yy-mm-dd',
										'showButtonPanel'=>true,
										'beforeShowDay' => 'js:function(date){
											var day = date.getDate();
											if ($.inArray(day, unavailableDates) < 0) {
												return [true,""];
											} else {
												return [false,""];
											}
										}',
										'minDate'=>0,
										'changeMonth'=>true,
										'changeYear'=>true,
										'yearRange'=>'nnnn:+5nn'
									)
								));
							?>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group <?php echo (!is_null($model->getError('mes_aplicacion')) ? 'error' : ''); ?>">
						<?php echo $form->labelEx($model,'mes_aplicacion', array('class'=>'control-label')); ?>
						<div class="controls">
							<?php echo $form->dropDownList($model, 'mes_aplicacion', array(
								1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
							), array('class'=>'span12', 'empty'=>'Seleccionar un mes')); ?>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php if ($model->tipo_nota == Caracteristicas::ALMACEN): ?>
		<div class="span4">
			<div class="control-group <?php echo (!is_null($model->getError('anio')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'anio', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->dropDownList($model, 'anio', $anios, array('class'=>'span12', 'empty'=>'Seleccionar',
					'ajax' => array(
						'type'=>'POST',
						'url'=>CController::createUrl('solicitudes/SelectProductos'),
						'success'=>'function(html){
							jQuery("#SolicitudesForm_product_id").html(html);
							jQuery(".chosen-select").trigger("chosen:updated");
						}',
						'data'=>'js:$("#solicitudes-form").serialize()'
					))); ?>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if ($model->tipo_nota == Caracteristicas::DESCUENTOS): ?>
		<div class="span4">
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group <?php echo (!is_null($model->getError('factura_id')) ? 'error' : ''); ?>">
						<?php echo $form->labelEx($model,'factura_id', array('class'=>'control-label')); ?>
						<div class="controls">
							<?php echo $form->textField($model, 'factura_id', array('class'=>'span12')); ?>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group <?php echo (!is_null($model->getError('importe')) ? 'error' : ''); ?>">
						<?php echo $form->labelEx($model,'importe', array('class'=>'control-label')); ?>
						<div class="controls">
							<?php echo $form->textField($model, 'importe', array('class'=>'span12 numeric')); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if ($model->tipo_nota == Caracteristicas::COOPERACION): ?>
		<div class="span4">
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group <?php echo (!is_null($model->getError('importe')) ? 'error' : ''); ?>">
						<?php echo $form->labelEx($model,'importe', array('class'=>'control-label')); ?>
						<div class="controls">
							<?php echo $form->textField($model, 'importe', array('class'=>'span12 numeric')); ?>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group <?php echo (!is_null($model->getError('mes_aplicacion')) ? 'error' : ''); ?>">
						<?php echo $form->labelEx($model,'mes_aplicacion', array('class'=>'control-label')); ?>
						<div class="controls">
							<?php echo $form->dropDownList($model, 'mes_aplicacion', array(
								1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
							), array('class'=>'span12', 'empty'=>'Seleccionar un mes')); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if ($model->tipo_nota == Caracteristicas::REFACTURACION): ?>
		<div class="span4">
			<div class="control-group <?php echo (!is_null($model->getError('factura_id')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'factura_id', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'factura_id', array('class'=>'span12')); ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
	
	<div class="row-fluid">
		<?php if($update && $model->tipo_nota != Caracteristicas::REFACTURACION && $model->tipo_nota != Caracteristicas::DESCUENTOS && $model->tipo_nota != Caracteristicas::COOPERACION): ?>
		<div class="span4">
			<div class="control-group <?php echo (!is_null($model->getError('factura_id')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'factura_id', array('class'=>'control-label')); ?>
				<div class="controls">
					<div class="input-append">
						<?php echo $form->textField($model, 'factura_id', array('class'=>'span12')); ?>
						<span class="add-on fa fa-search"></span>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if($model->tipo_nota == Caracteristicas::REFACTURACION): ?>
		<div class="span4">
			<label class="checkbox inline" style="font-size: 15px;">
				<?php
				echo $form->checkBox($model, 'cancela_sustituye', array('style'=>'width:20px; height:20px;')); 
				echo "&nbsp;".$model->getAttributeLabel('cancela_sustituye');
				?>
			</label>
		</div>
		<?php endif; ?>
	</div>

	<?php //if (!$update): ?>
	<button class="pull-right btn btn-primary btnsave">Guardar</button>
	<?php //endif; ?>
</fieldset>

<?php
if ($update)
{
	if ($model->tipo_nota == Caracteristicas::ALMACEN)
	{
		echo $this->renderPartial('_formAlmacen', array(
			'form' => $form,
			'productos' => $seleccion,
			'model' => $model,
			'update' => $update,
			'jsonData' => $jsonData,
			'id' => $id
		));
	}
	else if ($model->tipo_nota == Caracteristicas::DESCUENTOS)
	{
		echo $this->renderPartial('_formDescuentos', array(
			'form' => $form,
			'marcas' => $seleccion,
			'model' => $model,
			'update' => $update,
			'jsonData' => $jsonData,
			'id' => $id
		));
	}
	else if ($model->tipo_nota == Caracteristicas::COOPERACION)
	{
		echo $this->renderPartial('_formCooperacion', array(
			'form' => $form,
			'marcas' => $seleccion,
			'model' => $model,
			'update' => $update,
			'jsonData' => $jsonData,
			'id' => $id
		));
	}
	else 
	{
		echo $this->renderPartial('_formRefacturacion', array(
			'form' => $form,
			'model' => $model,
			'update' => $update,
			'jsonData' => $jsonData,
			'id' => $id
		));
	}
}
?>
<?php $this->endWidget(); ?>

<?php if ($update): ?>
<fieldset>
	<legend>Documentos Adjuntos</legend>
	<div class="row-fluid">
		<div class="span12">
			<?php $form = $this->beginWidget('CActiveForm',array(
				'id' => 'imageform',
				'enableAjaxValidation' => false,
				'action'=>CController::createUrl('solicitudes/Fileupload', array(
					'id'=>$id
				)),
				'method'=>'POST',
				'htmlOptions' => array(
					'enctype' => 'multipart/form-data'
				)
			));

			echo "Seleccione un archivo: ".$form->fileField($archivo, 'archivo', array('id'=>'photoimg'));
			echo "<span class=\"help-block\"></span>";
			echo "<span id=\"preview\"></span>";

			$this->endWidget(); ?>
			
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
							'style' => 'width:20%;'
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
					),
					array(
						'class'=>'CButtonColumn',
						'template' => '{borrar}',
						'htmlOptions' => array(
							'style' => 'width:5%;'
						),
						'buttons' => array(
							'borrar' => array(
								'label' => '<i class="icon-trash"></i>',
								'options' => array(
									'title' => 'Editar',
									'style' => 'padding: 5px;',
									'class' => 'evtFileDelete',
								),
								'url' => 'Yii::app()->controller->createUrl("solicitudes/FileDelete", array("id"=>$data->id))',
							)
						)
					)
				)
			));
			?>
		</div>
	</div>
</fieldset>

<fieldset>
	<legend>Comentarios y anotaciones</legend>
	<?php
	$form=$this->beginWidget('CActiveForm', array(
		'id' => 'solicitudes-comment-form',
	  	'action' => CController::createUrl('solicitudes/publicar', array('id'=>$id)),
	  	'htmlOptions' => array(
	  		'class' => '',
	  	),
		'enableClientValidation'=>false
	));
	?>
	<div class="row-fluid">
		<div class="span12">
			<div class="control-group">
				<?php echo $form->labelEx($model,'comentario', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textArea($model, 'comentario', array('class'=>'span12', 'rows'=>'5', 'style'=>'resize:none;', 'value'=>'')); ?>
				</div>
			</div>
			<div class="form-actions" style="text-align:right">
				<button class="btn btn-primary btn-danger">Enviar</button>
				<a href="<?php echo Yii::app()->createUrl('solicitudes'); ?>" class="btn">Regresar</a>
			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</fieldset>
<?php endif; ?>

<?php
$selectScript = '';
if ($model->tipo_nota == Caracteristicas::ALMACEN)
{
	$selectScript = "
			var scope = angular.element($(\"#outer\")).scope();
    		scope.\$apply(function() {
        		scope.completarFactura(ui.item.id);
    		});
	";
}
else if($model->tipo_nota == Caracteristicas::DESCUENTOS)
{
	$selectScript = "
			//$('.btnsave').attr('disabled', 'disabled');
			jQuery.ajax({
				url: '".CController::createUrl('solicitudes/ObtenerTotalFactura', array('id'=>$id))."',
				type: 'POST',
				data: jQuery('#solicitudes-form').serialize(),
				beforeSend: function(){},
				success: function(data) {
					var update = ".var_export($update, true).";
					if (data.response) {
						$('#".CHtml::activeId($model, 'importe')."').val(data.monto);
						/*if(update) {
							$('#solicitudes-form').submit();
						}*/
					}
				}
			});
	";
}
else if($model->tipo_nota == Caracteristicas::REFACTURACION)
{
	if ($update)
	{
		$selectScript = "
				$('.btnsave').attr('disabled', 'disabled');
				$('#solicitudes-form').submit();
		";
	}
}

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ui.datepicker-es.js',CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/chosen.jquery.min.js',CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.form.js',CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.inputmask.js',CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.inputmask.extensions.js',CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.inputmask.numeric.extensions.js',CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.blockUI.js',CClientScript::POS_END);
$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/chosen/chosen.min');
$cs->registerScript('solicitudes.jquery.main.create', "
	var specialKeys = new Array();
    specialKeys.push(8); //Backspace
    specialKeys.push(46); //Punto
    var unavailableDates = ".CJSON::encode(Yii::app()->params['diasNoDisponibles']).";

    $('.chosen-select').chosen({no_results_text: 'No se encontraron resultados para '});

	$('.numeric').on('keypress', function (e) {
		var keyCode = e.which ? e.which : e.keyCode;
		var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
		if (keyCode == 13) ret = false;
		return ret;
	}).on('paste', function (e) { return false; }).on('drop', function (e) { return false; }).inputmask('decimal', { 
		autoGroup: true,
		groupSeparator: ','
	});

	//$('.disallownumeric').on('keypress', function (e) { return false; });

	$('#".CHtml::activeId($model, 'factura_id')."').on('keyup', function(){
		if ($(this).val().length == 0) {
			$(this).parent().find('span:last').addClass('fa-search').removeClass('add-on-error').removeClass('fa-times').removeAttr('title');
		}
	});

	$('#".CHtml::activeId($model, 'factura_id')."').autocomplete({
		minLength: 3,
		source: function(request, response) {			
			$.post('".CController::createUrl('solicitudes/BuscarFactura')."', {
				term: request.term,
				cliente_codigo: $('#".CHtml::activeId($model, 'cliente')."').val(),
				YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
			}, response).done(function(response) {
				if (response.length == 0) {
					$('#".CHtml::activeId($model, 'factura_id')."').parent().find('span:last').removeClass('fa-search').addClass('add-on-error').addClass('fa-times').attr('title', 'La factura no se encuentra.');
				}
			});
        },
        focus: function() {
			// prevent value inserted on focus
			return false;
        },
        select: function(event, ui) {
			$('#".CHtml::activeId($model, 'factura_id')."').val(ui.item.label);
			".$selectScript."
			return false;
        }
	});

", CClientScript::POS_READY);

if ($update) {
	$cs->registerScript('solicitudes.jquery.main', "
		$(document).on('click', '.evtFileDelete', function(e) {
			e.preventDefault();
			var el = $(this);
			$.ajax({
				url: el.attr('href'),
				type: 'POST',
				data: {
					YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
				},
				success: function(data) {
					if (data) {
						$.fn.yiiGridView.update('documentos-grid');
					}
				}
			});
		});

		$('#photoimg').on('change', function() { 
			$('#preview').html('').html('<img src=\"".Yii::app()->theme->baseUrl."/css/loader.gif\" alt=\"Subiendo archivo, por favor espere...\"/>');
			$('#imageform').ajaxForm({
				dataType: 'text',
				target: '#preview',
				success: function(data){
					$('#photoimg').val('');
					$('#imageform')[0].reset();
					var response = $.parseJSON(data);
					if(response.id != '') {
						$('#preview').empty();
						$('#preview').removeAttr('style');
						$('#preview').removeClass('alert alert-error');
						$.fn.yiiGridView.update('documentos-grid');
					}
				},
				error: function(data){
					$('#preview').html('Ocurrió un error al intentar subir el archivo, por favor intente nuevamente.').addClass('alert alert-error');
					$('#preview').attr('style', 'padding-top:5px');
				}
			}).submit();
		});
	", CClientScript::POS_READY);
}
?>