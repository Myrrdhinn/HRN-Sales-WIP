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

<!-- Token input -->
<script type="text/javascript" src="vendor/autocomplete/src/jquery.tokeninput.js"></script>

<link rel="stylesheet" href="vendor/autocomplete/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="vendor/autocomplete/styles/token-input-facebook.css" type="text/css" />


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
   

   
   		  $content .='<select id="Months" class="SelectClass">
		    <option value="-1" hidden="hidden">Month</option>
			<option value="All">All</option>
			<option value="01">January</option>
			<option value="02">February</option>
			<option value="03">March</option>
			<option value="04">April</option>
			<option value="05">May</option>
			<option value="06">June</option>
			<option value="07">July</option>
			<option value="08">August</option>
			<option value="09">September</option>
			<option value="10">October</option>
			<option value="11">November</option>
			<option value="12">December</option>
		
	   </select>';
	   
	   		  if (isset($_SESSION['admin'])) {
				  $admin = $_SESSION['admin'];	
				}
				else {
					$admin = $_SESSION['user_id'];
					$content.= $main->motivation();
				}
	   
   
   	    $content .='<div id="Advanced">
		<p class="SelectLabel">Callback Date</p>';
		if (isset($_SESSION['admin']) && $_SESSION['admin'] < 2) {
					
		$content .='
		<p class="SelectLabel">Team Member</p>
		<p class="SelectLabel">Team</p>';
		}

		$content .='
		<p class="SelectLabel">Pitch Type</p>
		<p class="SelectLabel">Result Type</p>
		<br />
		  <select id="Callbacks" class="SelectClass">
		';
		
		$content .= $main->get_callbacks($admin);
        $content .='
		  </select>';
		  
		  if (isset($_SESSION['admin'])) {
		$content .='
		<select id="TeamMembers" class="SelectClass">';
		  
		
		$content .= $main->get_team_members('');
        $content .='
		  </select>';
		  

		  
				if ($_SESSION['admin'] < 2) {
					  $content .='
			  <select id="Teams" class="SelectClass">';
				
			  
			  $content .= $main->get_teams();
			  $content .='
				</select>';
				
				}
		}
		  
		   $content .='<select class="SelectClass" id="PitchType" name="PitchType">
		   <option value="" hidden="hidden" selected="selected">Select a Pitch Type</option>
		   <option value="All">All</option>';
	        $content .= $main->get_pitch_type('moo'); 
            $content .=' 
           </select>';
		   
		   $content .='<select class="SelectClass" id="PitchResult" name="PitchResult">
		   <option value="" hidden="hidden" selected="selected">Select a Result Type</option>
		   <option value="All">All</option>';
	        $content .= $main->get_pitch_result('moo');
            $content .=' 
           </select>';
 $content .=' <button id="ClearFilters">Clear Filters</button>';
		  
		  $content .='<label><br />Company search<input type="text" id="SearchField" /></search>'; 
		 $content .='</div>';
         
		$content .='<div id="loadingtext" style="display:none">Loading <img style="width:40px" alt="loading" src="img/icons/loading.gif" /></div>';
		 
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

     $_SESSION['PitchSelectedCallback'] = '';
	 $_SESSION['PitchSelectedTmembers'] = '';
	 $_SESSION['PitchSelectedTeams'] = '';
	 $_SESSION['PitchFilterCompany'] = '';
     $currentMonth = date('m'); 
	 $_SESSION['PitchSelectedMonth'] = $currentMonth;
	 $_SESSION['PitchFilterOrder'] = 'Date';
	 $_SESSION['PitchSelectedOrderType'] = 'DESC';
	 $_SESSION['PitchSelectedPitchType'] = '';
	 $_SESSION['PitchSelectedResultType'] = '';
     


					  
			 //Get the pitch data
	    $content .='<div id="Pitch_Container">';
		$content .= $pitches->list_pithces($_SESSION['PitchSelectedCallback'],$_SESSION['PitchSelectedTmembers'],$_SESSION['PitchSelectedTeams'], $admin, $_SESSION['PitchFilterCompany'], $currentMonth, $_SESSION['PitchFilterOrder'], $_SESSION['PitchSelectedOrderType'], $_SESSION['PitchSelectedPitchType'], $_SESSION['PitchSelectedResultType']);
        $content .='</div>';
	    
	   
	 } //if isset agenda_admin 
	 else {
		$content.="<h1 style='text-align:center'>Nothing to see here!</h1>"; 
	 }
	 
	 
	$content .=' </div>
	 
	<!--End of Main Wrapper-->
	</div>
	
	
  	   
	<br /><br /><br />'; 
  	   
	   
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
