<?php
$form=$this->beginWidget('CActiveForm', array(
	'id' => 'solicitudes-form',
  	'htmlOptions' => array(
  		'class' => 'pure-form pure-form-stacked',
  		'autocomplete' => 'off'
  	),
	'enableClientValidation'=>false
));
?>

<div class="contenttitle2">
	<h3>Datos Generales de la Solicitud</h3>
</div>

<div class="pure-g" style="margin-bottom: 20px;">
    <div class="pure-u-1-3">
    	<div class="control-group <?php echo (!is_null($model->getError('caracteristica')) ? 'error' : ''); ?>">
	    	<?php echo $form->labelEx($model,'caracteristica', array('class'=>'control-label')); ?>
	    	<div class="controls">
		    	<?php echo $form->dropDownList($model, 'caracteristica', $caracteristicas, array('empty'=>'Seleccionar',
				'ajax' => array(
					'type'=>'POST',
					'url'=>CController::createUrl('solicitudes/SelectRazon'),
					'update'=>'#'.CHtml::activeId($model, 'razon'),
					'data'=>'js:$("#solicitudes-form").serialize()'
				))); ?>
			</div>
		</div>
    </div>
    <div class="pure-u-1-3">
    	<div class="control-group <?php echo (!is_null($model->getError('razon')) ? 'error' : ''); ?>">
    		<?php echo $form->labelEx($model,'razon', array('class'=>'control-label')); ?>
    		<div class="controls">
    			<?php echo $form->dropDownList($model, 'razon', $razones, array('empty'=>'Seleccionar')); ?>
    		</div>
    	</div>
    </div>
    <div class="pure-u-1-3">
    	<div class="control-group <?php echo (!is_null($model->getError('cliente')) ? 'error' : ''); ?>">
    		<?php echo $form->labelEx($model,'cliente', array('class'=>'control-label')); ?>
    		<div class="controls">
    			<?php echo $form->dropDownList($model, 'cliente', $clientes, array('class'=>'chosen-select', 'empty'=>'Seleccionar', 'data-placeholder'=>'Selecciona un Cliente')); ?>
    		</div>
    	</div>
    </div>
</div>

