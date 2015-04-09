<?php 
namespace HRNSales\ajax;
use HRNSales\main as main;
include_once('main.php');	
if(!isset($_SESSION)) {
	session_start();
}

/*///////////// 
Save pitch data
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'Save_Pitch'){
	$the_main = new main\main;
    $result = $the_main->save_pitch();
	if (isset($result)) {
	  $_SESSION['Result'] =  'Success';
	  echo 'Success';	
	}

}// save Pitch data


/*///////////// 
Save number of calls
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'save_call_number'){
	$the_main = new main\main;
    $result = $the_main->save_call_number();
	if (isset($result)) {
	  $_SESSION['Result'] =  'Success';
	  echo 'Success';	
	}

}// save Pitch data


/*///////////// 
Pitch edit request
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'edit_pitch'){
	if (isset($_POST['pitch_num'])) {
	  $_SESSION['edit_pitch'] =  $_POST['pitch_num'];
	  echo 'Success';	
	}

}// save Pitch data



/*///////////// 
Actual Pitch edit
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'Edit_Pitch_Data'){
	$the_main = new main\main;
    $result = $the_main->edit_pitch();
	if (isset($result)) {
	  $_SESSION['Result'] =  'Success';
	  echo 'Success';	
	}

}// save Pitch data




?>