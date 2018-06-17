<?php
$this->pageTitle = Yii::app()->name;
?>

<article class="data-block">
	<div class="pull-right">
		<a href="<?php echo Yii::app()->createUrl('admin/facturas/index'); ?>" class="btn btn-info">Regresar</a>
	</div>
	<h2>
		Importar archivo de empresas
	</h2>
	<hr />
	<section>
		<?php $form=$this->beginWidget('CActiveForm', array(
		    'action'=>Yii::app()->createUrl($this->route),
		    'method'=>'POST',
		    'enableAjaxValidation' => false,
			'htmlOptions' => array(
				'enctype' => 'multipart/form-data'
			)
		)); ?>
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group <?php echo (!is_null($model->getError('archivo')) ? 'error' : ''); ?>">
					<?php echo $form->labelEx($model,'archivo', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->fileField($model, 'archivo'); ?>
						<span class="help-inline">
							<?php echo $form->error($model, 'archivo'); ?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Cargar</button>
			<a class="btn" href="<?php echo Yii::app()->createUrl('admin/empresas/index'); ?>" class="btn btn-info">Cancelar</a>
		</div>
		<?php $this->endWidget(); ?>
	</section>
</article>

<?php
Yii::app()->clientScript->registerScript('admin.empresas.importar', "
	
");
?>