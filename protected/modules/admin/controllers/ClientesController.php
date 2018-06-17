<?php
/**
 * ClientesController class file
 * 
 * @autor		Jackfiallos
 * @link		http://jackfiallos.com
 * @copyright	Copyright (c) 2011 Qbit Mexhico
 * @description
 * 
 *
 **/
class ClientesController extends Controller
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
				'actions'=>array('index', 'importar', 'update', 'create'),
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
		$model = new Clientes('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Clientes']))
        {
            $model->attributes = $_GET['Clientes'];
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

		try
		{
			if((isset($_POST['ImportarArchivoForm'])) && (strlen($_FILES['ImportarArchivoForm']['name']['archivo'])>0))
	        {
	            $model->attributes = $_POST['ImportarArchivoForm'];
	            $model->archivo = CUploadedFile::getInstance($model, 'archivo');
	            $model->scenario = 'clientes';

	            // Validaciones
	            if($model->validate())
	            {
	            	$this->tmpFileName = str_replace(" ","",date('dmYHis-z-').microtime()).".".$model->archivo->getExtensionName();
	                if ($model->archivo->saveAs(Yii::getPathOfAlias('webroot').ClientesController::FOLDER.$this->tmpFileName))
					{
						$fila = 1;
						if (($gestor = fopen(Yii::getPathOfAlias('webroot').ClientesController::FOLDER.$this->tmpFileName, "r")) !== FALSE)
						{
						    while (($datos = fgetcsv($gestor, 1024, ";")) !== FALSE)
						    {
						    	// El archivo debe de tener 3 campos
						    	if (count($datos) == 2)
						    	{
						    		// Verificar que exista al menos el código del cliente
						    		if (!empty($datos[0]))
						    		{
							    		// Buscando el cliente por codigo (segun unico) para evitar el registro duplicado
							    		$buscar = Clientes::model()->find(array(
							    			'condition' => 't.codigo = :codigo',
							    			'params' => array(
							    				':codigo' => trim($datos[0])
							    			)
							    		));

						    			// si el cliente no existe, se crea un nuevo registro
						    			if ($buscar === null)
							    		{
								        	$cliente = new Clientes();
											$cliente->codigo = trim($datos[0]);
											$cliente->nombre = trim($datos[1]);
											if ($cliente->save(false))
											{
												$clienteEmpresa = new ClientesHasEmpresas();
								                $clienteEmpresa->clientes_codigo = $cliente->codigo;
								                $clienteEmpresa->empresas_id = $model->empresa_id;
								                $clienteEmpresa->save(false);
												$error = false;
												$fila++;
											}
											unset($cliente);
										}
										else // si el cliente existe, se sobreescriben los datos
										{
											$buscar->codigo = trim($datos[0]);
											$buscar->nombre = trim($datos[1]);
											if ($buscar->save(false))
											{
												$error = false;
												$fila++;
											}
										}
										unset($buscar);
									}
								}
								else
								{
									Yii::app()->user->setFlash('Clientes.Error', 'El archivo tiene el formato incorrecto.');
									$error = true;
								}
							}
						    
						    // Cerrar el archivo en uso
						    if (fclose($gestor))
						    {
							    // Eliminar el archivo que temporalmente fue cargado
								if (file_exists(Yii::getPathOfAlias('webroot').ClientesController::FOLDER.$this->tmpFileName))
								{
									unlink (Yii::getPathOfAlias('webroot').ClientesController::FOLDER.$this->tmpFileName);
								}
							}

							if (!$error)
							{
								Yii::app()->user->setFlash('Clientes.Success', $fila.' registros importados correctamente.');
								$this->redirect(Yii::app()->createUrl('admin/clientes/index'));
							}
						}
	               	}
	            }
	        }
	        else
			{
				Yii::app()->user->setFlash('Clientes.Error', 'Es necesario que seleccione un archivo de tipo .csv');
				$error = true;
			}

			$modelEmpresas = Empresas::model()->findAll();
			$listEmpresas = CHtml::listData($modelEmpresas, 'id', 'nombre');
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
			Yii::app()->user->setFlash('Clientes.Error', $message);
		}

		$this->render('importar', array(
            'model'=>$model,
            'empresas' => $listEmpresas
        ));
	}

	/**
	 * [actionImportarDescuentos description]
	 * @return [type] [description]
	 */
	public function actionImportarDescuentos()
	{
		$model = new ImportarArchivoForm();
		$error = true;
		$listAnios = array();

		try
		{
			if((isset($_POST['ImportarArchivoForm'])) && (strlen($_FILES['ImportarArchivoForm']['name']['archivo'])>0))
	        {
	            $model->attributes = $_POST['ImportarArchivoForm'];
	            $model->archivo = CUploadedFile::getInstance($model, 'archivo');

	            // Validaciones
	            if($model->validate())
	            {
	            	$this->tmpFileName = str_replace(" ","",date('dmYHis-z-').microtime()).".".$model->archivo->getExtensionName();
	                if ($model->archivo->saveAs(Yii::getPathOfAlias('webroot').ClientesController::FOLDER.$this->tmpFileName))
					{
						$fila = 1;
						if (($gestor = fopen(Yii::getPathOfAlias('webroot').ClientesController::FOLDER.$this->tmpFileName, "r")) !== FALSE)
						{
						    while (($datos = fgetcsv($gestor, 1024, ";")) !== FALSE)
						    {
						    	// El archivo debe de tener 6 campos
						    	if (count($datos) == 6)
						    	{
						    		// Verificar que exista al menos el código del cliente
						    		if (!empty($datos[0]))
						    		{
							    		// Buscando el cliente por codigo (segun unico) para evitar el registro duplicado
							    		$buscar = Clientes::model()->with('descuentos')->together()->find(array(
							    			'condition' => 't.codigo = :codigo',
							    			'params' => array(
							    				':codigo' => $datos[0]
							    			)
							    		));

							    		if (($buscar !== null) && (empty($buscar->descuentos)))
							    		{
								        	$descuento = new DescuentoClientes();
											$descuento->codigo = $datos[2];
											$descuento->todas_nopfd = (float)$datos[3];
											$descuento->pfd_notrev = (float)$datos[4];
											$descuento->todas_trev = (float)$datos[5];
											$descuento->clientes_codigo = $datos[0];
											$descuento->anio_id = $model->anio_id;

											if ($descuento->save(false))
											{
												$error = false;
											}
											unset($descuento);
										}
										else
										{
											Yii::app()->user->setFlash('Clientes.Descuentos.Error', 'El archivo tiene registros duplicados o no existentes. fila#'.$fila);
											$error = true;
											//break;
										}
										
										unset($buscar);
									}
								}
								else
								{
									Yii::app()->user->setFlash('Clientes.Descuentos.Error', 'El archivo tiene el formato incorrecto.');
									$error = true;
								}

								$fila++;
							}
						    
						    // Cerrar el archivo en uso
						    if (fclose($gestor))
						    {
							    // Eliminar el archivo que temporalmente fue cargado
								if (file_exists(Yii::getPathOfAlias('webroot').ClientesController::FOLDER.$this->tmpFileName))
								{
									unlink (Yii::getPathOfAlias('webroot').ClientesController::FOLDER.$this->tmpFileName);
								}
							}

							if (!$error)
							{
								Yii::app()->user->setFlash('Clientes.Success', 'El archivo de descuentos, se ha importado correctamente.');
								$this->redirect(Yii::app()->createUrl('admin/clientes/index'));
							}
						}
	               	}
	            }
	        }
	        else
			{
				Yii::app()->user->setFlash('Clientes.Descuentos.Error', 'Es necesario que seleccione un archivo de tipo .csv');
			}

			$modelAnios = Anio::model()->findAll();
			$listAnios = CHtml::listData($modelAnios, 'id', 'anio');
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
			Yii::app()->user->setFlash('Clientes.Error', $message);
		}

		$this->render('importarDescuentos', array(
            'model'=>$model,
            'anios' => $listAnios
        ));
	}

	/**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Clientes the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Clientes::model()->findByPk($id);

        if($model===null)
        {
            throw new CHttpException(404,'The requested page does not exist.');
        }

        return $model;
    }

	/**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if(isset($_POST['Clientes']))
        {
            $model->attributes = $_POST['Clientes'];

            if ($model->validate())
            {
            	if (isset($_POST['Clientes']['empresa']))
            	{
		            for ($i=0; $i<count($_POST['Clientes']['empresa']); $i++)
		            {
		            	if($model->save())
			            {
			            	// Eliminar cualquier relacion anteriormente creada
			            	ClientesHasEmpresas::model()->deleteAll(array(
			            		'condition' => 'clientes_codigo = :codigo',
								'params' => array(
									':codigo' => $model->codigo
								)
							));

			                $clienteEmpresa = new ClientesHasEmpresas();
			                $clienteEmpresa->clientes_codigo = $model->codigo;
			                $clienteEmpresa->empresas_id = $_POST['Clientes']['empresa'][$i];
			                $clienteEmpresa->save();
			            }
		            }

		            $this->redirect(Yii::app()->createUrl('admin/clientes'));
				}
				else
				{
					$model->addError('empresa', 'Debe seleccionar al menos una empresa');
				}
			}
        }

		// empresas seleccionadas
		$modelEmpresasSeleccionadas = ClientesHasEmpresas::model()->with('empresas')->together()->findAll(array(
			'select' => 'empresas.*',
			'condition' => 't.clientes_codigo = :codigo',
			'params' => array(
				':codigo' => $model->codigo
			)
		));
		$listEmpresasSeleccionadas = CHtml::listData($modelEmpresasSeleccionadas, 'empresas.id', 'empresas.nombre');

		if (count($listEmpresasSeleccionadas)>0)
		{
			$inCondition = implode(',', array_keys($listEmpresasSeleccionadas));
		}
		else
		{
			$inCondition = 0;
		}

		// empresas disponibles
		$modelEmpresas = Empresas::model()->findAll(array(
			'condition' => 't.id NOT IN ('.$inCondition.')'
		));
		$listEmpresas = CHtml::listData($modelEmpresas, 'id', 'nombre');

        $this->render('update',array(
            'model' => $model,
            'empresas' => $listEmpresas,
            'empresasSeleccionadas' => $listEmpresasSeleccionadas
        ));
    }

    /**
     * [actionCreate description]
     * @return [type] [description]
     */
    public function actionCreate()
	{
		$model = new Clientes();

        if(isset($_POST['Clientes']))
        {
            $model->attributes = $_POST['Clientes'];

            if ($model->validate())
            {
            	if (!empty($_POST['Clientes']['empresa']))
            	{
		            for ($i=0; $i<count($_POST['Clientes']['empresa']); $i++)
		            {
		            	if($model->save())
			            {
			                $clienteEmpresa = new ClientesHasEmpresas();
			                $clienteEmpresa->clientes_codigo = $model->codigo;
			                $clienteEmpresa->empresas_id = $_POST['Clientes']['empresa'][$i];
			                $clienteEmpresa->save();
			            }
		            }

		            $this->redirect(Yii::app()->createUrl('admin/clientes'));
				}
				else
				{
					$model->addError('empresa', 'Debe seleccionar al menos una empresa');
				}
			}
        }

        // empresas
		$modelEmpresas = Empresas::model()->findAll();
		$listEmpresas = CHtml::listData($modelEmpresas, 'id', 'nombre');

        $this->render('create',array(
            'model' => $model,
            'empresas' => $listEmpresas
        ));
	}
}