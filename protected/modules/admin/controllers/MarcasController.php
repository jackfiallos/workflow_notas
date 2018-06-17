<?php
/**
 * FacturasController class file
 * 
 * @autor		Jackfiallos
 * @link		http://jackfiallos.com
 * @copyright	Copyright (c) 2011 Qbit Mexhico
 * @description
 * 
 *
 **/
class MarcasController extends Controller
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
				'actions'=>array('index', 'importar','update', 'create'),
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
		$model = new Marcas('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Marcas']))
        {
            $model->attributes = $_GET['Marcas'];
        }

        $this->render('index',array(
            'model' => $model,
       
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
	            $model->scenario = 'marcas';

	            // Validaciones
	            if($model->validate())
	            {
	            	$this->tmpFileName = str_replace(" ","",date('dmYHis-z-').microtime()).".".$model->archivo->getExtensionName();
	                if ($model->archivo->saveAs(Yii::getPathOfAlias('webroot').MarcasController::FOLDER.$this->tmpFileName))
					{
						if (($gestor = fopen(Yii::getPathOfAlias('webroot').MarcasController::FOLDER.$this->tmpFileName, "r")) !== FALSE)
						{
						    while (($datos = fgetcsv($gestor, 1024, ";")) !== FALSE)
						    {
						    	// El archivo debe de tener 7 campos
						    	if (count($datos) == 7)
						    	{
						    		// Buscando el cliente por codigo (segun unico) para evitar el registro duplicado
						    		$buscar = Marcas::model()->find(array(
						    			'condition' => 't.codigo = :codigo',
						    			'params' => array(
						    				':codigo' => $datos[1]
						    			)
						    		));

						    		if ($buscar === null)
						    		{
							        	$marca = new Marcas();
										$marca->es_gama = $datos[0];
										$marca->codigo = $datos[1];
										$marca->marca = $datos[2];
										$marca->cuenta_cooperacion = $datos[3];
										$marca->cuenta_descuento = $datos[4];
										$marca->color = $datos[5];
										$marca->identificador = $datos[6];
										$marca->empresas_id = $model->empresa_id;

										if ($marca->save(false))
										{
											$error = false;
										}
										unset($marca);
									}
									else
									{
										Yii::app()->user->setFlash('Marcas.Error', 'Se encontraron registros con c&oacute;digos duplicados.');
										$error = true;
									}
									unset($buscar);
								}
								else
								{
									Yii::app()->user->setFlash('Marcas.Error', 'El archivo tiene el formato incorrecto.');
									$error = true;
								}
							}
						    
						    // Cerrar el archivo en uso
						    if (fclose($gestor))
						    {
							    // Eliminar el archivo que temporalmente fue cargado
								if (file_exists(Yii::getPathOfAlias('webroot').MarcasController::FOLDER.$this->tmpFileName))
								{
									unlink (Yii::getPathOfAlias('webroot').MarcasController::FOLDER.$this->tmpFileName);
								}
							}

							if (!$error)
							{
								Yii::app()->user->setFlash('Marcas.Success', 'Registros importados correctamente.');
								$this->redirect(Yii::app()->createUrl('admin/marcas/index'));
							}
						}
	               	}
	            }
	        }
	        else
			{
				Yii::app()->user->setFlash('Marcas.Error', 'Es necesario que seleccione un archivo de tipo .csv');
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

		$this->render('importar',array(
            'model'=>$model,
            'empresas' => $listEmpresas
        ));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Sucursales the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Marcas::model()->findByPk($id);

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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Marcas']))
		{
			$model->attributes=$_POST['Marcas'];

			if($model->save())
			{
				$this->redirect(Yii::app()->createUrl('admin/marcas'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'empresas' => CHtml::listData(Empresas::model()->findAll(), 'id', 'nombre')
		));
	}	

	/**
	 * [actionCreate description]
	 * @return [type] [description]
	 */
	public function actionCreate()
	{
		$model = new Marcas();

		if(isset($_POST['Marcas']))
		{
			$model->attributes=$_POST['Marcas'];

			if($model->save())
			{
				$this->redirect(Yii::app()->createUrl('admin/marcas'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'empresas' => CHtml::listData(Empresas::model()->findAll(), 'id', 'nombre')
		));
	}	
}