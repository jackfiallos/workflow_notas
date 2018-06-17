<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Estad&iacute;sticas de Control</h1>
    <span class="pagedesc">Seleccione un rango de fechas</span>
</div>
<div id="contentwrapper" class="contentwrapper">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'graficas-form',
        'enableAjaxValidation'=>false,
    )); ?>
        <div class="pure-g">
            <div class="pure-u-1-3">
               <div class="control-group">
                    <?php echo $form->labelEx($model,'fechaini', array('class'=>'control-label')); ?>
                    <div class="controls">
                        <?php echo $form->textField($model, 'fechaini', array('id'=>'from','class'=>'input-normal')); ?>
                    </div>
                </div>
            </div>
            <div class="pure-u-1-3">
               <div class="control-group">
                    <?php echo $form->labelEx($model,'fechafin', array('class'=>'control-label')); ?>
                    <div class="controls">
                        <?php echo $form->textField($model, 'fechafin', array('id'=>'to','class'=>'input-normal')); ?>
                    </div>
                </div>
            </div>
            <div class="pure-u-1-3" style="padding-top: 20px;">
                <?php echo CHtml::submitButton('Graficar', array('id'=>'Graficar','class'=>'btn btn-primary')); ?>
            </div>
        </div>
    <?php $this->endWidget(); ?> 

    <hr />

    <section>
        <div class="row-fluid">
            <div  id="tiempopromedio">
                <h2>
                    Tiempo promedio que les toma a las notas desde su generacion hasta su cierre
                    <?php echo $promedio['valores']; ?>
                </h2>
            </div>
        </div>
    </section>
    <br />

    <section>
        <div class="row-fluid">
            <div  id="caracteristicas">
                <table>
                    <tr>
                        <td valign="top" style="padding-top: 5px; font-size: 12px;">  
                            <div style="display:table-cell; padding: 10px; border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto;">
                                <div style='font-size: 10px;' id="render01">
                                    <img class="imgresponsive" src="<?php echo Yii::app()->createUrl('supervisor/default/NotasCreditoXCaract'); ?>" alt="Notas generadas por característica" />
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
    <br />

    <section>
        <div class="row-fluid">
            <div  id="envionotas">
                <table>
                    <tr>
                        <td valign="top" style="padding-top: 5px; font-size: 12px;">  
                            <div style="display:table-cell; padding: 10px; border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto;">
                                <div style="font-size: 10px;" id="render02">
                                    <img class="imgresponsive" src="<?php echo Yii::app()->createUrl('supervisor/default/NotasCreditoEnviadas'); ?>" alt="Notas enviadas por Usuario" />
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
    <br />

    <section>
        <div class="row-fluid">
            <div  id="aprobadasvsrechazadas">
                <table>
                    <tr>
                        <td valign="top" style="padding-top: 5px; font-size: 12px;">  
                            <div style="display:table-cell; padding: 10px; border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto;">
                                <div style="font-size: 10px;" id="render03">   
                                    <img class="imgresponsive" src="<?php echo Yii::app()->createUrl('supervisor/default/NotasCreditoEstatus'); ?>" alt="Notas Aprobadas vs Rechazadas" />
                                </div>                     
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
</div>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/custom-theme/jquery-ui.css');
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ui.datepicker-es.js',CClientScript::POS_END);
$cs->registerScript('supevisor.jquery', "
    $('#from').datepicker({
        defaultDate: '+1w',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        showButtonPanel: true,
        dateFormat: 'dd-mm-yy',
        showAnim: 'clip',
        onClose: function(selectedDate) {
            $('#to').datepicker('option', 'minDate', selectedDate);
        }
    });           

    $('#to').datepicker({
        defaultDate: '+1w',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        showButtonPanel: true,
        dateFormat: 'dd-mm-yy',
        showAnim: 'clip',
        onClose: function(selectedDate) {
            $('#from').datepicker('option', 'maxDate', selectedDate);
        }
    });
", CClientScript::POS_READY);
?>