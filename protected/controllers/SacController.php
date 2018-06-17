<?php
/**
 * SacController class file
 * 
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-07-10
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class SacController extends Controller
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
				'actions'=>array('index', 'view', 'update'),
				'users'=>array('@'),
				'expression'=>'(!$user->isGuest && $user->verifyRole(Usuarios::SAC))'
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
		$model = new Notas('searchSac');
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
	 * [actionUpdate description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionUpdate($id)
	{
		// Validar el post del fomulario
		if(isset($_POST['ModificarEstatusForm']))
		{
			$model = new ModificarEstatusForm();
			$model->attributes = $_POST['ModificarEstatusForm'];

			// Validar modelo cumpla con las reglas  de validacion
			if($model->validate())
			{
				$nota = Notas::model()->findByPk($id);
				$nota->revision = $model->estatus;
				$nota->comentario = 'Nota impresa y enviada a Devoluciones';
				$nota->entry = $nota->comentario;
				if ($nota->save(false))
				{
					Yii::app()->user->setFlash('Sac.Success', 'Solicitud Impresa y enviada a Devoluciones');
					$this->redirect(Yii::app()->createUrl('sac/index'));
				}
				else
				{
					Yii::app()->user->setFlash('Sac.Error', 'Ocurrió un error al intentar procesar la solicitud.');
				}
			}
		}

		$this->redirect(Yii::app()->createUrl('sac/view', array('id'=>$id)));
	}

	/**
	 * [actionView description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionView($id)
	{
		$ordenCompra = null;
		$model = new ModificarEstatusForm();

		$criteria = new CDbCriteria();
		$criteria->condition = 't.estatus = :estatus AND (revision = :revision1 OR revision = :revision2) AND t.id = :id';
		$criteria->params = array(
			':estatus' => Notas::ESTATUS_PUBLICADO,
			':revision1' => Notas::REV_PROCESADO,
			':revision2' => Notas::REV_SAC,
			':id' => $id
		);

		$nota = Notas::model()->with('descuentos', 'anio', 'clientes', 'usuarios', 'razones.caracteristicas_tipo.caracteristicas')->together()->find($criteria);

		if ($nota === null)
		{
			Yii::app()->user->setFlash('Sac.Error', 'No tiene privilegios para ver esta solicitud.');
			$this->redirect(Yii::app()->createUrl('sac/index'));
		}

		// Validar el post del fomulario
		if(isset($_POST['ModificarEstatusForm']))
		{
			$model->attributes = $_POST['ModificarEstatusForm'];

			if ($model->estatus == Notas::REV_DEVOLUCIONES)
			{
				$model->scenario = 'devoluciones';
			}
			elseif ($model->estatus == Notas::REV_APROBADO) // Ocurre despues de devoluciones
			{
				$model->scenario = 'finalizar';
			}

			// Validar modelo cumpla con las reglas  de validacion
			if($model->validate())
			{
				$nota = Notas::model()->findByPk($id);
				$comentarioAux = (($model->estatus == Notas::REV_RECHAZADO) ? 'Nota Rechazada' : 'Nota Aceptada');
				$nota->revision = $model->estatus;
				
				// Ocurre antes de devoluciones
				if ($model->estatus == Notas::REV_DEVOLUCIONES)
				{
					$nota->comentario = $comentarioAux.' impresa y enviada a Devoluciones';
				}
				elseif ($model->estatus == Notas::REV_APROBADO) // Ocurre despues de devoluciones
				{
					$nota->comentario = $comentarioAux.' - '.$model->comentario;
					$nota->descripcion = $model->descripcion;
					$nota->entrada_almacen = $model->entrada_almacen;
					$nota->tipo_orden = $model->tipoOrden;
					$nota->fecha_cierre = date('Y-m-d');
					$nota->ordenCompra = $model->ordenCompra;

					// La sucursal se debe de insertar en la tabla de sucursales y relacionar con el cliente de la factura
					if (!empty($model->sucursal))
					{
						$sucursal = new Sucursales();
						$sucursal->nombre = $model->sucursal;
						$sucursal->clientes_codigo = $nota->clientes_codigo;
						if (!$sucursal->save(false))
						{
							Yii::app()->user->setFlash('Sac.Error', 'Ocurrió un error al intentar procesar la solicitud, int&eacute;ntelo nuevamente.');
							$this->redirect(Yii::app()->createUrl('sac/view', array('id' => $id)));
						}
					}					
				}
				elseif ($model->estatus == Notas::REV_RECHAZADO)
				{
					$nota->estatus = Notas::ESTATUS_BORRADOR;
					$nota->comentario = $comentarioAux.' - '.$model->comentario;
				}

				$nota->entry = $nota->comentario;				
				if ($nota->save(false))
				{
					$this->redirect(Yii::app()->createUrl('sac/index'));
				}
				else
				{
					Yii::app()->user->setFlash('Sac.Error', 'Ocurrió un error al intentar procesar la solicitud, int&eacute;ntelo nuevamente.');
					$this->redirect(Yii::app()->createUrl('sac/view', array('id'=>$id)));
				}
			}
			else
			{
				if ((!empty($model->revision)) && ($model->revision == Notas::REV_RECHAZADO))
				{
					Yii::app()->user->setFlash('Sac.Error', 'Es necesario que seleccione un estatus y escriba un comentario.');
				}
				else
				{
					Yii::app()->user->setFlash('Sac.Error', 'Para cerrar la solicitud, es necesario que complete todos los campos.');
				}
			}
		}

		// Verificar si la factura existe y si es así, entonces obtener el no. de orden de compra
		if (!empty($nota->num_factura))
		{
			$factura_data = Facturas::model()->find(array(
				'select' => 't.orden_compra',
				'condition' => 't.folio = :folio',
				'params' => array(
					':folio' => $nota->num_factura
				)
			));

			if ($factura_data !== null)
			{
				$ordenCompra = $factura_data->orden_compra;
			}
		}

		// Verificar si este cliente ya tiene alguna direccion registrada
		if (count($nota->clientes->sucursal) > 0)
		{
			$model->sucursal = $nota->clientes->sucursal[0]->nombre;
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
			'model' => $model,
			'ordenCompra' => $ordenCompra,
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
}