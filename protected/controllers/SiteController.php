<?php
/**
 * SiteController class file
 *
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 *
 */
class SiteController extends Controller
{
	const FOLDER = '/x_importar/productos';

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionHelp()
	{
		$this->layout = 'clean';
		$this->render('help');
	}

	public function actionEmail()
	{
		/*
		$f = array();
		$evitarFlujos = array();

		// Obtener todas las solicitudes que han sido publicadas
		$solicitudes = Notas::model()->with('clientes', 'flujos','razones.caracteristicas_tipo')->together()->findAll(array(
			'condition' => 't.estatus = :estatus',
			'params' => array(
				':estatus' => Notas::ESTATUS_PUBLICADO
			)
		));

		// Por cada solicitud encontrada, tomar su caracteristica tipo y determinar el flujo que corresponde
		foreach ($solicitudes as $solicitud)
		{
			$f[$solicitud->id] = array();

			// Encontrar el flujo de cada solicitud (relacion con usuarios)
			$flujos = Flujos::model()->with('usuarios')->together()->findAll(array(
				'condition' => 't.caracteristicasTipo_id = :tipo',
				'params' => array(
					':tipo' => $solicitud->razones->caracteristicas_tipo->id
				)
			));

			//
			if (!empty($solicitud->flujos))
			{
				foreach ($solicitud->flujos as $flujoNota)
				{
					array_push($evitarFlujos, $flujoNota->id);
				}
			}

			// Recorrer cada flujo para la solicitud recorrida
			foreach ($flujos as $flujo)
			{
				if (!in_array($flujo->id, $evitarFlujos))
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

						// Encontrar los solicitantes a quienes el aprobador puede atender
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
							if (!isset($f[$solicitud->id][$flujo->nivel_aprobacion]))
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

									if ($UsuarioLogistica != null)
									{
										// permite que solamente 1 usuario se agregue al arreglo
										if (count($f[$solicitud->id]) < 1)
										{
											$f[$solicitud->id][$nivel_aprobacion] = array(
												'nota' => $solicitud->id,
												'importe' => Yii::app()->locale->numberFormatter->formatCurrency(Tools::GetTotal($solicitud), 'MXN'),
												'cliente' => $solicitud->clientes->CodigoNombre,
												'usuario' => array(
													'id' => $UsuarioLogistica->id,
													'nombre' => $usuario->nombre,
													'correo' => $UsuarioLogistica->correo
												)
											);
										}
									}
								}

								// permite que solamente 1 usuario se agregue al arreglo
								if (count($f[$solicitud->id]) < 1)
								{
									$f[$solicitud->id][$flujo->nivel_aprobacion] = array(
										'nota' => $solicitud->id,
										'importe' => Yii::app()->locale->numberFormatter->formatCurrency(Tools::GetTotal($solicitud), 'MXN'),
										'cliente' => $solicitud->clientes->CodigoNombre,
										'usuario' => array(
											'id' => $usuario->id,
											'nombre' => $usuario->nombre,
											'correo' => $usuario->correo
										)
									);
								}
							}
						}
					}
				}
			}

			// Se reinicia el arreglo para evitar que otra solicitud evite algun flujo
			$evitarFlujos = array();
		}

		// Inicializar el objeto para envio de correos
		Yii::import('application.extensions.phpMailer.yiiPhpMailer');
		$mailer = new yiiPhpMailer;

		foreach ($f as $nota)
		{
			foreach ($nota as $flujos)
			{
				$str = CBaseController::renderInternal(Yii::getPathOfAlias('application.commands.views.templates').'/supervisor.php', array(
					'id' => $flujos['nota'],
					'cliente' => $flujos['cliente'],
					'nombre' => $flujos['usuario']['nombre'],
					'importe' => $flujos['importe'],
				), true);
				$subject = "Solicitudes pendientes por revisar";
				echo $str."<hr>";

				$mailer->Ready($subject, $str, array(
					'name'=>$flujos['usuario']['nombre'],
					'email'=>$flujos['usuario']['correo']
				));
			}
		}

		//header('Content-type: application/json');
		//echo CJSON::encode($f);
		Yii::app()->end();
		*/
    }

