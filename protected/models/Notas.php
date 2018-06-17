<?php

/**
 * https://rakeshwebdev.wordpress.com/2014/02/19/export-gridview-filtered-result-to-excel-in-yii-framework/
 * This is the model class for table "tblNotas".
 *
 * The followings are the available columns in table 'tblNotas':
 * @property integer $id
 * @property string $fecha_vencimiento
 * @property string $fecha_creacion
 * @property string $fecha_cierre
 * @property integer $estatus
 * @property integer $revision
 * @property integer $nivel_aprobacion
 * @property string $num_factura
 * @property string $comentario
 * @property integer $cancela_sustituye
 * @property string $descripcion
 * @property integer $tipo_orden
 * @property string $entrada_almacen
 * @property string $ordenCompra
 * @property integer $usuarios_id
 * @property integer $razones_id
 * @property string $clientes_codigo
 * @property integer $anio_id
 * @property string $indice
 * @property integer $procesado
 */
class Notas extends CActiveRecord
{
	const REV_PENDIENTE = 0;	// revision inicial - estatus por omision
	const REV_ACEPTADO = 1;		// revision preliminar - estatus modificado por los aprobadores
	const REV_APROBADO = 2;		// revision final - estatus modificado por sac en el ultimo nivel del flujo
	const REV_RECHAZADO = 3;	// rechazo
	const REV_PROCESADO = 4;	// revisado (cualquier aprobador o supervisor cambia el estatus hacia procesado)
	const REV_DEVOLUCIONES = 5; // devoluciones (sac cambia el estatus hacia devoluciones)
	const REV_SAC = 6; 			// sac antes de cerrar la solicitud (solo devoluciones puede cambiar a 6 o los supervisores segun la nota)
	//const REV_CERRADO = 7; 		// cerrado (devoluciones cambia el estatus hacia cerrado que es lo que pueden ver nuevamente sac ya para aceptarlo definitivamente)

	const ESTATUS_PUBLICADO = 1;
	const ESTATUS_BORRADOR = 0;

	const APROBACION_NORMAL = 0;    //  1 - normal
	const APROBACION_GERENCIAL = 1; //  2 - gerencial

	public $entry = '';
	public $folio;
	public $total=0;
	public $label_documentos;
	public $label_productos;
	public $label_descuentos;
	public $label_historial;
	public $label_facturas;
	public $label_cooperacio;
	public $label_total;
	public $filterUsuario;
	public $filterCliente;
	public $filterCaracteristica;