<div class="pure-g" style="margin-bottom: 20px;">
	<div class="pure-u-1-3">
		<div class="control-group <?php echo (!is_null($model->getError('sucursal')) ? 'error' : ''); ?>">
			<?php echo $form->labelEx($model,'sucursal', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo $form->dropDownList($model, 'sucursal', array(), array('empty'=>'Seleccionar Sucursal')); ?>
			</div>
		</div>
	</div>
	<div class="pure-u-1-3">
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
		<div class="pure-g">
			<div class="pure-u-1-2">
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
			<div class="pure-u-1-3">
				<div class="control-group <?php echo (!is_null($model->getError('mes_aplicacion')) ? 'error' : ''); ?>">
					<?php echo $form->labelEx($model,'mes_aplicacion', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->dropDownList($model, 'mes_aplicacion', array(
							1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
						), array('empty'=>'Seleccionar un mes')); ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<?php if ($model->tipo_nota == Caracteristicas::ALMACEN): ?>
	<div class="pure-u-1-3">
		<div class="control-group <?php echo (!is_null($model->getError('anio')) ? 'error' : ''); ?>">
			<?php echo $form->labelEx($model,'anio', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo $form->dropDownList($model, 'anio', $anios, array('empty'=>'Seleccionar',
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

	<?php if (($model->tipo_nota == Caracteristicas::DESCUENTOS) || ($model->tipo_nota == Caracteristicas::PROVISION)): ?>
	<div class="pure-u-1-3">
		<div class="pure-g">
			<div class="pure-u-1-2">
				<div class="control-group <?php echo (!is_null($model->getError('factura_id')) ? 'error' : ''); ?>">
					<?php echo $form->labelEx($model,'factura_id', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->textField($model, 'factura_id', array('class'=>'', 'style'=>'width:90%')); ?>
					</div>
				</div>
			</div>
			<div class="pure-u-1-2">
				<div class="control-group <?php echo (!is_null($model->getError('importe')) ? 'error' : ''); ?>">
					<?php echo $form->labelEx($model,'importe', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->textField($model, 'importe', array('class'=>'numeric', 'style'=>'width:90%')); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($model->tipo_nota == Caracteristicas::COOPERACION): ?>
	<div class="pure-u-1-3">
		<div class="pure-g">
			<div class="pure-u-1-2">
				<div class="control-group <?php echo (!is_null($model->getError('importe')) ? 'error' : ''); ?>">
					<?php echo $form->labelEx($model,'importe', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->textField($model, 'importe', array('class'=>'numeric', 'style'=>'width:90%')); ?>
					</div>
				</div>
			</div>
			<div class="pure-u-1-2">
				<div class="control-group <?php echo (!is_null($model->getError('mes_aplicacion')) ? 'error' : ''); ?>">
					<?php echo $form->labelEx($model,'mes_aplicacion', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->dropDownList($model, 'mes_aplicacion', array(
							1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
						), array('class'=>'', 'empty'=>'Seleccionar un mes', 'style'=>'width:90%')); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($model->tipo_nota == Caracteristicas::REFACTURACION): ?>
	<div class="pure-u-1-3">
		<div class="control-group <?php echo (!is_null($model->getError('factura_id')) ? 'error' : ''); ?>">
			<?php echo $form->labelEx($model,'factura_id', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo $form->textField($model, 'factura_id', array('class'=>'')); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

<div class="pure-g" style="margin-bottom: 20px;">
	<?php if($update && $model->tipo_nota != Caracteristicas::REFACTURACION && $model->tipo_nota != Caracteristicas::DESCUENTOS && $model->tipo_nota != Caracteristicas::COOPERACION): ?>
	<div class="pure-u-1-3">
		<div class="control-group <?php echo (!is_null($model->getError('factura_id')) ? 'error' : ''); ?>">
			<?php echo $form->labelEx($model,'factura_id', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo $form->textField($model, 'factura_id', array('class'=>'')); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if($model->tipo_nota == Caracteristicas::REFACTURACION): ?>
	<div class="pure-u-1-3">
		<label class="checkbox inline" style="font-size: 15px;">
			<?php
			echo $form->checkBox($model, 'cancela_sustituye', array('style'=>'width:20px; height:20px;'));
			echo "&nbsp;".$model->getAttributeLabel('cancela_sustituye');
			?>
		</label>
	</div>
	<?php endif; ?>
</div>

<div class="right">
	<button class="btn btn-primary btnsave">Guardar Cambios</button>
</div>

<hr />

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
			'jsonDataMarcas' => $jsonDataMarcas,
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
<div>
	<div class="contenttitle2">
		<h3>Documentos Adjuntos</h3>
	</div>
	<div class="pure-g">
		<div class="pure-u-5-5">
			<?php $form = $this->beginWidget('CActiveForm',array(
				'id' => 'imageform',
				'enableAjaxValidation' => false,
				'action'=>CController::createUrl('solicitudes/Fileupload', array(
					'id'=>$id
				)),
				'method'=>'POST',
				'htmlOptions' => array(
					'enctype' => 'multipart/form-data',
					'style' => 'margin-bottom:10px'
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
				'itemsCssClass' => 'stdtable',
				'emptyText'=>'No se encontraron documentos',
				'enableSorting' => true,
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
						'headerHtmlOptions'=>array(
							'class' => 'head1'
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
						'headerHtmlOptions'=>array(
							'class' => 'head1'
						),
					),
					array(
						'class'=>'CButtonColumn',
						'template' => '{borrar}',
						'htmlOptions' => array(
							'style' => 'width:5%;'
						),
						'headerHtmlOptions'=>array(
							'class' => 'head1'
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
</div>

<div>
	<div class="contenttitle2">
		<h3>Comentarios y anotaciones</h3>
	</div>
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
	<div class="pure-g">
		<div class="pure-u-5-5">
			<div class="control-group">
				<?php echo $form->labelEx($model,'comentario', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textArea($model, 'comentario', array('class'=>'', 'rows'=>'5', 'style'=>'resize:none;', 'value'=>'')); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="form-actions" style="text-align:right; margin-top:20px">
		<button class="button-large pure-button-primary pure-button">Enviar</button>
		<a href="<?php echo Yii::app()->createUrl('solicitudes'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-home"></i> Regresar</a>
	</div>

	<?php $this->endWidget(); ?>
</div>
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
			factura_id = ui.item.label;
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
$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/chosen/chosen.min.css');
$cs->registerScript('solicitudes.jquery.main.create', "
	var specialKeys = new Array();
    specialKeys.push(8); //Backspace
    specialKeys.push(46); //Punto
    var unavailableDates = ".CJSON::encode(Yii::app()->params['diasNoDisponibles']).";

    $('.chosen-select').chosen({
    	no_results_text: 'No se encontraron resultados para ',
    	width:'320px',
    	search_contains: true
    });

	$('.numeric').on('keypress', function (e) {
		var keyCode = e.which ? e.which : e.keyCode;
		var ret = ((keyCode >= 48 && keyCode <= 57) || $.inArray(keyCode, specialKeys) != -1);
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
