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
class RazonesController extends Controller
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
	 * @author:  Qbit Mexhico
	 * @revised: Jackfiallos
	 * @date:    2014-06-10
	 * @link:    [link]
	 * @version: [version]
	 * @return   [type]      [description]
	 */
	public function actionIndex()
	{		 
		$model = new Razones('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Razones']))
        {
            $model->attributes = $_GET['Razones'];
        }

        $this->render('index',array(
            'model' => $model
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
		$model = Razones::model()->findByPk($id);

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

		if(isset($_POST['Razones']))
		{
			$model->attributes=$_POST['Razones'];

			if($model->save())
			{
				$this->redirect(Yii::app()->createUrl('admin/razones'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'caracteristicas' => CHtml::listData(Caracteristicastipo::model()->findAll(), 'id', 'nombre')
		));
	}

	/**
	 * [actionCreate description]
	 * Crea una nueva razon  
	 * @author xineohp
	 * @version [version]
	 * @date    2014-06-19
	 * @return  [type]     [description]
	 */
	public function actionCreate()
	{
		// Crear modelo 
		$model = new Razones();
		$empresasSeleccionadas = '0';

        if(isset($_POST['Razones']))
        {
            $model->attributes = $_POST['Razones'];
			

        	//  Validar que el modelo cumpla con las reglas de validacion
            if($model->validate())
            {
				try
				{
					// Guardar razon
					$model->save();

	        		
					Yii::app()->user->setFlash('Razones.Success','Los datos se guardaron correctamente.');
				}
				catch(Exception $ex)
				{
					$message = $ex->getMessage();
					Yii::app()->user->setFlash('Razones.Error','Servicio no disponible.');
					Yii::log($message, CLogger::LEVEL_ERROR, 'application');
				}
                
				$this->redirect(Yii::app()->createUrl('admin/razones/index'));
            }
        }

        $this->render('create', array(
            'model'=>$model,
 			'caracteristicas' => CHtml::listData(Caracteristicastipo::model()->findAll(), 'id', 'nombre')       
        ));
	}


}