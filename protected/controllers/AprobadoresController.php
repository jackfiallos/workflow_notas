<?php
/**
 * AprobadoresController class file
 * 
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-07-10
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class AprobadoresController extends Controller
{
	/**
	 * Especifica los controles de acceso
	 * @author:  Qbit Mexhico
	 * @revised: Jackfiallos
	 * @date:    2014-07-10
	 * @link:    [link]
	 * @version: [version]
	 * @return   [type]      [description]
	 */
	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('index','update'),
				'users'=>array('@'),
				'expression'=>'(!$user->isGuest && Yii::app()->user->verifyRole(Usuarios::APROBADOR))'
			),
			array('deny',  // deny all users
				'users'=>array('*')
			)
		);
	}

	/**
	 * Control de acceso y curacion de campos de texto
	 * @author:  Qbit Mexhico
	 * @revised: Jackfiallos
	 * @date:    2014-07-10
	 * @link:    [link]
	 * @version: [version]
	 * @return   [type]      [description]
	 */
	public function filters()
	{
    	return array(
    		'accessControl',
        	array(
            	'application.filters.YXssFilter',
                'clean'   => '*',
                'tags'    => 'strict',
				'actions' => 'all'
            )
		);
	}

	/**
	 * [actionIndex description]
	 * Lista de solicitudes pendientes
	 * @author nixho
	 * @version [version]
	 * @date    2014-06-20
	 * @param   [type]     $id [description]
	 * @return  [type]         [description]
	 */
	public function actionIndex()
	{
		$model = new Notas('searchAprobadores');
        $model->unsetAttributes();

        if(isset($_POST['Notas']))
        {
            $model->attributes = $_POST['Notas'];
        }

		$usuario = Yii::app()->request->getParam('filterUsuario', '');
		$cliente = Yii::app()->request->getParam('filterCliente', ''); 
		$categoria = Yii::app()->request->getParam('filterCaracteristica', 0);

		$f = array();
		$bloqueadas = array(Notas::REV_APROBADO, Notas::REV_RECHAZADO, Notas::REV_PROCESADO, Notas::REV_DEVOLUCIONES);

		// Obtener todas las solicitudes
		$solicitudes = Notas::model()->with('razones.caracteristicas_tipo', 'cflujos')->together()->findAll(array(
			'condition' => 't.estatus = :estatus AND t.revision NOT IN('.implode(",", $bloqueadas).')',
			'params' => array(
				':estatus' => Notas::ESTATUS_PUBLICADO,
			)
		));

		// Por cada solicitud encontrada, tomar su caracteristica tipo y determinar el flujo que corresponde
		foreach ($solicitudes as $solicitud)
		{
			// Esto es una excepcion ya que no es a nivel caracteristica sino a nivel razon
			if (($solicitud->razones->codigo == 'RF11') || ($solicitud->razones->codigo == 'RF12'))
			{
				// Se tomara el primer usuario que se tenga para revision de D1 en nivel 1
				$nivel_aprobacion = 1;		
				$UsuarioLogistica = Usuarios::model()->with('flujos.caracteristicas_tipo')->together()->find(array(
					'condition' => 'caracteristicas_tipo.codigo = :codigo AND flujos.nivel_aprobacion = :nivel',
					'params' => array(
						':codigo' => 'D1',
						':nivel' => '1'
					)
				));

				// Luego de determinar si un usuario de logistica puede aprobarla, se compara el id del usuario encontrado contra el id del actual
				if (($UsuarioLogistica != null) && ($UsuarioLogistica->id == Yii::app()->user->id))
				{
					array_push($f, $solicitud->id);
				}
			}

			// Obtener los flujos en los que puede participar el usuario
			$flujos = Flujos::model()->with('usuarios')->together()->findAll(array(
				'condition' => 't.caracteristicasTipo_id = :tipo AND usuarios.id = :id',
				'params' => array(
					':tipo' => $solicitud->razones->caracteristicas_tipo->id,
					':id' => Yii::app()->user->id
				)
			));

			// Recorrer cada flujo
			foreach ($flujos as $flujo)
			{
				// Determinar el usuario de cada flujo para cada solicitud y crear el arreglo
				foreach ($flujo->usuarios as $usuario)
				{
					$cumple = false;

					// Si existe una expresion que validar, hacerlo para determinar que usuario debe atenderlo
					if (!is_null($flujo->expresion))
					{
						if (eval('return (Tools::GetTotal($solicitud) '.$flujo->expresion.');')) 
						{
							$cumple = true;
						}
					}
					else
					{
						$cumple = true;
					}

					$solicitantes = AprobadoresHasSolicitantes::model()->findAll(array(
						'condition' => 't.aprobador = :id',
						'params' => array(
							':id' => $usuario->id
						)
					));

					$usuarios_solicitantes = array();
					if ($solicitantes !== null)
					{
						foreach ($solicitantes as $solicitante)
						{
							array_push($usuarios_solicitantes, $solicitante->solicitante);
						}
					}

					if (($cumple) && (in_array($solicitud->usuarios_id, $usuarios_solicitantes)))
					{
						// Obtener el no. de flujos que ya revisaron la nota
						$notaFlujo = NotasHasFlujos::model()->count(array(
							 'condition' => 't.notas_id = :notaId',
							 'params' => array(
							 	':notaId' => $solicitud->id
							 )
						));

						// Si el no. de flujo + 1 = al nivel de aprobacion del usuario, entonces permitir mostrar la nota
						if (((int)$notaFlujo + 1) == $flujo->nivel_aprobacion)
						{
							array_push($f, $solicitud->id);
						}
					}
				}
			}
		}

		$model->f = $f;
		
		// Clientes
		$modelClientes = Clientes::model()->with('empresas')->together()->findAll(array(
			'condition' => 'empresas.id = :empresa_id',
			'params' => array(
				':empresa_id' => Yii::app()->user->getState('empresa_id')
			),
			'order' => 't.codigo ASC'
		));
		$listClientes = CHtml::listData($modelClientes, 'codigo', 'CodigoNombre');

		$this->render('index', array(
			'model' => $model,	
			'filterUsuario' => $usuario,
			'filterCliente' => $cliente,
			'filterCaracteristica' => $categoria,
			'clientes' => $listClientes
		));
	}

	/**
	 * [actionUpdate description]
	 * update solicitud
	 * @author nixho
	 * @version [version]
	 * @date    2014-06-20
	 * @param   [type]     $id [description]
	 * @return  [type]         [description]
	 */
	public function actionUpdate($id)
	{
		// Validar que el usuario pueda ver la nota 
		if (!Tools::UsuarioConNotaValida($id))
		{
			Yii::app()->user->setFlash('Aprobadores.Error','No tienen privilegios para ver el detalle de la nota.');
			$this->redirect(Yii::app()->createUrl('aprobadores/index'));
		}

		// Buscar nota y crear modelo 
		$nota = Notas::model()->findByPk($id);
		$model = new SolicitudesAprobarForm();

		// Validar el post del fomulario
		if(isset($_POST['SolicitudesAprobarForm']))
		{
			$model->attributes = $_POST['SolicitudesAprobarForm'];

			// Validar modelo cumpla con las reglas  de validacion
			if($model->validate())
			{
				// Guardar datos
				try
				{
					Tools::SaveNota( $nota , $model );
					Yii::app()->user->setFlash('Aprobadores.Success','Los datos se guardaron correctamente.');
				}
				catch(Exception $ex)
				{
					$message = $ex->getMessage();
					Yii::app()->user->setFlash('Aprobadores.Error','Servicio no disponible.');
					Yii::log($message, CLogger::LEVEL_ERROR, 'application');
				}
                
				$this->redirect(Yii::app()->createUrl('aprobadores/index'));
			}
		}

		// Encontrar si el cliente tiene descuentos disponibles
		$descuento = DescuentoClientes::model()->find(array(
			'condition' => 't.clientes_codigo = :codigo',
			'params' => array(
				':codigo' => $nota->clientes_codigo
			)
		));

		$this->render('update', array(
			'nota' => $nota,
			'descuento' => $descuento,
			'model' => $model,
			'lstAgnios' => Anio::model()->findAll(),
			'documentos' => new CActiveDataProvider('Documentos',array(
				'criteria' => array(
					'condition' => 't.notas_id=:notaId',
					'params' => array(
						':notaId' => $id
					)
				),
				'pagination'=>array(
					'pageSize'=> 100
				)
			)),
			'historial' =>  Historial::model()->with('usuarios')->together()->findAll(array(
				'condition' => 't.notas_id = :notaId',
				'params' => array(
					':notaId' => $id
				)
			)),
			'productos' =>  new CActiveDataProvider( 'Productos' , array(
				'criteria' => array(
					'with'=> array('precios','cnotas'),
					'together'=>true,
					'condition' => 'cnotas.notas_id = :id && precios.anio_id=:agnio ',
		            'params' => array(
		                ':id' =>  $nota->id,
		                ':agnio' =>  $nota->anio_id
		            )	
				),
				'pagination'=>array(
					'pageSize'=> 1000
				)
	        )),
			'descuentos' => new CActiveDataProvider( 'MarcasHasDescuentos' , array(
				'criteria' => array(
					'with'=> array('marcas'),
					'condition' => 't.descuentos_id = :id ',
		            'params' => array(
		                ':id' =>  (empty($nota->descuentos)) ? 0 : $nota->descuentos->id ,
		            )	
				),
				'pagination'=>array(
					'pageSize'=> 1000
				)
	        )),
			'facturas' => new CActiveDataProvider( 'Facturas' , array(
				'criteria' => array(
					'condition' => 't.folio=:folioFactura',
		            'params' => array(
		                 ':folioFactura' => empty($nota->num_factura) ? 0 : $nota->num_factura 
		            )	
				),
				'pagination'=>array(
					'pageSize'=> 1000
				)
	        )),
			'cooperacion' => new CActiveDataProvider( 'MarcasHasCooperacion' , array(
				'criteria' => array(
					'with'=> array('marcas'),
					'condition' => 't.cooperacion_id = :id ',
		            'params' => array(
		                ':id' =>  (empty($nota->cooperacion)) ? 0 : $nota->cooperacion->id ,
		            )	
				),
				'pagination'=>array(
					'pageSize'=> 1000
				)
	        )),
		));
	}
}