<?php
/**
 * ProductosController class file
 *
 * @autor		Jackfiallos
 * @link		http://jackfiallos.com
 * @copyright	Copyright (c) 2011 Qbit Mexhico
 * @description
 *
 *
 **/
class ProductosController extends Controller
{
	const FOLDER = '/protected/files/';
	private $tmpFileName = '';

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
				'actions'=>array('index', 'importar'),
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
		$model = new Productos('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Productos']))
        {
            $model->attributes = $_GET['Productos'];
        }

        // empresas
		$modelEmpresas = Empresas::model()->findAll();
		$listEmpresas = CHtml::listData($modelEmpresas, 'id', 'nombre');

        $this->render('index',array(
            'model' => $model,
            'empresas' => $listEmpresas
        ));
	}

	/**
	 * [actionImportar description]
	 * @return [type] [description]
	 */
	public function actionImportar()
	{
		$model = new ImportarArchivoForm();
		$error = true;
		$stop = 1;
		$columnaMaxima = 1;
		$iniciaAscii = 65;

		try
		{
			if((isset($_POST['ImportarArchivoForm'])) && (strlen($_FILES['ImportarArchivoForm']['name']['archivo'])>0))
	        {
	            $model->attributes = $_POST['ImportarArchivoForm'];
	            $model->archivo = CUploadedFile::getInstance($model, 'archivo');
	            $model->scenario = 'productos';

	            // Validaciones
	            if($model->validate())
	            {
	            	$this->tmpFileName = str_replace(" ","",date('dmYHis-z-').microtime()).".".$model->archivo->getExtensionName();
	                if ($model->archivo->saveAs(Yii::getPathOfAlias('webroot').ProductosController::FOLDER.$this->tmpFileName))
					{
						Yii::import('ext.phpexcel.XPHPExcel');
						$path = Yii::getPathOfAlias('webroot').ProductosController::FOLDER.$this->tmpFileName;

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
													$precio->linea = utf8_encode($marcas[$i][$j]['linea']);
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

						if (!$error)
						{
							Yii::app()->user->setFlash('Productos.Success', 'El archivo fue importado exitosamente.');
							$this->redirect(Yii::app()->createUrl('admin/productos/index'));
						}
	               	}
	            }
	        }
	        else
	        {
	        	Yii::app()->user->setFlash('Productos.Error', 'Por favor seleccione un archivo y eliga una empresa para relacionar los productos cargados.');
	        }

	        $modelEmpresas = Empresas::model()->findAll();
			$listEmpresas = CHtml::listData($modelEmpresas, 'id', 'nombre');
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
			Yii::app()->user->setFlash('Productos.Error', $message);
		}

		$this->render('importar', array(
            'model' => $model,
            'empresas' => $listEmpresas
        ));
	}

	/**
	 * [actionImportar description]
	 * @return [type] [description]
	 */
	public function actionImportar_()
	{
		$model = new ImportarArchivoForm();
		$error = true;

		try
		{
			if((isset($_POST['ImportarArchivoForm'])) && (strlen($_FILES['ImportarArchivoForm']['name']['archivo'])>0))
	        {
	            $model->attributes = $_POST['ImportarArchivoForm'];
	            $model->archivo = CUploadedFile::getInstance($model, 'archivo');
	            $model->scenario = 'productos';

	            // Validaciones
	            if($model->validate())
	            {
	            	$this->tmpFileName = str_replace(" ","",date('dmYHis-z-').microtime()).".".$model->archivo->getExtensionName();
	                if ($model->archivo->saveAs(Yii::getPathOfAlias('webroot').ProductosController::FOLDER.$this->tmpFileName))
					{
						$fila = 1;
						if (($gestor = fopen(Yii::getPathOfAlias('webroot').ProductosController::FOLDER.$this->tmpFileName, "r")) !== FALSE)
						{
						    while (($datos = fgetcsv($gestor, 1024, ";")) !== FALSE)
						    {
						    	// El archivo debe de tener 5 campos
						    	if (count($datos) == 5)
						    	{
						    		// Verificar que exista al menos el código del producto
						    		if (!empty($datos[0]))
						    		{
						    			// Buscando el cliente por codigo (segun unico) para evitar el registro duplicado
							    		$buscar = Productos::model()->find(array(
							    			'condition' => 't.codigo = :codigo',
							    			'params' => array(
							    				':codigo' => $datos[0]
							    			)
							    		));

							    		if ($buscar === null)
							    		{
											$productos = new Productos();
											$productos->codigo = $datos[0];
											$productos->descripcion = $datos[1];
											$productos->empresas_id = $model->empresa_id;

											if ($productos->save(false))
											{
												$anios = Anio::model()->findAll();
												if ($anios !== null)
												{
													$i = 0;
													foreach ($anios as $anio)
													{
														$precio = new ProductosPrecio();
														$precio->precio = $datos[$i+2];
														$precio->anio_id = $anio->id;
														$precio->productos_id = $productos->id;
														if ($precio->save(false))
														{
															$error = false;
														}
														unset($precio);
														$i++;
													}
												}
												unset($anios);
											}
											unset($productos);
										}
										else
										{
											Yii::app()->user->setFlash('Productos.Error', 'Se encontraron registros con c&oacute;digos duplicados.');
											$error = true;
										}
										unset($buscar);
									}
								}
								else
								{
									Yii::app()->user->setFlash('Productos.Error', 'El archivo tiene el formato incorrecto.');
									$error = true;
								}
							}

							// Cerrar el archivo
						    if (fclose($gestor))
						    {
						    	// Verificar si existe antes de borrarlo
						    	if (file_exists(Yii::getPathOfAlias('webroot').ProductosController::FOLDER.$this->tmpFileName))
						    	{
							    	unlink (Yii::getPathOfAlias('webroot').ProductosController::FOLDER.$this->tmpFileName);
							    }
						    }
						}

						if (!$error)
						{
							Yii::app()->user->setFlash('Productos.Success', 'El archivo fue importado exitosamente.');
							$this->redirect(Yii::app()->createUrl('admin/productos/index'));
						}
	               	}
	            }
	        }
	        else
	        {
	        	Yii::app()->user->setFlash('Productos.Error', 'Por favor seleccione un archivo y eliga una empresa para relacionar los productos cargados.');
	        }

	        $modelEmpresas = Empresas::model()->findAll();
			$listEmpresas = CHtml::listData($modelEmpresas, 'id', 'nombre');
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
			Yii::app()->user->setFlash('Productos.Error', $message);
		}

		$this->render('importar', array(
            'model' => $model,
            'empresas' => $listEmpresas
        ));
	}
}
