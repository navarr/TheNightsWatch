<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'The Night\'s Watch',
	'preload'=>array('log'),
	'import'=>array(
		'application.forms.*',
		'application.models.*',
		'application.components.*',
		'application.vendors.*',
		'application.filters.*',
	    'application.extensions.yii-mail.*',
	),
	'modules'=>array(),
	'components'=>array(
	    'mail' => array(
	        'class' => 'YiiMail',
        ),
		'request'=>array(
		    'class' => 'HttpRequest',
		    'noCsrfValidationRoutes' => array(
		        'map/update',
	        ),
			'enableCsrfValidation' => true,
			'enableCookieValidation' => true,
		),
		'user'=>array(
			'allowAutoLogin'=>true,
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				// Specific Routes
				'' => 'site/index',
				'chat' => 'chat/index',
				'login' => 'site/login',
				'register' => 'site/register',
				'logout' => 'site/logout',
			    'profile' => 'site/profile',
			    'KOS' => 'site/KOS',
			    'mods' => 'site/mods',
			    'rules' => 'site/rules',
			    'minecraft.jar' => 'site/modDownload',
			    
			    // Map Downloader
                'map/MineZ/<path:.+>' => 'map/download',
			    
				// Other Routes
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<id:\d+>/<action:\w+>'=>'<controller>/<action>',
				
				// Cape Route
                array('user/cape','pattern' => 'cape/<unique:\w+>.png', 'verb' => 'GET'),
			    array('user/capeHead','pattern' => 'cape/<unique:\w+>.png', 'verb' => 'HEAD'),

				// User Specific
				'user' => 'user/index',
				'user/<unique:\w+>'=>'user/view',
				'user/<unique:\w+>/<action:\w+>'=>'user/<action>',

				// Controller/Action
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<unique\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
	'params'=>array(
		'adminEmail'=>'me+thenightswatch@navarr.me',
	),
);
