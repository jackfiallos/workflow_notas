<?php

/**
 * This is the model class for table "catMarcas_has_descuentos".
 *
 * The followings are the available columns in table 'catMarcas_has_descuentos':
 * @property integer $id
 * @property string $importe
 * @property integer $marcas_id
 * @property integer $descuentos_id
 */
class MarcasHasDescuentos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catMarcas_has_descuentos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('importe, marcas_id, descuentos_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('marcas_id, descuentos_id', 'numerical', 'integerOnly'=>true),
			array('importe', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, importe, marcas_id, descuentos_id', 'safe', 'on'=>'search'),
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
			'marcas' => array(self::BELONGS_TO, 'Marcas', 'marcas_id'),
			'descuentos' => array(self::BELONGS_TO, 'Descuentos', 'descuentos_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'importe' => 'Importe',
			'marcas_id' => 'Marcas',
			'descuentos_id' => 'Descuentos',
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
		$criteria->compare('importe',$this->importe,true);
		$criteria->compare('marcas_id',$this->marcas_id);
		$criteria->compare('descuentos_id',$this->descuentos_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MarcasHasDescuentos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
