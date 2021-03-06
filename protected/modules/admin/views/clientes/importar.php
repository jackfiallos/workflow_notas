<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Importar archivo de clientes</h1>
    <span class="pagedesc">Importar archivo de clientes</span>
    <div class="pull-right" style="margin: 0 10px;">
		<a href="<?php echo Yii::app()->createUrl('admin/clientes/index'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-home"></i> Regresar</a>
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

	<?php $form=$this->beginWidget('CActiveForm', array(
	    'action'=>Yii::app()->createUrl($this->route),
	    'method'=>'POST',
	    'enableAjaxValidation' => false,
		'htmlOptions' => array(
			'enctype' => 'multipart/form-data'
		)
	)); ?>
	<div class="pure-g" style="margin-bottom:20px">
		<div class="pure-u-1-3">
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
		<div class="pure-u-1-3">
			<div class="control-group <?php echo (!is_null($model->getError('empresa_id')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'empresa_id', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->dropDownList($model, 'empresa_id', $empresas, array('class'=>'span12', 'empty'=>'Seleccionar Empresa')); ?>
					<span class="help-inline">
						<?php echo $form->error($model, 'empresa_id'); ?>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="form-actions" style="text-align:right; margin-top:20px">
		<button type="submit" class="button-large pure-button-primary pure-button">Cargar</button>
		<a href="<?php echo Yii::app()->createUrl('admin/clientes/index'); ?>" class="button-large button-secondary pure-button">Cancelar</a>
	</div>
	<?php $this->endWidget(); ?>
</div>