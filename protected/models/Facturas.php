<?php

/**
 * This is the model class for table "catFacturas".
 *
 * The followings are the available columns in table 'catFacturas':
 * @property integer $id
 * @property string $orden_compra
 * @property string $folio
 * @property string $fecha
 * @property string $monto
 * @property string $codigo_producto
 * @property string $descripcion_producto
 * @property string $precio_unitario
 * @property integer $cantidad_piezas
 * @property string $costo_iva
 * @property integer $clientes_codigo
 */
class Facturas extends CActiveRecord
{
	public $fecha_inicial;
	public $fecha_final;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catFacturas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('orden_compra, folio, fecha, monto, codigo_producto, descripcion_producto, precio_unitario, cantidad_piezas, costo_iva, clientes_codigo', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('cantidad_piezas', 'numerical', 'integerOnly'=>true),
			array('clientes_codigo', 'length', 'max'=>10),
			array('orden_compra', 'length', 'max'=>15),
			array('folio, monto, costo_iva', 'length', 'max'=>20),
			array('codigo_producto, precio_unitario', 'length', 'max'=>10),
			array('descripcion_producto', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, orden_compra, folio, fecha, monto, clientes_codigo', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'clientes' => array(self::BELONGS_TO, 'Clientes', 'clientes_codigo')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'orden_compra' => 'Orden de Compra',
			'folio' => 'Factura No.',
			'fecha' => 'Fecha',
			'monto' => 'Monto',
			'codigo_producto' => 'Codigo Producto',
			'descripcion_producto' => 'Descripcion Producto',
			'precio_unitario' => 'Precio Unitario',
			'cantidad_piezas' => 'Cantidad Piezas',
			'costo_iva' => 'Costo Iva',
			'clientes_codigo' => 'Cliente',
			// propiedades del formulario de busqueda
			'fecha_inicial' => 'Fecha Inicial',
			'fecha_final' => 'Fecha Final'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('orden_compra',$this->orden_compra,true);
		$criteria->compare('folio',$this->folio,true);
		//$criteria->compare('monto',$this->monto,true);
		$criteria->compare('clientes_codigo',$this->clientes_codigo);
		$criteria->with = array('clientes');
		$criteria->together = true;
		$criteria->group = 't.folio';

		/*$startDate = new DateTime($this->fecha_inicial.' 00:00:00');
		$endDate = new DateTime($this->fecha_final.' 23:59:59');

		$criteria->addBetweenCondition('fecha', $startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s'));*/

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.fecha DESC'
			),
			'pagination'=>array(
				'pageSize'=> 20,
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Facturas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
