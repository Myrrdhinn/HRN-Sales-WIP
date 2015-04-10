<?php 
use HRNSales\main as main;
use HRNSales\pitches as pitches;

   if (!isset($_SESSION['user'])) {
       require_once('login.php');
	   
   } else {
	   
	   //$new = new locations();
	//<link rel="stylesheet" href="css/new_speaker.css" />   
	   $content ='
	   <!--Jquery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<link href="css/admin_general.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin_index.css" />
<link href="css/general.css" rel="stylesheet">
<link href="css/pitch.css" rel="stylesheet">

<!--Include Font Awesome -->
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<script src="js/pitch.js"></script> 

</head>
<body>
 <!--Main Wrapper-->
	<div class="wrapper">
	  <h1 class="WrapperMainH1">HR Tech Europe - Sales Page |<br /> Pitch List</h1>
	  
	  	        <div id="MenuIconContainer">';
	
  
	if (isset($_SESSION['user'])) {
		

		 $content .= '<a title="Back to Main Page" href="index"><img alt="Back to Main Page" class="MenuIcon" src="img/icons/main.png" onmouseover="this.src=';
		 $content .="'img/icons/main_hover.png';";
		 $content .='" onmouseout="this.src=';
		 $content .="'img/icons/main.png';";
		 $content .='" ></a>';
	 
	}
	 
  $content .='</div>
	  
	 
	<!--Form container-->
	 <div id="container">';
	 
	 	if (isset($_SESSION['user'])) {
			include_once('controllers/main.php');
			include_once('controllers/pitches.php');
			$main = new main\main;
			$pitches = new pitches\pitches;
			
			if(isset($_SESSION['edit_pitch'])) {
			   unset($_SESSION['edit_pitch']);	
			}
			
			if (isset($_SESSION['Result']) && $_SESSION['Result'] != '') {
				$content .='<div id="ReturnValue" style="display:none">'.$_SESSION['Result'].'</div>';
				$_SESSION['Result'] = '';
			} else {
				$content .='<div id="ReturnValue" style="display:none"></div>';
			}
   
				if (isset($_SESSION['admin'])) {
				  $admin = -200;	
				}
				else {
					$admin = $_SESSION['user_id'];
				}
   
   	    $content .='<div id="Advanced">
		  <select id="Callbacks" class="SelectClass">
		';
		
		$content .= $main->get_callbacks($admin);
        $content .='
		  </select>
		</div>';
         
		 $category = ''; 
		 $value = ''; 
		   
		   /*  
		   JUST FOR TEST!!
		$category[0] = 	'u.id';	
		$value[0][0] = 2;
		$value[0][1] = 1;
		
		$category[1] = 'co.id';
		$value[1][0] = 14;
		
		*/

			 
			 //Get the pitch data
	    $content .='<div id="Pitch_Container">';
		$content .= $pitches->list_pithces($category,$value, $admin);
        $content .='</div>';
	    
	   
	 } //if isset agenda_admin 
	 else {
		$content.="<h1 style='text-align:center'>Nothing to see here!</h1>"; 
	 }
	 
	 
	$content .=' </div>
	 
	<!--End of Main Wrapper-->
	</div>
	
	
  	   
	<br /><br /><br />
  <a href="logout"><button name="logout">Logout</button></a>'; 
  	   
	   
   }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>HR Tech Europe - New Speaker</title>
<?php
echo $content;

?> 
</body>
</html>
