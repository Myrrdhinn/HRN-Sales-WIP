<?php
namespace HRNSales;
if(!isset($_SESSION)) {
	session_start();
}
  $params = explode("/", $_SERVER['REQUEST_URI']);
  
     
if (!isset($params[2]) || $params[2] == '') {
 	include_once('views\mainpage.php');
} else {
	$extensions = array('.php'=>'','.html'=>'');
	$class = strtr($params[2], $extensions);
	if ($class == 'index'){
	   $class = 'mainpage';	
	}
	
	$base_dir = __DIR__ . '/views/';
	$file = $base_dir . str_replace('\\', '/', $class) . '.php';
	
 if (file_exists($file)) {
    require $file;
 } else {
	 echo 'error';
 }
	
}


?>