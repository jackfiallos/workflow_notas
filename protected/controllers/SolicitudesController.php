<?php
/**
 * SolicitudesController class file
 *
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-07-10
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 *
 */
class SolicitudesController extends Controller
{
	/**
	 * @var resourceFolder saved inside all uploaded images
	 */
	const FOLDERIMAGES = '/protected/files/';

	/**
	 * @var string temporal filename
	 */
	private $tmpFileName = '';

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
				'actions'=>array('index', 'create', 'update', 'view', 'crear',
					'NotasAlmacen', 'DescuentosComerciales', 'CooperacionCliente', 'Refacturaciones', 'Provision',
					'SelectRazon', 'SelectCliente', 'SelectProductos',
					'Fileupload', 'FileDelete', 'Publicar',
					//'GuardarProductos', 'EliminarProducto',
					'ActualizaProducto', 'EliminarProducto', 'ActualizaCantidadProducto', 'ActualizaAceptacion',
					'GuardarDescuentos', 'EliminarDescuento', 'ActualizaImporteMarcas',
					'GuardarDescuentosCooperacion', 'EliminarDescuentoCooperacion', 'ActualizaImporteMarcasCooperacion',
					'BuscarFactura', 'ObtenerListaProductos', 'ObtenerTotalFactura', 'EliminaRelacionFactura'
				),
				'users'=>array('@'),
				'expression'=>'(!$user->isGuest && $user->verifyRole(Usuarios::SOLICITUD))'
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
	 * [actionSelectTipoCaracteristicas description]
	 * @return [type] [description]
	 */
	public function actionSelectRazon()
	{
		$output = '';

		if (Yii::app()->request->isPostRequest)
		{
			$model = new SolicitudesForm();
			if (isset($_POST['SolicitudesForm']) && (!empty($_POST['SolicitudesForm'])))
			{
				$model->attributes = $_POST['SolicitudesForm'];
			}

			$criteria = new CDbCriteria();
			$criteria->condition = 't.caracteristicasTipo_id = :id';
			$criteria->order = 't.id ASC';
			$criteria->params = array(
				':id' => $model->caracteristica
			);

			$data = Razones::model()->findAll($criteria);

			if ($data !== null)
			{
				$output = CHtml::tag('option', array('value'=>''), 'Selecciona una raz&oacute;n');
			    foreach($data as $row)
			    {
			        $output .= CHtml::tag('option', array('value'=>$row->id), CHtml::encode($row->nombre));
			    }
			}
		}

		echo $output;
		Yii::app()->end();
	}

	/**
	 * [actionSelectCliente description]
	 * @return [type] [description]
	 */
	public function actionSelectCliente()
	{
		$output = '';

		if (Yii::app()->request->isPostRequest)
		{
			$model = new SolicitudesForm();
			if (isset($_POST['SolicitudesForm']) && (!empty($_POST['SolicitudesForm'])))
			{
				$model->attributes = $_POST['SolicitudesForm'];
			}

			$criteria = new CDbCriteria();
			// TODO
			//$criteria->condition = 't.sucursales_codigo = :id';
			$criteria->order = 't.nombre ASC';
			$criteria->params = array(
				':id' => $model->sucursal
			);

			$data = Clientes::model()->findAll($criteria);

			if ($data !== null)
			{
				$output = CHtml::tag('option', array('value'=>''), 'Selecciona un Cliente');
			    foreach($data as $row)
			    {
			        $output .= CHtml::tag('option', array('value'=>$row->codigo), CHtml::encode($row->nombre));
			    }
			}
		}

		echo $output;
		Yii::app()->end();
	}

	/**
	 * [actionSelectProductos description]
	 * @return [type] [description]
	 */
	public function actionSelectProductos()
	{
		$output = '';

		if (Yii::app()->request->isPostRequest)
		{
			$model = new SolicitudesForm();
			if (isset($_POST['SolicitudesForm']) && (!empty($_POST['SolicitudesForm'])))
			{
				$model->attributes = $_POST['SolicitudesForm'];
			}

			$criteria = new CDbCriteria();
			$criteria->condition = 'precios.anio_id = :id';
			$criteria->order = 't.id ASC';
			$criteria->params = array(
				':id' => $model->anio
			);
			$criteria->group = 't.id';

			$data = Productos::model()->with('precios')->together()->findAll($criteria);

			if ($data !== null)
			{
				$output = CHtml::tag('option', array('value'=>''), 'Selecciona un Producto');
			    foreach($data as $row)
			    {
			        $output .= CHtml::tag('option', array('value'=>$row->id), CHtml::encode($row->codigo).' - '.CHtml::encode($row->descripcion));
			    }
			}
		}

		echo $output;
		Yii::app()->end();
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$model = new Notas('searchSolicitantes');
        $model->unsetAttributes();

        if(isset($_POST['Notas']))
        {
            $model->attributes = $_POST['Notas'];
        }

        $usuario = Usuarios::model()->with('grupo')->together()->find(array(
			'condition' => 't.id = :id',
			'params' => array(
				':id' => Yii::app()->user->id
			)
		));

		// Clientes
		$modelClientes = Clientes::model()->with('empresas', 'usuarios')->together()->findAll(array(
			'condition' => 'empresas.id = :empresa_id AND usuarios.id = :usuarios_id',
			'params' => array(
				':empresa_id' => Yii::app()->user->getState('empresa_id'),
				':usuarios_id' => Yii::app()->user->id
			),
			'order' => 't.codigo ASC'
		));

		$listClientes = CHtml::listData($modelClientes, 'codigo', 'CodigoNombre');

        $this->render('index', array(
            'model' => $model,
			'grupo' => $usuario->grupo->id,
			'clientes' => $listClientes
        ));
	}

