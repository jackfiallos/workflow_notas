<?php

/**
 * This is the model class for table "tblNiveles_has_usuarios".
 *
 * The followings are the available columns in table 'tblNiveles_has_usuarios':
 * @property integer $niveles_id
 * @property integer $usuarios_id
 * @property string $regla
 */
class NivelesHasUsuarios extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tblNiveles_has_usuarios';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('niveles_id, usuarios_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('niveles_id, usuarios_id', 'numerical', 'integerOnly'=>true),
			array('regla', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('niveles_id, usuarios_id, regla', 'safe', 'on'=>'search'),
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
			'niveles_id' => 'Niveles',
			'usuarios_id' => 'Usuarios',
			'regla' => 'Regla',
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

		$criteria->compare('niveles_id',$this->niveles_id);
		$criteria->compare('usuarios_id',$this->usuarios_id);
		$criteria->compare('regla',$this->regla,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TblNivelesHasUsuarios the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
