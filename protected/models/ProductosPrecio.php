<?php

/**
 * This is the model class for table "tblProductosPrecio".
 *
 * The followings are the available columns in table 'tblProductosPrecio':
 * @property integer $id
 * @property string $precio
 * @property integer $productos_id
 * @property integer $anio_id
 */
class ProductosPrecio extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tblProductosPrecio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('precio, productos_id, anio_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('productos_id, anio_id', 'numerical', 'integerOnly'=>true),
			array('precio', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, precio, productos_id, anio_id', 'safe', 'on'=>'search'),
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
			'anio' => array(self::BELONGS_TO, 'Anio', 'anio_id'),
			'productos' => array(self::BELONGS_TO, 'Productos', 'productos_id'),
			'cproductos' => array(self::HAS_MANY, 'ClientesProductos', 'tblProductosPrecio_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'precio' => 'Precio',
			'productos_id' => 'Productos',
			'anio_id' => 'Anio',
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
		$criteria->compare('precio', $this->precio, true);
		$criteria->compare('productos_id',$this->productos_id);
		$criteria->compare('anio_id',$this->anio_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=> 20
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductosPrecio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