	/**
	 * [actionCreate description]
	 * @return [type] [description]
	 */
	private function CrearSolicitud($tipo_nota)
	{
		$model = new SolicitudesForm();
		$listRazones = array();

		// Determinar los escenarios y el tipo de escenario
		if ($tipo_nota == Caracteristicas::DESCUENTOS)
		{
			$model->scenario = 'descuento';
			$model->tipo_nota = Caracteristicas::DESCUENTOS;
		}
		else if ($tipo_nota == Caracteristicas::COOPERACION)
		{
			$model->scenario = 'cooperacion';
			$model->tipo_nota = Caracteristicas::COOPERACION;
		}
		else if ($tipo_nota == Caracteristicas::REFACTURACION)
		{
			$model->scenario = 'refacturacion';
			$model->tipo_nota = Caracteristicas::REFACTURACION;
		}
		else if ($tipo_nota == Caracteristicas::PROVISION)
		{
			$model->scenario = 'provision';
			$model->tipo_nota = Caracteristicas::PROVISION;
		}
		else
		{
			$model->scenario = 'almacen';
			$model->tipo_nota = Caracteristicas::ALMACEN;
		}

		// Tomar los datos del formulario y almacenar
		if (isset($_POST['SolicitudesForm']))
		{
			$model->attributes = $_POST['SolicitudesForm'];

			// enviar la lista de razones si ya se tiene una caracteristica (evita el uso de ajax)
			if (isset($model->caracteristica))
			{
				$criteria = new CDbCriteria();
				$criteria->condition = 't.caracteristicasTipo_id = :id';
				$criteria->order = 't.id ASC';
				$criteria->params = array(
					':id' => $model->caracteristica
				);

				$modelRazon = Razones::model()->findAll($criteria);
				$listRazones = CHtml::listData($modelRazon, 'id', 'nombre');
			}

			if ($model->validate())
			{
				if ($model->save())
				{
					Yii::app()->user->setFlash('Solicitudes.Guardar', 'Solicitud guardada exitosamente');
					$this->redirect(Yii::app()->createUrl('solicitudes/update', array('id'=>$model->id)));
				}
			}
		}

		// Obtener datos para los dropdowns de caracteristicas
		$criteria = new CDbCriteria();
		$criteria->condition = 't.caracteristicas_id = :id';

		// Solamente aplica para descuentos y farma
		if (($tipo_nota == Caracteristicas::DESCUENTOS) && ((int)Yii::app()->user->getState('empresa_id') == 2))
		{
			$criteria->addCondition('t.codigo != "DF1"');
		}

		$criteria->order = 't.id ASC';
		$criteria->params = array(
			':id' => $tipo_nota
		);

		$modelCaracteristicas = Caracteristicastipo::model()->findAll($criteria);
		$listCaracteristicas = CHtml::listData($modelCaracteristicas, 'id', 'nombre');

		// Clientes
		$modelClientes = Clientes::model()->with('empresas', 'usuarios')->together()->findAll(array(
			'condition' => 'empresas.id = :empresa_id AND usuarios.id = :usuarios_id',
			'params' => array(
				':empresa_id' => Yii::app()->user->getState('empresa_id'),
				':usuarios_id' => Yii::app()->user->id
			),
			'order' => 't.codigo ASC'
		));

		$listClientes = CHtml::listData($modelClientes, 'codigo', 'CodigoNombre');

		// TODO: Se muestran todas las sucursales porque no definieron como está esta relacion con clientes o empresas
		//$modelSucursales = Sucursales::model()->findAll();
		//$listSucursales = CHtml::listData($modelSucursales, 'codigo', 'nombre');

		// Años
		$modelAnios = Anio::model()->findAll();
		$listAnios = CHtml::listData($modelAnios, 'id', 'anio');

		return array(
			'model' => $model,
			'caracteristicas' => $listCaracteristicas,
			//'sucursales' => $listSucursales,
			'anios' => $listAnios,
			'razones' => $listRazones,
			'clientes' => $listClientes
		);
	}

	/**
	 * [actionCrear description]
	 * @return [type] [description]
	 */
	public function actionCrear()
	{
		$this->render('crear');
	}

	/**
	 * [actionNotasAlmacen description]
	 * @return [type] [description]
	 */
	public function actionNotasAlmacen()
	{
		$response = $this->CrearSolicitud(Caracteristicas::ALMACEN);
		$this->render('create', array_merge($response,
			array('title' => 'Generar nueva solicitud de Nota de Almac&eacute;n')
		));
	}

	/**
	 * [actionDescuentosComerciales description]
	 * @return [type] [description]
	 */
	public function actionDescuentosComerciales()
	{
		$response = $this->CrearSolicitud(Caracteristicas::DESCUENTOS);
		$this->render('create', array_merge($response,
			array('title' => 'Generar nueva solicitud de Descuentos Comerciales')
		));
	}

	/**
	 * [actionCooperacionCliente description]
	 * @return [type] [description]
	 */
	public function actionCooperacionCliente()
	{
		// Solamente Dermo puede generar este tipo de notas
		if ((int)Yii::app()->user->getState('empresa_id') == 2)
		{
			throw new CHttpException(403,'No tienes permiso para ver el contenido de esta p&aacute;gina.');
		}

		$response = $this->CrearSolicitud(Caracteristicas::COOPERACION);
		$this->render('create', array_merge($response,
			array('title' => 'Generar nueva solicitud de Cooperaci&oacute;n al Cliente')
		));
	}

	/**
	 * [actionRefacturaciones description]
	 * @return [type] [description]
	 */
	public function actionRefacturaciones()
	{
		$response = $this->CrearSolicitud(Caracteristicas::REFACTURACION);
		$this->render('create', array_merge($response,
			array('title' => 'Generar nueva solicitud de Refacturaci&oacute;n')
		));
	}

	public function actionProvision()
	{
		$response = $this->CrearSolicitud(Caracteristicas::PROVISION);
		$this->render('create', array_merge($response,
			array('title' => 'Generar nueva solicitud de Provisi&oacute;n')
		));
	}

