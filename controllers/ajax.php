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


 if(isset($_POST['action']) && $_POST['action'] == 'save_call_num'){
	$the_main = new main\main;
    $result = $the_main->save_call_number();
	if (isset($result)) {
	  $_SESSION['Result'] =  'Success';
	  echo 'Success';	
	}

}// save Pitch data


/*///////////// 
Save number of minutes
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'save_minutes_num'){
	$the_main = new main\main;
    $result = $the_main->save_minutes_number();
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
    $result = $the_main->get_analytics_data($_POST['data'], $_POST['country'], $_POST['teams']);
	if (isset($result)) {
      echo $result;	
	}


}// save Pitch data

/*///////////// 
Date filter Intervall
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'analytics_date_filter_intervall'){
	$the_main = new main\main;
    $result = $the_main->get_analytics_data_intervall($_POST['data'], $_POST['country'], $_POST['teams']);
	if (isset($result)) {
      echo $result;	
	}

}// save Pitch data

/*///////////// 
Get Graph data
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'analytics_graph_filter'){
	$the_main = new main\main;
    $result = $the_main->get_analytics_graph_data($_POST['data'], $_POST['country'], $_POST['teams']);
	if (isset($result)) {
      echo $result;	
	}

}// save Pitch data

/*///////////// 
Get Graph data Intervall
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'analytics_graph_filter_intervall'){
	$the_main = new main\main;
    $result = $the_main->get_analytics_graph_data_intervall($_POST['data'], $_POST['country'], $_POST['teams']);
	if (isset($result)) {
      echo $result;	
	}

}// save Pitch data


/*///////////// 
Pitches Filter
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'pitches_filter'){
	$pitch = new pitches\pitches;
	if($_POST['data'] == 'All' || $_POST['data'] == '') {
		$category = '';
	    $value = '';
	} else {
		$category = $_POST['category'];
	    $value = $_POST['data'];
	}
	
	if (isset($_SESSION['admin'])) {
		 $admin = $_SESSION['admin'];
	} else {
		 $admin = $_SESSION['user_id'];
	}
    $result = $pitch->list_pithces($category,$value, $admin);
	if (isset($result)) {
      echo $result;	
	}

}// save Pitch data


/*///////////// 
Filter the Team Member select box
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'team_select_box_filter'){
	$the_main = new main\main;
	
	if ($_POST['group'] == 'All'){
		$team_id = '';
		
	} else {
		$team_id = $_POST['group'];
	}
	
    $result = $the_main->get_team_members($team_id);
	if (isset($result)) {
      echo $result;	
	}

}// save Pitch data

/*///////////// 
Callrate user filter
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'callrate_user_filter'){
	$the_main = new main\main;

    $result = $the_main->list_calls($_POST['team']);
	if (isset($result)) {
      echo $result;	
	}

}// save Pitch data

?>