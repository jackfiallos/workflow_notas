<?php
/**
 * dev configuration file
 *
 * @author		Qbit Mexhico
 * @revised		Jackfiallos
 * @date		2014-06-09
 * @link:    	[link]
 * @version: 	[version]
 * @copyright	Copyright (c) 2011 Qbit Mexhico
 *
 */

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '1');
ini_set('display_errors', 'On');

// Load main config file
$main = include_once('main.php');

// Development configurations
$development = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',

	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'workflownotas'
		),
	),

	// application components
	'components'=>array(
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
		'log' => array(
		 	'class' => 'CLogRouter',
		 	'routes' => array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				/*array(
					'class'=>'CWebLogRoute',
					'levels'=>'error, warning, trace',
					'categories'=>'system.*'
				),*/
				array(
					'class'=>'CWebLogRoute',
					'levels'=>'error, warning',
					'categories'=>'error.*'
				)
			)
		)
	),
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);

//merge both configurations and return them
return CMap::mergeArray($main, $development);