	/**
	 * [actionView description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionView($id)
	{
		$nota = Notas::model()->with('descuentos', 'anio', 'clientes', 'usuarios', 'razones.caracteristicas_tipo.caracteristicas')->together()->find(array(
			'condition' => 't.id = :id AND t.usuarios_id = :usuario',
			'params' => array(
				':id' => $id,
				':usuario' => Yii::app()->user->id
			)
		));

		if ($nota === null)
		{
			throw new CHttpException(403,'No tienes permiso para ver el contenido de esta p&aacute;gina.');
		}

		// Encontrar si el cliente tiene descuentos disponibles
		$descuento = DescuentoClientes::model()->find(array(
			'condition' => 't.clientes_codigo = :codigo',
			'params' => array(
				':codigo' => $nota->clientes_codigo
			)
		));

		$this->render('view', array(
			'nota' => $nota,
			'descuento' => $descuento,
			'id' => $id,
			'lstAgnios' => Anio::model()->findAll(),
			'documentos' => new CActiveDataProvider('Documentos',array(
				'criteria' => array(
					'condition' => 't.notas_id=:notaId',
					'order' => 't.id ASC',
					'params' => array(
						':notaId' => $id
					)
				),
				'pagination'=>array(
					'pageSize'=> 50
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
					'order' => 't.id ASC',
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

	/**
	 * [actionUpdate description]
	 * @return [type] [description]
	 */
	public function actionUpdate($id)
	{
		$jsonData = array();
		$marcasArray = array();

		$nota = Notas::model()->with('razones.caracteristicas_tipo.caracteristicas', 'clientes', 'descuentos')->together()->find(array(
			'condition' => 't.id = :id AND t.estatus = :estatus AND t.usuarios_id = :usuario',
			'params' => array(
				':id' => $id,
				':estatus' => Notas::ESTATUS_BORRADOR,
				':usuario' => Yii::app()->user->id
			)
		));

		// Esta es una nota existente
		if ($nota !== null)
		{
			// verificar que solo el usuario que creo la nota puede modificarla
			if ($nota->usuarios_id == Yii::app()->user->id)
			{
				$model = new SolicitudesForm();
				$model->tipo_nota = $nota->razones->caracteristicas_tipo->caracteristicas->id;
				$model->caracteristica = $nota->razones->caracteristicas_tipo->id;
				$model->fecha_vencimiento = $nota->fecha_vencimiento;
				$model->razon = $nota->razones_id;
				$model->factura_id = $nota->num_factura;
				//$model->sucursal = $nota->clientes->sucursales->codigo;
				$model->cliente = $nota->clientes_codigo;
				$model->anio = $nota->anio_id;
				//$model->catalogo_precios = $nota->indice;
				$model->comentario = $nota->comentario;
				$model->cancela_sustituye = $nota->cancela_sustituye;

				if ($model->tipo_nota == Caracteristicas::ALMACEN)
				{
					$model->scenario = 'almacen';
					$title = 'Nota de Cr&eacute;dito de Almac&eacute;n';
					$seleccion = array();
					$i = 0;

					// Lista de productos (si existen)
					$modelProductos = Productos::model()->with('cnotas','precios.cproductos')->together()->findAll(array(
						'condition' => 'cnotas.notas_id = :id AND cproductos.catClientes_codigo = :codigo AND precios.anio_id = :agnio',
						'params' => array(
							':id' => $id,
							':codigo' => $nota->clientes_codigo,
							':agnio' => $nota->anio_id
						)
					));

					foreach($modelProductos as $productos)
					{
						array_push($jsonData, array(
							'id' => $productos->id,
							'descripcion' => $productos->descripcion,
							'codigo' => $productos->codigo,
							'cantidad' => $productos->cnotas[0]->cantidad_piezas,
							'precio' => $productos->precios[0]->precio,
							'impuesto' => $productos->precios[0]->anio->impuesto,
							'descuento' => 0,
							'aceptacion' => $productos->cnotas[0]->aceptacion,
							'no_iva' => false
						));
					}

					//** ESTO SOLAMENTE ES PARA PINTAR LA NUEVA ESTRUCTURA (MARCAS, LINEAS, PRODUCTOS)
					// Obtener todas las marcas disponibles
					$marcas = Marcas::model()->findAll(array(
						//'limit' => 1,
						'condition' => 't.es_gama = :gama',
						'params' => array(
							':gama' => Marcas::SOLO_MARCAS
						)
					));

					foreach($marcas as $marca)
					{
						$lineas = array();

						// Con el id de la marca encontrar todos los productos
						$Productos = Productos::model()->with('precios.cproductos')->together()->findAll(array(
							'select' => 'precios.*',
							'condition' => 't.marcas_id = :marca AND cproductos.catClientes_codigo = :codigo',
							'params' => array(
								':codigo' => $nota->clientes_codigo,
								':marca' => $marca->id
							)
						));

						// Iterar cada producto para encontrar a que linea pertenece
						foreach ($Productos as $producto)
						{
							// Encontrar la linea a la que pertenece el producto
							if (!in_array($producto->linea, $lineas))
							{
								array_push($lineas, $producto->linea);
							}
						}

						$l = array();
						foreach ($lineas as $linea)
						{
							array_push($l, array(
								'titulo' => $linea,
								'productos' => array()
							));
						}

						foreach($Productos as $producto)
						{
							$aplica_descuento = 0;
							$no_iva = false;

							// Encontrar cada precio del producto
							foreach ($producto->precios as $precio)
							{
								if (in_array($producto->linea, $lineas))
								{
									$cantidad_piezas = 0;
									$porcentaje_aceptacion = 100;
									foreach ($jsonData as $data)
									{
										if ($data['codigo'] == $producto->codigo)
										{
											$cantidad_piezas = $data['cantidad'];
											$porcentaje_aceptacion = $data['aceptacion'];
											break;
										}
									}

									$l[array_search($producto->linea, $lineas)]['productos'][] = array(
										'id' => $producto->id,
										'index' => $i++,
										'descripcion' => $producto->descripcion,
										'codigo' => $producto->codigo,
										'precio' => $precio->precio,
										'impuesto' => $precio->anio->impuesto,
										'descuento' => $aplica_descuento,
										'no_iva' => $no_iva,
										'aceptacion' => $porcentaje_aceptacion,
										'cantidad' => $cantidad_piezas
									);
								}
							}
						}

						if (!empty($l))
						{
							array_push($marcasArray, array(
								'url' => strtolower($marca->marca),
								'marca' => strtoupper($marca->marca),
								'logo' => 'logo-'.str_replace("-", "", strtolower($marca->marca)).'.jpg',
								'backgroundcolor' => $marca->color,
								'color' => '#FFFFFF',
								'current' => ($marca->id == 1) ? true : null,
								'lineas' => $l
							));
						}

						$l = array();
					}
					//** FIN DE LA NUEVA ESTRUCTURA
				}
				else if ($model->tipo_nota == Caracteristicas::DESCUENTOS)
				{
					$model->scenario = 'descuento';
					$title = 'Nota de Cr&eacute;dito de Descuento';

					// Porque solamente las notas de tipo campaña pueden ver las gamas
					$incluirGamas = null;
					if ($nota->razones->caracteristicas_tipo->codigo != 'DC1')
					{
						$incluirGamas = 'AND t.es_gama = 0';
					}

					// Marcas
					$marcas = Marcas::model()->with('empresas.usuarios')->together()->findAll(array(
						'condition' => 'empresas.id = :empresa_id AND usuarios.id = :usuarios_id '.$incluirGamas,
						'params' => array(
							':empresa_id' => Yii::app()->user->getState('empresa_id'),
							':usuarios_id' => Yii::app()->user->id
						),
					));

					$seleccion = CHtml::listData($marcas, 'id', 'FullCode');
					$model->scenario = 'descuento';

					$descuento = Descuentos::model()->find(array(
						'condition' => 't.notas_id = :notas',
						'params' => array(
							':notas' => $nota->id
						)
					));

					if ($descuento !== null)
					{
						$model->importe = $descuento->importe;
						$model->mes_aplicacion = $descuento->mes_aplicacion;
					}

					$modelMarcas = MarcasHasDescuentos::model()->with('marcas')->together()->findAll(array(
						'condition' => 't.descuentos_id = :id',
						'order' => 't.id ASC',
						'params' => array(
							':id' => !empty($nota->descuentos->id) ? $nota->descuentos->id: 0
						)
					));

					foreach($modelMarcas as $brand)
					{
						array_push($jsonData, array(
							'response' => true,
							'id' => $brand->id,
							'importe' => $brand->importe,
							'codigo' => $brand->marcas->codigo,
							'marca' => $brand->marcas->marca,
							'monto' => $nota->descuentos->importe
						));
					}
				}
				else if ($model->tipo_nota == Caracteristicas::COOPERACION)
				{
					$model->scenario = 'cooperacion';
					$title = 'Nota de Cr&eacute;dito de Cooperaci&oacute;n';

					// Marcas
					$marcas = Marcas::model()->with('empresas.usuarios')->together()->findAll(array(
						'condition' => 'empresas.id = :empresa_id AND usuarios.id = :usuarios_id AND t.es_gama = 0',
						'params' => array(
							':empresa_id' => Yii::app()->user->getState('empresa_id'),
							':usuarios_id' => Yii::app()->user->id
						),
					));
					$seleccion = CHtml::listData($marcas, 'id', 'FullCode');
					$model->scenario = 'cooperacion';

					$cooperacion = Cooperacion::model()->find(array(
						'condition' => 't.notas_id = :notas',
						'params' => array(
							':notas' => $nota->id
						)
					));

					$model->mes_aplicacion = $cooperacion->mes_aplicacion;

					if ($cooperacion !== null)
					{
						$model->importe = $cooperacion->importe;
					}

					$modelMarcas = MarcasHasCooperacion::model()->with('marcas')->together()->findAll(array(
						'condition' => 't.cooperacion_id = :id',
						'params' => array(
							':id' => $cooperacion->id
						)
					));

					foreach($modelMarcas as $brand)
					{
						array_push($jsonData, array(
							'response' => true,
							'id' => $brand->id,
							'importe' => $brand->importe,
							'codigo' => $brand->marcas->codigo,
							'marca' => $brand->marcas->marca,
							'monto' => $cooperacion->importe
						));
					}
				}
				else
				{
					$model->scenario = 'refacturacion';
					$title = 'Nota de Cr&eacute;dito de Refacturaci&oacute;n';

					$seleccion = array();

					$facturas = Facturas::model()->with('clientes')->together()->findAll(array(
						'condition' => 't.folio = :folio',
						'params' => array(
							':folio' => $model->factura_id
						)
					));

					if ($facturas !== null)
					{
						$jsonData = array(
							'id' => $facturas[0]->id,
							'orden_compra' => $facturas[0]->orden_compra,
							'cliente' => $facturas[0]->clientes->nombre,
							'folio' => $facturas[0]->folio,
							'fecha' => $facturas[0]->fecha,
							'monto' => $facturas[0]->monto,
							'productos' => array(),
						);

						foreach($facturas as $producto)
						{
							array_push($jsonData['productos'], array(
								'codigo' => $producto->codigo_producto,
								'descripcion' => $producto->descripcion_producto,
								'precio' => $producto->precio_unitario,
								'cantidad' => $producto->cantidad_piezas,
								'costo_iva' => $producto->costo_iva
							));
						}
					}
				}

				// Tomar los datos del formulario y almacenar
				if (isset($_POST['SolicitudesForm']))
				{
					$model->attributes = $_POST['SolicitudesForm'];
					$model->id = $id;
					$model->estatus = Notas::ESTATUS_BORRADOR;
					$model->entry = 'Registro Actualizado';

					if ($model->validate())
					{
						if ($model->save($id))
						{
							if ($model->tipo_nota == Caracteristicas::DESCUENTOS)
							{
								// Verificar si se modifico el no. de factura
								if ((!empty($model->factura_id)) && ($model->factura_id != $nota->num_factura))
								{
									// Encontrar la unica relacion con el registro de descuentos
									$descuento = Descuentos::model()->find(array(
										'condition' => 't.notas_id = :nota',
										'params' => array(
											':nota' => $nota->id
										)
									));

									// Solamente si se encuentran descuentos
									if ($descuento !== null)
									{
										$borrarDescuentos = MarcasHasDescuentos::model()->deleteAll(array(
											'condition' => 'descuentos_id = :descuentos',
											'params' => array(
												':descuentos' => $descuento->id
											)
										));

										if ($borrarDescuentos)
										{
											$descuento->delete();
										}
									}
								}
							}

							Yii::app()->user->setFlash('Solicitudes.Guardar', 'Solicitud actualizada exitosamente');
							$this->redirect(Yii::app()->createUrl('solicitudes/update', array('id'=>$id)));
						}
					}
					else
					{
						$errores = $model->getErrors();

						foreach ($errores as $error)
						{
							Yii::app()->user->setFlash('Solicitudes.Warning', $error[0]);
							break;
						}
					}
				}

				// Tipo de nota
				$modelTipoNotas = Caracteristicas::model()->findAll();
				$listTipoNotas = CHtml::listData($modelTipoNotas, 'id', 'nombre');

				// Caracteristica
				$modelCaracteristicas = Caracteristicastipo::model()->findAll(array(
					'condition' => 't.caracteristicas_id = :id',
					'order' => 't.id ASC',
					'params' => array(
						':id' => $model->tipo_nota
					)
				));
				$listCaracteristicas = CHtml::listData($modelCaracteristicas, 'id', 'nombre');

				// Razon
				$modelRazones = Razones::model()->findAll(array(
					'condition' => 't.caracteristicasTipo_id = :id',
					'order' => 't.id ASC',
					'params' => array(
						':id' => $model->caracteristica
					)
				));
				$listRazones = CHtml::listData($modelRazones, 'id', 'nombre');

				// Clientes
				$modelClientes = Clientes::model()->with('empresas', 'usuarios')->together()->findAll(array(
					'condition' => 'empresas.id = :empresa_id AND usuarios.id = :usuarios_id',
					'params' => array(
						':empresa_id' => Yii::app()->user->getState('empresa_id'),
						':usuarios_id' => Yii::app()->user->id
					),
					'order' => 't.codigo ASC'
				));
				$listClientes = CHtml::listData($modelClientes, 'codigo', 'CodigoNombre');

				// TODO: Se muestran todas las sucursales porque no definieron como está esta relacion con clientes o empresas
				//$modelSucursales = Sucursales::model()->findAll();
				//$listSucursales = CHtml::listData($modelSucursales, 'codigo', 'nombre');

				// Años
				$modelAnios = Anio::model()->findAll();
				$listAnios = CHtml::listData($modelAnios, 'id', 'anio');

				// Listas de precios cargadas
				/*$modelListaPrecios = ProductosPrecio::model()->findAll(array(
					'select' => 't.indice',
					'group' => 't.indice'
				));
				$listaPrecios = CHtml::listData($modelListaPrecios, 'indice', 'indice');*/

				$documentos = new CActiveDataProvider('Documentos', array(
					'criteria'=>array(
						'condition' => 't.notas_id = :id',
						'params' => array(
							':id' => $id
						)
					),
					'sort'=>array(
						'defaultOrder'=>'t.id DESC'
					),
					'pagination'=>array(
						'pageSize'=> 20,
						'pageVar'=>'p'
					)
				));

				$this->render('update', array(
					//'sucursales' => $listSucursales,
					//'listaPrecios' => $listaPrecios,
					'id' => $id,
					'model' => $model,
					'notas' => $listTipoNotas,
					'caracteristicas' => $listCaracteristicas,
					'razones' => $listRazones,
					'clientes' => $listClientes,
					'anios' => $listAnios,
					'seleccion' => $seleccion,
					'jsonData' => CJSON::encode($jsonData),
					'jsonDataMarcas' => CJSON::encode(array(
						'marcas'=>$marcasArray
					)),
					'archivo' => new ImportarArchivoForm(),
					'documentos' => $documentos,
					'title' => $title
				));
			}
		}
		else
		{
			throw new CHttpException(403,'No tienes permiso para ver el contenido de esta p&aacute;gina.');
		}
	}

