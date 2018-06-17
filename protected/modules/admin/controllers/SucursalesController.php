<?php
/**
 * SucursalesController class file
 * 
 * @autor		Jackfiallos
 * @link		http://jackfiallos.com
 * @copyright	Copyright (c) 2011 Qbit Mexhico
 * @description
 * 
 *
 **/
class SucursalesController extends Controller
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
				'actions'=>array('index', 'importar', 'update'),
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
		$model = new Sucursales('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Sucursales']))
        {
            $model->attributes = $_GET['Sucursales'];
        }

        $this->render('index',array(
            'model' => $model
        ));
	}

	/**
	 * [actionImportar description]
	 * @return [type] [description]
	 */
	public function actionImportar()
	{
		$model = new ImportarArchivoForm();

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
	                if ($model->archivo->saveAs(Yii::getPathOfAlias('webroot').SucursalesController::FOLDER.$this->tmpFileName))
					{
						$fila = 1;
						if (($gestor = fopen(Yii::getPathOfAlias('webroot').SucursalesController::FOLDER.$this->tmpFileName, "r")) !== FALSE)
						{
						    while (($datos = fgetcsv($gestor, 1024, ";")) !== FALSE)
						    {
								$sucursal = new Sucursales();
								$sucursal->codigo = $datos[0];
								$sucursal->nombre = $datos[1];
								$sucursal->save(false);
								unset($sucursal);
							}
						    fclose($gestor);
						}

						// if($model->save())
						// {
						// 	$this->redirect(Yii::app()->createUrl('admin/facturas/index'));
						// }
	               	}
	            }
	        }
		}
		catch(Exception $ex)
		{
			//
		}

		$this->render('importar', array(
            'model'=>$model
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
		$model = Sucursales::model()->findByPk($id);

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

		if(isset($_POST['Sucursales']))
		{
			$model->attributes = $_POST['Sucursales'];

			if($model->save())
			{
				$this->redirect(Yii::app()->createUrl('admin/sucursales'));
			}
		}

		$this->render('update',array(
			'model' => $model,
			'empresas' => CHtml::listData(Empresas::model()->findAll(), 'id', 'nombre')
		));
	}
}