<?php
// Load config
require_once 'config/config.php';
// Load helpers
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';
// loading libraries
//require_once 'libraries/Core.class.php';
//require_once 'libraries/Controller.class.php';
//require_once 'libraries/Database.class.php';

// Auto-loader for libraries
spl_autoload_register(function($className){
	require_once 'libraries/' . $className . '.class.php';
});
?>