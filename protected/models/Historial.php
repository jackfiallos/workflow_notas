<?php

/**
 * This is the model class for table "tblHistorial".
 *
 * The followings are the available columns in table 'tblHistorial':
 * @property integer $id
 * @property string $descripcion
 * @property integer $notas_id
 * @property integer $usuarios_id
 */
class Historial extends CActiveRecord
{
	public $usuario;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tblHistorial';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('descripcion, notas_id, usuarios_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('notas_id, usuarios_id', 'numerical', 'integerOnly'=>true),
			array('fecha_creacion', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, descripcion, notas_id, usuarios_id', 'safe', 'on'=>'search'),
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
			'usuarios' => array(self::BELONGS_TO, 'Usuarios', 'usuarios_id'),
			'nota' => array(self::BELONGS_TO, 'Notas', 'notas_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'descripcion' => 'Descripcion',
			'notas_id' => 'Notas',
			'usuarios_id' => 'Usuarios',
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
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('notas_id',$this->notas_id);
		$criteria->compare('usuarios_id',$this->usuarios_id);

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
	 * @return TblHistorial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