	public $maximo_nivel;
	public $f = array(); // este es un arreglo de id de solicitudes que el usuario solicitante debe o puede ver

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tblNotas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha_vencimiento, usuarios_id, clientes_codigo, razones_id', 'required', 'message' => '<strong>{attribute}</strong> es un campo requerido'),
			array('procesado, estatus, revision, nivel_aprobacion, usuarios_id, tipo_orden, razones_id, anio_id,  cancela_sustituye', 'numerical', 'integerOnly'=>true),
			array('clientes_codigo', 'length', 'max'=>10),
			array('num_factura, entrada_almacen', 'length', 'max'=>20),
			array('descripcion', 'length', 'max'=>25),
			array('ordenCompra', 'length', 'max'=>15),
			array('fecha_creacion, fecha_cierre, comentario', 'safe'),
			array('fecha_creacion', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tipo_orden, entrada_almacen, fecha_vencimiento, fecha_creacion, fecha_cierre, estatus, revision, nivel_aprobacion, num_factura, descripcion, ordenCompra, comentario, cancela_sustituye, usuarios_id, clientes_codigo, razones_id, anio_id, procesado', 'safe', 'on'=>'search'),
			array('id, num_factura, clientes_codigo', 'safe', 'on'=>'searchSolicitantes'),
			array('id, num_factura, clientes_codigo', 'safe', 'on'=>'searchSac'),
			array('id, num_factura, clientes_codigo', 'safe', 'on'=>'searchDevoluciones'),
			array('id, num_factura, clientes_codigo, usuarios_id, razones_id', 'safe', 'on'=>'searchSupervisores'),
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
			'usuarios'    => array(self::BELONGS_TO, 'Usuarios', 'usuarios_id'),
			'razones'     => array(self::BELONGS_TO, 'Razones', 'razones_id'),
			'clientes'    => array(self::BELONGS_TO, 'Clientes', 'clientes_codigo'),
			'anio'        => array(self::BELONGS_TO, 'Anio', 'anio_id'),
			'documentos'  => array(self::HAS_MANY, 'Documentos', 'notas_id'),
			'cflujos'     => array(self::HAS_MANY, 'NotasHasFlujos', 'notas_id'),
			'historial'   => array(self::HAS_MANY, 'Historial', 'notas_id'),
			'productos'   => array(self::MANY_MANY, 'Productos', 'tblNotas_has_productos(notas_id, productos_id)'),
			'cnotas' => array(self::HAS_MANY, 'NotasHasProductos', 'notas_id'),
			'flujos'   => array(self::MANY_MANY, 'Flujos', 'tblNotas_has_catFlujos(notas_id, flujos_id)'),
			'descuentos'  => array(self::HAS_ONE, 'Descuentos', 'notas_id'),
			'cooperacion' => array(self::HAS_ONE, 'Cooperacion', 'notas_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                   => 'Folio',
			'fecha_vencimiento'    => 'Fecha Vencimiento',
			'fecha_creacion'       => 'Fecha Creaci&oacute;n',
			'fecha_cierre'         => 'Fecha Finalizaci&oacute;n',
			'estatus'              => 'Estatus',
			'revision'             => 'Revision',
			'nivel_aprobacion'     => 'Nivel Aprobacion',
			'folio'                => 'Folio',
			'num_factura'          => 'No. de Factura',
			'comentario'           => 'Comentario',
			'cancela_sustituye'    => 'Cancelar y Sustituir',
			'usuarios_id'          => 'Usuario',
			'clientes_codigo'      => 'Cliente',
			'razones_id'           => 'Razones',
			'anio_id'              => 'A&ntilde;o',
			'label_documentos'     => 'Documentos',
			'label_productos'      => 'Productos',
			'label_descuentos'     => 'Descuentos',
			'label_historial'      => 'Historial',
			'filterUsuario'        => 'Usuario',
			'filterCliente'        => 'Cliente',
			'filterCaracteristica' => 'Caracter&iacute;stica',
			'label_total'          => 'Total',
			'label_facturas'       => 'Facturas',
			'label_cooperacion'    => 'Cooperacion',
			'descripcion'          => 'Descripcion',
			'tipo_orden'           => 'Tipo de Orden',
			'entrada_almacen'      => 'Entrada de Almac&eacute;n',
			'ordenCompra'          => 'Orden de Compra',
			//'indice'               => 'Indice',
			'procesado' => 'Procesado',
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
		$criteria->compare('fecha_vencimiento',$this->fecha_vencimiento,true);
		$criteria->compare('fecha_creacion',$this->fecha_creacion,true);
		$criteria->compare('fecha_cierre',$this->fecha_cierre,true);
		$criteria->compare('estatus',$this->estatus);
		$criteria->compare('revision',$this->revision);
		$criteria->compare('nivel_aprobacion',$this->nivel_aprobacion);
		$criteria->compare('num_factura',$this->num_factura,true);
		$criteria->compare('comentario',$this->comentario,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('tipo_orden',$this->tipo_orden);
		$criteria->compare('entrada_almacen',$this->entrada_almacen,true);
		$criteria->compare('cancela_sustituye',$this->cancela_sustituye);
		$criteria->compare('ordenCompra',$this->ordenCompra,true);
		$criteria->compare('usuarios_id',$this->usuarios_id);
		$criteria->compare('clientes_codigo',$this->clientes_codigo);
		$criteria->compare('razones_id',$this->razones_id);
		$criteria->compare('anio_id',$this->anio_id);
		//$criteria->compare('indice',$this->indice,true);
		$criteria->compare('procesado',$this->procesado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=> 20
			)
		));
	}

