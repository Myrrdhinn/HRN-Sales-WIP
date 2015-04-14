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
<script src="js/mainpage.js"></script> 
</head>
<body>

 <!--Main Wrapper-->
	<div class="wrapper">
	  <h1 class="WrapperMainH1">HR Tech Europe - Sales | Main Page</h1>
	  
	  <p class="WelcomeTexT">Welcome to the Sales Page Interface of <br> HR Tech Europe</p>
	   <p class="WelcomeTexT"> Please select the page where you want go or change the number of calls you made this day:</p>
	   
	<!--Form container-->
	 <div id="container">';
if (isset($_SESSION['user'])) {
			include_once('controllers/main.php');
			$main = new main\main;
			
	        $calls = $main->get_call_nums(); 
			
		 if (isset($_SESSION['Result']) && $_SESSION['Result'] != '') {
				$content .='<div id="ReturnValue" style="display:none">'.$_SESSION['Result'].'</div>';
				$_SESSION['Result'] = '';
			} else {
				$content .='<div id="ReturnValue" style="display:none"></div>';
			}		
			
	/*
    $content .='	 	 
	 <form>
	   <div id="CallInputs">
	     <input id="CallInputField" class="AdminInputField" type="text" placeholder="Call number" value="'.$calls.'" />
	     <button id="CallSubmit" class="AdminSubmitButton">Change</button>
	   </div>	 
	 </form>';
	 */
	 
       $content .='<div id="MenuIconContainer">';
	  

		 $content .= '<a href="new" title="New Pitch"><img class="MenuIcon" src="img/icons/new.png" onmouseover="this.src=';
		 $content .="'img/icons/new_hover.png';";
		 $content .='" onmouseout="this.src=';
		 $content .="'img/icons/new.png';";
		 $content .='" ></a>';
		 
		 $content .= '<a href="pitches" title="Pitch List"><img class="MenuIcon" src="img/icons/list.png" onmouseover="this.src=';
		 $content .="'img/icons/list_hover.png';";
		 $content .='" onmouseout="this.src=';
		 $content .="'img/icons/list.png';";
		 $content .='" ></a>';
	 
	
	 

	
	if (isset($_SESSION['admin'])) {
		/*
		 $content .= '<a href="goals" title="Goals"><img class="MenuIcon" src="img/icons/goals.png" onmouseover="this.src=';
		 $content .="'img/icons/goals_hover.png';";
		 $content .='" onmouseout="this.src=';
		 $content .="'img/icons/goals.png';";
		 $content .='" ></a>';
		 */
		 
		 $content .= '<a href="analytics" title="Analytics"><img class="MenuIcon" src="img/icons/analytics.png" onmouseover="this.src=';
		 $content .="'img/icons/analytics_hover.png';";
		 $content .='" onmouseout="this.src=';
		 $content .="'img/icons/analytics.png';";
		 $content .='" ></a>';
		 
		 $content .= '<a href="calls" title="Call Rates"><img class="MenuIcon" src="img/icons/calls.png" onmouseover="this.src=';
		 $content .="'img/icons/calls_hover.png';";
		 $content .='" onmouseout="this.src=';
		 $content .="'img/icons/calls.png';";
		 $content .='" ></a>';
	 
	}
	 
	
	
  $content .='</div>';
  
  }//if session user is set
  	   
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
