<?php
/**
 * DefaultController class file
 * 
 * @autor		Jackfiallos
 * @link		http://jackfiallos.com
 * @copyright	Copyright (c) 2011 Qbit Mexhico
 * @description
 * 
 *
 **/
class DefaultController extends Controller
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
				'actions'=>array(
					'index', 'Revisar','Update', 'pendientes',
					'NotasCreditoXCaract', 'NotasCreditoEnviadas', 'NotasCreditoEstatus'
				),
				'users'=>array('@'),
				'expression'=>'!$user->isGuest && $user->verifyRole(Usuarios::SUPERVISOR)'
			),
			array('deny',
				'users'=>array('*')
			)
		);
	}

	/**
	 * 
	 * @author:  Israel Arizmendi
	 * @revised: Jackfiallos
	 * @date:    2014-06-10
	 * @link:    [link]
	 * @version: [version]
	 * @return   [type]      [description]
	 */
	public function actionIndex()
	{
		$model = new DefaultForm;
        $model->unsetAttributes();  

		if(isset($_POST['DefaultForm']))
		{
			$model->attributes = $_POST['DefaultForm'];
			
			$fechaini = $model->fechaini;
			$fechafin = $model->fechafin;

			$inidd = substr($fechaini, 0, 2);
			$inimm = substr($fechaini, 3, 2);
			$iniyy = substr($fechaini, 6, 4);
			$fecha_ini = $iniyy."-".$inimm."-".$inidd;

			$findd = substr($fechafin, 0, 2);
			$finmm = substr($fechafin, 3, 2);
			$finyy = substr($fechafin, 6, 4);
			$fecha_fin = $finyy."-".$finmm."-".$findd;
		}
		else
		{
			$fecha_ini = date('y-m-d',strtotime('-30 day'));
			$fecha_fin = date('y-m-d');
		}

        //print_r(" FECHA INI [".$fecha_ini."] FECHA FIN  [".$fecha_fin."]");

		Yii::app()->session['fechaini'] =$fecha_ini;
		Yii::app()->session['fechafin'] =$fecha_fin;
        
        $this->render('index',array(
        	'model' => $model,
        	'promedio' => $this->getNotasCreditoTiempoPromedio()
    	));
	}

	/**
	 * [actionPendientes description]
	 * @return [type] [description]
	 */
	public function actionPendientes()
	{
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

					if ($cumple)
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
		
		$criteria = new CDbCriteria;
		$criteria->addInCondition('t.id', $f);
		$criteria->order = 't.id DESC';

		$model = new CActiveDataProvider('Notas', array(
			'criteria'=> $criteria,
			'pagination'=>array(
				'pageSize'=> 20,
				'pageVar'=>'p'
			)
		));

		$this->render('pendientes', array(
			'model' => $model
		));
	}

	/**
	 * [actionRevisar description]
	 * This is a cool function
	 * @author nixho
	 * @version [version]
	 * @date    2014-06-24
	 * @return  [type]     [description]
	 */
	public function actionRevisar()
	{
		$model = new Notas('searchSupervisores');
        $model->unsetAttributes();

        if(isset($_POST['Notas']))
        {
            $model->attributes = $_POST['Notas'];
        }

		// Clientes
		$modelClientes = Clientes::model()->with('empresas')->together()->findAll(array(
			'condition' => 'empresas.id = :empresa_id',
			'params' => array(
				':empresa_id' => Yii::app()->user->getState('empresa_id')
			),
			'order' => 't.codigo ASC'
		));
		$listClientes = CHtml::listData($modelClientes, 'codigo', 'CodigoNombre');

		// Usuarios
		$modelUsuarios = Usuarios::model()->with('empresas', 'permisos')->together()->findAll(array(
			'condition' => 'empresas.id = :empresa_id AND permisos.id NOT IN ('.implode(',', array(Usuarios::ADMIN, Usuarios::SUPERVISOR)).')',
			'params' => array(
				':empresa_id' => Yii::app()->user->getState('empresa_id')
			),
			'order' => 't.nombre ASC'
		));
		$listUsuarios = CHtml::listData($modelUsuarios, 'id', 'nombre');

		$this->render('revisar', array(
			'model' => $model,
			'clientes' => $listClientes,
			'usuarios' => $listUsuarios
		));
	}

	/**
	 * [actionUpdate description]
	 * This is a cool function
	 * @author nixho
	 * @version [version]
	 * @date    2014-06-24
	 * @param   [type]     $id [description]
	 * @return  [type]         [description]
	 */
	public function actionUpdate($id)
	{
		// Validar que el usuario pueda editar la nota
		$puedeEditarNota = Tools::UsuarioConNotaValida($id);
		$nota = Notas::model()->with('razones.caracteristicas_tipo.caracteristicas')->together()->findByPk($id);
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
					Tools::SaveNota($nota, $model);
					Yii::app()->user->setFlash('Supervisor.Success','Los datos se guardaron correctamente.');
				}
				catch(Exception $ex)
				{
					$message = $ex->getMessage();
					Yii::app()->user->setFlash('Supervisor.Error','Servicio no disponible.');
					Yii::log($message, CLogger::LEVEL_ERROR, 'application');
				}
                
				$this->redirect(Yii::app()->createUrl('supervisor/default/revisar'));
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
			'puedeEditarNota' => $puedeEditarNota,
			//'lstAgnios' => Anio::model()->findAll(),
			'documentos' => new CActiveDataProvider('Documentos',array(
				'criteria' => array(
					'condition' => 't.notas_id=:notaId',
					'params' => array(':notaId' => $id )
				),
				'pagination'=>array(
					'pageSize'=> 50
				)
			)),
			'historial' => Historial::model()->findAll(array(
				'select' => '* , usuario.nombre as usuario',
				'join' => ' left  join '.Usuarios::model()->tableName().' as usuario on t.usuarios_id = usuario.id',
				'condition' => 't.notas_id=:notaId',
				'params' => array(':notaId' => $id )
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
			'descuentos' => new CActiveDataProvider('MarcasHasDescuentos', array(
				'criteria' => array(
					'with'=> array('marcas'),
					'condition' => 't.descuentos_id = :id',
		            'params' => array(
		                ':id' => ((empty($nota->descuentos)) ? 0 : $nota->descuentos->id),
		            )	
				),
				'pagination'=>array(
					'pageSize'=> 1000
				)
	        )),
			'facturas' => new CActiveDataProvider('Facturas', array(
				'criteria' => array(
					'condition' => 't.folio=:folioFactura',
		            'params' => array(
		                 ':folioFactura' => (empty($nota->num_factura) ? 0 : $nota->num_factura)
		            )	
				),
				'pagination'=>array(
					'pageSize'=> 1000
				)
	        )),
			'cooperacion' => new CActiveDataProvider('MarcasHasCooperacion', array(
				'criteria' => array(
					'with'=> array('marcas'),
					'condition' => 't.cooperacion_id = :id ',
		            'params' => array(
		                ':id' => ((empty($nota->cooperacion)) ? 0 : $nota->cooperacion->id),
		            )	
				),
				'pagination' => array(
					'pageSize' => 1000
				)
	        )),
		));
	}

    /**
     * Notas credito generadas por caracteristica (pie por notas de almacen - comercial - cooperacion - etc)
     * @return [type] [description]
     */
	public function actionNotasCreditoXCaract()
	{
		Yii::import('application.extensions.pChart.yiipData');
		Yii::import('application.extensions.pChart.yiipDraw');
		Yii::import('application.extensions.pChart.yiipPie');
		Yii::import('application.extensions.pChart.yiipImage');

		$fechaini = Yii::app()->session['fechaini'];
		$fechafin = Yii::app()->session['fechafin'];
  
        $caracteristicas = Caracteristicas::model()->findAll();
        $num = count($caracteristicas);

    	if (($fechaini=="--") and ($fechafin=="--")) {
          	// Obtener todas las notas
			$notas = Notas::model()->with('razones.caracteristicas_tipo')->together()->findAll(array(
				'condition' => 't.estatus = :estatus AND t.revision IN (:revision1, :revision2, :revision3)',
				'params' => array(
				':estatus' => Notas::ESTATUS_PUBLICADO,
				':revision1' => Notas::REV_PROCESADO,
				':revision2' => Notas::REV_DEVOLUCIONES,
				':revision3' => Notas::REV_SAC,
				)
			)); 
    	}
    	else
    	{
	    	// Obtener todas las notas
			$notas = Notas::model()->with('razones.caracteristicas_tipo')->together()->findAll(array(
				'condition' => ' (t.fecha_creacion >= :val_fechaini and t.fecha_creacion <= :val_fechafin) AND t.estatus = :estatus AND t.revision IN (:revision1, :revision2, :revision3)',
				'params' => array(
				':estatus' => Notas::ESTATUS_PUBLICADO,
				':revision1' => Notas::REV_PROCESADO,
				':revision2' => Notas::REV_DEVOLUCIONES,
				':revision3' => Notas::REV_SAC,
		    	':val_fechaini' => $fechaini,
		    	':val_fechafin' => $fechafin,
				)
			)); 
    	}	

    	//print_r("FECHA INI [".$fechaini."]"."FECHA FIN [".$fechafin."]");					
		//print_r($notas);
        
        for ($i = 0; $i < $num; $i++)
        {
        	$monto=0;
    		foreach ($notas as $key => $value) 
    		{
    			switch ($i) 
    			{
    				case 0:						   													                              
                       	if($value->razones->caracteristicas_tipo->caracteristicas_id==Caracteristicas::ALMACEN)
                       	{
							$monto= $monto+Tools::GetTotal($value);
						}
						break;
					case 1:
						if($value->razones->caracteristicas_tipo->caracteristicas_id== Caracteristicas::DESCUENTOS)
						{
							$monto = $monto + Tools::GetTotal($value);
						}
    					break;
    				case 2:
 						if($value->razones->caracteristicas_tipo->caracteristicas_id== Caracteristicas::COOPERACION)
 						{
							$monto = $monto + Tools::GetTotal($value);
						}
    					break;
    				case 3:
 						if($value->razones->caracteristicas_tipo->caracteristicas_id== Caracteristicas::REFACTURACION)
 						{
    						$monto = $monto + Tools::GetTotal($value);
						}	
    					break;
    				default:
    			    	$numnotas=0;
    					break;
    			}
    		}
    		$numnotas = round($monto,2);    

		    $valores[$i] = Yii::app()->locale->numberFormatter->formatCurrency($numnotas, "");   
			$tiposnotas[$i] = $caracteristicas[$i]['nombre']." ($".Yii::app()->locale->numberFormatter->formatCurrency($numnotas, "").")"; 
		}

		//print_r($valores);	

		$Notas = array(
			'valores'=>$valores,
			'tiposnotas'=>$tiposnotas			
		);		

	    /* Create and populate the pData object */
		$MyData = new yiipData();   

		//aqui van los valores de cada caracteristica 
		$MyData->addPoints($Notas['valores'],"ScoreA"); 

		$MyData->setSerieDescription("Notas","Notas por caracteristica");

		//Aqui van los nombres de cada caracteristica
		$MyData->addPoints($Notas['tiposnotas'],"Labels"); 

		$MyData->setAbscissa("Labels");

		/* Create the pChart object */
		$myPicture = new yiipImage(1080,432,$MyData);

		/* Draw a solid background */
		$myPicture->drawFilledRectangle(0,0,1080,432, array(
			"R"=>255, 
			"G"=>255, 
			"B"=>255, 
			"Dash"=>1, 
			"DashR"=>190,
			"DashG"=>203,
			"DashB"=>107
		));

		/* Overlay with a gradient */
		$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
		$myPicture->drawGradientArea(0,0,1080,432,DIRECTION_VERTICAL,$Settings);

		$myPicture->drawRectangle(0,0,1079,431,array("R"=>0,"G"=>0,"B"=>0));

		/* Write the picture title */  
		//Descomentar
		$myPicture->setFontProperties(array(
			"FontName"=>"themes/v2/fonts/Roboto-Regular-webfont.ttf",
			"FontSize"=>22
		));

		$myPicture->drawText(1,35,"Notas generadas por característica",array(
			"R"=>0,
			"G"=>0,
			"B"=>0
		));

		//Descomentar
		$myPicture->setFontProperties(array(
			"FontName"=>"themes/v2/fonts/Roboto-Regular-webfont.ttf",
			"FontSize"=>10,
			"R"=>0,
			"G"=>0,
			"B"=>0
		)); 

		/* Create the pPie object */ 
		$PieChart = new yiipPie($myPicture,$MyData);

		/* Draw an AA pie chart */ 
		$PieChart->draw3DPie(280,265,array(
			"Radius"=>120,
			"DrawLabels"=>TRUE,
			"LabelStacked"=>TRUE,
			"WriteValues"=>PIE_VALUE_PERCENTAGE,
			"DataGapAngle"=>8,
			"DataGapRadius"=>6,
			"ValueR"=>0,
			"ValueG"=>0,
			"ValueB"=>0,
			"Border"=>TRUE,
			"BorderR"=>0,
			"BorderG"=>0,
			"BorderB"=>0
		));

		$myPicture->setShadow(TRUE,array("X"=>3,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

		$PieChart->drawPieLegend(780,50,array(
			"Style"=>LEGEND_ROUND,
			"Mode"=>LEGEND_VERTICAL,
			"Alpha"=>50
		));

		$data = $myPicture->toBase64();

		$im = imagecreatefromstring($data);
		if ($im !== false) 
		{
		    header('Content-Type: image/png');
		    imagepng($im);
		    imagedestroy($im);
		}
		else 
		{
		    echo 'An error occurred.';
		}
	}

   	/**
   	 * notas de credito enviadas por los usuarios con permiso de generacion 
   	 * (cuantas notas de credito crea c/ usuario) (permiso solicitud cuantas notas por usuario) solicitures
   	 * @return [type] [description]
   	 */
	public function actionNotasCreditoEnviadas()
	{
		Yii::import('application.extensions.pChart.yiipData');
		Yii::import('application.extensions.pChart.yiipDraw');
		Yii::import('application.extensions.pChart.yiipPie');
		Yii::import('application.extensions.pChart.yiipImage');

		$fechaini = Yii::app()->session['fechaini'];
		$fechafin = Yii::app()->session['fechafin'];

		$criteria = new CDbCriteria;
    	$criteria->condition = 'permisos_id = :val_id';
    	$criteria->params = array(
    		':val_id' => Usuarios::SOLICITUD
    	);

        $usuarios = Usuarios::model()->with('permisos')->together()->findAll($criteria);
 		$numusers = count($usuarios);
       
        for ($j = 0; $j < $numusers; $j++)
        {			
		    $usuario_id = $usuarios[$j]['id'];
        	$totalxUsuario = 0; 
  
    		//print_r("FECHA INI [".$fechaini."]"."FECHA FIN [".$fechafin."]");

  			if (($fechaini=="--") and ($fechafin=="--")) 
    		{
				$notas = Notas::model()->with('usuarios','razones.caracteristicas_tipo')->together()->findAll(array(
					'condition' => 'usuarios.id = t.usuarios_id  AND t.usuarios_id = :val_usuario_id AND t.estatus = :estatus AND t.revision IN (:revision1, :revision2, :revision3)',
					'params' => array(
						':estatus' => Notas::ESTATUS_PUBLICADO,
						':revision1' => Notas::REV_PROCESADO,
						':revision2' => Notas::REV_DEVOLUCIONES,
						':revision3' => Notas::REV_SAC,
						':val_usuario_id' => $usuario_id
					)
				)); 
    		}
    		else
    		{
				$notas = Notas::model()->with('usuarios','razones.caracteristicas_tipo')->together()->findAll(array(
					'condition' => 'usuarios.id = t.usuarios_id  AND t.usuarios_id = :val_usuario_id AND (t.fecha_creacion >= :val_fechaini and t.fecha_creacion <= :val_fechafin) AND t.estatus = :estatus AND t.revision IN (:revision1, :revision2, :revision3)',
					'params' => array(
						':estatus' => Notas::ESTATUS_PUBLICADO,
						':revision1' => Notas::REV_PROCESADO,
						':revision2' => Notas::REV_DEVOLUCIONES,
						':revision3' => Notas::REV_SAC,
						':val_usuario_id' => $usuario_id,
					    ':val_fechaini' => $fechaini,
					    ':val_fechafin' => $fechafin
					)
				)); 
    		}	
			
			$monto = 0;	   
			foreach ($notas as $key => $value)
			{
            	if ($value->razones->caracteristicas_tipo->caracteristicas_id==Caracteristicas::ALMACEN)
            	{
					$monto = $monto+Tools::GetTotal($value);					
				}					
			}	

			$totalxUsuario = $totalxUsuario + round($monto,2);

			$monto = 0;	   
			foreach ($notas as $key => $value)
			{
            	if ($value->razones->caracteristicas_tipo->caracteristicas_id==Caracteristicas::DESCUENTOS)
            	{
					$monto = $monto+Tools::GetTotal($value);					
				}					
			}	

			$totalxUsuario = $totalxUsuario + round($monto,2);

			$monto = 0;	   
			foreach ($notas as $key => $value)
			{
            	if ($value->razones->caracteristicas_tipo->caracteristicas_id==Caracteristicas::COOPERACION)
            	{
					$monto = $monto+Tools::GetTotal($value);					
				}					
			}	

			$totalxUsuario = $totalxUsuario + round($monto,2);

			$monto = 0;	   
			foreach ($notas as $key => $value)
			{
            	if ($value->razones->caracteristicas_tipo->caracteristicas_id==Caracteristicas::REFACTURACION)
            	{
					$monto= $monto+Tools::GetTotal($value);					
				}					
			}	

			$totalxUsuario = $totalxUsuario + round($monto,2);			

			$valores[$j] = Yii::app()->locale->numberFormatter->formatCurrency($totalxUsuario, "");   
			$tiposnotas[$j] = $usuarios[$j]['nombre']." ($".Yii::app()->locale->numberFormatter->formatCurrency($totalxUsuario, "").")"; 
	    }

		//print_r($valores);	
		$conValores=array(0);
		$conTiposnotas=array("Sin usuarios");
        $numVal=0; 
        $conValores = array();
        $conTiposnotas = array();

	    foreach ($valores as $key => $value)
	    {
	    	if ($value!=0)
	    	{
	    		$conValores[$numVal]=$value;
	    		$conTiposnotas[$numVal]=$tiposnotas[$key];
	    		$numVal++;
	    	}
	    		
	    }

		$Notas = array(
			'valores'=>$conValores,
			'tiposnotas'=>$conTiposnotas			
		);	

	    $MyData = new yiipData();   

		if (empty($Notas['valores']))
		{
			$Notas['valores'] = 0;
		}

		if (empty($Notas['tiposnotas']))
		{
			$Notas['tiposnotas'] = '';
		}

		//aqui van los valores de cada caracteristica 
		$MyData->addPoints($Notas['valores'],"ScoreA"); 

		$MyData->setSerieDescription("Notas","Notas por caracteristica");

		//Aqui van los nombres de cada caracteristica
		$MyData->addPoints($Notas['tiposnotas'],"Labels"); 

		$MyData->setAbscissa("Labels");

		/* Create the pChart object */
		$myPicture = new yiipImage(1080,462,$MyData);

		/* Draw a solid background */
		$myPicture->drawFilledRectangle(0,0,1080,432, array(
			"R"=>255, 
			"G"=>255, 
			"B"=>255, 
			"Dash"=>1, 
			"DashR"=>190,
			"DashG"=>203,
			"DashB"=>107
		));

		/* Overlay with a gradient */
		$Settings = array("StartR"=>192, "StartG"=>207, "StartB"=>255, "EndR"=>1, "EndG"=>38, "EndB"=>38, "Alpha"=>50);
		$myPicture->drawGradientArea(0,0,1080,432,DIRECTION_VERTICAL,$Settings);

		$myPicture->drawRectangle(0,0,1079,433,array("R"=>0,"G"=>0,"B"=>0));
		
		/* Write the picture title */  
		//Descomentar
		$myPicture->setFontProperties(array(
			"FontName"=>"themes/v2/fonts/Roboto-Regular-webfont.ttf",
			"FontSize"=>22
		));

		$myPicture->drawText(10,35,"Notas enviadas por usuario", array(
			"R"=>0,
			"G"=>0,
			"B"=>0
		));

		/* Set the default font properties */ 
		//Descomentar
		$myPicture->setFontProperties(array(
			"FontName"=>"themes/v2/fonts/Roboto-Regular-webfont.ttf",
			"FontSize"=>10,
			"R"=>0,
			"G"=>0,
			"B"=>0)
		);

		/* Create the pPie object */ 
		$PieChart = new yiipPie($myPicture,$MyData);

		/* Draw an AA pie chart */ 
		$PieChart->draw3DPie(280,265,array(
			"Radius"=>120,
			"DrawLabels"=>TRUE,
			"LabelStacked"=>TRUE,
			"WriteValues"=>PIE_VALUE_PERCENTAGE,
			"DataGapAngle"=>8,
			"DataGapRadius"=>6,
			"ValueR"=>0,
			"ValueG"=>0,
			"ValueB"=>0,
			"Border"=>TRUE,
			"BorderR"=>0,
			"BorderG"=>0,
			"BorderB"=>0
		));

		$myPicture->setShadow(TRUE,array("X"=>3,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

		$PieChart->drawPieLegend(780,50,array(
			"Style"=>LEGEND_ROUND,
			"Mode"=>LEGEND_VERTICAL,
			"Alpha"=>50
		));

		$data = $myPicture->toBase64();

		$im = imagecreatefromstring($data);
		if ($im !== false) 
		{
		    header('Content-Type: image/png');
		    imagepng($im);
		    imagedestroy($im);
		}
		else 
		{
		    echo 'An error occurred.';
		}
	}

    /**
     * % de rechazos y aprobaciones para el total de notas (en cada estatus)
     * @return [type] [description]
     */
	public function actionNotasCreditoEstatus()
	{
		Yii::import('application.extensions.pChart.yiipData');
		Yii::import('application.extensions.pChart.yiipDraw');
		Yii::import('application.extensions.pChart.yiipPie');
		Yii::import('application.extensions.pChart.yiipImage');

		$fechaini = Yii::app()->session['fechaini'];
		$fechafin = Yii::app()->session['fechafin'];

		$numrev = 2;

        $estatus = array(
    		array(
    			'estatus' => Notas::REV_APROBADO,
    			'nombre' => 'Aprobadas'
    		),
    		array(
    			'estatus' => Notas::REV_RECHAZADO,
    			'nombre' => 'Rechazadas'
    		)
    	);
  
        for ($j = 0; $j < $numrev; $j++)
        {
			$revision_id=$estatus[$j]['estatus'];
        	$totalxRevision=0; 

    		//print_r("FECHA INI [".$fechaini."]"."FECHA FIN [".$fechafin."]");
					
    		if (($fechaini=="--") and ($fechafin=="--")) 
    		{
				$notas = Notas::model()->with('razones.caracteristicas_tipo')->together()->findAll(array(
					'condition' => 't.estatus = :estatus AND t.revision = :val_revision_id ',
					'params' => array(
						':val_revision_id' => $revision_id,	
						':estatus' => Notas::ESTATUS_PUBLICADO
					)
				));
    		}
    		else
    		{
				$notas = Notas::model()->with('razones.caracteristicas_tipo')->together()->findAll(array(
					'condition' => ' (t.fecha_creacion >= :val_fechaini and t.fecha_creacion <= :val_fechafin) AND t.estatus = :estatus AND t.revision = :val_revision_id ',
					'params' => array(
						':estatus' => Notas::ESTATUS_PUBLICADO,
						':val_revision_id' => $revision_id,
				    	':val_fechaini' => $fechaini,
				    	':val_fechafin' => $fechafin
					)
				));
    		}

			$monto = 0;	   
			foreach ($notas as $key => $value)
			{							
            	if ($value->razones->caracteristicas_tipo->caracteristicas_id==Caracteristicas::ALMACEN)
            	{
					$monto = $monto+Tools::GetTotal($value);					
				}					
			}	

			$totalxRevision = $totalxRevision + round($monto,2);

			$monto = 0;	   
			foreach ($notas as $key => $value)
			{
            	if ($value->razones->caracteristicas_tipo->caracteristicas_id==Caracteristicas::DESCUENTOS)
            	{
					$monto = $monto+Tools::GetTotal($value);					
				}					
			}	

			$totalxRevision = $totalxRevision + round($monto,2);

			$monto = 0;	   
			foreach ($notas as $key => $value)
			{
            	if ($value->razones->caracteristicas_tipo->caracteristicas_id==Caracteristicas::COOPERACION)
            	{
					$monto= $monto+Tools::GetTotal($value);					
				}					
			}	

			$totalxRevision = $totalxRevision + round($monto,2);

			$monto = 0;	   
			foreach ($notas as $key => $value)
			{
            	if ($value->razones->caracteristicas_tipo->caracteristicas_id==Caracteristicas::REFACTURACION)
            	{
					$monto = $monto+Tools::GetTotal($value);					
				}					
			}	

			$totalxRevision = $totalxRevision + round($monto,2);
	
			$valores[$j] = Yii::app()->locale->numberFormatter->formatCurrency($totalxRevision, ""); 
			$tiposnotas[$j] = $estatus[$j]['nombre']." ($".Yii::app()->locale->numberFormatter->formatCurrency($totalxRevision, "").")";
	    }

		//print_r($valores);	

		$Notas = array(
			'valores'=>$valores,
			'tiposnotas'=>$tiposnotas			
		);

		/* Create and populate the pData object */
		$MyData = new yiipData();   
		//aqui van los valores de cada caracteristica 
		$MyData->addPoints($Notas['valores'],"ScoreA"); 

		$MyData->setSerieDescription("Notas","Notas por caracteristica");

		//Aqui van los nombres de cada caracteristica
		$MyData->addPoints($Notas['tiposnotas'],"Labels"); 

		$MyData->setAbscissa("Labels");

		/* Create the pChart object */
		$myPicture = new yiipImage(1080,432,$MyData);

		/* Draw a solid background */
		$myPicture->drawFilledRectangle(0,0,1080,432, array(
			"R"=>255, 
			"G"=>255, 
			"B"=>255, 
			"Dash"=>1, 
			"DashR"=>190,
			"DashG"=>203,
			"DashB"=>107
		));

		/* Overlay with a gradient */
		$Settings = array("StartR"=>255, "StartG"=>238, "StartB"=>219, "EndR"=>253, "EndG"=>200, "EndB"=>159, "Alpha"=>50);
		$myPicture->drawGradientArea(0,0,1080,432,DIRECTION_VERTICAL,$Settings);

		$myPicture->drawRectangle(0,0,1079,431,array("R"=>0,"G"=>0,"B"=>0));

		/* Write the picture title */  
		//Descomentar
		$myPicture->setFontProperties(array(
			"FontName"=>"themes/v2/fonts/Roboto-Regular-webfont.ttf",
			"FontSize"=>22
		));

		$myPicture->drawText(10,35,"Notas aprobadas vs. rechazadas",array(
			"R"=>0,
			"G"=>0,
			"B"=>0
		));

		/* Set the default font properties */ 
		//Descomentar
		$myPicture->setFontProperties(array(
			"FontName"=>"themes/v2/fonts/Roboto-Regular-webfont.ttf",
			"FontSize"=>10,
			"R"=>0,
			"G"=>0,
			"B"=>0
		));

		/* Create the pPie object */ 
		$PieChart = new yiipPie($myPicture,$MyData);

		/* Draw an AA pie chart */ 
		$PieChart->draw3DPie(280,265,array(
			"Radius"=>120,
			"DrawLabels"=>TRUE,
			"LabelStacked"=>TRUE,
			"WriteValues"=>PIE_VALUE_PERCENTAGE,
			"DataGapAngle"=>8,
			"DataGapRadius"=>6,
			"ValueR"=>0,
			"ValueG"=>0,
			"ValueB"=>0,
			"Border"=>TRUE,
			"BorderR"=>0,
			"BorderG"=>0,
			"BorderB"=>0
		));

		$myPicture->setShadow(TRUE,array("X"=>3,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

		$PieChart->drawPieLegend(780,50,array(
			"Style"=>LEGEND_ROUND,
			"Mode"=>LEGEND_VERTICAL,
			"Alpha"=>50
		));

		$data = $myPicture->toBase64();

		$im = imagecreatefromstring($data);
		if ($im !== false) 
		{
		    header('Content-Type: image/png');
		    imagepng($im);
		    imagedestroy($im);
		}
		else 
		{
		    echo 'An error occurred.';
		}
	}

    /**
     * Tiempo promedio que les toma a las notas entre su generación hasta su cierre 
     * (numero no grafica) (todas las notas publicadas con estatus publicado) las 
     * que no tengan fecha de cierre tomar fecha actual.
     * @return [type] [description]
     */
	public function getNotasCreditoTiempoPromedio()
	{
 		$command=Yii::app()->db->createCommand();
        $command->select('TO_DAYS(if(fecha_cierre is NULL, now(), if(fecha_cierre="0000-00-00", now(), fecha_cierre ) ))-TO_DAYS(fecha_creacion) as dias');
        $command->from('tblNotas');
        $command->where('estatus=:id', array(':id'=>1));
        $rows=$command->queryAll(); 

        $i = 0;
        $sum = 0;
        
        foreach ($rows as $key => $value)
        {
        	$sum = $sum+$value['dias'];
        	$i++;
        }

        if ($i!=0)
        {
           $promedio = $sum/$i;  
        }
        else
        {
         	$promedio= 0;
        } 

		$DatosG = array(
			'valores'=>round($promedio,2),
			'tiposnotas'=>"Promedio Días"			
		);		

		return $DatosG;
	}


}