	/**
	 * [actionPublicar description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionPublicar($id)
	{
		$nota = Notas::model()->with('razones.caracteristicas_tipo.caracteristicas', 'clientes', 'documentos')->together()->find(array(
			'condition' => 't.id = :id AND t.estatus = :estatus',
			'params' => array(
				':id' => $id,
				':estatus' => Notas::ESTATUS_BORRADOR
			)
		));

		if ($nota !== null)
		{
			if ($nota->usuarios_id == Yii::app()->user->id)
			{
				$model = new SolicitudesForm();
				$model->scenario = 'publish';

				// Tomar los datos del formulario y almacenar
				if (isset($_POST['SolicitudesForm']))
				{
					$model->attributes = $_POST['SolicitudesForm'];
					$model->id = $nota->id;
					$model->estatus = Notas::ESTATUS_PUBLICADO;
					$model->entry = 'Registro Publicado. - '.$model->comentario;
					$model->tipo_nota = $nota->razones->caracteristicas_tipo->caracteristicas->id;
					$model->use_validations = true;

					if (count($nota->documentos) == 0)
					{
						Yii::app()->user->setFlash('Solicitudes.Error', 'Es necesario que anexe al menos un documento a la lista');
						$this->redirect(Yii::app()->createUrl('solicitudes/update', array('id'=>$id)));
					}
					else
					{
						if ($model->validate() && $model->save($id, true))
						{
							Yii::app()->user->setFlash('Solicitudes.Success', 'Solicitud enviada exitosamente');
							$this->redirect(Yii::app()->createUrl('solicitudes/index'));
						}
						else
						{
							$errores = $model->getErrors();

							if (array_key_exists('importe', $errores))
							{
								Yii::app()->user->setFlash('Solicitudes.Error', $errores['importe'][0]);
							}
							else
							{
								Yii::app()->user->setFlash('Solicitudes.Error', 'Es necesario que escriba un comentario antes de publicar esta solicitud.');
							}

							$this->redirect(Yii::app()->createUrl('solicitudes/update', array('id'=>$id)));
						}
					}
				}
			}
		}

		throw new CHttpException(403,'No tienes permiso para ver el contenido de esta p&aacute;gina.');
	}

	/**
	 * [actionGuardarProductos description]
	 * @return [type] [description]
	 */
	/*public function actionGuardarProductos($id)
	{
		$response = array();

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$model = new SolicitudesForm();
				if (isset($_POST['SolicitudesForm']) && (!empty($_POST['SolicitudesForm'])))
				{
					$model->attributes = $_POST['SolicitudesForm'];

					$notaProducto = NotasHasProductos::model()->findByAttributes(array(
						'notas_id' => $id,
						'productos_id' => $model->product_id
					));

					// Encontrar si el cliente tiene descuentos disponibles
					$descuento = DescuentoClientes::model()->find(array(
						'condition' => 't.clientes_codigo = :codigo',
						'params' => array(
							':codigo' => $model->cliente
						)
					));

					if ($notaProducto === null)
					{
						$notasproductos = new NotasHasProductos();
						$notasproductos->notas_id = $id;
						$notasproductos->productos_id = $model->product_id;
						$notasproductos->aceptacion = 100;
						if ($notasproductos->save())
						{
							$aplica_descuento = 0;
							$no_iva = false;

							$producto = Productos::model()->with('cnotas','precios.anio')->together()->find(array(
								'condition' => 'cnotas.id = :id && precios.anio_id=:agnio ',
								'params' => array(
									':id' => $notasproductos->id,
									':agnio' => $model->anio
								)
							));

							if ($descuento !== null)
							{
								// Determinar para que tipo de productos tiene descuento
								if (in_array($producto->codigo, Yii::app()->params['descuentos']['TREV']))
								{
									$aplica_descuento = $descuento->todas_trev;
									$no_iva = true;
								}
								else if (in_array($producto->codigo, Yii::app()->params['descuentos']['PFD']))
								{
									$aplica_descuento = $descuento->pfd_notrev;
									$no_iva = true;
								}
								else
								{
									$aplica_descuento = $descuento->todas_nopfd;
								}
							}

							$response = array(
								'response' => true,
								'id' => $producto->cnotas[0]->id,
								'descripcion' => $producto->descripcion,
								'codigo' => $producto->codigo,
								'cantidad' => $producto->cnotas[0]->cantidad_piezas,
								'precio' => $producto->precios[0]->precio,
								'impuesto' => $producto->precios[0]->anio->impuesto,
								'descuento' => $aplica_descuento,
								'aceptacion' => $producto->cnotas[0]->aceptacion,
								'no_iva' => $no_iva
							);
						}
						else
						{
							$response = array('response'=>false);
						}
					}
					else
					{
						$response = array('response'=>false);
					}
				}
				else
				{
					throw new CHttpException(403,'No tienes permiso para ver el contenido de esta p&aacute;gina.');
				}
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
		echo CJSON::encode($response);
		Yii::app()->end();
	}*/

