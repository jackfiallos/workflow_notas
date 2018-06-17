<?php
/**
 * LoginForm class file
 * 
 * @author      Qbit Mexhico
 * @date        2014-07-25
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class DefaultForm extends CFormModel
{
	public $fechaini;
	public $fechafin;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('fechaini, fechafin', 'required', 'message'=>'<strong>{attribute}</strong> es requerido.'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'fechaini' => 'Fecha Inicial',
			'fechafin' => 'Fecha Final'
		);
	}

}
