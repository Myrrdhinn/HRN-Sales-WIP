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

/*
 if(isset($_POST['action']) && $_POST['action'] == 'save_call_num'){
	$the_main = new main\main;
    $result = $the_main->save_call_number();
	if (isset($result)) {
	  $_SESSION['Result'] =  'Success';
	  echo 'Success';	
	}

}// save Pitch data
*/

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
Pitches Filter change
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'pitch_list_change'){
	 
	 if (isset($_POST['month'])){
		 $_SESSION['PitchSelectedMonth'] = $_POST['month'];
	 }
	 
	if (isset($_SESSION['PitchSelectedMonth'])){
        $currentMonth = $_SESSION['PitchSelectedMonth'];
	} else {
		$currentMonth = date('m');
	}
	
	 if (isset($_POST['pitch'])){
		  $_SESSION['PitchSelectedPitchType'] = $_POST['pitch'];
	 }
	
	if (isset($_POST['result'])){
		  $_SESSION['PitchSelectedResultType'] = $_POST['result'];
	 }
	 
	if (isset($_POST['callback'])){
		  $_SESSION['PitchSelectedCallback'] = $_POST['callback'];
	 }	
	 
	if (isset($_POST['tmembers'])){
		  $_SESSION['PitchSelectedTmembers'] = $_POST['tmembers'];
	 }	
	 
	if (isset($_POST['teams'])){
		  $_SESSION['PitchSelectedTeams'] = $_POST['teams'];
	 }	
	 
	if (isset($_POST['company'])){
		 $_SESSION['PitchFilterCompany'] = $_POST['company'];
	 }	 
	 
	   
		
	if (isset($_SESSION['admin'])) {
		 $admin = $_SESSION['admin'];
	} else {
		 $admin = $_SESSION['user_id'];
	}	
		
	  $pitch = new pitches\pitches;
      $result = $pitch->list_pithces($_SESSION['PitchSelectedCallback'],$_SESSION['PitchSelectedTmembers'],$_SESSION['PitchSelectedTeams'], $admin, $_SESSION['PitchFilterCompany'], $currentMonth, $_SESSION['PitchFilterOrder'], $_SESSION['PitchSelectedOrderType'], $_SESSION['PitchSelectedPitchType'], $_SESSION['PitchSelectedResultType']);
	

	
	if (isset($result)) {
      echo $result;	
	}

}// save Pitch data


/*///////////// 
Pitches Filter Order change
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'pitch_list_order_change'){


		if(isset($_SESSION['PitchFilterOrder']) && $_SESSION['PitchFilterOrder'] == $_POST['order']){
			
		    if ($_SESSION['PitchSelectedOrderType'] == 'DESC'){
				$_SESSION['PitchSelectedOrderType'] = 'ASC';
			} else {
			   if ($_SESSION['PitchSelectedOrderType'] == 'ASC'){
				$_SESSION['PitchSelectedOrderType'] = 'DESC';
				
			    }
			}
			

		 
		}else {
			$_SESSION['PitchFilterOrder'] = $_POST['order'];
			$_SESSION['PitchSelectedOrderType'] = 'DESC';
		}
		
		
	if (isset($_SESSION['admin'])) {
		 $admin = $_SESSION['admin'];
	} else {
		 $admin = $_SESSION['user_id'];
	}	
		
	  $pitch = new pitches\pitches;
      $result = $pitch->list_pithces($_SESSION['PitchFilterCategory'],$_SESSION['PitchFilterValue'], $admin, $_SESSION['PitchFilterCompany'], $_SESSION['PitchSelectedMonth'], $_SESSION['PitchFilterOrder'], $_SESSION['PitchSelectedOrderType'], $_SESSION['PitchSelectedPitchType'], $_SESSION['PitchSelectedResultType']);
	

	
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


/*///////////// 
Get search data
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'get_search_data'){
	$the_main = new main\main;
	

    $result = $the_main->get_search_data();
	if (isset($result)) {
	 
	 echo $result;	
	}


}



/*///////////// 
Create user
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'add_new_admin'){
	$the_main = new main\main;
	

    $result = $the_main->add_user();
	if (isset($result)) {
	 $_SESSION['Result'] = "User have been saved!";
	 echo $result;	
	}


}// new user

/*///////////// 
Edit user
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'edit_user_data_save'){
	$the_main = new main\main;
	

    $result = $the_main->edit_user();
	if (isset($result)) {
	 $_SESSION['Result'] = "User have been saved!";
	 echo $result;	
	}


}// new user

/*///////////// 
User Edit Request
///////////////*/


 if(isset($_POST['action']) && $_POST['action'] == 'user_edit_request'){
	$the_main = new main\main;
	
	if (isset($_POST['sId'])){
	   $_SESSION['user_edit_id'] = $_POST['sId'];	
	}


}// new user

?>