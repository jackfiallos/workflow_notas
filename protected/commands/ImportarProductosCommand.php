<?php
//php cron.php importarproductos
class ImportarProductosCommand extends CConsoleCommand 
{
	const FOLDER = '/x_importar/productos';

	/**
	 * [run description]
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function run($args) 
	{
		$error = '';
		$titulos = array();
		$a = array();
		
		try
		{
			// Verificar que el directorio exista
			if (is_dir(Yii::app()->basePath.ImportarProductosCommand::FOLDER))
			{
				// obtener el path del directorio
				$dir = Yii::app()->basePath.ImportarProductosCommand::FOLDER;
				$path = $dir.'/precios.xlsx';

				Yii::import('ext.phpexcel.XPHPExcel');
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
					    }

					    break; // porque solo queremos la primer hoja
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

		Tools::print_array($a);
	}
}