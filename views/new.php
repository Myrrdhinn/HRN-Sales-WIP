<?php 
use HRNSales\main as main;

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

<!--Include Font Awesome -->
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<script src="js/new.js"></script> 

</head>
<body>
 <!--Main Wrapper-->
	<div class="wrapper">
	  <h1 class="WrapperMainH1">HR Tech Europe - Sales Page |<br /> Add New Pitch</h1>
	  
	  	        <div id="MenuIconContainer">';
	
  
	if (isset($_SESSION['user'])) {
		
		if (isset($_SESSION['edit_pitch'])) {
			 $content .= '<a title="Back to Main Page" href="pitches"><img alt="Back to Main Page" class="MenuIcon" src="img/icons/main.png" onmouseover="this.src=';
			 $content .="'img/icons/main_hover.png';";
			 $content .='" onmouseout="this.src=';
			 $content .="'img/icons/main.png';";
			 $content .='" ></a>';
			
		} else {
			 $content .= '<a title="Back to Main Page" href="index"><img alt="Back to Main Page" class="MenuIcon" src="img/icons/main.png" onmouseover="this.src=';
			 $content .="'img/icons/main_hover.png';";
			 $content .='" onmouseout="this.src=';
			 $content .="'img/icons/main.png';";
			 $content .='" ></a>';
			
		}


	 
	}
	 
  $content .='</div>
	  
	 
	<!--Form container-->
	 <div id="container">';
	 
	 	if (isset($_SESSION['user'])) {
			include_once('controllers/main.php');
			$main = new main\main;
			
			if (isset($_SESSION['Result']) && $_SESSION['Result'] != '') {
				$content .='<div id="ReturnValue" style="display:none">'.$_SESSION['Result'].'</div>';
				$_SESSION['Result'] = '';
			} else {
				$content .='<div id="ReturnValue" style="display:none"></div>';
			}
    
	//If the user wants to edit a pitch instead of upload a new one
	//-------------------------------------------------------------------------         
	if (isset($_SESSION['edit_pitch'])) {
		$data = $main->get_pitch_edit_data($_SESSION['edit_pitch']);

	     $content .='<form id="speakers" name="speakers" method="post" action="controllers/ajax.php" enctype="multipart/form-data"><br />
  
     <fieldset>
	    <legend>Delegate</legend>
		<input class="AdminInputField" required="required" name="FirstName" id="FirstName" type="text" placeholder="First Name" value="'.$data['first_name'].'" /><br />
		<input class="AdminInputField" required="required" name="LastName" id="LastName" type="text" placeholder="Last Name" value="'.$data['last_name'].'" /><br />
        <input class="AdminInputField" name="DelegateTitle" id="DelegateTitle" type="text" placeholder="Title" value="'.$data['title'].'" /><br />
		<input class="AdminInputField" required="required" name="CompanyName" id="CompanyName" type="text" placeholder="Company Name" value="'.$data['company_name'].'" /><br />
		
		   <select class="SelectClass" id="Country" name="Country">';
	 $content .= $main->get_countries($data['country_code']);
    $content .=' 
    </select>	
     </fieldset>';

  $content .='

	  <fieldset>
	     <legend>Pitch</legend>
		 
		   <br /><select class="SelectClass" id="PitchType" name="PitchType">';
	        $content .= $main->get_pitch_type($data['ptype']);
            $content .=' 
           </select><br /><br />
		 

		  <label>Call back date:<input class="AdminInputField" id="CallBackDate" name="CallBackDate" type="date" value="'.$data['callback_date'].'"/></label><br />
		   <select class="SelectClass" id="PitchResult" name="PitchResult">';
	        $content .= $main->get_pitch_result($data['retype']);
            $content .=' 
           </select><br />
		  <input class="AdminInputField" id="NumberOfDeals" name="NumberOfDeals" type="number" placeholder="Number of Deals" value="'.$data['deals'].'" /><br />
	      <textarea class="TextAreaClass" id="Reason" name="Reason">'.$data['reason'].'</textarea><br />

   </fieldset>';

    //$content .= '<button class="AdminSubmitButton" name="NewPitchSave" id="NewPitchSave" type="Submit">Save</button>';
	
 $content .= '

  </form>
	
  	   <!-- End of Form Container-->';
	   
	   //if isset pitch edit
	   unset($_SESSION['edit_pitch']);
	   //-------------------------------------------------------------------------------------
	      } else {
		   //if isset pitch edit is NOT set
		  
		  	     $content .='<form id="speakers" name="speakers" method="post" action="controllers/ajax.php" enctype="multipart/form-data"><br />
  
     <fieldset>
	    <legend>Delegate</legend>
		<input class="AdminInputField" required="required" name="FirstName" id="FirstName" type="text" placeholder="First Name" /><br />
		<input class="AdminInputField" required="required" name="LastName" id="LastName" type="text" placeholder="Last Name" /><br />
        <input class="AdminInputField" name="DelegateTitle" id="DelegateTitle" type="text" placeholder="Title" /><br />
		<input class="AdminInputField" required="required" name="CompanyName" id="CompanyName" type="text" placeholder="Company Name" /><br />
		
		   <select class="SelectClass" id="Country" name="Country">';
	 $content .= $main->get_countries('');
    $content .=' 
    </select>
	<div id="UploadCountry" style="display:none">
	 <br /><input class="AdminInputField" id="NewCountryName" type="text" placeholder="Country Name" /><br />
		<input class="AdminInputField" required="required" name="NewCountryCode" id="NewCountryCode" type="text" placeholder="Country Code" /><br />
	 </div>
	
     </fieldset>';

  $content .='

	  <fieldset>
	     <legend>Pitch</legend>
		 
		   <br /><select class="SelectClass" id="PitchType" name="PitchType">';
	        $content .= $main->get_pitch_type('');
            $content .=' 
           </select><br /><br />
		 

		  <label>Call back date:<input class="AdminInputField" id="CallBackDate" name="CallBackDate" type="date" /></label><br />
		   <select class="SelectClass" id="PitchResult" name="PitchResult">';
	        $content .= $main->get_pitch_result('');
            $content .=' 
           </select><br />
		  <input class="AdminInputField" id="NumberOfDeals" name="NumberOfDeals" type="number" placeholder="Number of Deals" /><br />
	      <textarea class="TextAreaClass" id="Reason" name="Reason" placeholder="Reason"></textarea><br />

   </fieldset>';

   $content .= '<button class="AdminSubmitButton" name="NewPitchSave" id="NewPitchSave" type="Submit">Save</button>';
	
 $content .= '

  </form>
	
  	   <!-- End of Form Container-->';
		  
		  
		  
		  
		  }//if isset pitch edit is NOT set else end
	   //---------------------------------------------------------------------
	   
	   
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
