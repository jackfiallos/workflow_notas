<?php
/**
 * LogisticaController class file
 * 
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-07-10
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class LogisticaController extends Controller
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
				'actions'=>array('index', 'view', 'update', 'ActualizaLoteProducto'),
				'users'=>array('@'),
				'expression'=>'!$user->isGuest && $user->verifyRole(Usuarios::LOGISTICA)'
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
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$model = new Notas('searchDevoluciones');
        $model->unsetAttributes();

        if(isset($_POST['Notas']))
        {
            $model->attributes = $_POST['Notas'];
        }

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
			'clientes' => $listClientes
		));
	}

	/**
	 * [actionView description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionView($id)
	{
		$criteria = new CDbCriteria();
		$criteria->with = array('razones.caracteristicas_tipo');
		$criteria->together = true;
		$criteria->condition = 't.estatus = :estatus AND revision = :revision AND t.id = :id';
		$criteria->params = array(
			':estatus' => Notas::ESTATUS_PUBLICADO,
			':revision' => Notas::REV_DEVOLUCIONES,
			':id' => $id
		);

		$nota = Notas::model()->with('descuentos', 'anio', 'clientes', 'usuarios', 'razones.caracteristicas_tipo.caracteristicas')->together()->find($criteria);

		if ($nota === null)
		{
			Yii::app()->user->setFlash('Logistica.Error', 'No tiene privilegios para ver esta solicitud.');
			$this->redirect(Yii::app()->createUrl('logistica/index'));
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
					'pageSize'=> 5
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
					'pageSize'=> 50
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
					'pageSize'=> 50
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
					'pageSize'=> 50
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
					'pageSize'=> 50
				)
	        )),
		));
	}

	/**
	 * [actionActualizar description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionUpdate($id)
	{
		$jsonData = array();
		$permitirSiguienteNivel = true;

		$criteria = new CDbCriteria();
		$criteria->with = array('razones.caracteristicas_tipo');
		$criteria->together = true;
		$criteria->condition = 't.estatus = :estatus AND revision = :revision AND t.id = :id';
		$criteria->params = array(
			':estatus' => Notas::ESTATUS_PUBLICADO,
			':revision' => Notas::REV_DEVOLUCIONES,
			':id' => $id
		);

		$nota = Notas::model()->with('razones.caracteristicas_tipo.caracteristicas', 'clientes', 'descuentos', 'anio')->together()->find($criteria);

		if ($nota !== null)
		{
			// Lista de productos (si existen)
			$modelProductos = Productos::model()->with('cnotas','precios.anio')->together()->findAll(array(
				'condition' => 'cnotas.notas_id = :id && precios.anio_id=:agnio ', 
				'params' => array(
					':id' => $id,
					':agnio' => $nota->anio_id
				)
			));

			// Encontrar si el cliente tiene descuentos disponibles
			$descuento = DescuentoClientes::model()->find(array(
				'condition' => 't.clientes_codigo = :codigo',
				'params' => array(
					':codigo' => $nota->clientes_codigo
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
						// A los productos trevisage no se les debe calcular el iva
						$no_iva = true;
					}
					else
					{
						$aplica_descuento = $descuento->todas_nopfd;
					}
				}

				// Si alguno de los productos no posee lote, caducidad o almacen no permitir el siguiente nivel
				if ((!$permitirSiguienteNivel) || (empty($productos->cnotas[0]->num_lote)) || (empty($productos->cnotas[0]->caducidad)) || (empty($productos->cnotas[0]->almacen)))
				{
					$permitirSiguienteNivel = false;
				}

				array_push($jsonData, array(
					'id' => $productos->cnotas[0]->id,
					'descripcion' => $productos->descripcion,
					'codigo' => $productos->codigo,
					'cantidad' => $productos->cnotas[0]->cantidad_piezas,
					'precio' => $productos->precios[0]->precio,
					'impuesto' => $productos->precios[0]->anio->impuesto,
					'descuento' => $aplica_descuento,
					'aceptacion' => $productos->cnotas[0]->aceptacion,
					'no_iva' => $no_iva,
					'lote' => $productos->cnotas[0]->num_lote,
					'almacen' => $productos->cnotas[0]->almacen,
					'caducidad' => $productos->cnotas[0]->caducidad,
				));
			}

			// Validar el post del fomulario
			if(isset($_POST['ModificarEstatusForm']))
			{
				$model = new ModificarEstatusForm();
				$model->scenario = 'devoluciones';
				$model->attributes = $_POST['ModificarEstatusForm'];

				// Verificar que todos los productos de esta nota tienen lote, almacen y caducidad
				if ($permitirSiguienteNivel)
				{
					// Validar modelo cumpla con las reglas  de validacion
					if($model->validate())
					{
						//Tools::print_array($jsonData);
						$nota = Notas::model()->findByPk($id);
						
						$comentarioAux = (($model->estatus == Notas::REV_SAC) ? 'Nota Aceptada' : 'Nota Rechazada');
	        			$nota->comentario = $comentarioAux." - ".$model->comentario;
						$nota->revision = $model->estatus;
						$nota->entry = $nota->comentario;
						if ($nota->save(false))
						{
							$this->redirect(Yii::app()->createUrl('logistica/index'));
						}
						else
						{
							Yii::app()->user->setFlash('Logistica.Error', 'Ocurrió un error al intentar procesar la solicitud.');
						}
					}
					else
					{
						Yii::app()->user->setFlash('Logistica.Error', 'Es necesario que seleccione un estatus y escriba un comentario.');
					}
				}
				else
				{
					Yii::app()->user->setFlash('Logistica.Error', 'Todos los productos deben tener un n&uacute;mero de lote, un almac&eacute;n y una fecha de caducidad.');
				}
			}

			$title = 'Nota de Cr&eacute;dito de Almac&eacute;n';

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
				'id' => $id,
				'model' => $nota,
				'modelform' => new ModificarEstatusForm(),
				'jsonData' => CJSON::encode($jsonData),
				'archivo' => new ImportarArchivoForm(),
				'documentos' => $documentos,
				'title' => $title,
				'historial' => Historial::model()->findAll(array(
					'condition' => 't.notas_id = :id',
					'params' => array(
						':id' => $id
					)
				))
			));

			Yii::app()->end();
		}
		else
		{
			Yii::app()->user->setFlash('Logistica.Error', 'No tiene privilegios para ver esta solicitud.');
			$this->redirect(Yii::app()->createUrl('logistica/index'));
		}
	}

	/**
	 * [actionActualizaLoteProducto description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionActualizaLoteProducto($id)
	{
		$output = false;

		try
		{
			if (Yii::app()->request->isPostRequest)
			{
				$model = NotasHasProductos::model()->find(array(
					'condition' => 't.notas_id = :nota AND t.id = :id',
					'params' => array(
						':nota' => $id,
						':id' => $_POST['id']
					),
				));

		        if($model !== null)
		        {
		        	$model->num_lote = $_POST['lote'];
		        	$model->almacen = $_POST['almacen'];
		        	$model->caducidad = $_POST['caducidad'];
		        	if ($model->save(false))
		        	{
		        		$output = true;
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
}