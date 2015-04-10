<?php 
namespace HRNSales\ajax;
use HRNSales\main as main;
use HRNSales\pitches as pitches;
include_once('main.php');
include_once('pitches.php');	
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


/*///////////// 
Date filter
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'analytics_date_filter'){
	$the_main = new main\main;
    $result = $the_main->get_analytics_data($_POST['data'], $_POST['country'], '');
	if (isset($result)) {
      echo $result;	
	}

}// save Pitch data

/*///////////// 
Date filter Intervall
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'analytics_date_filter_intervall'){
	$the_main = new main\main;
    $result = $the_main->get_analytics_data_intervall($_POST['data'], $_POST['country'], '');
	if (isset($result)) {
      echo $result;	
	}

}// save Pitch data


/*///////////// 
Pitches Call Back Filter
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'pitches_callback_filter'){
	$pitch = new pitches\pitches;
	if($_POST['data'] == 'All' || $_POST['data'] == '') {
		$category = '';
	    $value = '';
	} else {
		$category = 'pr.callback_date';
	    $value = $_POST['data'];
	}
	
	if (isset($_SESSION['admin'])) {
		 $admin = -200;
	} else {
		 $admin = $_SESSION['user_id'];
	}
    $result = $pitch->list_pithces($category,$value, $admin);
	if (isset($result)) {
      echo $result;	
	}

}// save Pitch data



?>