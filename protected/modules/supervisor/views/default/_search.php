<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'post',
)); ?>

<div class="pure-g">
    <div class="pure-u-1-5">
       <div class="control-group">
            <?php echo $form->label($model, 'id'); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'id'); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-5">
       <div class="control-group">
            <?php echo $form->label($model, 'num_factura'); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'num_factura'); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-5">
       <div class="control-group">
            <?php echo $form->label($model, 'usuarios_id'); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'usuarios_id', $usuarios, array('class'=>'chosen-select', 'empty'=>'Seleccionar', 'data-placeholder'=>'Selecciona un Usuario')); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-5">
       <div class="control-group">
            <?php echo $form->label($model, 'clientes_codigo'); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'clientes_codigo', $clientes, array('class'=>'chosen-select', 'empty'=>'Seleccionar', 'data-placeholder'=>'Selecciona un Cliente')); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-5">
       <div class="control-group">
            <?php echo $form->label($model, 'razones_id'); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'razones_id', array( 
                        Caracteristicas::ALMACEN=> 'Almacén' , 
                        Caracteristicas::DESCUENTOS=> 'Descuentos' , 
                        Caracteristicas::COOPERACION=> 'Cooperación' , 
                        Caracteristicas::REFACTURACION  => 'Refacturación'), 
                    array('class'=>'chosen-select', 'empty'=>'Seleccionar', 'data-placeholder'=>'Selecciona un tipo'));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="pure-g">
    <div class="pure-u-5-5" style="margin-top: 29px; text-align:right">
        <?php echo CHtml::submitButton('Filtrar', array('class'=>'button-large pure-button-primary pure-button')); ?>
        <a href="<?php echo Yii::app()->createUrl('supervisor/default/revisar'); ?>" class="button-large button-secondary pure-button">Limpiar</a>
    </div>
</div>

<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/chosen.jquery.min.js',CClientScript::POS_END);
$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/chosen/chosen.min');
$cs->registerScript('solicitudes.jquery.main.create', "
    $('.chosen-select').chosen({
        no_results_text: 'No se encontraron resultados para ', 
        width:'200px',
        search_contains: true
    });
", CClientScript::POS_READY);
?>