	/**
	 * [actionEliminarProducto description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionEliminarProducto($id)
	{
		$output = false;

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$model = NotasHasProductos::model()->find(array(
					'condition' => 't.notas_id = :nota AND t.productos_id = :id',
					'params' => array(
						':nota' => $id,
						':id' => $_POST['id']
					),
				));

		        if($model !== null)
		        {
		        	$output = $model->delete();
		        }
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
        echo CJSON::encode($output);
        Yii::app()->end();
	}

	/**
	 * [actionActualizaCantidadProducto description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionActualizaProducto($id)
	{
		$response = array();

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$model = NotasHasProductos::model()->find(array(
					'condition' => 't.notas_id = :nota AND t.productos_id = :id',
					'params' => array(
						':nota' => $id,
						':id' => $_POST['id']
					),
				));

				// Porque ya existe
		        if($model !== null)
		        {
		        	$model->cantidad_piezas = (int)$_POST['value'];
		        	$model->aceptacion = (int)$_POST['aceptacion'];
		        	$model->save();
		        }
		        else
		        {
		        	$notasproductos = new NotasHasProductos();
					$notasproductos->notas_id = $id;
					$notasproductos->productos_id = (int)$_POST['id'];
					$notasproductos->cantidad_piezas = (int)$_POST['value'];
					$notasproductos->aceptacion = (int)$_POST['aceptacion'];
					$notasproductos->save();
		        }

		        // Lista de productos (si existen)
				$modelProductos = Productos::model()->with('cnotas','precios.anio')->together()->findAll(array(
					'condition' => 'cnotas.notas_id = :id AND precios.anio_id = :agnio ',
					'params' => array(
						':id' => $id,
						':agnio' => (int)$_POST['anio']
					)
				));

				// Encontrar si el cliente tiene descuentos disponibles
				$descuento = DescuentoClientes::model()->find(array(
					'condition' => 't.clientes_codigo = :codigo',
					'params' => array(
						':codigo' => (int)$_POST['cliente']
					)
				));

				foreach($modelProductos as $productos)
				{
					$aplica_descuento = 0;
					$no_iva = false;

					if ($descuento !== null)
					{
						// Determinar para que tipo de productos tiene descuento
						if (in_array($productos->codigo, Yii::app()->params['descuentos']['TREV']))
						{
							$aplica_descuento = $descuento->todas_trev;
							// A los productos trevisage no se les debe calcular el iva
							$no_iva = true;
						}
						else if (in_array($productos->codigo, Yii::app()->params['descuentos']['PFD']))
						{
							$aplica_descuento = $descuento->pfd_notrev;
							// A los productos pfd no se les debe calcular el iva
							$no_iva = true;
						}
						else
						{
							$aplica_descuento = $descuento->todas_nopfd;
						}
					}

					array_push($response, array(
						'id' => $productos->id,
						'descripcion' => $productos->descripcion,
						'codigo' => $productos->codigo,
						'cantidad' => $productos->cnotas[0]->cantidad_piezas,
						'precio' => $productos->precios[0]->precio,
						'impuesto' => $productos->precios[0]->anio->impuesto,
						'descuento' => $aplica_descuento,
						'aceptacion' => $productos->cnotas[0]->aceptacion,
						'no_iva' => $no_iva
					));
				}
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
        echo CJSON::encode($response);
        Yii::app()->end();
	}

	/**
	 * [actionActualizaAceptacion description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionActualizaAceptacion($id)
	{
		$output = false;

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$model = NotasHasProductos::model()->find(array(
					'condition' => 't.notas_id = :nota AND t.productos_id = :id',
					'params' => array(
						':nota' => $id,
						':id' => $_POST['id']
					),
				));

		        if($model !== null)
		        {
		        	$model->aceptacion = (int)$_POST['value'];
		        	$output = $model->save();
		        }
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
        echo CJSON::encode($output);
        Yii::app()->end();
	}

	/**
	 * [actionGuardarDescuentos description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionGuardarDescuentos($id)
	{
		$response = array();

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$model = new SolicitudesForm();
				if (isset($_POST['SolicitudesForm']) && (!empty($_POST['SolicitudesForm'])))
				{
					$model->attributes = $_POST['SolicitudesForm'];

					$nota = Notas::model()->with('descuentos')->together()->findByPk($id);

					$marcaDescuento = MarcasHasDescuentos::model()->findByAttributes(array(
						'descuentos_id' => $nota->descuentos->id,
						'marcas_id' => $model->marca_id
					));

					if ($marcaDescuento === null)
					{
						$marcasDescuentos = new MarcasHasDescuentos();
						$marcasDescuentos->importe = 0;
						$marcasDescuentos->descuentos_id = $nota->descuentos->id;
						$marcasDescuentos->marcas_id = $model->marca_id;

						if ($marcasDescuentos->save())
						{
							$descuento = Marcas::model()->with('cdescuentos')->together()->find(array(
								'condition' => 'cdescuentos.id = :id',
								'params' => array(
									':id' => $marcasDescuentos->id
								)
							));

							if (!is_null($descuento))
							{
								$response = array(
									'response' => true,
									'id' => $descuento->cdescuentos[0]->id,
									'importe' => $descuento->cdescuentos[0]->importe,
									'codigo' => $descuento->codigo,
									'marca' => $descuento->marca,
									'monto' => $nota->descuentos->importe
								);
							}
						}
						else
						{
							$response = array('response'=>false);
						}
					}
					else
					{
						$response = array('response'=>false);
					}
				}
				else
				{
					throw new CHttpException(403,'No tienes permiso para ver el contenido de esta p&aacute;gina.');
				}
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
		echo CJSON::encode($response);
		Yii::app()->end();
	}

	/**
	 * [actionEliminarProducto description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionEliminarDescuento($id)
	{
		$output = false;

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$nota = Notas::model()->with('descuentos')->together()->findByPk($id);

				$model = MarcasHasDescuentos::model()->find(array(
					'condition' => 't.descuentos_id = :descuento AND t.id = :id',
					'params' => array(
						':descuento' => $nota->descuentos->id,
						':id' => $_POST['id']
					),
				))->delete();

		        if($model !== null)
		        {
		        	$output = true;
		        }
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
        echo CJSON::encode($output);
        Yii::app()->end();
	}

	/**
	 * [actionActualizaImporteMarcas description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionActualizaImporteMarcas($id)
	{
		$output = false;
		$porcentajeUtilizado = 0;
		$valid = true;

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$nota = Notas::model()->with('descuentos')->together()->findByPk($id);

				$model = MarcasHasDescuentos::model()->find(array(
					'condition' => 't.descuentos_id = :descuento AND t.id = :id',
					'params' => array(
						':descuento' => $nota->descuentos->id,
						':id' => $_POST['id']
					),
				));

		        if($model !== null)
		        {
		        	// Encontrar todas las marcas y sus descuentos
		        	// Se excluye el id del elemento modificado
		        	$marcas_descuentos = MarcasHasDescuentos::model()->findAll(array(
						'condition' => 't.descuentos_id = :descuento AND t.id != :id',
						'params' => array(
							':descuento' => $nota->descuentos->id,
							':id' => $model->id
						),
					));

		        	// si se encuentran datos
					if ($marcas_descuentos !== null)
					{
						// se toma el valor enviado por parametro y desde ahi se inicia la cuenta
						$porcentajeUtilizado = (float)$_POST['value'];

						// recorrer cada registro sumando cada item del porcentaje
						foreach ($marcas_descuentos as $md)
						{
							$porcentajeUtilizado += $md->importe;
						}

						if ($porcentajeUtilizado > 100)
						{
							$valid = false;
						}
					}

					if($valid)
					{
			        	$model->importe = (float)$_POST['value'];
			        	if ($model->save())
			        	{
			        		$output = true;
			        	}
			        }
		        }
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
        echo CJSON::encode($output);
        Yii::app()->end();
	}

	/**
	 * [actionGuardarDescuentosCooperacion description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionGuardarDescuentosCooperacion($id)
	{
		$response = array();

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$model = new SolicitudesForm();
				if (isset($_POST['SolicitudesForm']) && (!empty($_POST['SolicitudesForm'])))
				{
					$model->attributes = $_POST['SolicitudesForm'];

					$nota = Notas::model()->with('cooperacion')->together()->findByPk($id);

					$marcaCooperacion = MarcasHasCooperacion::model()->findByAttributes(array(
						'cooperacion_id' => $nota->cooperacion->id,
						'marcas_id' => $model->marca_id
					));

					if ($marcaCooperacion === null)
					{
						$marcasCooperacion = new MarcasHasCooperacion();
						$marcasCooperacion->importe = 0;
						$marcasCooperacion->cooperacion_id = $nota->cooperacion->id;
						$marcasCooperacion->marcas_id = $model->marca_id;

						if ($marcasCooperacion->save())
						{
							$marcas = Marcas::model()->with('ccooperacion')->together()->find(array(
								'condition' => 'ccooperacion.id = :id',
								'params' => array(
									':id' => $marcasCooperacion->id
								)
							));

							if (!is_null($marcas))
							{
								$response = array(
									'response' => true,
									'id' => $marcas->ccooperacion[0]->id,
									'importe' => $marcas->ccooperacion[0]->importe,
									'codigo' => $marcas->codigo,
									'marca' => $marcas->marca,
									'monto' => $marcas->ccooperacion[0]->cooperacion->importe
								);
							}
						}
						else
						{
							$response = array('response'=>false);
						}
					}
					else
					{
						$response = array('response'=>false);
					}
				}
				else
				{
					throw new CHttpException(403,'No tienes permiso para ver el contenido de esta p&aacute;gina.');
				}
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
		echo CJSON::encode($response);
		Yii::app()->end();
	}

	/**
	 * [actionEliminarDescuentoCooperacion description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionEliminarDescuentoCooperacion($id)
	{
		$output = false;

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$nota = Notas::model()->with('cooperacion')->together()->findByPk($id);

				$model = MarcasHasCooperacion::model()->find(array(
					'condition' => 't.cooperacion_id = :cooperacion AND t.id = :id',
					'params' => array(
						':cooperacion' => $nota->cooperacion->id,
						':id' => $_POST['id']
					),
				))->delete();

		        if($model !== null)
		        {
		        	$output = true;
		        }
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
        echo CJSON::encode($output);
        Yii::app()->end();
	}

	/**
	 * [actionActualizaImporteMarcasCooperacion description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionActualizaImporteMarcasCooperacion($id)
	{
		$output = false;
		$porcentajeUtilizado = 0;
		$valid = true;

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$nota = Notas::model()->with('cooperacion')->together()->findByPk($id);

				$model = MarcasHasCooperacion::model()->find(array(
					'condition' => 't.cooperacion_id = :cooperacion AND t.id = :id',
					'params' => array(
						':cooperacion' => $nota->cooperacion->id,
						':id' => $_POST['id']
					),
				));

		        if($model !== null)
		        {
		        	// Encontrar todas las marcas y sus cooperaciones
		        	// Se excluye el id del elemento modificado
		        	$marcas_cooperaciones = MarcasHasCooperacion::model()->findAll(array(
						'condition' => 't.cooperacion_id = :cooperacion AND t.id != :id',
						'params' => array(
							':cooperacion' => $nota->cooperacion->id,
							':id' => $model->id
						),
					));

					// si se encuentran datos
					if ($marcas_cooperaciones !== null)
					{
						// se toma el valor enviado por parametro y desde ahi se inicia la cuenta
						$porcentajeUtilizado = (float)$_POST['value'];

						// recorrer cada registro sumando cada item del porcentaje
						foreach ($marcas_cooperaciones as $md)
						{
							$porcentajeUtilizado += $md->importe;
						}

						if ($porcentajeUtilizado > 100)
						{
							$valid = false;
						}
					}

					if($valid)
					{
			        	$model->importe = (float)$_POST['value'];
			        	if ($model->save())
			        	{
			        		$output = true;
			        	}
			        }
		        }
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
        echo CJSON::encode($output);
        Yii::app()->end();
	}

	/**
	 * Permita la subida de archivos desde la
	 * pagina ofertas/compete
	 */
	public function actionFileupload($id)
	{
		$output = array();
		$model = new ImportarArchivoForm;

		try
		{
			if((isset($_POST['ImportarArchivoForm'])) && (strlen($_FILES['ImportarArchivoForm']['name']['archivo'])>0))
	        {
	            $model->attributes = $_POST['ImportarArchivoForm'];
	            $model->archivo = CUploadedFile::getInstance($model,'archivo');

	            if (!$model->archivo->getError())
				{
					$this->tmpFileName = str_replace(" ","",date('dmYHis-z-').microtime()).".".$model->archivo->getExtensionName();

	            	$model->archivo->saveAs(Yii::getPathOfAlias('webroot').SolicitudesController::FOLDERIMAGES.$this->tmpFileName);

					$archivo = new Documentos();
					$archivo->archivo = $model->archivo->getName();
					$archivo->descripcion = $this->tmpFileName;
					$archivo->notas_id = $id;
					if(!$archivo->save())
					{
						if (file_exists(Yii::getPathOfAlias('webroot').SolicitudesController::FOLDERIMAGES.$this->tmpFileName))
						{
							unlink (Yii::getPathOfAlias('webroot').SolicitudesController::FOLDERIMAGES.$this->tmpFileName);
						}

						$output = array('error'=>$archivo->getErrors());
					}
					else
					{
						$output = array(
							'id' => $archivo->primaryKey,
							'file' => Yii::app()->baseUrl.'/protected/files/'.$this->tmpFileName
						);
					}
	            }
				else
				{
					$output = array('error'=>$model->archivo->getError());
				}
	        }
	        else
	        {
	        	$output = array('error'=>'Es necesario subir un archivo.');
	        }
	    }
	    catch(Exception $ex)
	    {
	    	$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
	    }

        //header('Content-type: application/json');
        header('Content-Type: text/html');
        echo CJSON::encode($output);
        Yii::app()->end();
	}

