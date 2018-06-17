<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Actualizar datos del cliente</h1>
    <span class="pagedesc">Actualizar datos del cliente</span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/clientes'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-home"></i> Regresar</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">

	<?php $form=$this->beginWidget('CActiveForm', array(
	    'id'=>'clientes-form',
	    'enableAjaxValidation'=>false,
	)); ?>

	<div class="pure-g" style="margin-bottom:20px">
		<div class="pure-u-1-3">
			<div class="control-group <?php echo (strlen($form->error($model,'codigo'))>0) ? 'error' : null; ?>">
				<?php echo $form->labelEx($model,'codigo', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'codigo', array('class'=>'input-normal')); ?>
					<span class="help-inline">
						<?php echo $form->error($model,'codigo'); ?>
					</span>
				</div>
			</div>
		</div>
		<div class="pure-u-1-3">
			<div class="control-group <?php echo (strlen($form->error($model,'nombre'))>0) ? 'error' : null; ?>">
				<?php echo $form->labelEx($model,'nombre', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'nombre', array('class'=>'input-normal')); ?>
					<span class="help-inline">
						<?php echo $form->error($model,'nombre'); ?>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="pure-g" style="margin-bottom:20px">
		<div class="pure-u-2-5">
			<div class="control-group <?php echo (strlen($form->error($model,'empresas_id'))>0) ? 'error' : null; ?>">
				<?php echo CHtml::label('Empresas Disponibles','listaEmpresas', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo CHtml::listBox('listaEmpresas', null, $empresas, array('multiple'=>'multiple', 'style'=> 'width:100%; height:125px;')); ?>
				</div>
			</div>
		</div>
		<div class="pure-u-1-5" style="padding-top:30px; text-align:center;">
            <a style="display:block; width:15px; margin:0 auto;" class="pure-button move-items" move-to="right">&#62;&#62;</a>
            <a style="display:block; width:15px; margin:0 auto; margin-top:10px;" class="pure-button move-items" move-to="left">&#60;&#60;</a>
        </div>
        <div class="pure-u-2-5">
			<div class="control-group <?php echo (strlen($form->error($model,'empresa'))>0) ? 'error' : null; ?>">
				<?php echo CHtml::label('Empresas Seleccionadas', CHtml::activeId($model, 'empresa'), array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->listBox($model, 'empresa', $empresasSeleccionadas, array('multiple'=>'multiple', 'style'=> 'width:100%; height:125px;')); ?>
					<span class="help-inline">
						<?php echo $form->error($model,'empresa'); ?>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="form-actions" style="text-align:right; margin-top:20px">
        <?php echo CHtml::submitButton('Guardar', array('class'=>'button-large pure-button-primary pure-button')); ?>
		<a href="<?php echo Yii::app()->createUrl('admin/clientes'); ?>" class="button-large button-secondary pure-button">Regresar</a>
	</div>

	<?php $this->endWidget(); ?>
</div>

<?php
$cs=Yii::app()->clientScript;
$cs->registerScript('scriptUpdateUsuarios',"

	$('.btn-primary').on('click', function(e) {
		e.preventDefault();
		$('#".CHtml::activeId($model, 'empresa')." option').prop('selected', true);
		$('#clientes-form').submit();
	});

    $(document).on('click', 'a.move-items', function(e){
        e.preventDefault();

        if ($(this).attr('move-to') == 'right') {
			$('#listaEmpresas :selected').each(function(index, option) { 
	            if ($('#".CHtml::activeId($model, 'empresa')."').find('option').length === 0) {
	                $('#".CHtml::activeId($model, 'empresa')."').html(option);
	            }
	            else {
	                $('#".CHtml::activeId($model, 'empresa')."').find('option:last').after(option);
	            }
	        });
        }
        else if ($(this).attr('move-to') == 'left') {
			$('#".CHtml::activeId($model, 'empresa')." :selected').each(function(index, option) { 
	            if ($('#listaEmpresas').find('option').length === 0) {
	                $('#listaEmpresas').html(option);
	            }
	            else {
	                $('#listaEmpresas').find('option:last').after(option);
	            }
	        });
        }
    });
", CClientScript::POS_END);
?>