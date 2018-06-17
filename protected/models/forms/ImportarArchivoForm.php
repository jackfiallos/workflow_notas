<?php
/**
 * ImportarArchivoForm class file
 * 
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class ImportarArchivoForm extends CFormModel
{
	public $archivo;
	public $empresa_id;
	public $anio_id;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('archivo', 'required', 'message' => '<strong>{attribute}</strong> es requerido.'),
			array('empresa_id', 'required', 'on'=>'productos', 'message' => 'La <strong>{attribute}</strong> es requerida.'),
			array('empresa_id', 'required', 'on'=>'clientes', 'message' => 'La <strong>{attribute}</strong> es requerida.'),
			array('empresa_id', 'required', 'on'=>'marcas', 'message' => 'La <strong>{attribute}</strong> es requerida.'),
			array('empresa_id, anio_id', 'numerical', 'integerOnly'=>true),
            array('archivo', 'file', 'allowEmpty' => false),
            array('archivo', 'file', 'maxSize'=> 100000000, 'tooLarge' => 'Debes elegir un archivo con un peso de hasta 3MB.'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'archivo' => 'Selecciona el Archivo a importar',
			'empresa_id' => 'Empresa',
			'anio_id' => 'A&ntilde;o'
		);
	}
}
