<?php

/**
 * This is the model class for table "catCaracteristicasTipo".
 *
 * The followings are the available columns in table 'catCaracteristicasTipo':
 * @property integer $id
 * @property string $nombre
 * @property string $codigo
 * @property integer $caracteristicas_id
 */
class Caracteristicastipo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catCaracteristicasTipo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, codigo, caracteristicas_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('caracteristicas_id', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>100),
			array('codigo', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nombre, codigo, caracteristicas_id', 'safe', 'on'=>'search'),
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
			'caracteristicas' => array(self::BELONGS_TO, 'Caracteristicas', 'caracteristicas_id'),
			//'razones' => array(self::HAS_MANY, 'Razones', 'caracteristicasTipo_id'),
			'razones' => array(self::HAS_ONE, 'Razones', 'caracteristicasTipo_id'),
			'flujos' => array(self::HAS_MANY, 'Flujos', 'caracteristicasTipo_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nombre' => 'Nombre',
			'codigo' => 'Codigo',
			'caracteristicas_id' => 'Caracteristicas',
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
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('codigo',$this->codigo,true);
		$criteria->compare('caracteristicas_id',$this->caracteristicas_id);

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
	 * @return CatCaracteristicasTipo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
