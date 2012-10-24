<?php
return array(
	'version' => 
	array(
		'app' => 
		array(
			'default' => 
			array(
				0 => '001_create_news',
				1 => '002_create_messages',
			),
		),
		'module' => 
		array(
		),
		'package' => 
		array(
			'dbacl' => 
			array(
				0 => '001_init',
			),
			'sentry' => 
			array(
				0 => '001_install_sentry_auth',
			),
		),
	),
	'folder' => 'migrations/',
	'table' => 'migration',
);
