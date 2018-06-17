<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'usuarios-form',
    'focus' => array($model, 'username'),
    'htmlOptions' => array(
        'class' => '',
    ),
    'enableClientValidation'=>false
)); ?>

<div class="contenttitle2">
    <h3>Informaci&oacute;n general de la cuenta</h3>
</div>
<div class="pure-g" style="margin-bottom:20px">
    <div class="pure-u-8-24">
        <div class="control-group <?php echo (!is_null($model->getError('username')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'username', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'username', array('class'=>'span12','maxlength'=>15)); ?>
                <span class="help-inline">
                    <?php echo $form->error($model,'username'); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="pure-u-8-24">
        <div class="control-group <?php echo (!is_null($model->getError('nombre')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'nombre', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'nombre', array('class'=>'span12','maxlength'=>100)); ?>
                <span class="help-inline">
                    <?php echo $form->error($model,'nombre'); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="pure-u-8-24">
        <div class="control-group <?php echo (!is_null($model->getError('correo')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'correo', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'correo', array('class'=>'span12','maxlength'=>100)); ?>
                <span class="help-inline">
                    <?php echo $form->error($model,'correo'); ?>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="pure-g" style="margin-bottom:20px">
    <div class="pure-u-8-24">
        <div class="control-group <?php echo (!is_null($model->getError('clave')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'clave', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->passwordField($model, 'clave', array('class'=>'span12','maxlength'=>100)); ?>
                <span class="help-inline">
                    <?php echo $form->error($model,'clave'); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="pure-u-8-24">
        <div class="control-group <?php echo (!is_null($model->getError('estatus')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'estatus', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'estatus', array(
                    Usuarios::ACTIVO => 'Activo',
                    Usuarios::INACTIVO => 'Inactivo'
                ), array('class'=>'span12', 'empty'=>'Seleccionar Estatus')); ?>
                 <span class="help-inline">
                    <?php echo $form->error($model,'estatus'); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="pure-u-8-24">
        <div class="control-group <?php echo (!is_null($model->getError('grupos_id')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'grupos_id', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'grupos_id', CHtml::listData($grupos, 'id', 'nombre'), array('class'=>'span12', 'empty'=>'Seleccionar un Grupo')); ?>
                 <span class="help-inline">
                    <?php echo $form->error($model,'grupos_id'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="contenttitle2">
    <h3>Empresas Relacionadas</h3>
</div>
<div class="pure-g" style="margin-bottom:20px">
    <div class="pure-u-2-5">
        <div class="control-group">
            <?php echo $form->labelEx($model,'EmpresasDisponibles', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo CHtml::listBox(null, 'EmpresasSeleccionadas',
                   CHtml::listData( $empresasDisponibles, 'id','nombre'),
                   array('multiple'=>'multiple','style'=> 'width: 100%;','id'=>'EmpresasDisponibles'));
                ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-5" style="padding-top: 30px;">
        <a style="display:block; width:15px; margin:0 auto;" class="pure-button move-items" data-type="rigth" data-form="#EmpresasSeleccionadasform" data-element="#EmpresasDisponibles" data-move="#EmpresasSeleccionadas" >&#62;&#62;</a>
        <a style="display:block; width:15px; margin:0 auto; margin-top:10px;" class="pure-button move-items" data-type="left" data-form="#EmpresasSeleccionadasform" data-element="#EmpresasSeleccionadas"  data-move="#EmpresasDisponibles" >&#60;&#60;</a>
    </div>
    <div class="pure-u-2-5">
        <div class="control-group <?php echo (!is_null($model->getError('EmpresasSeleccionadas')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'EmpresasSeleccionadas', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo CHtml::listBox(null, 'EmpresasSeleccionadas',
                   CHtml::listData( $empresasSeleccionadas, 'id', 'nombre'),
                   array('multiple'=>'multiple','style'=> 'width: 100%;','id'=>'EmpresasSeleccionadas'));
                ?>

                <?php echo $form->listBox($model, 'EmpresasSeleccionadas',
                   CHtml::listData( $empresasSeleccionadas, 'id','nombre'),
                   array('multiple'=>'multiple','style'=> 'width: 100%;display:none','id'=>'EmpresasSeleccionadasform'));
                ?>

                <span class="help-inline">
                    <?php echo $form->error($model,'EmpresasSeleccionadas'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="contenttitle2">
    <h3>Permisos para el Usuario</h3>
</div>
<div class="pure-g" style="margin-bottom:20px">
    <div class="pure-u-2-5">
        <div class="control-group">
            <?php echo $form->labelEx($model,'PermisosDisponibles', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo CHtml::listBox(null, 'PermisosDisponibles',
                   CHtml::listData( $permisosDisponibles, 'id', 'permiso'),
                   array('multiple'=>'multiple','style'=> 'width: 100%;','id'=>'PermisosDisponibles'));
                ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-5" style="padding-top:30px; text-align:center;">
        <a style="display:block; width:15px; margin:0 auto;" class="pure-button move-items" data-type="rigth" data-form="#PermisosDisponiblesform" data-element="#PermisosDisponibles" data-move="#PermisosSeleccionados">&#62;&#62;</a>
        <a style="display:block; width:15px; margin:0 auto; margin-top:10px;" class="pure-button move-items" data-type="left" data-form="#PermisosDisponiblesform" data-element="#PermisosSeleccionados" data-move="#PermisosDisponibles">&#60;&#60;</a>
    </div>
    <div class="pure-u-2-5">
        <div class="control-group <?php echo (!is_null($model->getError('PermisosSeleccionados')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'PermisosSeleccionados', array('class'=>'control-label')); ?>
            <div class="controls">

                <?php echo CHtml::listBox(null, 'PermisosSeleccionados',
                   CHtml::listData( $permisosSeleccionados, 'id','permiso'),
                   array('multiple'=>'multiple','style'=> 'width: 100%;','id'=>'PermisosSeleccionados'));
                ?>
                <?php echo $form->listBox($model, 'PermisosSeleccionados',
                   CHtml::listData( $permisosSeleccionados, 'id','permiso'),
                   array('multiple'=>'multiple','style'=> 'width: 100%;display:none','id'=>'PermisosDisponiblesform'));
                ?>

                <span class="help-inline">
                    <?php echo $form->error($model,'PermisosSeleccionados'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="contenttitle2">
    <h3>Clientes Asignados (aplicable s&oacute;lo para solicitantes)</h3>
</div>
<div class="pure-g" style="margin-bottom:20px">
    <div class="pure-u-2-5">
        <div class="control-group">
            <?php echo CHtml::label('Clientes Disponibles', 'lstClientes'); ?>
            <div class="controls">
                <?php echo CHtml::listBox('lstClientes', '', $lstClientes, array('multiple'=>'multiple','style'=> 'width: 100%;')); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-5" style="padding-top:30px; text-align:center;">
        <a style="display:block; width:15px; margin:0 auto;" data-from="lstClientes" data-to="<?php echo CHtml::activeId($model, 'clientes'); ?>" class="pure-button move-options" move-to="right">&#62;&#62;</a>
        <a style="display:block; width:15px; margin:0 auto; margin-top:10px;" data-from="lstClientes" data-to="<?php echo CHtml::activeId($model, 'clientes'); ?>" class="pure-button move-options" move-to="left">&#60;&#60;</a>
    </div>
    <div class="pure-u-2-5">
        <div class="control-group <?php echo (!is_null($model->getError('clientes')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'clientes', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->listBox($model, 'clientes', $lstClientesAsignados, array('multiple'=>'multiple','style'=> 'width: 100%')); ?>
                <span class="help-inline">
                    <?php echo $form->error($model,'clientes'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="contenttitle2">
    <h3>Solicitantes Asignados (aplicable s&oacute;lo para aprobadores)</h3>
</div>
<div class="pure-g" style="margin-bottom:20px">
    <div class="pure-u-2-5">
        <div class="control-group">
            <?php echo CHtml::label('Solicitantes Disponibles', 'lstSolicitantes'); ?>
            <div class="controls">
                <?php echo CHtml::listBox('lstSolicitantes', '', $lstSolicitantes, array('multiple'=>'multiple','style'=> 'width: 100%;')); ?>
            </div>
        </div>
    </div>
    <div class="pure-u-1-5" style="padding-top:30px; text-align:center;">
        <a style="display:block; width:15px; margin:0 auto;" data-from="lstSolicitantes" data-to="<?php echo CHtml::activeId($model, 'solicitantes'); ?>" class="pure-button move-options" move-to="right">&#62;&#62;</a>
        <a style="display:block; width:15px; margin:0 auto; margin-top:10px;" data-from="lstSolicitantes" data-to="<?php echo CHtml::activeId($model, 'solicitantes'); ?>" class="pure-button move-options" move-to="left">&#60;&#60;</a>
    </div>
    <div class="pure-u-2-5">
        <div class="control-group <?php echo (!is_null($model->getError('solicitantes')) ? 'error' : ''); ?>">
            <?php echo $form->labelEx($model,'solicitantes', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->listBox($model, 'solicitantes', $lstSolicitantesAsignados, array('multiple'=>'multiple','style'=> 'width: 100%')); ?>
                <span class="help-inline">
                    <?php echo $form->error($model,'solicitantes'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="contenttitle2">
    <h3>Nivel de Aprobaci&oacute;n (aplicable s&oacute;lo a aprobadores y supervisores)</h3>
</div>
<?php if (!is_null($form->error($model, 'FlujosSeleccionadas'))): ?>
<div class="evtMessage alert alert-danger">
    <?php echo $form->error($model,'FlujosSeleccionadas'); ?>
</div>
<?php endif; ?>

<div style="width:99%;">
    <table class="stdtable">
        <thead>
            <tr>
                <th class="head1" style="width: 20%;text-align: center;">Tipo de Nota</th>
                <th class="head1" style="width: 20%;text-align: center;">Raz&oacute;n</th>
                <th class="head1" style="width: 20%;text-align: center;">Expresi&oacute;n</th>
                <th class="head1" style="width: 10%;text-align: center;">Nivel</th>
                <th class="head1" style="width: 10%;text-align: center;">#</th>
            </tr>
        </thead>
    </table>
</div>
<div style="width:100%;height: 280px;overflow: auto;margin-top:0;">
    <table class="table">
        <tbody>
            <?php

                $paintAlmacen = false;
                $paintDescuento = false;
                $paintCooperacion = false;
                $paintRefacturacion = false;

                    while (list($key,$value) = each( $flujos) ) { ?>
                    <tr>
                        <?php if ($value->caracteristicas_tipo->caracteristicas_id  == Caracteristicas::ALMACEN &&
                                 !$paintAlmacen ) :
                                    $paintAlmacen = true;
                         ?>
                                </tr><td colspan="5" style="font-weight:bold; background-color:#D6E2E9">Notas de Cr&eacute;dito de Almac&eacute;n</td><tr>
                        <?php elseif ($value->caracteristicas_tipo->caracteristicas_id  == Caracteristicas::DESCUENTOS &&
                                 !$paintDescuento ) :
                                  $paintDescuento = true;
                        ?>
                                </tr><td colspan="5" style="font-weight:bold; background-color:#D6E2E9">Notas de Descuento Comercial</td><tr>
                        <?php elseif ($value->caracteristicas_tipo->caracteristicas_id  == Caracteristicas::COOPERACION &&
                                 !$paintCooperacion ) :
                                  $paintCooperacion = true;
                        ?>
                               </tr><td colspan="5" style="font-weight:bold; background-color:#D6E2E9">Notas de Cooperaci&oacute;n al Cliente</td><tr>
                        <?php elseif ($value->caracteristicas_tipo->caracteristicas_id  == Caracteristicas::REFACTURACION &&
                                 !$paintRefacturacion ) :
                                  $paintRefacturacion = true;
                        ?>
                                </tr><td colspan="5" style="font-weight:bold; background-color:#D6E2E9">Notas de Refacturaci&oacute;n</td><tr>
                        <?php endif; ?>

                        <td style="width: 20%;">
                            <label for="<?php echo 'flujo_'.$value->id;?>">
                                <?php echo (!empty( $value->caracteristicas_tipo ) ? '('.$value->caracteristicas_tipo->codigo.') '.$value->caracteristicas_tipo->nombre : '');?>
                            </label>
                        </td>
                        <td style="width: 20%;">
                            <label for="<?php echo 'flujo_'.$value->id;?>">
                                <?php echo (!empty( $value->caracteristicas_tipo ) ? $value->caracteristicas_tipo->razones->nombre : '');?>
                            </label>
                        </td>
                        <td style="width: 20%;text-align: center;">
                            <label for="<?php echo 'flujo_'.$value->id;?>"> <?php echo  $value->expresion;?> </label>
                        </td>
                        <td style="width: 10%;text-align: center;">
                            <label for="<?php echo 'flujo_'.$value->id;?>"> <?php echo  $value->nivel_aprobacion;?> </label>
                        </td>
                        <td style="width: 10%;text-align: center;">

                            <input id="<?php echo 'flujo_'.$value->id;?>"
                                   type="checkbox"
                                   name="Usuarios[FlujosSeleccionadas][]"
                                   <?php echo !( in_array($value->id, $model->FlujosSeleccionadas)) ? '' : 'checked'; ?>
                                   value="<?php echo $value->id;?>"/>

                        </td>
                    </tr>
            <?php }  ?>
        </tbody>
    </table>
</div>

<div class="form-actions" style="text-align:right; margin-top:20px">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', array('class'=>'button-large pure-button-primary pure-button')); ?>
    <a href="<?php echo Yii::app()->createUrl('admin/usuarios/index'); ?>" class="button-large button-secondary pure-button">Cancelar</a>
</div>

<?php $this->endWidget(); ?>
<?php
$cs=Yii::app()->clientScript;
$cs->registerScript('scriptCreateUsuarios',"

    // mostrar u ocultar las opciones de clientes
    /*var clientes = ".(CJSON::encode(CHtml::listData($permisosSeleccionados, 'id', 'permiso'))).";
    for (var i in clientes){
        if ((i == ".Usuarios::SOLICITUD.")) {
            $('.evtClientes').show();
        }
    }*/

    $('.pure-button-primary').on('click', function(e) {
        e.preventDefault();
        $('#".CHtml::activeId($model, 'clientes')." option').prop('selected', true);
        $('#".CHtml::activeId($model, 'solicitantes')." option').prop('selected', true);
        $('#usuarios-form').submit();
    });

    $(document).on('click', 'a.move-options', function(e){
        e.preventDefault();
        var el = $(this);
        if ($(this).attr('move-to') == 'right') {
            $('#'+el.attr('data-from')+' :selected').each(function(index, option) {
                $('#'+el.attr('data-to')+'').append(option);
            });
        }
        else if ($(this).attr('move-to') == 'left') {
            $('#'+el.attr('data-to')+' :selected').each(function(index, option) {
                $('#'+el.attr('data-from')+'').append(option);
            });
        }
    });

    $(document).on('click', 'a.move-items', function(e){
        e.preventDefault();
        var element = $(this).attr('data-element');
        var elementMove = $(this).attr('data-move');
        var elementform = $(this).attr('data-form');
        var elementSelected =  ( $(this).attr('data-type') == 'left' ) ? element : elementMove;
        var optionsSelected = new Array();

        $(element+' :selected').each(function(index, option ){
            optionsSelected.push( option );
        });

        for(var option in optionsSelected) {
            if( $(elementMove).find('option').length === 0 ){
                $(elementMove).html( optionsSelected[option] );
            }
            else{
                $(elementMove).find('option:last').after( optionsSelected[option] );
            }
        }

        var html = '';
        $(elementSelected).find('option').each(function(index, option ){
            html  += '<option value='+$(option).val()+ '>'+  $(option).text()    +'</option>' ;
        });

        $(elementform +' option').remove();
        $(elementform).html(html);
        $(elementform +' option').prop('selected', true);

        e.stopPropagation();
    });

    //$( $('a.move-items')[0] ).click();
    //$( $('a.move-items')[2] ).click();
");
?>
