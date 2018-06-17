<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'caracteristicastipo-form',
        'enableAjaxValidation'=>false,
    )); ?>

    <div class="pure-g" style="margin-bottom:20px">
        <div class="pure-u-5-5">
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

    <div class="pure-g" style="margin-bottom:20px">
        <div class="pure-u-5-5">
            <div class="control-group <?php echo (strlen($form->error($model,'caracteristicas_id'))>0) ? 'error' : null; ?>">
                <?php echo $form->labelEx($model,'caracteristicas_id', array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo $form->dropDownList($model, 'caracteristicas_id', $caracteristicas); ?>                      
                    <span class="help-inline">
                        <?php echo $form->error($model,'caracteristicas_id'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions" style="text-align:right; margin-top:20px">
        <?php echo CHtml::submitButton('Guardar', array('class'=>'button-large pure-button-primary pure-button')); ?>
        <a href="<?php echo Yii::app()->createUrl('admin/caracteristicastipo'); ?>" class="button-large button-secondary pure-button">Regresar</a>
    </div>

    <?php $this->endWidget(); ?>