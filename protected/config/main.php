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
	'name'=>'Notas de Crédito - Pierre Fabre',

	// preloading 'log' component
	'preload'=>array('log'),

	'modules'=>array(
		'admin',
		'supervisor'
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.models.forms.*'
	),

	// theme
	'theme'=>'v2',

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
		'user'=>array(
			'allowAutoLogin' => true,
			'loginUrl' => array('site/index'),
			'class' => 'ValidateUser'
		),
		'request' => array(
			'class' => 'CHttpRequest',
			'enableCookieValidation' => true,
			'enableCsrfValidation' => true
        ),
		'errorHandler' => array(
            'errorAction'=>'site/error'
        ),
        'urlManager'=>array(
			'urlFormat'=>'get',
			'showScriptName' => true,
			'caseSensitive' => false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'clientScript'=>array(
            'coreScriptPosition' => CClientScript::POS_END
        ),
        'widgetFactory' => array(
            'widgets' => array(
                'CJuiAutoComplete' => array(
                    'themeUrl' => 'themes/v2/css',
                    'theme' => 'custom-theme',
                ),
                'CJuiDatePicker' => array(
                    'themeUrl' => 'themes/v2/css',
                    'theme' => 'custom-theme',
                ),
            ),
        ),
	),

	'params'=>array(
		'adminEmail'=>'webmaster@example.com',
		'iva' => 16,
		'diasNoDisponibles' => array(1,2,26,27,28,29,30,31),
		// Variables usuario host y password del servidor del correo
        'mailSenderEmail' => 'hola@test.com',
        'mailSenderName'=>'Servicio Avisos',
        'mailHost'=>'smtp.test.com',
        'mailSMTPAuth'=>true,
        'mailUsername'=>'test',
        'mailPassword'=>'test',
        // Descuentos a clientes por productos (codigo)
        'descuentos' => array(
        	'TREV' => array(
        		641535,641536
        	),
        	'PFD' => array(
        		643202,641516,641517
        	)
        )
	),
);
