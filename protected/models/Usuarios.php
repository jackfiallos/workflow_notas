<?php

/**
 * This is the model class for table "tblUsuarios".
 *
 * The followings are the available columns in table 'tblUsuarios':
 * @property integer $id
 * @property string $username
 * @property string $nombre
 * @property string $correo
 * @property string $clave
 * @property integer $estatus
 * @property string $fecha_creacion
 * @property integer $grupos_id
 * @property string $last_login
 * @property integer $islogged
 */
class Usuarios extends CActiveRecord
{
	const ADMIN = 1;
	const SUPERVISOR = 2;
	const APROBADOR = 3;
	const SOLICITUD = 4;
	const LOGISTICA = 5;
	const SAC = 6;

	const ACTIVO = 1;
	const INACTIVO = 0;

	public $PermisosSeleccionados = array();
	public $EmpresasSeleccionadas = array();
	public $FlujosSeleccionadas = array();

	public $clientes = array();
	public $solicitantes = array();

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tblUsuarios';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, nombre, correo', 'required','message'=>'Debe describir el {attribute}.'),
			array('username', 'required','message'=>'Seleccione al menos una opci&oacute;n'),
			array('clave', 'required','message'=>'Debe de escribir la {attribute}.'),
			array('correo','email','message'=>'El {attribute} no es válido.'),
			array('estatus, grupos_id, islogged', 'numerical', 'integerOnly'=>true , 'message' => 'Selecciona una opción.'),
			array('username', 'unique', 'className' => 'Usuarios', 'attributeName' => 'username', 'message' => 'El nombre de usuario ya se encuentra registrado'),
			array('PermisosSeleccionados', 'required','message' => 'El usuario debe tener asignado al menos un permiso.'),
			array('EmpresasSeleccionadas', 'required','message' => 'El usuario debe tener asignada al menos una empresa.'),
			array('FlujosSeleccionadas', 'required','message' => 'Seleccione por lo menos una de las opciones.' , 'on' => 'flujos'),
			array('FlujosSeleccionadas', 'flujosValidos' , 'on' => 'flujos'),
			// Reglas especiales para ciertos perfiles de usuario
			array('clientes', 'required', 'message' => 'Debe de seleccionar al menos un cliente.', 'on'=>'solicitante'),
			array('solicitantes', 'required', 'message' => 'Debe de seleccionar al menos un solicitante', 'on'=>'aprobador'),
			// --
			array('estatus', 'required', 'message' => 'Selecciona una opción.'),
			array('username', 'length', 'max'=>15, 'tooLong' => 'El {attribute} es muy extenso, el máximo es de 15 caracteres.' ),
			array('clave', 'length', 'min'=>4, 'tooShort' => 'La {attribute} es muy pequeña, el mínimo es de 4 caracteres.' ),
			array('nombre, correo', 'length', 'max'=>100,'tooLong' => 'El {attribute} es muy extenso, el máximo es de 100 caracteres.' ),
			array('clave', 'length', 'max'=>100,'tooLong' => 'La {attribute} es muy extensa, el máximo es de 100 caracteres.' ),
			array('fecha_creacion', 'safe'),
			array('fecha_creacion', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, nombre, correo, clave, estatus, fecha_creacion, grupos_id, last_login, islogged', 'safe', 'on'=>'search'),
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
			'permisos'=>array(self::MANY_MANY, 'Permisos', 'tblUsuarios_has_catPermisos(usuarios_id, permisos_id)'),
			'niveles'=>array(self::MANY_MANY, 'Niveles', 'tblNiveles_has_usuarios(niveles_id, usuarios_id)'),
			'empresas'=>array(self::MANY_MANY, 'Empresas', 'tblUsuarios_has_catEmpresas(usuarios_id, empresas_id)'),
			'notas' => array(self::HAS_MANY, 'Notas', 'usuarios_id'),
			'flujos'=>array(self::MANY_MANY, 'Flujos', 'tblUsuarios_has_catFlujos(usuarios_id, flujos_id)'),
			'grupo' => array(self::BELONGS_TO, 'Grupos', 'grupos_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Nombre de inicio de sesi&oacute;n',
			'nombre' => 'Nombre del usuario',
			'correo' => 'Correo electr&oacute;nico',
			'clave' => 'Clave de acceso',
			'estatus' => 'Estatus',
			'fecha_creacion' => 'Fecha de Creaci&oacute;n',
			'PermisosDisponibles' =>  'Permisos Disponibles',
			'PermisosSeleccionados' => 'Permisos Seleccionados',
			'EmpresasDisponibles' => 'Empresas Disponibles',
			'EmpresasSeleccionadas' => 'Empresas Seleccionadas',
			'grupos_id' => 'Grupo al que pertenece',
			'clientes' => 'Clientes Asignados',
			'aprobadores' => 'Aprobadores Asignados',
			'last_login' => 'Ultimo ingreso',
			'islogged' => 'Usuario logeado'
		);
	}


	/**
	 * [flujosValidos description]
	 * Validar nivel de aprobador
	 * @author nixho
	 * @version [version]
	 * @date    2014-06-27
	 * @return  [type]     [description]
	 */
	public function flujosValidos()
	{
		if( !empty($this->FlujosSeleccionadas) && in_array( (string)Usuarios::APROBADOR ,$this->PermisosSeleccionados) )
		{
			$flujosids = Tools::GetUltimosNivele();
			while( list($key,$value) = each($flujosids) )
			{
				if( in_array( $value , $this->FlujosSeleccionadas ) )
				{
					$flujos  = Flujos::model()->with('caracteristicas_tipo','caracteristicas_tipo.caracteristicas')->together()->findByPk($value);
					$mensaje = 'El nivel('.$flujos->nivel_aprobacion.') con '.$flujos->caracteristicas_tipo->nombre;
					$mensaje = $mensaje.' y  '.$flujos->caracteristicas_tipo->caracteristicas->nombre;
					$this->addError('PermisosSeleccionados', $mensaje.' .No lo puede tener un aprobador. ');
					$this->addError('FlujosSeleccionadas', $mensaje.' .No lo puede tener un aprobador. ');
					break;
				}
			}
		}
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('correo',$this->correo,true);
		$criteria->compare('clave',$this->clave,true);
		$criteria->compare('estatus',$this->estatus);
		$criteria->compare('fecha_creacion',$this->fecha_creacion,true);
		$criteria->compare('grupos_id',$this->grupos_id);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('islogged', $this->islogged);

		$criteria->with = array('grupo');
		$criteria->together = true;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=> 50
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Usuarios the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * [beforeSave description]
	 * @return [type] [description]
	 */
	protected function beforeSave()
	{
        $this->username = strtolower(str_replace(' ', '', $this->username));
		return true;
	}
}
