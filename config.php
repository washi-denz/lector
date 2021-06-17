<?php

	# Configuraciones básicas

	date_default_timezone_set('America/Lima');

	define('APP_NAME','Lector');
	define('APP_CODE','38247854562748hjshfr3742rfhwkljdfhsdr67t27');

	define('THEME_NAME','web');
	define('SEPARATOR','/');

	define('URI', __DIR__);
	define('URI_MOD', URI . SEPARATOR . 'module' . SEPARATOR);
	define('URI_THEME', URI . SEPARATOR . 'template' . SEPARATOR . THEME_NAME);
	define('URL_PROTOCOL', 'http://'); // Cambiar esta linea por: define('URL_PROTOCOL', 'https://'); cuando esté en la nube

	define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
	define('URL_SUB_FOLDER', str_replace(URI_MOD, '', dirname($_SERVER['SCRIPT_NAME'])));

	define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER); // Cambiar esta línea por: define('URL',URL_PROTOCOL.URL_DOMAIN.SEPARATOR); cuando esté en la nube
	define('URL_NORMAL', URL_PROTOCOL . URL_DOMAIN);
	define('URL_THEME', URL . SEPARATOR . 'template' . SEPARATOR . THEME_NAME);

	define('TITLE', 'Inicio | '.APP_NAME);
	
	# Configuración de Base de Datos local
	
	define('DB_TYPE', 'mysql');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'bd_lector');
	define('DB_USER', 'root');
	define('DB_PASS', '');
	define('DB_CHARSET', 'utf8');
	define('DB_TIME_ZONE', '-05:00');
    
	# Configuración de Base de Datos remota

	/*	
	define('DB_TYPE', 'mysql');
	define('DB_HOST', 'localhost');
	define('DB_NAME', '...');
	define('DB_USER', '...');
	define('DB_PASS', '...');
	define('DB_CHARSET', 'utf8');
	define('DB_TIME_ZONE', '-05:00');
	*/

	# máximo registros para mostrar
	define('REG_MAX',4);

	# Configurar token de seguridad

	define('CSM_TOKEN','ca5cde5bacdc67701f72d40289d68e31');

?>