<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Actualizar datos de la Empresa</h1>
    <span class="pagedesc">Actualizar datos de la Empresa</span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/empresas'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-home"></i> Regresar</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">

	<?php $form=$this->beginWidget('CActiveForm', array(
	    'id'=>'empresas-form',
	    'enableAjaxValidation'=>false,
	)); ?>

	<div class="pure-g" style="margin-bottom:20px">
		<div class="pure-u-5-5">
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

	<div class="form-actions" style="text-align:right; margin-top:20px">
        <?php echo CHtml::submitButton('Guardar', array('class'=>'button-large pure-button-primary pure-button')); ?>
		<a href="<?php echo Yii::app()->createUrl('admin/empresas'); ?>" class="button-large button-secondary pure-button">Regresar</a>
	</div>

	<?php $this->endWidget(); ?>
</div>