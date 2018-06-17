<?php

/**
 * This is the model class for table "tblProcesamiento".
 *
 * The followings are the available columns in table 'tblProcesamiento':
 * @property integer $id
 * @property string $tipo
 * @property string $archivo
 * @property string $fecha
 */
class Procesamiento extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tblProcesamiento';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tipo, fecha', 'required'),
            array('tipo', 'length', 'max'=>45),
            array('archivo', 'length', 'max'=>100),
            array('fecha', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>true),
            array('id, tipo, archivo, fecha', 'safe', 'on'=>'search'),
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
            'tipo' => 'Tipo',
            'archivo' => 'Archivo',
            'fecha' => 'Fecha',
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
        $criteria->compare('tipo',$this->tipo,true);
        $criteria->compare('archivo',$this->archivo,true);
        $criteria->compare('fecha',$this->fecha,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Procesamiento the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}