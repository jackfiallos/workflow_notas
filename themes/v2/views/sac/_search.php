<fieldset>
    <legend>
        <h3>Filtro de B&uacute;squeda</h3>
    </legend>
    <?php $form=$this->beginWidget('CActiveForm', array(
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'post',
    )); ?>
    <div class="pure-g">
        <div class="pure-u-1-4">
           <div class="control-group">
                <?php echo $form->label($model,'id'); ?>
                <div class="controls">
                    <?php echo $form->textField($model,'id'); ?>
                </div>
            </div>
        </div>
        <div class="pure-u-1-4">
           <div class="control-group">
                <?php echo $form->label($model,'num_factura'); ?>
                <div class="controls">
                    <?php echo $form->textField($model,'num_factura'); ?>
                </div>
            </div>
        </div>
        <div class="pure-u-1-4">
           <div class="control-group">
                <?php echo $form->label($model,'clientes_codigo'); ?>
                <div class="controls">
                    <?php echo $form->dropDownList($model, 'clientes_codigo', $clientes, array('class'=>'chosen-select', 'empty'=>'Seleccionar', 'data-placeholder'=>'Selecciona un Cliente')); ?>
                </div>
            </div>
        </div>
        <div class="pure-u-1-4" style="padding-top:20px">
            <?php echo CHtml::submitButton('Filtrar', array('class'=>'button-large pure-button-primary pure-button')); ?>
            <a href="<?php echo Yii::app()->createUrl('sac'); ?>" class="button-large button-secondary pure-button">Limpiar</a> 
        </div>
    </div>
    <?php $this->endWidget(); ?>
</fieldset>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/chosen.jquery.min.js',CClientScript::POS_END);
$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/chosen/chosen.min');
$cs->registerScript('solicitudes.jquery.main.create', "
    $('.chosen-select').chosen({
        no_results_text: 'No se encontraron resultados para ', 
        width:'220px',
        search_contains: true
    });
", CClientScript::POS_READY);
?>