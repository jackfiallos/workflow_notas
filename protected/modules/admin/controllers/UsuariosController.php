<?php
/**
 * UsuariosController class file
 *
 * @autor		Jackfiallos
 * @link		http://jackfiallos.com
 * @copyright	Copyright (c) 2011 Qbit Mexhico
 * @description
 *
 *
 **/
class UsuariosController extends Controller
{
	//public $layout = 'application.modules.admin.views.layouts.base';

	/**
	 * Control de acceso y cura los campos de texto
	 * @author:  Qbit Mexhico
	 * @revised: Jackfiallos
	 * @date:    2014-06-10
	 * @link:    [link]
	 * @version: [version]
	 * @return   [type]      [description]
	 */
	public function filters()
	{
    	return array(
    		'accessControl'
		);
	}

	/**
	 * Especifica los controles de acceso
	 * @author:  Qbit Mexhico
	 * @revised: Jackfiallos
	 * @date:    2014-06-10
	 * @link:    [link]
	 * @version: [version]
	 * @return   [type]      [description]
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index', 'create', 'update'),
				'users'=>array('@'),
				'expression'=>'!$user->isGuest && $user->verifyRole(Usuarios::ADMIN)'
			),
			array('deny',
				'users'=>array('*')
			)
		);
	}

	/**
	 * Show contact's list
	 * @author:  Israel Arizmendi
	 * @revised: Jackfiallos
	 * @date:    2014-06-10
	 * @link:    [link]
	 * @version: [version]
	 * @return   [type]      [description]
	 */
	public function actionIndex()
	{
		$model = new Usuarios('search');

        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['Usuarios']))
        {
            $model->attributes = $_GET['Usuarios'];
        }

        $this->render('index', array(
            'model'=>$model,
        ));
	}

	/**
	 * [actionCreate description]
	 * Crea un nuevo usuario
	 * @author nixho
	 * @version [version]
	 * @date    2014-06-19
	 * @return  [type]     [description]
	 */
	public function actionCreate()
	{
		// Crear modelo
		$model = new Usuarios();
		$escenario = "";
		$model->PermisosSeleccionados = array();
		$model->EmpresasSeleccionadas = array();
		$model->FlujosSeleccionadas   = array();

        if(isset($_POST['Usuarios']))
        {
        	$model->attributes = $_POST['Usuarios'];
			$model->PermisosSeleccionados = isset($_POST['Usuarios']['PermisosSeleccionados']) ? $_POST['Usuarios']['PermisosSeleccionados'] : array();
        	$model->EmpresasSeleccionadas = isset($_POST['Usuarios']['EmpresasSeleccionadas']) ? $_POST['Usuarios']['EmpresasSeleccionadas'] : array();
        	$model->FlujosSeleccionadas = isset($_POST['Usuarios']['FlujosSeleccionadas']) ? $_POST['Usuarios']['FlujosSeleccionadas'] : array();

        	if (in_array((string)Usuarios::APROBADOR ,$model->PermisosSeleccionados) || in_array((string)Usuarios::SUPERVISOR,$model->PermisosSeleccionados))
        	{
				$escenario = "flujos";
				$model->scenario = 'flujos';
			}

        	//  Validar que el modelo cumpla con las reglas de validacion
            if ($model->validate())
            {
				try
				{
					// Guardar usuario
					$model->save();

					// Buscar en el catalgo de permisos donde id se encuntre en el arreglo de permisos seleccionados
	        		$lstPermisos = Permisos::model()->findAll(array(
						'condition'=>'id IN ('.implode(",",$model->PermisosSeleccionados).')',
					));

	        		// Asignar permiso al usuario
					while (list($key,$value) = each($lstPermisos))
					{
						$usuariosHasPermisos = new UsuariosHasPermisos();
						$usuariosHasPermisos->usuarios_id = $model->id;
						$usuariosHasPermisos->permisos_id = $value->id;
						$usuariosHasPermisos->save(false);
					}

					// Buscar  en catalogo de empresas donde el id se encuentre en arreglo de empresas seleccionadas
					$lstEmpresas = Empresas::model()->findAll(array(
						'condition'=>'id IN ('.implode(",",$model->EmpresasSeleccionadas).')',
					));

					// Asignar empresas al usuairo
					while (list($key,$value) = each($lstEmpresas))
					{
						$usuariosHasEmpresas = new UsuariosHasEmpresas();
						$usuariosHasEmpresas->usuarios_id = $model->id;
						$usuariosHasEmpresas->empresas_id = $value->id;
						$usuariosHasEmpresas->save(false);
					}

					// Jackfiallos - Establece la relacion entre el usuario y sus clientes
					if ((isset($_POST['Usuarios']['clientes'])) && (!empty($_POST['Usuarios']['clientes'])))
					{
						foreach($_POST['Usuarios']['clientes'] as $cliente)
						{
							$usuarioCliente = new UsuariosHasClientes();
							$usuarioCliente->usuarios_id = $model->id;
							$usuarioCliente->clientes_codigo = $cliente;
							$usuarioCliente->save(false);
						}
					}

					// Jackfiallos - Establece la relacion entre el aprobador y los solicitantes
					if ((isset($_POST['Usuarios']['solicitantes'])) && (!empty($_POST['Usuarios']['solicitantes'])))
					{
						foreach($_POST['Usuarios']['solicitantes'] as $solicitante)
						{
							$aprobadoresSolicitantes = new AprobadoresHasSolicitantes();
							$aprobadoresSolicitantes->aprobador = $model->id;
							$aprobadoresSolicitantes->solicitante = $solicitante;
							$aprobadoresSolicitantes->save(false);
						}
					}

					if ($escenario == 'flujos')
					{
						// Buscar en el catalogo de flujos donde el id se encuentre en los flujos seleccionados
						$lstFlujos = Flujos::model()->findAll(array(
							'condition'=>'id IN ('.implode(",",$model->FlujosSeleccionadas).')',
						));

						// Asiganr las nuevas reglas del usuario
						while (list($key,$value) = each($lstFlujos))
						{
							$usuariosHasFlujos = new UsuariosHasFlujos();
							$usuariosHasFlujos->usuarios_id = $model->id;
							$usuariosHasFlujos->flujos_id = $value->id;
							$usuariosHasFlujos->save(false);
						}
					}

					Yii::app()->user->setFlash('Usuarios.Success','Los datos se guardaron correctamente.');
				}
				catch(Exception $ex)
				{
					$message = $ex->getMessage();
					Yii::app()->user->setFlash('Usuarios.Error','Servicio no disponible.');
					Yii::log($message, CLogger::LEVEL_ERROR, 'application');
				}

				$this->redirect(Yii::app()->createUrl('admin/usuarios/index'));
            }
        }

        // ------------------------------------------------
	    // Obtener todos los clientes
	    $clientes = Clientes::model()->findAll(array(
	    	'condition' => 'TRIM(t.nombre) != \'\'',
	    	'order' => 't.codigo ASC',
	    ));
        $lstClientes = CHtml::listData($clientes, 'codigo', 'CodigoNombre');

        // Encontrar todos los solicitantes relacionados
        $solicitantesIds = array(1);
        $solicitantes = Usuarios::model()->findAll(array(
        	'condition' => 't.id NOT IN ('.implode(',', $solicitantesIds).')',
        ));
        $lstSolicitantes = CHtml::listData($solicitantes, 'id', 'nombre');
        // ------------------------------------------------


        $lstPermisosDisponibles = Permisos::model()->findAll(array(
			'condition'=>'id NOT IN ( '.( empty($model->PermisosSeleccionados) ? 0 : implode(",",$model->PermisosSeleccionados)).')',
			'order' => 'permiso'
		));

        $lstPermisosSeleccionados = Permisos::model()->findAll(array(
			'condition'=>'id IN (  '.(empty($model->PermisosSeleccionados) ? 0 : implode(",",$model->PermisosSeleccionados)).')',
			'order' => 'permiso'
		));

        $lstEmpresasDisponibles = Empresas::model()->findAll(array(
			'condition'=>'id NOT IN ('.( empty($model->EmpresasSeleccionadas) ? 0 : implode(",",$model->EmpresasSeleccionadas) ).')',
			'order' => 'nombre'
		));

        $lstEmpresasSeleccionadas = Empresas::model()->findAll(array(
			'condition'=>'id IN ( '.(empty($model->EmpresasSeleccionadas) ? 0 : implode(",",$model->EmpresasSeleccionadas)).')',
			'order' => 'nombre'
		));

        $this->render('create', array(
        	'model'=>$model,
        	'lstClientes' => $lstClientes,
        	'lstClientesAsignados' => array(),
        	'lstSolicitantes' => $lstSolicitantes,
            'lstSolicitantesAsignados' => array(),
            'permisosDisponibles' => $lstPermisosDisponibles,
            'permisosSeleccionados' => $lstPermisosSeleccionados,
            'empresasDisponibles' => $lstEmpresasDisponibles,
            'empresasSeleccionadas' => $lstEmpresasSeleccionadas,
			'flujos' => Flujos::model()->with('caracteristicas_tipo')->findAll(array(
				'order'=>'caracteristicas_tipo.id, t.nivel_aprobacion ASC'
			)),
			'grupos' => Grupos::model()->findAll()
        ));
	}

	/**
	 * [actionUpdate description]
	 * This is a cool function
	 * @author nixho
	 * @version [version]
	 * @date    2014-06-19
	 * @param   [type]     $id [description]
	 * @return  [type]         [description]
	 */
	public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        // Buscar los permisos del usuario
		$escenario = "";
		$model->PermisosSeleccionados = array_keys( CHtml::listData( $model->permisos,'id','id') );
		$model->EmpresasSeleccionadas = array_keys( CHtml::listData( $model->empresas,'id','id') );
		$model->FlujosSeleccionadas   = array_keys( CHtml::listData( $model->flujos  ,'id','id') );

		$model->PermisosSeleccionados = empty($model->PermisosSeleccionados) ? array() : $model->PermisosSeleccionados;
		$model->EmpresasSeleccionadas = empty($model->EmpresasSeleccionadas) ? array() : $model->EmpresasSeleccionadas;
		$model->FlujosSeleccionadas   = empty($model->FlujosSeleccionadas) ? array() : $model->FlujosSeleccionadas;

        if(isset($_POST['Usuarios']))
        {
        	$model->attributes = $_POST['Usuarios'];
			$model->PermisosSeleccionados = isset($_POST['Usuarios']['PermisosSeleccionados']) ? $_POST['Usuarios']['PermisosSeleccionados'] : array();
        	$model->EmpresasSeleccionadas = isset($_POST['Usuarios']['EmpresasSeleccionadas']) ? $_POST['Usuarios']['EmpresasSeleccionadas'] : array();
        	$model->FlujosSeleccionadas = isset($_POST['Usuarios']['FlujosSeleccionadas']) ? $_POST['Usuarios']['FlujosSeleccionadas'] : array();

        	if (in_array((string)Usuarios::APROBADOR, $model->PermisosSeleccionados) || in_array((string)Usuarios::SUPERVISOR,$model->PermisosSeleccionados))
        	{
			    $escenario = 'flujos';
				$model->scenario='flujos';
			}

            // Validar modelo
            if ($model->validate())
            {
            	try
            	{
	            	// Guardar el modelo
	            	$model->save();

	            	// Eliminar los permisos del usuairo
	            	UsuariosHasPermisos::model()->deleteAll(array('condition'=> 'usuarios_id =:id',
						'params' => array('id' => $id )
					));

	            	// Elminar las empresas relacionadas del usuario
					UsuariosHasEmpresas::model()->deleteAll(array(
						'condition'=> 'usuarios_id =:id','params' => array('id' => $id )
					));

					// Buscar en el catalogo de premisos seleccionados
					$lstPermisos = Permisos::model()->findAll(array(
						'condition'=>'id IN ('.implode(",",$model->PermisosSeleccionados).')',
					));

					// 	Asignar los permisos al usuario
					while (list($key,$value) = each($lstPermisos))
					{
						$usuariosHasPermisos = new UsuariosHasPermisos();
						$usuariosHasPermisos->usuarios_id = $id;
						$usuariosHasPermisos->permisos_id = $value->id;
						$usuariosHasPermisos->save(false);
					}

					// Buscar en el catalogo de empresas donde el id se encunetre en el empresasSeleccionadas
					$lstEmpresas = Empresas::model()->findAll(array(
						'condition'=>'id IN ('.implode(",",$model->EmpresasSeleccionadas).')',
					));

					// Asignar empresas al usuario
					while (list($key,$value) = each($lstEmpresas))
					{
						$usuariosHasEmpresas = new UsuariosHasEmpresas();
						$usuariosHasEmpresas->usuarios_id = $id;
						$usuariosHasEmpresas->empresas_id = $value->id;
						$usuariosHasEmpresas->save(false);
					}

					// Jackfiallos - Establece la relacion entre el usuario y sus clientes
					if ((isset($_POST['Usuarios']['clientes'])) && (!empty($_POST['Usuarios']['clientes'])))
					{
						// Eliminar la relacion entre el solicitante y sus clientes
						UsuariosHasClientes::model()->deleteAll(array(
							'condition'=> 'usuarios_id = :id',
							'params' => array(
								'id' => $id
							)
						));

						foreach($_POST['Usuarios']['clientes'] as $cliente)
						{
							$usuarioCliente = new UsuariosHasClientes();
							$usuarioCliente->usuarios_id = $id;
							$usuarioCliente->clientes_codigo = $cliente;
							$usuarioCliente->save(false);
						}
					}
					else
					{
						// Eliminar la relacion entre el solicitante y sus clientes
						UsuariosHasClientes::model()->deleteAll(array(
							'condition'=> 'usuarios_id = :id',
							'params' => array(
								'id' => $id
							)
						));
					}

					// Jackfiallos - Establece la relacion entre el aprobador y los solicitantes
					if ((isset($_POST['Usuarios']['solicitantes'])) && (!empty($_POST['Usuarios']['solicitantes'])))
					{
						// Eliminar la relacion entre el solicitante y sus clientes
						AprobadoresHasSolicitantes::model()->deleteAll(array(
							'condition'=> 'aprobador = :id',
							'params' => array(
								'id' => $id
							)
						));

						foreach($_POST['Usuarios']['solicitantes'] as $solicitante)
						{
							$aprobadoresSolicitantes = new AprobadoresHasSolicitantes();
							$aprobadoresSolicitantes->aprobador = $model->id;
							$aprobadoresSolicitantes->solicitante = $solicitante;
							$aprobadoresSolicitantes->save(false);
						}
					}
					else
					{
						// Eliminar la relacion entre el solicitante y sus clientes
						AprobadoresHasSolicitantes::model()->deleteAll(array(
							'condition'=> 'aprobador = :id',
							'params' => array(
								'id' => $id
							)
						));
					}

					if ($escenario == 'flujos')
					{
						// Eliminar los reglas de flujo del usuario
						UsuariosHasFlujos::model()->deleteAll(array('condition'=> 'usuarios_id =:id',
							'params' => array('id' => $id )
						));

						// Buscar en el catalogo de flujos id se encuentre en el arregli de flujos seleccionaods
						$lstFlujos = Flujos::model()->findAll(array(
							'condition'=>'id IN ('.implode(",",$model->FlujosSeleccionadas).')',
						));
						// Recorrer el arreglo y crear guardar los registros de flujo usuairo
						while (list($key,$value) = each($lstFlujos))
						{
							$usuariosHasFlujos = new UsuariosHasFlujos();
							$usuariosHasFlujos->usuarios_id = $id;
							$usuariosHasFlujos->flujos_id = $value->id;
							$usuariosHasFlujos->save(false);
						}
					}

                	Yii::app()->user->setFlash('Usuarios.Success', 'Los datos se actualizaron correctamente.');
				}
				catch(Exception $ex)
				{
					$message = $ex->getMessage();
					Yii::app()->user->setFlash('Usuarios.Error','Servicio no disponible.');
					Yii::log($message, CLogger::LEVEL_ERROR, 'application');
				}

				$this->redirect(Yii::app()->createUrl('admin/usuarios/index'));
            }
        }

		// ------------------------------------------------
        // Encontrar todos los clientes relacionados al usuario
        $clientesAsignados = Clientes::model()->with('usuarios')->together()->findAll(array(
        	'condition' => 'usuarios.id = :id AND TRIM(t.nombre) IS NOT NULL',
        	'order' => 't.codigo ASC',
        	'params' => array(
        		':id' => $id
        	)
        ));
        $lstClientesAsignados = CHtml::listData($clientesAsignados, 'codigo', 'CodigoNombre');

        // Filtrar todos los clientes encontrados (ids)
        $clientesIds = array(0);
        if ($lstClientesAsignados != null)
        {
	        foreach ($clientesAsignados as $clientes_)
	        {
				if (is_numeric($clientes_->codigo))
				{
	        		array_push($clientesIds, $clientes_->codigo);
				}
	        }
	    }

	    // Obtener los demas clientes
	    $clientes = Clientes::model()->findAll(array(
	    	'condition' => 't.codigo NOT IN ('.implode(',', $clientesIds).') AND TRIM(t.nombre) != \'\'',
	    	'order' => 't.codigo ASC'
	    ));
        $lstClientes = CHtml::listData($clientes, 'codigo', 'CodigoNombre');

        // Encontrar todos los solicitantes relacionados
        $solicitantesAsignados = AprobadoresHasSolicitantes::model()->with('usuarios')->together()->findAll(array(
        	'condition' => 't.aprobador = :id',
        	'params' => array(
        		':id' => $id
        	)
        ));

        $solicitantesIds = array(1, $id); // incluir el administrador y al usuario mismo
        $solicitantesList = array();
        if ($solicitantesAsignados != null)
        {
	        foreach ($solicitantesAsignados as $clientes_)
	        {
	        	array_push($solicitantesIds, $clientes_->solicitante);
	        	array_push($solicitantesList, $clientes_->usuarios);
	        }
	    }
	    $lstSolicitantesAsignados = CHtml::listData($solicitantesList, 'id', 'nombre');

        $solicitantes = Usuarios::model()->findAll(array(
        	'condition' => 't.id NOT IN ('.implode(',', $solicitantesIds).')',
        ));
        $lstSolicitantes = CHtml::listData($solicitantes, 'id', 'nombre');
        // ------------------------------------------------


        $lstPermisosDisponibles = Permisos::model()->findAll(array(
			'condition'=>'id NOT IN ( '.( empty($model->PermisosSeleccionados) ? 0 : implode(",",$model->PermisosSeleccionados)).')',
			'order' => 'permiso'
		));

        $lstPermisosSeleccionados = Permisos::model()->findAll(array(
			'condition'=>'id IN (  '.(empty($model->PermisosSeleccionados) ? 0 : implode(",",$model->PermisosSeleccionados)).')',
			'order' => 'permiso'
		));

        $lstEmpresasDisponibles = Empresas::model()->findAll(array(
			'condition'=>'id NOT IN ('.( empty($model->EmpresasSeleccionadas) ? 0 : implode(",",$model->EmpresasSeleccionadas) ).')',
			'order' => 'nombre'
		));

        $lstEmpresasSeleccionadas = Empresas::model()->findAll(array(
			'condition'=>'id IN ( '.(empty($model->EmpresasSeleccionadas) ? 0 : implode(",",$model->EmpresasSeleccionadas)).')',
			'order' => 'nombre'
		));

        $this->render('update', array(
            'model'=>$model,
            'lstClientes' => $lstClientes,
            'lstClientesAsignados' => $lstClientesAsignados,
            'lstSolicitantes' => $lstSolicitantes,
            'lstSolicitantesAsignados' => $lstSolicitantesAsignados,
            'permisosDisponibles' => $lstPermisosDisponibles,
            'permisosSeleccionados' => $lstPermisosSeleccionados,
            'empresasDisponibles' => $lstEmpresasDisponibles,
            'empresasSeleccionadas' => $lstEmpresasSeleccionadas,
			'flujos' => Flujos::model()->with('caracteristicas_tipo')->findAll(array(
				'order'=>'caracteristicas_tipo.id, t.nivel_aprobacion ASC'
			)),
			'grupos' => Grupos::model()->findAll()
        ));
    }

    /**
     * [loadModel description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function loadModel($id)
    {
        $model = Usuarios::model()->with('flujos', 'empresas')->findByPk($id);

        if($model === null)
        {
            throw new CHttpException(404,'The requested page does not exist.');
        }

        return $model;
    }
}
