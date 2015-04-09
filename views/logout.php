<?php
namespace HRNSales\siteauth;
require_once('controllers/siteauth.php');



 if (isset($_SESSION['user'])) {
	   
		   $auth = new siteauth;
		   $output = $auth->logout();

   }
   
?> 