	/**
	 * [actionFileDelete description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionFileDelete($id)
	{
		$output = false;
		$model = Documentos::model()->with('notas')->together()->findByPk($id);

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
		        if($model !== null)
		        {
		        	if ($model->notas->usuarios_id == Yii::app()->user->id)
		        	{
			        	if ($model->delete())
			        	{
			        		$output = true;
			        		if (file_exists(Yii::getPathOfAlias('webroot').SolicitudesController::FOLDERIMAGES.$this->descripcion))
							{
								unlink (Yii::getPathOfAlias('webroot').SolicitudesController::FOLDERIMAGES.$this->descripcion);
							}
			        	}
			        }
		        }
		    }
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
        echo CJSON::encode($output);
        Yii::app()->end();
	}

	/**
	 * [actionEliminaRelacionFactura description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionEliminaRelacionFactura($id)
	{
		$response = array();

		if (Yii::app()->request->isPostRequest)
		{
			$model = new SolicitudesForm();
			$nota = Notas::model()->findByPk($id);

			if ($nota !== null)
			{
				// Almacenar el folio de la factura en el registro de la nota
				//$nota = Notas::model()->findByPk($id);
				$nota->entry = 'Se ha eliminado la relacion con la factura '.$nota->num_factura;
				$nota->num_factura = $model->factura_id;
				if ($nota->save(false))
				{
					$response = array(true);
				}
			}
		}

		header('Content-type: application/json');
        echo CJSON::encode($response);
        Yii::app()->end();
	}

	/**
	 * [actionBuscarFactura description]
	 * @return [type] [description]
	 */
	public function actionBuscarFactura()
	{
		$response = array();

		if (Yii::app()->request->isPostRequest)
		{
			$criteria = new CDbCriteria();
			$criteria->condition = 't.clientes_codigo = :cliente';
			$criteria->params = array(
				':cliente' => Yii::app()->request->getParam('cliente_codigo', 0),
				':folio' => Yii::app()->request->getParam('term')
			);
			$criteria->compare("REPLACE(REPLACE(folio, ' ', ''), '-','')", Yii::app()->request->getParam('term'), true);
			$criteria->addCondition('folio = :folio', 'OR');
			$criteria->group = 'folio';
			$criteria->order = 'id ASC';
			$criteria->limit = 20;

			$facturas = Facturas::model()->findAll($criteria);

			foreach($facturas as $factura)
			{
				array_push($response, array(
					'id' => $factura->folio,
					'label' => trim($factura->folio)
				));
			}
		}

		header('Content-type: application/json');
        echo CJSON::encode($response);
        Yii::app()->end();
	}