	public function actionTest()
	{
		/*
		Yii::import('ext.phpexcel.XPHPExcel');
		$path = "protected/data/sample#2.xlsx";
		$stop = 1;
		$columnaMaxima = 1;
		$iniciaAscii = 65;

		$objPHPExcel = XPHPExcel::ReadExcelIOFactory($path);

		// Ciclo utilizado para obtener el # de columnas disponibles a recorrer
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
		{
			$highestColumn = $worksheet->getHighestColumn();
		    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

			// Determinar el # de columnas a recorrer
		    for ($col = 0; $col < $highestColumnIndex; ++ $col)
		    {
		    	$columnaMaxima = $col;
		    	$cell = $worksheet->getCellByColumnAndRow($col, 2); // Columnas de la fila 1
		        $val = $cell->getValue();
		        if (empty($val))
		        {
		        	$stop++;
		        	if ($stop >= 2)
		        	{
		        		break(2);
		        	}
		        }
		    }
		}

		// aqui se almacenaran los productos
		$p = array();
		$titulos = array();
		$marcas = array();

		$celdaAnio = $worksheet->getCellByColumnAndRow($columnaMaxima - 1, 1);
		$valorAnio = utf8_decode($celdaAnio->getValue());

		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
		{
		    $highestRow = $worksheet->getHighestRow(); // e.g. 10
		    $highestColumn = strtoupper(chr($iniciaAscii + $columnaMaxima - 1));
		    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

		    // Se recorren todas las columnas con titulos
	        for ($col = 0; $col < $highestColumnIndex; ++ $col)
	        {
	            $celda = $worksheet->getCellByColumnAndRow($col, 2);
	            $valor = utf8_decode($celda->getValue());
	            array_push($titulos, $valor);
	        }


		    // Recorrer cada fila
		    for ($row = 3; $row <= $highestRow; ++ $row)
		    {
		    	// Obtener el valor de cada columns
		        for ($col = 0; $col < $highestColumnIndex; ++ $col)
		        {
		            $cell = $worksheet->getCellByColumnAndRow($col, $row);
		            $val = utf8_decode($cell->getValue());

		            $a[$titulos[$col]] = $val;
		        }

		        //if ($a['codigo'] != 'SEPARATOR')
	        	//{
	        	//	array_push($marcas, $a);
	        	//}

		        // si la marca ya existe, solamente agregar el arreglo de nuevos valores
		        if (array_key_exists($a['marca'], $marcas))
		        {
			        array_push($marcas[$a['marca']], $a);
		        }
		        else
		        {
		        	if ($a['codigo'] != 'SEPARATOR')
		        	{
		        		$marcas[$a['marca']] = array($a);
		        	}
		        }
		    }

		    break; // porque solo queremos la primer hoja
		}

		Tools::print_array($marcas);
		die();

		// Utilizado solamente para obtener el nombre de la columna precio (se utilizara como indice)
		$x = array_keys($marcas[key($marcas)][0]);
		for($i=0; $i<=4; $i++)
		{
			unset($x[$i]);
		}
		$columnaPrecio = $x[key($x)];

		$contadorMarcas = count($marcas);
		if ($contadorMarcas > 0)
		{
			// Encontrar el año utilizado como cabecera en el archivo
			$criteria = new CDbCriteria();
			$criteria->compare('anio', $valorAnio);
			$anio = Anio::model()->find($criteria);

			if ($anio !== null)
			{
				// Recorrer las marcas
				foreach($marcas as $i => $ival)
				{
					// Determinar primero si la marca existe en el catalogo de marcas, si existe se tomara su id
					$criteria = new CDbCriteria();
					$criteria->compare('marca', $i, true);
					$brand = Marcas::model()->find($criteria);

					if ($brand !== null)
					{
						// Se recorren los productos encontrados en cada marca
						for($j=0; $j<count($marcas[$i]); $j++)
						{
							$producto = Productos::model()->find(array(
								'condition' => 't.codigo = :codigo',
								'params' => array(
									':codigo' => $marcas[$i][$j]['codigo']
								)
							));

							// Si el producto no se ha encontrado, agregarlo como nuevo
							if ($producto === null)
							{
								$productos = new Productos();
								$productos->codigo = $marcas[$i][$j]['codigo'];
								$productos->descripcion = utf8_encode($marcas[$i][$j]['descripcion']);
								$productos->empresas_id = 1;//$model->empresa_id;
								$productos->marcas_id = $brand->id;

								if ($productos->save(false))
								{
									$precio = new ProductosPrecio();
									$precio->precio = floatval($marcas[$i][$j][$columnaPrecio]);
									$precio->linea = $marcas[$i][$j]['linea'];
									$precio->indice = $columnaPrecio;
									$precio->anio_id = $anio->id;
									$precio->productos_id = $productos->id;

									if ($precio->save(false))
									{
										$error = false;
									}
									unset($precio);
								}
								unset($productos);
							}
							else
							{
								// Determinar antes si el precio y año ya habian sido dados de alta
								$costo = ProductosPrecio::model()->find(array(
									'condition' => 't.precio = :precio AND t.anio_id = :anio',
									'params' => array(
										':precio' => floatval($marcas[$i][$j][$columnaPrecio]),
										':anio' => $anio->id
									)
								));

								// solamente se agregar precios que no coincidan en año y costo (para evitar duplicados)
								if ($costo === null)
								{
									// como el producto ya existe solamente agregar el precio
									$precio = new ProductosPrecio();
									$precio->precio = floatval($marcas[$i][$j][$columnaPrecio]);
									$precio->linea = $marcas[$i][$j]['linea'];
									$precio->indice = $columnaPrecio;
									$precio->anio_id = $anio->id;
									$precio->productos_id = $producto->id;

									if ($precio->save(false))
									{
										$error = false;
									}
									unset($precio);
								}
							}
						}
					}
				}
			}
		}
		*/
	}

