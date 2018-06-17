<?php $form=$this->beginWidget('CActiveForm', array(
	    'id'=>'marcas-form',
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
			<div class="control-group <?php echo (strlen($form->error($model,'cuenta_cooperacion'))>0) ? 'error' : null; ?>">
				<?php echo $form->labelEx($model,'cuenta_cooperacion', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'cuenta_cooperacion', array('class'=>'input-normal')); ?>
					<span class="help-inline">
						<?php echo $form->error($model,'cuenta_cooperacion'); ?>
					</span>
				</div>
			</div>
		</div>
		<div class="pure-u-1-3">
			<div class="control-group <?php echo (strlen($form->error($model,'cuenta_descuento'))>0) ? 'error' : null; ?>">
				<?php echo $form->labelEx($model,'cuenta_descuento', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'cuenta_descuento', array('class'=>'input-normal')); ?>
					<span class="help-inline">
						<?php echo $form->error($model,'cuenta_descuento'); ?>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="pure-g" style="margin-bottom:20px">
		<div class="pure-u-1-3">
			<div class="control-group <?php echo (strlen($form->error($model,'marca'))>0) ? 'error' : null; ?>">
				<?php echo $form->labelEx($model,'marca', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'marca', array('class'=>'input-normal')); ?>
					<span class="help-inline">
						<?php echo $form->error($model,'marca'); ?>
					</span>
				</div>
			</div>
		</div>
		<div class="pure-u-1-3">
			<div class="control-group <?php echo (strlen($form->error($model,'empresas_id'))>0) ? 'error' : null; ?>">
				<?php echo $form->labelEx($model,'empresas_id', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->dropDownList($model, 'empresas_id', CHtml::listData(Empresas::model()->findAll(), 'id', 'nombre'), array('empty' => 'Seleccione una Empresa')); ?>
					<span class="help-inline">
						<?php echo $form->error($model,'empresas_id'); ?>
					</span>
				</div>
			</div>
		</div>
		<div class="pure-u-1-3">
			<div class="control-group <?php echo (strlen($form->error($model,'es_gama'))>0) ? 'error' : null; ?>">
				<?php echo $form->labelEx($model,'es_gama', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->checkBox($model,'es_gama'); ?>
					<span class="help-inline">
						<?php echo $form->error($model,'es_gama'); ?>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="pure-g" style="margin-bottom:20px">
		<div class="pure-u-1-3">
			<div class="control-group <?php echo (strlen($form->error($model,'identificador'))>0) ? 'error' : null; ?>">
				<?php echo $form->labelEx($model,'identificador', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'identificador', array('class'=>'input-normal')); ?>
					<span class="help-inline">
						<?php echo $form->error($model,'identificador'); ?>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="form-actions" style="text-align:right; margin-top:20px">
        <?php echo CHtml::submitButton('Guardar', array('class'=>'button-large pure-button-primary pure-button')); ?>
		<a href="<?php echo Yii::app()->createUrl('admin/marcas'); ?>" class="button-large button-secondary pure-button">Regresar</a>
	</div>

<?php $this->endWidget(); ?>