<?php 
use HRNSales\NewData as newData;
use HRNSales\main as main;

   if (!isset($_SESSION['user'])) {
	  	 require_once('login.php');
   } else {
	   
	   $content ='
	   <!--Jquery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link href="css/admin_general.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin_edit_general.css" />
<link rel="stylesheet" href="css/admin_index.css" />
<link rel="stylesheet" href="css/analytics.css" />
<script src="js/analytics.js"></script> 
</head>
<body>

 <!--Main Wrapper-->
	<div class="wrapper">
	  <h1 class="WrapperMainH1">HR Tech Europe - Sales | Main Page - Goals</h1>
	   
	<!--Form container-->
	 <div id="container">';
if (isset($_SESSION['admin'])) {
			include_once('controllers/main.php');
			$main = new main\main;
			
		 if (isset($_SESSION['Result']) && $_SESSION['Result'] != '') {
				$content .='<div id="ReturnValue" style="display:none">'.$_SESSION['Result'].'</div>';
				$_SESSION['Result'] = '';
			} else {
				$content .='<div id="ReturnValue" style="display:none"></div>';
			}		
			
	
    $content .='	 	 	 
	 
      <div id="MenuIconContainer">';
	  

			 $content .= '<a title="Back to Main Page" href="index"><img alt="Back to Main Page" class="MenuIcon" src="img/icons/main.png" onmouseover="this.src=';
			 $content .="'img/icons/main_hover.png';";
			 $content .='" onmouseout="this.src=';
			 $content .="'img/icons/main.png';";
			 $content .='" ></a>';
		
	
  $content .='</div>';
  
    $content .='<p class="WelcomeTexT">Change the desirable goals:</p>';
  
  }
     $content .='
	 <!-- ;) -->
	 <div id="MainAnalContent">';
  
     $content .= $main->get_analytics_data('',''); 
	 
   $content .='</div>';
  	   
	 $content .='<br /><br /><br />
  <a href="logout"><button name="logout">Logout</button></a>

  
  
  	
	   <!-- End of Form Container-->
	 </div>
	 
	<!--End of Main Wrapper-->
	</div>
	
	';  
   }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>HR Tech Europe - Sales</title>
<?php
echo $content;

?> 
</body>
</html>
