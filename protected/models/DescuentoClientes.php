<?php

/**
 * This is the model class for table "CatDescuentoClientes".
 *
 * The followings are the available columns in table 'CatDescuentoClientes':
 * @property integer $id
 * @property string $codigo
 * @property string $todas_nopfd
 * @property string $pfd_notrev
 * @property string $todas_trev
 * @property integer $clientes_codigo
 * @property integer $anio_id
 */
class DescuentoClientes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catDescuentoClientes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('codigo, todas_nopfd, pfd_notrev, todas_trev, clientes_codigo', 'required'),
			array('anio_id', 'numerical', 'integerOnly'=>true),
			array('clientes_codigo', 'length', 'max'=>10),
			array('codigo, todas_nopfd, pfd_notrev, todas_trev', 'length', 'max'=>5),
			array('id, codigo, todas_nopfd, pfd_notrev, todas_trev, clientes_codigo', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'codigo' => 'Codigo',
			'todas_nopfd' => 'Todas Nopfd',
			'pfd_notrev' => 'Pfd Notrev',
			'todas_trev' => 'Todas Trev',
			'clientes_codigo' => 'Clientes Codigo',
			'anio_id' => 'A&ntilde;o'
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
		$criteria->compare('todas_nopfd',$this->todas_nopfd,true);
		$criteria->compare('pfd_notrev',$this->pfd_notrev,true);
		$criteria->compare('todas_trev',$this->todas_trev,true);
		$criteria->compare('clientes_codigo',$this->clientes_codigo);
		$criteria->compare('anio_id',$this->anio_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DescuentoClientes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
