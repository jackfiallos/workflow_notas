<?php
$this->pageTitle = Yii::app()->name . ' - Iniciar sesión';
?>

<div class="loginbox">
    <div class="loginboxinner"> <br /><br />
        <div align="center"><h3> NOTAS DE CR&Eacute;DITO </h3></div>
        <br clear="all" />
        <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'focus'=>array($model,'username'),
        'htmlOptions'=>array(
            'class'=>'form-signin',
        ),
        'enableClientValidation'=>false
        ));

        //echo $form->errorSummary($model);
        ?>
            <div class="username <?php echo (!is_null($model->getError('username')) ? 'inputerror' : ''); ?>">
                <div class="usernameinner">
                    <?php echo $form->textField($model,'username', array('class'=>'', 'placeholder'=>'Nombre del usuario')); ?>
                </div>
            </div>
            <div class="password <?php echo (!is_null($model->getError('password')) ? 'inputerror' : ''); ?>">
                <div class="passwordinner">
                    <?php echo $form->passwordField($model,'password', array('class'=>'', 'placeholder'=>'Clave de acceso')); ?>
                </div>
            </div>
            <div class="company <?php echo (!is_null($model->getError('company')) ? 'inputerror' : ''); ?>">
                <div class="companyinner">
                    <?php echo $form->dropDownList($model,'company', $empresas, array('class'=>'', 'empty'=>'Seleccionar Empresa')); ?>
                </div>
            </div>
            <?php echo CHtml::submitButton('ENTRAR', array('class'=>'', 'style'=>'width:310px')); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>