	/**
	 * [searchNotas description]
	 * @return [type] [description]
	 */
	public function searchSolicitantes()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('id', $this->id);
		$criteria->compare('num_factura', $this->num_factura, true);
		$criteria->compare('clientes_codigo', $this->clientes_codigo);
		$criteria->compare('usuarios_id', Yii::app()->user->id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id DESC'
			),
			'pagination'=>array(
				'pageSize'=> 20,
				'pageVar'=>'p'
			)
		));
	}

	/**
	 * [searchSac description]
	 * @return [type] [description]
	 */
	public function searchSac()
	{
		$criteria = new CDbCriteria();
		$criteria->with = array('razones.caracteristicas_tipo');
		$criteria->together = true;
		$criteria->compare('id', $this->id);
		$criteria->compare('num_factura', $this->num_factura, true);
		$criteria->compare('clientes_codigo', $this->clientes_codigo);
		$criteria->addCondition('t.estatus = :estatus AND (revision = :revision1 OR revision = :revision2)');
		$criteria->addCondition('(SELECT COUNT(*) FROM '.NotasHasFlujos::model()->tableName().' nf WHERE nf.notas_id = t.id) = (SELECT MAX(nivel_aprobacion) FROM '.Flujos::model()->tableName().' f WHERE f.caracteristicasTipo_id = caracteristicas_tipo.id)');
		$criteria->params = array(
			':estatus' => Notas::ESTATUS_PUBLICADO,
			':revision1' => Notas::REV_PROCESADO,
			':revision2' => Notas::REV_SAC
		);

		return new CActiveDataProvider('Notas', array(
			'criteria'=> $criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id ASC',
			),
			'pagination'=>array(
				'pageSize'=> 20,
				'pageVar'=>'p'
			)
		));
	}

	/**
	 * [searchDevoluciones description]
	 * @return [type] [description]
	 */
	public function searchDevoluciones()
	{
		$criteria = new CDbCriteria();
		$criteria->with = array('razones.caracteristicas_tipo');
		$criteria->together = true;
		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.num_factura', $this->num_factura, true);
		$criteria->compare('t.clientes_codigo', $this->clientes_codigo);
		$criteria->compare('t.estatus', Notas::ESTATUS_PUBLICADO);
		$criteria->compare('t.revision', Notas::REV_DEVOLUCIONES);
		$criteria->addCondition('(SELECT COUNT(*) FROM '.NotasHasFlujos::model()->tableName().' nf WHERE nf.notas_id = t.id) = (SELECT MAX(nivel_aprobacion) FROM '.Flujos::model()->tableName().' f WHERE f.caracteristicasTipo_id = caracteristicas_tipo.id)');

		return new CActiveDataProvider('Notas', array(
			'criteria'=> $criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id ASC',
			),
			'pagination'=>array(
				'pageSize'=> 20,
				'pageVar'=>'p'
			)
		));
	}

	/**
	 * [searchAprobadores description]
	 * @return [type] [description]
	 */
	public function searchAprobadores()
	{
		$criteria = new CDbCriteria;
		$criteria->addInCondition('t.id', $this->f);
		$criteria->compare('id', $this->id);
		$criteria->compare('num_factura', $this->num_factura, true);
		$criteria->compare('clientes_codigo', $this->clientes_codigo);
		$criteria->order = 't.id DESC';

		return new CActiveDataProvider('Notas', array(
			'criteria'=> $criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id ASC',
				'attributes'=>array(
		            'usuario_nombre'=>array(
		                'asc'=>'usuarios.nombre',
		                'desc'=>'usuarios.nombre DESC',
		            ),
		            'cliente_nombre'=>array(
		                'asc'=>'clientes.nombre',
		                'desc'=>'clientes.nombre DESC',
		            ),
		            'anio_valor'=>array(
		                'asc'=>'anio.anio',
		                'desc'=>'anio.anio DESC',
		            ),
		            '*'
		        )
			),
			'pagination'=>array(
				'pageSize'=> 20,
				'pageVar'=>'p'
			)
		));
	}

	public function searchSupervisores()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.num_factura', $this->num_factura, true);
		$criteria->compare('t.clientes_codigo', $this->clientes_codigo);
		$criteria->compare('t.usuarios_id', $this->usuarios_id);
		$criteria->compare('t.estatus', Notas::ESTATUS_PUBLICADO);
		$criteria->compare('caracteristicas.id', $this->razones_id);
		$criteria->order = 't.id DESC';
		$criteria->with = array('razones.caracteristicas_tipo.caracteristicas');
		$criteria->together = true;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id DESC'
			),
			'pagination'=>array(
				'pageSize'=> 20,
				'pageVar'=>'p'
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * [beforeSave description]
	 * @return [type] [description]
	 */
	public function afterSave()
	{
		if (!empty($this->entry))
		{
	    	$historial = new Historial();
			$historial->descripcion = $this->entry;
			$historial->notas_id = $this->id;
			$historial->usuarios_id = Yii::app()->user->id;
			$historial->save(false);
		}

    	return parent::afterSave();
	}

	/**
	 * [afterFind description]
	 * @return [type] [description]
	 */
	public function afterFind()
	{
	    $this->folio = str_pad($this->id, 10, 0, STR_PAD_LEFT);

    	return parent::afterFind();
	}
}
