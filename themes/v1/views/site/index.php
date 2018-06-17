<?php
$this->pageTitle = Yii::app()->name . ' - Iniciar sesión';
?>

<div>
  <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
  	'focus'=>array($model,'username'),
  	'htmlOptions'=>array(
  		'class'=>'form-signin',
  	),
	'enableClientValidation'=>false
	)); ?>

		<h2 class="form-signin-heading">Iniciar Sesi&oacute;n</h2>

		<hr />
	
		<div class="control-group <?php echo (!is_null($model->getError('username')) ? 'error' : ''); ?>">
			<?php echo $form->labelEx($model,'username', array('class'=>'control-label')); ?>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on">
						<i class="icon-user"></i>
					</span>
					<?php echo $form->textField($model,'username', array('class'=>'input-block-level')); ?>
				</div>
			</div>
		</div>

		<div class="control-group <?php echo (!is_null($model->getError('password')) ? 'error' : ''); ?>">
			<?php echo $form->labelEx($model,'password', array('class'=>'control-label')); ?>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on">
						<i class="icon-lock"></i>
					</span>
					<?php echo $form->passwordField($model,'password', array('class'=>'input-block-level')); ?>
					<?php echo $model->getError('password'); ?>
				</div>
			</div>
		</div>

		<div class="control-group <?php echo (!is_null($model->getError('company')) ? 'error' : ''); ?>">
			<?php echo $form->labelEx($model,'company', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo $form->dropDownList($model,'company', $empresas, array('class'=>'input-block-level', 'empty'=>'Seleccionar')); ?>
			</div>
		</div>

		<hr />

		<div class="actions">
			<?php echo CHtml::submitButton('Entrar', array('class'=>'btn btn-primary')); ?>
		</div>

	<?php $this->endWidget(); ?>
</div>

<?php
$cs=Yii::app()->clientScript;
$cs->registerCss('loginform', '
body {
	padding-top: 40px;
	padding-bottom: 40px;
	background-color: #f5f5f5;
}

.form-signin {
	max-width: 300px;
	padding: 19px 29px 29px;
	margin: 0 auto 20px;
	background-color: #fff;
	border: 1px solid #e5e5e5;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	-moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	box-shadow: 0 1px 2px rgba(0,0,0,.05);
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
	margin-bottom: 10px;
	text-align: center;
}
.form-signin input[type="text"],
.form-signin input[type="password"] {
	font-size: 15px;
	height: auto;
	margin-bottom: 15px;
	padding: 7px 9px;
}
');
?>