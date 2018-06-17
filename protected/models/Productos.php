<?php

/**
 * This is the model class for table "catProductos".
 *
 * The followings are the available columns in table 'catProductos':
 * @property integer $id
 * @property string $codigo
 * @property string $descripcion
 * @property string $linea
 * @property string $indice
 * @property integer $empresas_id
 */
class Productos extends CActiveRecord
{
	private $FullCode;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catProductos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('codigo, descripcion, empresas_id, marcas_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('empresas_id, marcas_id', 'numerical', 'integerOnly'=>true),
			array('codigo, indice', 'length', 'max'=>10),
			array('descripcion, linea', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, codigo, descripcion, linea, indice, empresas_id, marcas_id', 'safe', 'on'=>'search'),
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
			'precios' => array(self::HAS_MANY, 'ProductosPrecio', 'productos_id'),
			'notas' => array(self::MANY_MANY, 'Notas', 'tblNotas_has_productos(notas_id, productos_id)'),
			'cnotas' => array(self::HAS_MANY, 'NotasHasProductos', 'productos_id'),
			'empresas' => array(self::BELONGS_TO, 'Empresas', 'empresas_id'),
			'marcas' => array(self::BELONGS_TO, 'Marcas', 'marcas_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'codigo' => 'Codigo',
			'descripcion' => 'Descripcion',
			'linea' => 'Linea',
            'indice' => 'Indice',
			'empresas_id' => 'Empresa',
			'marcas_id' => 'Marcas',
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
        $criteria->compare('codigo',$this->codigo,true);
        $criteria->compare('descripcion',$this->descripcion,true);
        $criteria->compare('linea',$this->linea,true);
        $criteria->compare('empresas_id',$this->empresas_id);
        $criteria->compare('marcas_id',$this->marcas_id);
		$criteria->with = array('precios', 'empresas');
		$criteria->together = true;

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
	 * @return Productos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * [getFullName description]
	 * @return [type] [description]
	 */
	public function getFullCode()
	{
	    return $this->codigo.' - '.$this->descripcion;
	}
}
