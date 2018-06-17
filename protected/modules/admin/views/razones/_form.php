<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'razones-form',
    'focus' => array($model, 'nombre'),
    'htmlOptions' => array(
        'class' => '',
    ),
    'enableClientValidation'=>false
)); ?>

    <div class="pure-g" style="margin-bottom:20px">
        <div class="pure-u-1-3">
            <div class="control-group <?php echo (!is_null($model->getError('nombre')) ? 'error' : ''); ?>">
                <?php echo $form->labelEx($model,'nombre', array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo $form->textField($model, 'nombre', array('class'=>'span12','maxlength'=>15)); ?>
                    <span class="help-inline">
                        <?php echo $form->error($model,'nombre'); ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="pure-u-1-3">
            <div class="control-group <?php echo (!is_null($model->getError('codigo')) ? 'error' : ''); ?>">
                <?php echo $form->labelEx($model,'codigo', array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo $form->textField($model, 'codigo', array('class'=>'span12','maxlength'=>100)); ?>
                    <span class="help-inline">
                        <?php echo $form->error($model,'codigo'); ?>
                    </span>
                </div>
            </div>
        </div>  
    </div>
    
    <div class="pure-g" style="margin-bottom:20px">
        <div class="pure-u-1-3">
            <div class="control-group <?php echo (strlen($form->error($model,'cuenta'))>0) ? 'error' : null; ?>">
                <?php echo $form->labelEx($model,'cuenta', array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo $form->textField($model, 'cuenta', array('class'=>'span12','maxlength'=>10)); ?>
                    <span class="help-inline">
                        <?php echo $form->error($model,'cuenta'); ?>
                    </span>
                </div>
            </div>
        </div> 
        <div class="pure-u-1-3">
            <div class="control-group <?php echo (strlen($form->error($model,'caracteristicas_id'))>0) ? 'error' : null; ?>">
                <?php echo $form->labelEx($model,'caracteristicasTipo_id', array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo $form->dropDownList($model, 'caracteristicasTipo_id', $caracteristicas, array('class'=>'span12')); ?>
                    <span class="help-inline">
                        <?php echo $form->error($model,'caracteristicasTipo_id'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions" style="text-align:right; margin-top:20px">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', array('class'=>'button-large pure-button-primary pure-button')); ?>
        <a href="<?php echo Yii::app()->createUrl('admin/razones/index'); ?>" class="button-large button-secondary pure-button">Cancelar</a>
    </div>

<?php $this->endWidget(); ?>

