<?php

/**
 * This is the model class for table "tblNotas_has_productos".
 *
 * The followings are the available columns in table 'tblNotas_has_productos':
 * @property integer $id
 * @property integer $notas_id
 * @property integer $productos_id
 * @property integer $cantidad_piezas
 * @property integer $num_lote
 * @property string $almacen
 * @property string $caducidad
 * @property integer $aceptacion
 */
class NotasHasProductos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tblNotas_has_productos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('notas_id, productos_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('notas_id, productos_id, cantidad_piezas, aceptacion', 'numerical', 'integerOnly'=>true),
			array('num_lote', 'length', 'max'=>15),
			array('almacen, caducidad', 'length', 'max'=>10),
			array('aceptacion', 'default', 'value'=>100, 'setOnEmpty'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, notas_id, productos_id, cantidad_piezas, num_lote, almacen, caducidad, aceptacion', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'notas_id' => 'Notas',
			'productos_id' => 'Productos',
			'cantidad_piezas' => 'Cantidad Piezas',
			'num_lote' => 'No. Lote',
			'almacen' => 'Almacen',
			'caducidad' => 'Caducidad',
			'aceptacion' => 'Aceptacion',
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
		$criteria->compare('notas_id',$this->notas_id);
		$criteria->compare('productos_id',$this->productos_id);
		$criteria->compare('cantidad_piezas',$this->cantidad_piezas);
		$criteria->compare('num_lote',$this->num_lote);
		$criteria->compare('almacen',$this->almacen,true);
		$criteria->compare('caducidad',$this->caducidad,true);
		$criteria->compare('aceptacion',$this->aceptacion);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NotasHasProductos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
