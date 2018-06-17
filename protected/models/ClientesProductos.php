<?php

/**
 * This is the model class for table "catClientes_has_tblProductosPrecio".
 *
 * The followings are the available columns in table 'catClientes_has_tblProductosPrecio':
 * @property integer $id
 * @property string $catClientes_codigo
 * @property integer $tblProductosPrecio_id
 */
class ClientesProductos extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'catClientes_has_tblProductosPrecio';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('catClientes_codigo, tblProductosPrecio_id', 'required'),
            array('tblProductosPrecio_id', 'numerical', 'integerOnly'=>true),
            array('catClientes_codigo', 'length', 'max'=>10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, catClientes_codigo, tblProductosPrecio_id', 'safe', 'on'=>'search'),
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
            'catClientes_codigo' => 'Cat Clientes Codigo',
            'tblProductosPrecio_id' => 'Tbl Productos Precio',
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
        $criteria->compare('catClientes_codigo',$this->catClientes_codigo,true);
        $criteria->compare('tblProductosPrecio_id',$this->tblProductosPrecio_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ClientesProductos the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
