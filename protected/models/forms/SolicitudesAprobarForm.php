<?php
/**
 * SolicitudesForm class file
 * 
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class SolicitudesAprobarForm extends CFormModel
{
	public $estatus;
	public $comentario;

	/**
	 * Declares the validation rules.
	 * The rules state that usermail and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('estatus', 'required','message'=>'Selecciona una opci&oacute;n.'),
			array('comentario', 'required','message'=>'Escribe un comentario de la solicitud.'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'estatus' => 'Estatus',
			'comentario' => 'Comentario'
		);
	}
 
}
