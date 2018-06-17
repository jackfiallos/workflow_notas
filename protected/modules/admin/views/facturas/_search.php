<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

<div class="pure-g">
    <div class="pure-u-1-3">
        <div class="control-group <?php echo (!is_null($model->getError('orden_compra')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'orden_compra', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'orden_compra', array('class'=>'span12', 'size'=>15, 'maxlength'=>15)); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-3">
        <div class="control-group <?php echo (!is_null($model->getError('folio')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'folio', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'folio', array('class'=>'span12', 'size'=>20, 'maxlength'=>20)); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-3">
        <div class="control-group <?php echo (!is_null($model->getError('clientes_codigo')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'clientes_codigo', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'clientes_codigo', $clientes, array('class'=>'span12', 'empty'=>'Seleccionar Cliente')); ?>
            </div>
        </div>
    </div>
</div>
<div class="pure-g">
    <div class="pure-u-5-5" style="margin-top: 29px;">
        <?php echo CHtml::submitButton('Filtrar', array('class'=>'button-large pure-button-primary pure-button')); ?>
        <a href="<?php echo Yii::app()->createUrl('admin/facturas/index'); ?>" class="button-large button-secondary pure-button">Limpiar</a>
    </div>
</div>

<?php $this->endWidget(); ?>