	/**
	 * [actionObtenerListaProductos description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionObtenerListaProductos($id)
	{
		$response = array('response'=>false);

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$model = new SolicitudesForm();

				if (isset($_POST['SolicitudesForm']) && (!empty($_POST['SolicitudesForm'])))
				{
					$model->attributes = $_POST['SolicitudesForm'];

					// Es necesario que exista un año y un indice de precios
					if (!empty($model->anio) && !empty($model->catalogo_precios))
					{
						// Se borran todos los productos previos
						NotasHasProductos::model()->deleteAll(array(
							'condition' => 'notas_id = :notas',
							'params' => array(
								':notas' => $id
							))
						);

						// Buscar todas los productos de la factura
						$facturas = Facturas::model()->findAll(array(
							'condition' => 't.folio = :folio',
							'params' => array(
								':folio' => trim($model->factura_id)
							)
						));

						// Recorrer todos los productos encontrados
						foreach ($facturas as $factura)
						{
							// Por cada producto encontrado, buscarlo en el catalogo productos utilizando su codigo
							$producto = Productos::model()->with('precios.anio')->together()->find(array(
								'condition' => 't.codigo = :codigo AND anio.id = :anio',
								'params' => array(
									':codigo' => $factura->codigo_producto,
									':anio' => $model->anio
								)
							));

							// Si se encuentra el producto, almacenar la relacion con la nota
							if ($producto !== null)
							{
								$notasProductos = new NotasHasProductos();
								$notasProductos->notas_id = $id;
								$notasProductos->productos_id = $producto->id;
								$notasProductos->cantidad_piezas = $factura->cantidad_piezas;
								$notasProductos->save();
							}
						}

						// Almacenar el folio de la factura en el registro de la nota
						$nota = Notas::model()->findByPk($id);
						$nota->num_factura = $model->factura_id;
						$nota->clientes_codigo = $model->cliente;
						$nota->entry = 'Se ha relacionado con la factura '.$model->factura_id;
						$nota->save(false);

						$response = array('response'=>true);
					}
					else
					{
						$response['message'] = 'Empty factura_id y catalogo_precios';
					}
				}
			}
			else
			{
				throw new CHttpException(403,'No tienes permiso para ver el contenido de esta p&aacute;gina.');
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
		echo CJSON::encode($response);
		Yii::app()->end();
		//$this->redirect(Yii::app()->createUrl('update', array('id'=>$id)));
	}

	/**
	 * [actionObtenerTotalFactura description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionObtenerTotalFactura($id)
	{
		$response = array();

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				if (isset($_POST['SolicitudesForm']) && (!empty($_POST['SolicitudesForm'])))
				{
					// Buscar todas los productos de la factura
					$factura = Facturas::model()->find(array(
						'condition' => 't.folio = :folio',
						'params' => array(
							':folio' => trim($_POST['SolicitudesForm']['factura_id'])
						)
					));

					$response = array(
						'response' => true,
						'monto' => $factura->monto
					);
				}
				else
				{
					$response = array('response'=>false);
				}
			}
			else
			{
				throw new CHttpException(403,'No tienes permiso para ver el contenido de esta p&aacute;gina.');
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		header('Content-type: application/json');
		echo CJSON::encode($response);
		Yii::app()->end();
	}
}
