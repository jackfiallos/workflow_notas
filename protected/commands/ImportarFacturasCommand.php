<?php
//php cron.php importarfacturas
class ImportarFacturasCommand extends CConsoleCommand 
{
	const FOLDER = '/x_importar/facturas';

	/**
	 * [run description]
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function run($args) 
	{
		$error = '';
		
		try
		{
			// Verificar que el directorio exista
			if (is_dir(Yii::app()->basePath.ImportarFacturasCommand::FOLDER))
			{
				// obtener el path del directorio
				$dir = Yii::app()->basePath.ImportarFacturasCommand::FOLDER;

				// obtener los archivos del directorio y evitar los puntos del OS
				$files = array_diff(scandir($dir), array('..', '.'));

				// Recorrer cada archivo
				foreach ($files as $file)
				{
					$criteria = new CDbCriteria;
					$criteria->compare('archivo', $file, true);

					// Por cada archivo verificar si su nombre ya existe en la tabla tblProcesamiento
					$existe = Procesamiento::model()->count($criteria);

					// si no existe, entonces se puede procesar
					if (!(bool)$existe)
					{
						if (($gestor = fopen(Yii::app()->basePath.ImportarFacturasCommand::FOLDER.$file, "r")) !== FALSE)
						{
							while (($datos = fgetcsv($gestor, 1024, ";")) !== FALSE)
							{
								// El archivo debe de tener 12 campos
								if (count($datos) == 12)
								{
									$factura = new Facturas();
									$factura->orden_compra = $datos[0];
									$factura->clientes_codigo = $datos[2];
									$factura->folio = $datos[3];
									$factura->fecha = $datos[4];
									$factura->monto = $datos[5];
									$factura->codigo_producto = $datos[6];
									$factura->descripcion_producto = $datos[7];
									$factura->precio_unitario = $datos[8];
									$factura->cantidad_piezas = $datos[9];
									$factura->costo_iva = $datos[10];
									
									if ($factura->save(false))
									{
										unset($factura);
									}
									else
									{
										$error = $factura->getError();
									}
								}
								else
								{
									$error = 'El archivo tiene el formato incorrecto';
								}
							}

							// Cerrar el archivo
							fclose($gestor);

							// almacenar que el archivo fue procesado
							$procesado = new Procesamiento();
							$procesado->tipo = 'factura';
							$procesado->archivo = $file;
							$procesado->save(false);
						}

						if (!empty($error))
						{
							throw new Exception($error);
						}
					}

					echo $file."\n";
				}
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
			echo $message;
		}
	}
}