<?php
$this->pageTitle = Yii::app()->name;
?>

<article class="data-block">
	<div class="pull-right">
		<a href="<?php echo Yii::app()->createUrl('admin/sucursales'); ?>" class="btn btn-info">Regresar</a>
	</div>
	<h2>
		Actualizar datos de Sucursal
	</h2>
	<hr />
	<section>
		<?php $form=$this->beginWidget('CActiveForm', array(
		    'id'=>'sucursales-form',
		    'enableAjaxValidation'=>false,
		)); ?>

		<div class="row-fluid">
			<div class="span12">
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
		</div>

		<div class="row-fluid">
			<div class="span12">
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

		<div class="row-fluid">
			<div class="span12">
				<div class="control-group <?php echo (strlen($form->error($model,'empresas_id'))>0) ? 'error' : null; ?>">
					<?php echo $form->labelEx($model,'empresas_id', array('class'=>'control-label')); ?>
					<div class="controls">

						<?php echo $form->dropDownList($model, 'empresas_id', CHtml::listData(Empresas::model()->findAll(), 'id', 'nombre')); ?>						
						<span class="help-inline">
							<?php echo $form->error($model,'empresas_id'); ?>
						</span>
					</div>
				</div>
			</div>
		</div>

		<div class="form-actions">
	        <?php echo CHtml::submitButton('Guardar', array('class'=>'btn btn-primary')); ?>
			<a href="<?php echo Yii::app()->createUrl('admin/sucursales'); ?>" class="btn">Regresar</a>
		</div>

		<?php $this->endWidget(); ?>
	</section>
</article>