<?php

/**
 * This is the model class for table "catRazones".
 *
 * The followings are the available columns in table 'catRazones':
 * @property integer $id
 * @property string $nombre
 * @property string $codigo
 * @property string $cuenta
 * @property integer $caracteristicasTipo_id
 */
class Razones extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catRazones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, codigo, cuenta, caracteristicasTipo_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('caracteristicasTipo_id', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>100),
			array('codigo', 'length', 'max'=>5),
			array('cuenta', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nombre, codigo, caracteristicasTipo_id', 'safe', 'on'=>'search'),
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
			'niveles'=>array(self::MANY_MANY, 'Niveles', 'tblRazones_has_niveles(niveles_id, razones_id)')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nombre' => 'Nombre de la Raz&oacute;n',
			'codigo' => 'C&oacute;digo',
			'cuenta' => 'Cuenta',
			'caracteristicasTipo_id' => 'Tipo de Caracter&iacute;stica',
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
		$criteria->compare('cuenta',$this->cuenta,true);
		$criteria->compare('caracteristicasTipo_id',$this->caracteristicasTipo_id);

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
	 * @return CatRazones the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
