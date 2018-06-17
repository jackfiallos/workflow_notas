<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

<div class="pure-g">
    <div class="pure-u-1-3">
        <div class="control-group">
            <?php echo $form->labelEx($model,'codigo', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'codigo', array('class'=>'span12', 'size'=>15, 'maxlength'=>15)); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-3">
        <div class="control-group">
            <?php echo $form->labelEx($model,'descripcion', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'descripcion', array('class'=>'span12', 'size'=>20, 'maxlength'=>20)); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-3">
        <div class="control-group">
            <?php echo $form->labelEx($model,'empresas_id', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'empresas_id', $empresas, array('class'=>'span12', 'empty'=>'Seleccionar Empresa')); ?>
            </div>
        </div>
    </div>
</div>
<div class="pure-g">
    <div class="pure-u-5-5" style="margin-top: 29px;">
        <?php echo CHtml::submitButton('Filtrar', array('class'=>'button-large pure-button-primary pure-button')); ?>
        <a href="<?php echo Yii::app()->createUrl('admin/productos/index'); ?>" class="button-large button-secondary pure-button">Limpiar</a>
    </div>
</div>

<?php $this->endWidget(); ?>