<?php
/**
 * ModificarEstatus class file
 * 
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class ModificarEstatusForm extends CFormModel
{
	public $estatus;
	public $comentario;
	public $tipoOrden;
	public $entrada_almacen;
	public $descripcion;
	public $sucursal;
	public $ordenCompra;

	/**
	 * Declares the validation rules.
	 * The rules state that usermail and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('estatus', 'required', 'message'=>'Selecciona una opci&oacute;n.'),
			array('comentario', 'required','message'=>'Selecciona una opci&oacute;n.', 'on'=>'devoluciones'),
			array('estatus, comentario, descripcion, tipoOrden, entrada_almacen, ordenCompra', 'required', 'on'=>'finalizar'),
			array('comentario', 'length', 'max'=>250),
			array('tipoOrden', 'numerical', 'integerOnly'=>true),
			array('descripcion', 'length', 'max'=>25),
			array('entrada_almacen', 'length', 'max'=>20),
			array('sucursal', 'length', 'max'=>100),
			array('ordenCompra', 'length', 'max'=>20)
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'estatus' => 'Estatus',
			'comentario' => 'Comentario',
			'tipoOrden' => 'Tipo de Orden',
			'entrada_almacen' => 'Entrada al Almac&eacute;n Cliente',
			'descripcion' => 'Comentario de la factura',
			'sucursal' => 'Sucursal',
			'ordenCompra' => 'Orden de Compra'
		);
	}
}