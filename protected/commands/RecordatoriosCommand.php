<?php
//php cron.php recordatorios
class RecordatoriosCommand extends CConsoleCommand 
{
    public function run($args) 
	{
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
				
				$mailer->Ready($subject, $str, array(
					'name'=>$flujos['usuario']['nombre'], 
					'email'=>$flujos['usuario']['correo']
				));
			}
		}

		//header('Content-type: application/json');
		//echo CJSON::encode($f);
		Yii::app()->end();
    }
}