<?php
/**
 * main configuration file
 *
 * @author		Qbit Mexhico
 * @revised		Jackfiallos
 * @date		2014-06-09
 * @link:    	[link]
 * @version: 	[version]
 * @copyright	Copyright (c) 2011 Qbit Mexhico
 *
 */
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Notas de Crédito',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	// Este es el lenguaje en el que mostrara las cosas
	'language'=>'es_mx',
    'sourceLanguage'=>'es',

	// application components
	'components'=>array(
		'cache'=>array(
			'class' => 'CApcCache',
			'class' => 'CMemCache',
			'servers'=>array(
				array(
					'host'=>'localhost',
					'port'=>11211,
				),
			),
		),
		'db' =>  array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=localhost;port=3306;dbname=workflownotas',
			'initSQLs'=>array('SET NAMES utf8'),
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
        	'tablePrefix' => '',
        	'emulatePrepare' => true,
        	'enableProfiling' => true,
        	'schemaCacheID' => 'cache',
        	'schemaCachingDuration' => 3600
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'logFile'=>'cron.log',
					'levels'=>'trace, error, warning',
                    'categories'=>'system',
				),
				array(
					'class'=>'CFileLogRoute',
					'logFile'=>'cron_trace.log',
					'levels'=>'trace',
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// Variables usuario host  y password del serivdor del correo
        'mailSenderEmail' => 'hola@test.com',
		'mailSenderName' => 'Workflow Notas',
		'mailHost' => 'smtp.test.com',
		'mailSMTPAuth' => true,
		'mailUsername' => 'test',
		'mailPassword' => 'test',
	),
);
