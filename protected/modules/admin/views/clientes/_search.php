<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

<div class="pure-g">
    <div class="pure-u-1-5">
        <div class="control-group">
            <?php echo $form->labelEx($model,'codigo', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'codigo', array('class'=>'span12', 'size'=>15, 'maxlength'=>15)); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-5">
        <div class="control-group">
            <?php echo $form->labelEx($model,'nombre', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'nombre', array('class'=>'span12', 'size'=>20, 'maxlength'=>20)); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-5">
        <div class="control-group">
            <?php echo $form->labelEx($model,'empresa', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'empresa', $empresas, array('class'=>'span12', 'empty'=>'Seleccionar Empresa')); ?>
            </div>
        </div>
    </div>
</div>
<div class="pure-g">
    <div class="pure-u-5-5" style="margin-top: 29px;">
        <?php echo CHtml::submitButton('Filtrar', array('class'=>'button-large pure-button-primary pure-button')); ?>
        <a href="<?php echo Yii::app()->createUrl('admin/clientes/index'); ?>" class="button-large button-secondary pure-button">Limpiar</a>
    </div>
</div>

<?php $this->endWidget(); ?>