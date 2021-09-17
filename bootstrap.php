<?php
/*
** Load config and helpers
*/
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';

/*
** Auto-loader for libraries
*/
spl_autoload_register(function($className){
	require_once 'libraries/' . $className . '.class.php';
});
?>