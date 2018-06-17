<?php
/**
 * prod configuration file
 *
 * @author		Qbit Mexhico
 * @revised		Jackfiallos
 * @date		2014-06-09
 * @link:    	[link]
 * @version: 	[version]
 * @copyright	Copyright (c) 2011 Qbit Mexhico
 *
 */

// Load main config file
$main = include_once('main.php');

// Production configurations
$production = array(
	// application components
	'components'=>array(
		'clientScript'=>array(
            'packages'=>array(
                'jquery'=>array(
                    'baseUrl'=>'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/',
                    'js'=>array('jquery.min.js'),
                )
            ),
            'coreScriptPosition' => CClientScript::POS_END
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
		'log' => array(
	    	'class' => 'CLogRouter',
	    	'routes' => array(
	         	array(
					'class' => 'CEmailLogRoute',
                    'levels'=>'error, warning',
                    'emails' => 'erling.fiallos@qbit.com.mx',
                    'subject' => 'Qompit Error - Application Log',
                    'sentFrom' => 'erling.fiallos@qbit.com.mx',
                    'categories'=>'system.*',
	            ),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);

//merge both configurations and return them
return CMap::mergeArray($main, $production);
