<?php

/**
 * This is the model class for table "catMarcas".
 *
 * The followings are the available columns in table 'catMarcas':
 * @property integer $id
 * @property string $codigo
 * @property string $marca
 * @property integer $es_gama
 * @property string $cuenta_cooperacion
 * @property string $cuenta_descuento
 * @property integer $empresas_id
 * @property string $color
 * @property string $identificador
 */
class Marcas extends CActiveRecord
{
    const SOLO_GAMAs = 1;
    const SOLO_MARCAS = 0;

    private $FullCode;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'catMarcas';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('codigo', 'unique', 'message'=>'Este c&oacute;digo est&aacute; siendo utilizado por otra Marca'),
            array('codigo, marca, empresas_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
            array('es_gama, empresas_id', 'numerical', 'integerOnly'=>true),
            array('codigo, cuenta_cooperacion, cuenta_descuento', 'length', 'max'=>10),
            array('marca', 'length', 'max'=>50),
            array('color', 'length', 'max'=>7),
            array('identificador', 'length', 'max'=>5),
            array('id, codigo, marca, es_gama, cuenta_cooperacion, cuenta_descuento, empresas_id, color, identificador', 'safe', 'on'=>'search'),
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
            'empresas' => array(self::BELONGS_TO, 'Empresas', 'empresas_id'),
            'descuentos' => array(self::MANY_MANY, 'Descuentos', 'catMarcas_has_descuentos(marcas_id, descuentos_id)'),
            'cdescuentos' => array(self::HAS_MANY, 'MarcasHasDescuentos', 'marcas_id'),
            'ccooperacion' => array(self::HAS_MANY, 'MarcasHasCooperacion', 'marcas_id'),
            'empresas' => array(self::BELONGS_TO, 'Empresas', 'empresas_id'),            
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'codigo' => 'C&oacute;digo',
            'marca' => 'Nombre de la Marca',
            'es_gama' => 'Es Gama',
            'cuenta_cooperacion' => 'Cuenta de Cooperaci&oacute;n',
            'cuenta_descuento' => 'Cuenta de Descuento',
            'empresas_id' => 'Empresa',
            'color' => 'Color',
            'identificador' => 'Identificador',
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
        $criteria->compare('marca',$this->marca,true);
        $criteria->compare('es_gama',$this->es_gama);
        $criteria->compare('cuenta_cooperacion',$this->cuenta_cooperacion,true);
        $criteria->compare('cuenta_descuento',$this->cuenta_descuento,true);
        $criteria->compare('empresas_id',$this->empresas_id);
        $criteria->compare('color',$this->color,true);
        $criteria->compare('identificador',$this->identificador,true);

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
     * @return Marcas the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * [getFullName description]
     * @return [type] [description]
     */
    public function getFullCode()
    {
        return $this->codigo.' - '.$this->marca;
    }
}