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
class FacturasController extends Controller
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
		$model = new Facturas('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Facturas']))
        {
            $model->attributes = $_GET['Facturas'];
        }

        // Clientes
		$modelClientes = Clientes::model()->findAll();
		$listClientes = CHtml::listData($modelClientes, 'codigo', 'nombre');

        $this->render('index',array(
            'model' => $model,
            'clientes' => $listClientes
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

	            // Validaciones
	            if($model->validate())
	            {
	            	$this->tmpFileName = str_replace(" ","",date('dmYHis-z-').microtime()).".".$model->archivo->getExtensionName();
	                if ($model->archivo->saveAs(Yii::getPathOfAlias('webroot').FacturasController::FOLDER.$this->tmpFileName))
					{
						if (($gestor = fopen(Yii::getPathOfAlias('webroot').FacturasController::FOLDER.$this->tmpFileName, "r")) !== FALSE)
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
										$error = false;
									}
									unset($factura);
								}
								else
								{
									Yii::app()->user->setFlash('Facturas.Error', 'El archivo tiene el formato incorrecto.');
									$error = true;
								}
							}
						    
						    // Cerrar el archivo
						    if (fclose($gestor))
						    {
						    	// Verificar si existe antes de borrarlo
						    	if (file_exists(Yii::getPathOfAlias('webroot').FacturasController::FOLDER.$this->tmpFileName))
						    	{
							    	unlink (Yii::getPathOfAlias('webroot').FacturasController::FOLDER.$this->tmpFileName);
							    }
						    }
						}

						if (!$error)
						{
							Yii::app()->user->setFlash('Facturas.Success', 'El archivo fue importado exitosamente.');
							$this->redirect(Yii::app()->createUrl('admin/facturas/index'));
						}
	               	}
	            }
	        }
	        else
			{
				Yii::app()->user->setFlash('Facturas.Error', 'Es necesario que seleccione un archivo de tipo .csv');
				$error = true;
			}
		}
		catch(Exception $ex)
		{
			$message = $ex->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
			Yii::app()->user->setFlash('Facturas.Error', $message);
		}

		$this->render('importar',array(
            'model'=>$model
        ));
	}
}