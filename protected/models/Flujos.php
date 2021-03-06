<?php

/**
 * This is the model class for table "catFlujos".
 *
 * The followings are the available columns in table 'catFlujos':
 * @property integer $id
 * @property integer $nivel_aprobacion
 * @property string $expresion
 * @property integer $caracteristicasTipo_id
 */
class Flujos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catFlujos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nivel_aprobacion, caracteristicasTipo_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('nivel_aprobacion, caracteristicasTipo_id', 'numerical', 'integerOnly'=>true),
			array('expresion', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nivel_aprobacion, expresion, caracteristicasTipo_id', 'safe', 'on'=>'search'),
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
			'caracteristicas_tipo' => array(self::BELONGS_TO, 'Caracteristicastipo', 'caracteristicasTipo_id'),
			'usuarios' => array(self::MANY_MANY, 'Usuarios', 'tblUsuarios_has_catFlujos(flujos_id, usuarios_id)')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nivel_aprobacion' => 'Nivel Aprobacion',
			'expresion' => 'Expresion',
			'caracteristicasTipo_id' => 'Caracteristicas Tipo',
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
		$criteria->compare('nivel_aprobacion',$this->nivel_aprobacion);
		$criteria->compare('expresion',$this->expresion,true);
		$criteria->compare('caracteristicasTipo_id',$this->caracteristicasTipo_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Flujos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
