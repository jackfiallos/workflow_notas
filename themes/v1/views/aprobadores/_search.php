<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

<div class="row-fluid">
    <div class="span3">
       <div class="control-group">
                <?php echo CHtml::label( Notas::model()->getAttributeLabel('filterUsuario') ,'',array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo CHtml::textField('filterUsuario',$filterUsuario,array('class'=>'span12')) ?>
                </div>
            </div>
    </div>
    <div class="span3">
       <div class="control-group">
                <?php echo CHtml::label( Notas::model()->getAttributeLabel('filterCliente') ,'',array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo CHtml::textField('filterCliente',$filterCliente,array('class'=>'span12')) ?>
                </div>
            </div>
    </div>
    <div class="span3">
       <div class="control-group">
                <?php echo CHtml::label( Notas::model()->getAttributeLabel('filterCaracteristica') ,'',array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo CHtml::dropDownList('filterCaracteristica',$filterCaracteristica,
                        array( 
                            Caracteristicas::ALMACEN=> 'Almacén' , 
                            Caracteristicas::DESCUENTOS=> 'Descuentos' , 
                            Caracteristicas::COOPERACION=> 'Cooperación' , 
                            Caracteristicas::REFACTURACION  => 'Refacturación' ),
                        array('prompt'=>'Selecciona opción','class'=>'span12')) ?>
                        
                </div>
            </div>
    </div>
    <div class="span3" style="margin-top: 29px;">
        <?php echo CHtml::submitButton('Filtrar', array('class'=>'btn btn-primary btn-small')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>