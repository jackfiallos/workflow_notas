<?php

/**
 * This is the model class for table "tblDescuentos".
 *
 * The followings are the available columns in table 'tblDescuentos':
 * @property integer $id
 * @property string $importe
 * @property integer $mes_aplicacion
 * @property integer $notas_id
 */
class Descuentos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tblDescuentos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('importe, notas_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('mes_aplicacion, notas_id', 'numerical', 'integerOnly'=>true),
			array('importe', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, importe, mes_aplicacion, notas_id', 'safe', 'on'=>'search'),
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
			'marcas' => array(self::MANY_MANY, 'Marcas', 'catMarcas_has_descuentos(descuentos_id, marcas_id)'),
			'cmarcas' => array(self::HAS_MANY, 'MarcasHasDescuentos', 'descuentos_id'),
			'descuentos' => array(self::BELONGS_TO, 'Notas', 'notas_id'),
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
			'mes_aplicacion' => 'Mes de aplicaci&oacute;n',
			'notas_id' => 'Notas',
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
		$criteria->compare('mes_aplicacion',$this->mes_aplicacion);
		$criteria->compare('notas_id',$this->notas_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Descuentos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * [beforeSave description]
	 * @return [type] [description]
	 */
	public function beforeSave()
	{
    	$this->importe = str_replace(",", "", $this->importe);

    	return parent::beforeSave();
	}
}