	// Notificaciones via email a los usuarios en los flujos despues de los aprobadores
	public function actionDarSeguimientoOtros()
	{
		$f = array();
		$plantilla = '';
		$start = (float) array_sum(explode(' ', microtime()));

		try
		{
			// Obtener todas las solicitudes
			$solicitudes = Notas::model()->with('razones.caracteristicas_tipo', 'flujos')->together()->findAll(array(
				'condition' => 't.estatus = :estatus AND t.revision IN (:revision1, :revision2, :revision3)',
				'params' => array(
					':estatus' => Notas::ESTATUS_PUBLICADO,
					':revision1' => Notas::REV_PROCESADO,
					':revision2' => Notas::REV_DEVOLUCIONES,
					':revision3' => Notas::REV_SAC
				)
			));

			// Por cada solicitud encontrada, tomar su caracteristica tipo y determinar el flujo que corresponde
			foreach ($solicitudes as $solicitud)
			{
				$usuarios = null;
				$url = Yii::app()->createAbsoluteUrl('site/index');

				if ($solicitud->revision == Notas::REV_PROCESADO)
				{
					// Enviar un correo a todos los usuarios con permiso de SAC
					$usuarios = Usuarios::model()->with('permisos')->together()->findAll(array(
						'condition' => 'permisos.id = :permiso',
						'params' => array(
							':permiso' => Usuarios::SAC
						)
					));
					$plantilla = 'sac_stage1';
					$url = Yii::app()->createAbsoluteUrl('sac/view', array('id'=>$solicitud->id));
				}
				elseif ($solicitud->revision == Notas::REV_DEVOLUCIONES)
				{
					// Enviar un correo a todos los usuarios con permiso de SAC
					$usuarios = Usuarios::model()->with('permisos')->together()->findAll(array(
						'condition' => 'permisos.id = :permiso',
						'params' => array(
							':permiso' => Usuarios::LOGISTICA
						)
					));
					$plantilla = 'devoluciones';
					$url = Yii::app()->createAbsoluteUrl('logistica/view', array('id'=>$solicitud->id));
				}
				elseif ($solicitud->revision == Notas::REV_SAC)
				{
					// Enviar un correo a todos los usuarios con permiso de SAC
					$usuarios = Usuarios::model()->with('permisos')->together()->findAll(array(
						'condition' => 'permisos.id = :permiso',
						'params' => array(
							':permiso' => Usuarios::SAC
						)
					));
					$plantilla = 'sac_stage2';
					$url = Yii::app()->createAbsoluteUrl('sac/view', array('id'=>$solicitud->id));
				}

				// Si existen usuarios
				if ($usuarios !== null)
				{
					foreach ($usuarios as $usuario)
					{
						Yii::import('application.extensions.phpMailer.yiiPhpMailer');

						$params = array(
							'nombre' => $usuario->nombre,
							'url' => $url,
							'id' => $solicitud->id,
							'folio' => $solicitud->folio,
							'importe' => Yii::app()->locale->numberFormatter->formatCurrency(Tools::GetTotal($solicitud), 'MXN'),
							'cliente' => $solicitud->clientes->nombre,
							'cliente_codigo' => $solicitud->clientes_codigo,
							'fecha_creacion' => $solicitud->fecha_creacion,
							'fecha_vencimiento' => $solicitud->fecha_vencimiento
						);

						array_push($f, $params);

						$str = $this->renderPartial('//templates/'.$plantilla, $params, true);

						//echo $str;

						$subject = "Solicitudes pendientes por revisar";
						$mailer = new yiiPhpMailer;
						//$mailer->Ready($subject, $str, array('name'=>$usuario->nombre, 'email'=>$usuario->correo));
					}
				}
			}
		}
		catch (Exception $exc)
		{
			$message = $exc->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
			array_push($f, $message);
		}

		$end = (float) array_sum(explode(' ',microtime()));

		$size = memory_get_usage(true);
		$unit = array('b','kb','mb','gb','tb','pb');

		header('Content-type: application/json');
		echo CJSON::encode(array(
			'action'=>'DarSeguimientoOtros',
			'result'=>'ok',
			'memory'=>@round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i],
			'duration'=>sprintf("%.2f", ($end-$start))." seg",
			'date'=>date("Y-m-d G:i:s"),
			//'data' => $f
		));
		Yii::app()->end();
	}

	// Notificaciones via email a los usuarios aprobadores y supervisores
	public function actionDarSeguimientoAprobadores()
	{
		$f = array();
		$start = (float) array_sum(explode(' ', microtime()));

		try
		{
			// Obtener todas las solicitudes
			$solicitudes = Notas::model()->with('razones.caracteristicas_tipo', 'flujos')->together()->findAll(array(
				'condition' => 't.estatus = :estatus AND t.revision IN (:revision1, :revision2)',
				'params' => array(
					':estatus' => Notas::ESTATUS_PUBLICADO,
					':revision1' => Notas::REV_PENDIENTE,
					':revision2' => Notas::REV_ACEPTADO
				)
			));

			// Por cada solicitud encontrada, tomar su caracteristica tipo y determinar el flujo que corresponde
			foreach ($solicitudes as $solicitud)
			{
				$flujosCompletados = array(0);
				foreach($solicitud->flujos as $flujos)
				{
					array_push($flujosCompletados, $flujos->nivel_aprobacion);
				}

				$flujos = Flujos::model()->with('usuarios')->together()->findAll(array(
					'condition' => 't.caracteristicasTipo_id = :tipo AND t.nivel_aprobacion NOT IN ('.implode(",", $flujosCompletados).')',
					'params' => array(
						':tipo' => $solicitud->razones->caracteristicas_tipo->id
					)
				));

				// Recorrer cada flujo
				foreach ($flujos as $flujo)
				{
					// Determinar el usuario de cada flujo para cada solicitud y crear el arreglo
					foreach ($flujo->usuarios as $usuario)
					{
						$cumple = false;
						$existe = false;
						$flujoCompletado = false;

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
							if (!isset($f[$solicitud->id]))
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

									if ($UsuarioLogistica != null)
									{
										$f[$solicitud->id] = array(
											'id' => $solicitud->id,
											'flujo' => $flujo->id,
											'folio' => $solicitud->folio,
											'nivel' => $flujo->nivel_aprobacion,
											'caracteristica' => $solicitud->razones->caracteristicas_tipo->codigo,
											'razon' => $solicitud->razones->codigo,
											'importe' => Yii::app()->locale->numberFormatter->formatCurrency(Tools::GetTotal($solicitud), 'MXN'),
											'cumple' => $cumple,
											'expresion' => $flujo->expresion,
											'cliente' => $solicitud->clientes->nombre,
											'cliente_codigo' => $solicitud->clientes_codigo,
											'fecha_creacion' => $solicitud->fecha_creacion,
											'fecha_vencimiento' => $solicitud->fecha_vencimiento,
											'usuario' => array(
												'id' => $usuario->id,
												'nombre' => $usuario->nombre,
												'correo' => $usuario->correo
											)
										);
									}
								}

								$f[$solicitud->id] = array(
									'id' => $solicitud->id,
									'flujo' => $flujo->id,
									'folio' => $solicitud->folio,
									'nivel' => $flujo->nivel_aprobacion,
									'caracteristica' => $solicitud->razones->caracteristicas_tipo->codigo,
									'razon' => $solicitud->razones->codigo,
									'importe' => Yii::app()->locale->numberFormatter->formatCurrency(Tools::GetTotal($solicitud), 'MXN'),
									'cumple' => $cumple,
									'expresion' => $flujo->expresion,
									'cliente' => $solicitud->clientes->nombre,
									'cliente_codigo' => $solicitud->clientes_codigo,
									'fecha_creacion' => $solicitud->fecha_creacion,
									'fecha_vencimiento' => $solicitud->fecha_vencimiento,
									'usuario' => array(
										'id' => $usuario->id,
										'nombre' => $usuario->nombre,
										'correo' => $usuario->correo
									)
								);
							}
						}
					}
				}
			}

			foreach($f as $data)
			{
				Yii::import('application.extensions.phpMailer.yiiPhpMailer');

				$str = $this->renderPartial('//templates/aprobadores', array(
					'id' => $data['id'],
					'url' => Yii::app()->createAbsoluteUrl('aprobadores/update', array('id'=>$data['id'])),
					'folio' => $data['folio'],
					'cliente' => $data['cliente'],
					'cliente_codigo' => $data['cliente_codigo'],
					'fecha_creacion' => $data['fecha_creacion'],
					'fecha_vencimiento' => $data['fecha_vencimiento'],
					'importe' => $data['importe'],
					'nombre' => $data['usuario']['nombre'],
					'correo' => $data['usuario']['correo']
				), true);

				//echo $str;

				$subject = "Solicitudes pendientes por revisar";
				$mailer = new yiiPhpMailer;
				//$mailer->Ready($subject, $str, array('name'=>$data['usuario']['nombre'], 'email'=>$data['usuario']['correo']));
			}
		}
		catch (Exception $exc)
		{
			$message = $exc->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
			array_push($f, $message);
		}

		$end = (float) array_sum(explode(' ',microtime()));

		$size = memory_get_usage(true);
		$unit = array('b','kb','mb','gb','tb','pb');

		header('Content-type: application/json');
		echo CJSON::encode(array(
			'action'=>'DarSeguimientoAprobadores',
			'result'=>'ok',
			'memory'=>@round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i],
			'duration'=>sprintf("%.2f", ($end-$start))." seg",
			'date'=>date("Y-m-d G:i:s"),
			//'data' => $f
		));
		Yii::app()->end();
	}

	public function actionVerificar()
	{
		$f = array();

		// Obtener todas las solicitudes
		$solicitudes = Notas::model()->with('razones.caracteristicas_tipo')->together()->findAll(array(
			'condition' => 't.estatus = :estatus AND t.revision NOT IN (:revision1, :revision2)',
			'params' => array(
				':estatus' => Notas::ESTATUS_PUBLICADO,
				':revision1' => Notas::REV_RECHAZADO,
				':revision2' => Notas::REV_APROBADO
			)
		));

		// Por cada solicitud encontrada, tomar su caracteristica tipo y determinar el flujo que corresponde
		foreach ($solicitudes as $solicitud)
		{
			$flujos = Flujos::model()->with('usuarios')->together()->findAll(array(
				'condition' => 't.caracteristicasTipo_id = :tipo',
				'params' => array(
					':tipo' => $solicitud->razones->caracteristicas_tipo->id
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
						if (!isset($f[$solicitud->id][$flujo->nivel_aprobacion]))
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

								if ($UsuarioLogistica != null)
								{
									$f[$solicitud->id][$nivel_aprobacion] = array(
										'flujo' => $flujo->id,
										'nivel' => $nivel_aprobacion,
										'caracteristica' => $solicitud->razones->caracteristicas_tipo->codigo,
										'razon' => $solicitud->razones->codigo,
										'importe' => Yii::app()->locale->numberFormatter->formatCurrency(Tools::GetTotal($solicitud), 'MXN'),
										'cumple' => $cumple,
										'expresion' => null,
										'usuario' => array(
											'id' => $UsuarioLogistica->id,
											'correo' => $UsuarioLogistica->correo
										)
									);
								}
							}

							$f[$solicitud->id][$flujo->nivel_aprobacion] = array(
								'flujo' => $flujo->id,
								'nivel' => $flujo->nivel_aprobacion,
								'caracteristica' => $solicitud->razones->caracteristicas_tipo->codigo,
								'razon' => $solicitud->razones->codigo,
								'importe' => Yii::app()->locale->numberFormatter->formatCurrency(Tools::GetTotal($solicitud), 'MXN'),
								'cumple' => $cumple,
								'expresion' => $flujo->expresion,
								'usuario' => array(
									'id' => $usuario->id,
									'correo' => $usuario->correo
								)
							);
						}
					}
				}
			}
		}

		header('Content-type: application/json');
		echo CJSON::encode($f);
		Yii::app()->end();
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$model = new LoginForm;

		// Verificar si el usuario tiene sesion
		if(!Yii::app()->user->isGuest)
		{
			$this->redirect($this->verify());
		}

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];

			if($model->validate() && $model->login())
			{
				// Almacenar el tipo de empresa
				Yii::app()->user->setState('empresa_id', $model->company);
				Yii::app()->user->setState('empresa_nombre', Empresas::model()->findByPk($model->company)->nombre);
				$this->redirect($this->verify());
			}
			else
			{
				$model->addError('company', 'errorsote');
			}
		}

		$modelEmpresas = Empresas::model()->findAll();
		$listEmpresas = CHtml::listData($modelEmpresas, 'id', 'nombre');

		$this->layout = 'login';
		$this->render('index',array(
			'model' => $model,
			'empresas' => $listEmpresas
		));
	}

	/**
	 * [actionError description]
	 * @return [type] [description]
	 */
	public function actionError()
	{
		if($error = Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
			{
				echo $error['message'];
			}
			else
			{
				$this->render('error', $error);
			}
		}
	}

	/**
	 * [actionLogout description]
	 * @return [type] [description]
	 */
	public function actionLogout()
	{
		$user = Usuarios::model()->findByPk(Yii::app()->user->id);

		if($user !== null)
		{
			$user->islogged = 0; // 0 porque el usuario ya se ha deslogueado
			$user->save(false);
		}

		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * [verify description]
	 * @return [type] [description]
	 */
	private function verify()
	{
		$output = '';

		// Determinar el permiso del usuario y redireccionar a la pagina que deberia
		if (Yii::app()->user->verifyRole(Usuarios::ADMIN))
		{
			$output = Yii::app()->createUrl('admin/clientes/index');
		}
		elseif (Yii::app()->user->verifyRole(Usuarios::SUPERVISOR))
		{
			$output = Yii::app()->createUrl('supervisor/default/index');
		}
		elseif (Yii::app()->user->verifyRole(Usuarios::APROBADOR))
		{
			$output = Yii::app()->createUrl('aprobadores');
		}
		elseif (Yii::app()->user->verifyRole(Usuarios::SOLICITUD))
		{
			$output = Yii::app()->createUrl('solicitudes');
		}
		elseif (Yii::app()->user->verifyRole(Usuarios::LOGISTICA))
		{
			$output = Yii::app()->createUrl('logistica');
		}
		elseif (Yii::app()->user->verifyRole(Usuarios::SAC))
		{
			$output = Yii::app()->createUrl('sac');
		}
		else
		{
			$output = Yii::app()->createUrl('solicitudes');
		}

		return $output;
	}

	/**
	 * [actionImportarPrecios description]
	 * @return [type] [description]
	 */
	public function actionImportarPrecios()
	{
		$codigosProducto = array();
		$registro = array();
		$null = array();
		$fila = 0;
		$columna = 0;

		try
		{
			// Verificar que el directorio exista
			if (is_dir(Yii::app()->basePath.SiteController::FOLDER))
			{
				// obtener el path del directorio
				$dir = Yii::app()->basePath.SiteController::FOLDER;
				$path = $dir.'/precios.csv';

				// abrir el archivo
				if (($gestor = fopen($path, "r")) !== FALSE)
				{
					// interpretar cada linea del archivo
					while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE)
					{
						// La primera linea siempre tendra los codigos de los productos
						if ($fila == 1)
						{
							$codigosProducto = explode(";", $datos[0]);

							// Quitar las 2 primeras columnas
							$null = array_shift($codigosProducto);
							$null = array_shift($codigosProducto);
						}
						elseif ($fila > 1)
						{
							// las siguientes filas de datos
							$registro = explode(";", $datos[0]);

							// Verificamos que el codigo del cliente ya este registrado
							$cliente = Clientes::model()->find(array(
								'condition' => 't.codigo = :codigo',
								'params' => array(
									':codigo' => $registro[0]
								)
							));

							// Si existe entonces vamos a crear los registros y sus relaciones
							if ($cliente !== null)
							{
								// recorrer cada columna del registro (se omiten las 2 primeras - codigo y cliente)
								for($i=2; $i<count($registro); $i++)
								{
									// Verificar que el codigo del producto existe
									$producto = Productos::model()->find(array(
										'select' => 't.id',
										'condition' => 't.codigo = :codigo',
										'params' => array(
											':codigo' => $codigosProducto[$i-2]
										)
									));

									if ($producto !== null)
									{
										//$existeProductoPrecio = ProductosPrecio::model()->count(array(
										//	'condition' => 't.precio = :precio AND t.productos_id = :producto_id',
										//	'params' => array(
										//		':precio' => $registro[$i],
										//		':producto_id' => $producto->id
										//	)
										//));

										// 0 significa que no existe y lo debemos registrar
										//if (intval($existeProductoPrecio) === 0)
										//{
											$precio = new ProductosPrecio();
											$precio->precio = $registro[$i];
											$precio->productos_id = $producto->id;
											$precio->anio_id = 1; // 2014 id

											// Se guarda el precio del producto
											if ($precio->save())
											{
												// Y se crea la relación con el cliente
												$clienteProducto = new ClientesProductos();
												$clienteProducto->catClientes_codigo = $cliente->codigo;
												$clienteProducto->tblProductosPrecio_id = $precio->primaryKey;
												$clienteProducto->save();
											}
											else
											{
												Yii::log('Error al guardar un precio por '.$registro[$i].' productoId: '.$producto->id, CLogger::LEVEL_ERROR, 'application');
											}
										//}
									}
									else
									{
										Yii::log('No se encontro el producto: '.$codigosProducto[$i-2], CLogger::LEVEL_ERROR, 'application');
									}

									// solamente para contar el numero de columnas recorridas
									if ($fila == 2)
									{
										$columna++;
									}
								}
							}
							else
							{
								Yii::log('No se encontro el cliente: '.$registro[0], CLogger::LEVEL_ERROR, 'application');
							}
						}

						$fila++;
					}
				}
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
			echo $message;
		}

		echo 'Se procesaron '.$fila.' filas y '.$columna.' columnas';
	}

	/**
	 * [actionImport description]
	 * @return [type] [description]
	 */
	public function actionImportarProductos()
	{
		$error = '';
		$productos = array();
		$marcas = array();
		$marcaId = null;

		try
		{
			// Verificar que el directorio exista
			if (is_dir(Yii::app()->basePath.SiteController::FOLDER))
			{
				// obtener el path del directorio
				$dir = Yii::app()->basePath.SiteController::FOLDER;
				$path = $dir.'/productos.csv';

				// abrir el archivo
				if (($gestor = fopen($path, "r")) !== FALSE)
				{
					$fila = 0;

					// interpretar cada linea del archivo
				    while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE)
				    {
						// La fila #1 determina los nombres de las columnas
						if ($fila > 0)
						{
							$registro = explode(";", $datos[0]);

							$producto = Productos::model()->find(array(
								'condition' => 't.codigo = :codigo',
								'params' => array(
									':codigo' => $registro[0]
								)
							));

							// Si el producto no se ha encontrado, agregarlo como nuevo
							if ($producto === null)
							{
								$reg = trim($registro[2]);
								// Si la letra no existe en el arreglo o no viene vacia
								if ((!empty($reg)) && (!array_key_exists($registro[2], $marcas)))
								{
									// Obtener su id y agregarla
									$marcaId = Marcas::model()->find(array(
										'select' => 'id',
										'condition' => 't.identificador = :letra',
										'params' => array(
											':letra' => $registro[2]
										)
									));

									if ($marcaId !== null)
									{
										$marcas[$registro[2]] = $marcaId->id;
									}
								}

								if (array_key_exists($registro[2], $marcas))
								{
									$productos = new Productos();
									$productos->codigo = $registro[0];
									$productos->descripcion = utf8_encode($registro[1]);
									$productos->linea = utf8_encode($registro[3]);
									$productos->empresas_id = 1;
									$productos->marcas_id = $marcas[$registro[2]];

									$productos->save(false);
									unset($productos);
								}
							}
						}

				        $fila++;
				    }
				}
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
			echo $message;
		}

		echo 'Importacion finalizada';
	}
}
