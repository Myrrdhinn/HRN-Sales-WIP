<?php
namespace HRNSales;
if(!isset($_SESSION)) {
	$lifetime=3600;
    session_set_cookie_params($lifetime);
	session_start();
}

  $params = explode("/", $_SERVER['REQUEST_URI']);

	  $class = 'mainpage';	

if (!isset($params[1]) || $params[1] == '') {
	
} else {
	$extensions = array('.php'=>'','.html'=>'');
	$class = strtr($params[1], $extensions);
	if ($class == 'index'){
	   $class = 'mainpage';	
	}
}


	$base_dir = __DIR__ . '/views/';
	$file = $base_dir . str_replace('\\', '/', $class) . '.php';
	
 if (file_exists($file)) {
    require $file;
 } else {
	 echo 'error';
 }
	


?>