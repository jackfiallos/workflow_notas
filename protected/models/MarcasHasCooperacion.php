<?php

/**
 * This is the model class for table "catMarcas_has_cooperacion".
 *
 * The followings are the available columns in table 'catMarcas_has_cooperacion':
 * @property integer $id
 * @property string $importe
 * @property integer $marcas_id
 * @property integer $cooperacion_id
 */
class MarcasHasCooperacion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catMarcas_has_cooperacion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('importe, marcas_id, cooperacion_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('marcas_id, cooperacion_id', 'numerical', 'integerOnly'=>true),
			array('importe', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, importe, marcas_id, cooperacion_id', 'safe', 'on'=>'search'),
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
			'cooperacion' => array(self::BELONGS_TO, 'Cooperacion', 'cooperacion_id'),
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
			'cooperacion_id' => 'Cooperacion',
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
		$criteria->compare('cooperacion_id',$this->cooperacion_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MarcasHasCooperacion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
