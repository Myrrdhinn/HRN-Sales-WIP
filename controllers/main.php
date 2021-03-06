<?php 
namespace HRNSales\main;
use HRNSales\config as config;
include_once('config.php');	
if(!isset($_SESSION)) {
	$lifetime=3600;
    session_set_cookie_params($lifetime);
	session_start();
}

class main extends config {

/*
-------------------------------
New Pitch Functions
-------------------------------
*/	
public function get_countries($sel_country) {
	$content = '';
	$selected = '';
	 if($sel_country == '' || (!isset($sel_country))){
	  $content = '<option value="" hidden="hidden" selected="selected">Select a Country</option>'; 
	 }
	 
		 $country_q = "SELECT id, country_code, country_name FROM countries ORDER BY country_code ASC";
		 $countries = $this->pdo->prepare($country_q);
		 $countries->execute();
		 
					if ($countries->rowCount() > 0) {
					while($country = $countries->fetch()){
						
						if($country['country_code'] == $sel_country){
							$selected = ' selected="selected" ';   
						   }

                         $content .= '<option '.$selected.' value="'.$country['id'].'">'.$country['country_code'].' ('.$country['country_name'].')</option>';
						 
						 $selected ='';
					} //personal fetch assoc end
				}  //personal num rows if end
				
        /* $content .= '<option value="Other">Other</option>';*/
	return $content;	
}


public function get_pitch_type($sel_type) {
	$content = '';
	$selected = '';
	
	 if($sel_type == '' || (!isset($sel_type))){
	  	$content = '<option value="" hidden="hidden" selected="selected">Pitched</option>'; 
	 }

		 $type_q = "SELECT id, type FROM pitch_type ORDER BY id DESC";
		 $pitch = $this->pdo->prepare($type_q);
		 $pitch->execute();
		 
					if ($pitch->rowCount() > 0) {
					while($type = $pitch->fetch()){
						 if($type['type'] == $sel_type){
							$selected = ' selected="selected" ';   
						   }
						
                         $content .= '<option '.$selected.' value="'.$type['id'].'">'.$type['type'].'</option>';
						  $selected ='';
					} //personal fetch assoc end
				}  //personal num rows if end
				
	return $content;	
}

public function get_pitch_result($sel_type) {
	$content = '';
	$selected = '';
	
	 if($sel_type == '' || (!isset($sel_type))){
	  	$content = '<option value="" hidden="hidden" selected="selected">Result</option>';
	 }

	
		 $type_q = "SELECT id, type FROM result_type ORDER BY id DESC";
		 $pitch = $this->pdo->prepare($type_q);
		 $pitch->execute();
		 
					if ($pitch->rowCount() > 0) {
					while($type = $pitch->fetch()){
						if($type['type'] == $sel_type){
							$selected = ' selected="selected" ';   
						   }
						
                         $content .= '<option '.$selected.' value="'.$type['id'].'">'.$type['type'].'</option>';
						  $selected ='';
					} //personal fetch assoc end
				}  //personal num rows if end
				
	return $content;	
}

public function save_pitch() {
	

		$country = $_POST['Country'];
	$name = explode (' ', $_POST['name']);
	if (isset($name[0])){
		$first_name = $name[0];
	} else {
		$first_name = $name;
	}
	
	
	$last_name = '';
	
	if (isset($name[1])) {
		$nid = 0;
		
		 foreach ($name as $n) {
			 if ($nid > 0) {
				 $last_name .= $n.' ';
			 }
			 $nid++;
		 }
	}
	
	/*
	Timestamps :(
	*/
   $CurrentPHPTimeStamp = date('Y-m-d G:i:s');
   $CompareDate = date('Y-m-d');
   $PHPTime = date('G:i:s');

	$date = date("j F Y");
	
	  //if the date of pitch is set
	if (isset($_POST['DateOfPitch'])) {
		//Compare with the current day
		
		if ($_POST['DateOfPitch'] == $CompareDate) {
			//The pitch is set to today
			$PHPTimeStamp =  $CurrentPHPTimeStamp;
		} else {
		    //the pitch is older	
			$dateOfPitch = explode('-',$_POST['DateOfPitch']);
			
			$PHPTimeStamp = $_POST['DateOfPitch'].' '.$PHPTime;
			$date = date ("j F Y",(mktime(0,0,0,$dateOfPitch[1],$dateOfPitch[2],$dateOfPitch[0])));
		}
		
	} else {
		$PHPTimeStamp =  $CurrentPHPTimeStamp;
	}
	
	


	
	      $this->pdo->beginTransaction();

		 $delegate_q = "INSERT INTO delegates SET first_name = :first_name, last_name = :last_name, title = :title, date= :date";
		 $delegate = $this->pdo->prepare($delegate_q);
		 
		 $delegate->bindValue(':first_name', $first_name, \PDO::PARAM_STR);
		 $delegate->bindValue(':last_name', $last_name, \PDO::PARAM_STR);
		 $delegate->bindValue(':title', $_POST['DelegateTitle'], \PDO::PARAM_STR);
		 $delegate->bindValue(':date', $PHPTimeStamp, \PDO::PARAM_STR);
		 
		 $delegate->execute();
		 
            $delegate_id = $this->pdo->lastInsertId(); 
		 
		 $company_q = "INSERT INTO company SET company_name = :company_name, date = :date";
		 $company = $this->pdo->prepare($company_q);
		 
		 $company->bindValue(':company_name', $_POST['CompanyName'], \PDO::PARAM_STR);
		 $company->bindValue(':date', $PHPTimeStamp, \PDO::PARAM_STR);
		  
		 $company->execute();

		    $company_id = $this->pdo->lastInsertId();
			
		 $connection_q = "INSERT INTO delegate_connection SET country_id = :country_id, delegate_id = :delegate_id, company_id = :company_id, date = :date";
		 $connection = $this->pdo->prepare($connection_q);
		 
		 $connection->bindValue(':country_id', $country, \PDO::PARAM_INT);
		 $connection->bindValue(':delegate_id', $delegate_id, \PDO::PARAM_INT);
		 $connection->bindValue(':company_id', $company_id, \PDO::PARAM_INT);
		 $connection->bindValue(':date', $PHPTimeStamp, \PDO::PARAM_STR);
		 
		 $connection->execute();
		 
		    $connection_id = $this->pdo->lastInsertId();
		 
		 
		 $pitch_data_q = "INSERT INTO pitch_data SET date_of_pitch = :pitch_date, user_id = :user_id, delegate_id = :delegate_id, pitch_type_id = :pitch_type, date = :date";
		 $pitch_data = $this->pdo->prepare($pitch_data_q);
		 
		 $pitch_data->bindValue(':pitch_date', $date, \PDO::PARAM_STR);
		 $pitch_data->bindValue(':user_id', $_SESSION['user_id'], \PDO::PARAM_INT);
		 $pitch_data->bindValue(':delegate_id', $delegate_id, \PDO::PARAM_INT);
		 $pitch_data->bindValue(':pitch_type', $_POST['PitchType'], \PDO::PARAM_INT);
		 $pitch_data->bindValue(':date', $PHPTimeStamp, \PDO::PARAM_STR);
		 
		 $pitch_data->execute();
		 
		 	$pitch_data_id = $this->pdo->lastInsertId();
			
			
		 $pitch_result_q = "INSERT INTO pitch_result SET pitch_data_id = :data_id, result_type_id = :result_type, deals = :deal_nums, callback_date = :callback, price = :price, reason = :reason, date = :date";
		 $pitch_result = $this->pdo->prepare($pitch_result_q);
		 
		 $pitch_result->bindValue(':data_id', $pitch_data_id, \PDO::PARAM_INT);
		 $pitch_result->bindValue(':result_type', $_POST['PitchResult'], \PDO::PARAM_INT);
		 $pitch_result->bindValue(':deal_nums', $_POST['NumberOfDeals'], \PDO::PARAM_INT);
		 $pitch_result->bindValue(':callback', $_POST['CallBackDate'], \PDO::PARAM_STR);
		 $pitch_result->bindValue(':price', $_POST['Price'], \PDO::PARAM_STR);
		 $pitch_result->bindValue(':reason', $_POST['Reason'], \PDO::PARAM_STR);
		 $pitch_result->bindValue(':date', $PHPTimeStamp, \PDO::PARAM_STR);
		 
		 $pitch_result->execute();
		 
		    $pitch_result_id = $this->pdo->lastInsertId();
		 
		 $this->pdo->commit();
		 
		 return $pitch_result_id;
	
}


public function edit_pitch() {
	
 if (isset($_POST['Edit']) && $_POST['Edit'] > -1){
	 
	 $CurrentData = $this->get_pitch_edit_data($_POST['Edit']);
		

	$name = explode (' ', $_POST['name']);
	if (isset($name[0])){
		$first_name = $name[0];
	} else {
		$first_name = $name;
	}
	
	
	$last_name = '';
	
	if (isset($name[1])) {
		$nid = 0;
		
		 foreach ($name as $n) {
			 if ($nid > 0) {
				 $last_name .= $n.' ';
			 }
			 $nid++;
		 }
	}


	
	$country = $_POST['Country'];

	$date = date("j F Y");
	
		/*
	Timestamps :(
	*/
   $CurrentPHPTimeStamp = date('Y-m-d G:i:s');
   $CompareDate = date('Y-m-d');
   $PHPTime = date('G:i:s');

	$date = date("j F Y");
	
	  //if the date of pitch is set
	if (isset($_POST['DateOfPitch'])) {
		//Compare with the current day
		
		if ($_POST['DateOfPitch'] == $CompareDate) {
			//The pitch is set to today
			$PHPTimeStamp =  $CurrentPHPTimeStamp;
		} else {
		    //the pitch is older	
			$dateOfPitch = explode('-',$_POST['DateOfPitch']);
			
			$PHPTimeStamp = $_POST['DateOfPitch'].' '.$PHPTime;
			$date = date ("j F Y",(mktime(0,0,0,$dateOfPitch[1],$dateOfPitch[2],$dateOfPitch[0])));
		}
		
	} else {
		$PHPTimeStamp =  $CurrentPHPTimeStamp;
	}
	
	


	
	
	
	    if( ($CurrentData['first_name'] != $first_name) || ($CurrentData['last_name'] != $last_name) || ($CurrentData['title'] != $_POST['DelegateTitle']) ){
			//if there's a name of title change
		   $delegate_q = "INSERT INTO delegates SET first_name = :first_name, last_name = :last_name, title = :title";
		   $delegate = $this->pdo->prepare($delegate_q);
		   
		   $delegate->bindValue(':first_name', $first_name, \PDO::PARAM_STR);
		   $delegate->bindValue(':last_name', $last_name, \PDO::PARAM_STR);
		   $delegate->bindValue(':title', $_POST['DelegateTitle'], \PDO::PARAM_STR);
		   
		   $delegate->execute();
		   
			  $delegate_id = $this->pdo->lastInsertId();
		}
			 
		 
		 
	  if( ($CurrentData['company_name'] != $_POST['CompanyName']) ){
		  //company change
			 $company_q = "INSERT INTO company SET company_name = :company_name";
			 $company = $this->pdo->prepare($company_q);
			 
			 $company->bindValue(':company_name', $_POST['CompanyName'], \PDO::PARAM_STR);
			 
			 $company->execute();
	
				$company_id = $this->pdo->lastInsertId();
			
	   }
			
	  if (isset($delegate_id) || isset($company_id) || $country != $CurrentData['country_id']) {	
	         if (!isset($delegate_id)) {
				 $delegate_id = $CurrentData['delegate_id'];
			 }
			 
			 if (!isset($company_id)){
				 $company_id = $CurrentData['company_id'];
			 }
	  	
		   $connection_q = "UPDATE delegate_connection SET country_id = :country_id, delegate_id = :delegate_id, company_id = :company_id WHERE id= :id";
		   $connection = $this->pdo->prepare($connection_q);
		   
		   $connection->bindValue(':country_id', $country, \PDO::PARAM_INT);
		   $connection->bindValue(':delegate_id', $delegate_id, \PDO::PARAM_INT);
		   $connection->bindValue(':company_id', $company_id, \PDO::PARAM_INT);
		   $connection->bindValue(':id', $CurrentData['delc_conn_id'], \PDO::PARAM_INT);
		   
		   
		   $connection->execute();
		   
			  $connection_id = $this->pdo->lastInsertId();
	  }
	  
	  	     if (!isset($delegate_id)) {
				 $delegate_id = $CurrentData['delegate_id'];
			 }
		 
	    if( ($CurrentData['ptype'] != $_POST['PitchType']) || ($CurrentData['delegate_id'] != $delegate_id) || $CurrentData['PitchDate'] != $_POST['DateOfPitch'] ){	
		   //Pitch data change 
			 $pitch_data_q = "UPDATE pitch_data SET delegate_id = :delegate_id, pitch_type_id = :pitch_type, date_of_pitch= :pitchdate, date = :date WHERE id= :id";
			 $pitch_data = $this->pdo->prepare($pitch_data_q);
			 
			 $pitch_data->bindValue(':delegate_id', $delegate_id, \PDO::PARAM_INT);
			 $pitch_data->bindValue(':pitch_type', $_POST['PitchType'], \PDO::PARAM_INT);
			 $pitch_data->bindValue(':id', $CurrentData['pitch_data_id'], \PDO::PARAM_INT);
			 $pitch_data->bindValue(':pitchdate', $date, \PDO::PARAM_STR);
			 $pitch_data->bindValue(':date', $PHPTimeStamp, \PDO::PARAM_STR);
			 
			 $pitch_data->execute();
			 
				$pitch_data_id = $this->pdo->lastInsertId();
		}
		
	   if( ($CurrentData['retype'] != $_POST['PitchResult']) || ($CurrentData['deals'] != $_POST['NumberOfDeals']) || ($CurrentData['callback_date'] != $_POST['CallBackDate']) || ($CurrentData['reason'] != $_POST['Reason']) || ($CurrentData['price'] != $_POST['Price']) || $CurrentData['PitchDate'] != $_POST['DateOfPitch']  ){			
		 $pitch_result_q = "UPDATE pitch_result SET result_type_id = :result_type, deals = :deal_nums, callback_date = :callback, price = :price, reason = :reason, date = :date WHERE id= :id";
		 $pitch_result = $this->pdo->prepare($pitch_result_q);
		 
		 $pitch_result->bindValue(':result_type', $_POST['PitchResult'], \PDO::PARAM_INT);
		 $pitch_result->bindValue(':deal_nums', $_POST['NumberOfDeals'], \PDO::PARAM_INT);
		 $pitch_result->bindValue(':callback', $_POST['CallBackDate'], \PDO::PARAM_STR);
		 $pitch_result->bindValue(':price', $_POST['Price'], \PDO::PARAM_STR);
		 $pitch_result->bindValue(':reason', $_POST['Reason'], \PDO::PARAM_STR);
		 $pitch_result->bindValue(':id', $CurrentData['id'], \PDO::PARAM_INT);
		 $pitch_result->bindValue(':date', $PHPTimeStamp, \PDO::PARAM_STR);
		 
		 $pitch_result->execute();
		 
		    $pitch_result_id = $this->pdo->lastInsertId();
	   }
		 
		 if(isset($pitch_result_id)) {
			 return $pitch_result_id;
			 
		 }elseif (isset($pitch_data_id)) {
			  return $pitch_data_id;
			 
		 }elseif (isset($connection_id)) {
			 return $connection_id;
		 }
		 
	
 }//if isset Edit
	
}


public function get_pitch_edit_data($pitch_num) {
			$data[0][0] = '';
		$i = 0;
		
		//Get basic date about a sponsors
		                    //pitch date            Deleagate name,     Title          country   pitch type, result type, deals,   callback      reason
		$pitch_q = "SELECT pr.id, de.id AS delegate_id, cy.id AS company_id, pd.date_of_pitch, DATE(pd.date) AS PitchDate, de.first_name, de.last_name, de.title, co.country_code, co.id AS country_id, delc.id AS delc_conn_id, pd.id AS pitch_data_id, pt.type as ptype, re.type as retype, pr.deals, pr.price, pr.callback_date, pr.reason, cy.company_name, u.username FROM pitch_data as pd, delegates as de, delegate_connection as delc, countries as co, pitch_type as pt, result_type as re, pitch_result as pr, company as cy, users as u WHERE pd.user_id=u.id AND pd.delegate_id=delc.delegate_id AND delc.country_id=co.id AND delc.delegate_id=de.id AND delc.company_id=cy.id AND pd.pitch_type_id=pt.id AND pr.pitch_data_id=pd.id AND pr.result_type_id=re.id AND pr.id= :id ORDER BY pd.date DESC";	
					
		$pitch = $this->pdo->prepare($pitch_q);
		$pitch->bindValue(':id', $pitch_num, \PDO::PARAM_INT);
		$pitch->execute();

			if ($pitch->rowCount() > 0) {
					$pitches = $pitch->fetch();
			}  //stat num row end
			
		return $pitches;
	
	
}


/*
-------------------------------
Mainpage functions
-------------------------------
*/	
 public function get_call_nums() {
	 		 $type_q = "SELECT calls FROM user_calls WHERE user_id = :id AND call_date = CURDATE()";
		 $call = $this->pdo->prepare($type_q);
		 $call->bindValue(':id', $_SESSION['user_id'], \PDO::PARAM_INT);
		 $call->execute();
		 
				if ($call->rowCount() > 0) {
					$call_num = $call->fetch();
					$content = $call_num[0];
				} else {  //row count > 0
				
					$insert_call = "INSERT INTO user_calls SET user_id = :id, call_date = CURDATE(), calls = 0";
					 $call2 = $this->pdo->prepare($insert_call);
					 $call2->bindValue(':id', $_SESSION['user_id'], \PDO::PARAM_INT);
					 $call2->execute();
					 $content = 0;
				
				}
	return $content;	
	 
	 
	 
 }
 
 
public function save_call_number() {
	
	if (isset($_POST['num'])) {
		   $get_call = "SELECT calls FROM user_calls WHERE user_id = :user_id AND call_date = CURDATE()";
		   $call = $this->pdo->prepare($get_call);
		   $call->bindValue(':user_id', $_POST['id'], \PDO::PARAM_INT);
		   $call->execute();
		   
		  if ($call->rowCount() > 0) {
				 $insert_call = "UPDATE user_calls SET calls = :calls WHERE user_id = :user_id AND call_date = CURDATE()";
				 $call2 = $this->pdo->prepare($insert_call);
				 $call2->bindValue(':calls', $_POST['num'], \PDO::PARAM_INT);
				 $call2->bindValue(':user_id', $_POST['id'], \PDO::PARAM_INT);
				 $call2->execute();
		  } else {
				 $insert_call = "INSERT INTO user_calls SET user_id = :id, call_date = CURDATE(), calls = :calls";
				 $call2 = $this->pdo->prepare($insert_call);
				 $call2->bindValue(':id', $_POST['id'], \PDO::PARAM_INT);
				 $call2->bindValue(':calls', $_POST['num'], \PDO::PARAM_INT);
				 $call2->execute();
				 
			  
		  }
		
		
		

		   
			 return 'Success';
			 
			 
	}

	
}

public function save_minutes_number() {
	
	if (isset($_POST['minutenum']) && $_POST['minutenum'] != '') {
		   $get_call = "SELECT minutes FROM user_calls WHERE user_id = :user_id AND call_date = CURDATE()";
		   $call = $this->pdo->prepare($get_call);
		   $call->bindValue(':user_id', $_POST['id'], \PDO::PARAM_INT);
		   $call->execute();
		   
		  if ($call->rowCount() > 0) {
				 $insert_call = "UPDATE user_calls SET minutes = :minutes WHERE user_id = :user_id AND call_date = CURDATE()";
				 $call2 = $this->pdo->prepare($insert_call);
				 $call2->bindValue(':minutes', $_POST['minutenum'], \PDO::PARAM_INT);
				 $call2->bindValue(':user_id', $_POST['id'], \PDO::PARAM_INT);
				 $call2->execute();
		  } else {
				 $insert_call = "INSERT INTO user_calls SET user_id = :id, call_date = CURDATE(), minutes = :minutes";
				 $call2 = $this->pdo->prepare($insert_call);
				 $call2->bindValue(':id', $_POST['id'], \PDO::PARAM_INT);
				 $call2->bindValue(':minutes', $_POST['minutenum'], \PDO::PARAM_INT);
				 $call2->execute();
				 
			  
		  }
 
			 
	}
	

		if (isset($_POST['callnum']) && $_POST['callnum'] != '') {
		   $get_call = "SELECT calls FROM user_calls WHERE user_id = :user_id AND call_date = CURDATE()";
		   $call = $this->pdo->prepare($get_call);
		   $call->bindValue(':user_id', $_POST['id'], \PDO::PARAM_INT);
		   $call->execute();
		   
		  if ($call->rowCount() > 0) {
				 $insert_call = "UPDATE user_calls SET calls = :calls WHERE user_id = :user_id AND call_date = CURDATE()";
				 $call2 = $this->pdo->prepare($insert_call);
				 $call2->bindValue(':calls', $_POST['callnum'], \PDO::PARAM_INT);
				 $call2->bindValue(':user_id', $_POST['id'], \PDO::PARAM_INT);
				 $call2->execute();
		  } else {
				 $insert_call = "INSERT INTO user_calls SET user_id = :id, call_date = CURDATE(), calls = :calls";
				 $call2 = $this->pdo->prepare($insert_call);
				 $call2->bindValue(':id', $_POST['id'], \PDO::PARAM_INT);
				 $call2->bindValue(':calls', $_POST['callnum'], \PDO::PARAM_INT);
				 $call2->execute();
				 
			  
		  }
		
			 
	}
	
	
 return 'Success';
	
}

/*
-------------------------------
Goals functions
-------------------------------
*/	

 public function get_goal_data() {
	 $content = '';
	 $i = 0;
	 	$users_q = "SELECT id, username FROM users";
		 $users = $this->pdo->prepare($users_q);
		 $users->execute();
		 
				if ($users->rowCount() > 0) {
					while($user = $users->fetch()) {
						$data[$i][0] = $user[1]; 
						
						$type_q = "SELECT SUM(pr.deals) AS deals FROM pitch_result as pr, pitch_data as pd WHERE pd.user_id = :id AND pr.pitch_data_id=pd.id";
						 $deals = $this->pdo->prepare($type_q);
						 $deals->bindValue(':id', $user[0], \PDO::PARAM_INT);
						 $deals->execute();
						
						$deal_data = $deals->fetch();
						$data[$i][1] = $deal_data[0]; 
					$i++;	
					}
				}
				
				
	$content .=' <form>
	<div id="GoalInputs">
	   <div class="Inputs">
	      <label>Deals
	     <input id="DelegateGoalField" class="AdminInputField" type="text" placeholder="Delegates" value="60" /></label>
	     <button id="DelegateGoalSubmit" class="AdminSubmitButton">Change</button>
	   </div>	
	    <div class="Inputs">
		<label>GBP
	   	<input id="GBPGoalField" class="AdminInputField" type="text" placeholder="GBP" value="9000" /></label>
	     <button id="GBPGoalSubmit" class="AdminSubmitButton">Change</button>  
		 </div>	
	</div>
	 </form>';	
	 
	 $summary = 0;
	 foreach ($data as $deal) {
		  $content .='<p>'.$deal[0].': '.$deal[1].'</p>';
		  $summary = $summary + $deal[1];
	 }
	 $content .='<br /><p>All: '.$summary.'</p>';
	 
	 $needed = 60 - $summary;
	 
	 $content .='<br /><p>We Need: '.$needed.'</p>';		
	return $content;	
	 
	 
	 
 }

/*
-------------------------------
Graph data
-------------------------------
*/

 public function get_analytics_graph_data($date, $country, $teams) {
	 $content = '';
	  $all_pitch_num = 0;
	  $all_deal_num = 0;
	  $all_list_data = '';
	  $all_total_price = 0;
	  $all_total_paid = 0;
	  $all_total_VIP = 0;
	  $username = '';
	  $z = 0;
	  
      $content['users'] = array();
	  
	  if(isset($_SESSION['admin']) && $_SESSION['admin'] > 1) {
		  unset($teams);
		  $teams[0] = $_SESSION['team_id'];
	  }
	 
	 	$users_q = "SELECT u.id, u.username FROM users as u, user_team_connection as utc WHERE u.id=utc.user_id AND u.rank <> 0 AND u.rank <> 5";
				if (isset($teams[0]) && $teams[0] != 'All' && $teams[0] != '') {
						$teamid = -1;
					  foreach ($teams as $team) {
							$teamid ++;
							if ($teamid == 0){
								$plus_q = ' AND (utc.team_id= :tid'.$teamid;
							} else {
								$plus_q .= ' OR utc.team_id= :tid'.$teamid;
							}
						   
						   
					  }
					  $plus_q .= ')';
					  $users_q .= $plus_q;
				  }
				  
		
		 $users = $this->pdo->prepare($users_q);
		 
						  
				  
					if (isset($teams[0]) && $teams[0] != 'All' && $teams[0] != '') {
						$teamid = -1;
					  foreach ($teams as $team) {
						  $teamid++;
							$users->bindValue(':tid'.$teamid, $team, \PDO::PARAM_INT);
						   
					  }
					  
				  }
					 
		 
		 
		 $users->execute();
		 
				if ($users->rowCount() > 0) {
					while($user = $users->fetch()) {

						
							$userdata[0][0] = '';
					
							$i = 0;
							$plus_q = '';
							$s = 0;
							
							//Get basic date about a sponsors
											   //pitch
							$pitch_q = "SELECT COUNT(pd.id) as pitches, SUM(pr.deals) as deals, SUM(pr.price) as price, pd.date_of_pitch, (SELECT SUM(prt.deals) FROM pitch_result as prt, pitch_data as pdt WHERE prt.result_type_id=3 AND pdt.user_id=u.id AND pdt.id=prt.pitch_data_id AND pdt.date_of_pitch=pd.date_of_pitch) as Paid, (SELECT SUM(prt.deals) FROM pitch_result as prt, pitch_data as pdt WHERE prt.result_type_id=4 AND pdt.user_id=u.id AND pdt.id=prt.pitch_data_id AND pdt.date_of_pitch=pd.date_of_pitch) as VIP FROM users as u, pitch_data as pd, pitch_result as pr, countries as co, delegate_connection as delc WHERE u.id= :id AND u.rank <> 0 AND u.rank <> 5 AND u.id=pd.user_id AND pr.pitch_data_id=pd.id AND pd.delegate_id=delc.delegate_id AND delc.country_id=co.id";	
					        //if we (nem jut eszembe, szóval) dátum alapján szűrünk .. oh.. filter.. damn
							//-----------------------------------------
 					    if (isset($date) && $date != '' && $date != 'All') {
							$plus_q = ' AND pd.date_of_pitch LIKE :pitch';
						
						     $pitch_q .= $plus_q;
					
						  }
						  
						  
						  //if we filter (;)) based on country
						  //-------------------------------
						  if (isset($country) && $country != -1 && $country != '') {
							 $plus_q = ' AND co.id= :country';
						
						     $pitch_q .= $plus_q;
							  
						  }
						  
						  
						$pitch_q .=' GROUP BY pd.date_of_pitch ORDER BY pd.date';
						
					
						
							$pitch = $this->pdo->prepare($pitch_q);
							$pitch->bindValue(':id', $user[0], \PDO::PARAM_INT);
							

							 if (isset($date) && $date != ''  && $date != 'All') {
							   $pitch->bindValue(':pitch', $date, \PDO::PARAM_STR);
							 }
							 
							 if (isset($country) && $country != -1 && $country != '') {
							   $pitch->bindValue(':country', $country, \PDO::PARAM_INT);
							 }
							
							 
							 $pitch->execute();
							 
							$pitch_num = 0;
							$deal_num = 0;
							$list_data = '';
							$total_price = 0;
						    $total_paid = 0;
							$total_VIP = 0;
					
								if ($pitch->rowCount() > 0) {
									while($pitches = $pitch->fetch()){
										
											$content['users'][$user[0]]['labels'][$i] = $pitches['date_of_pitch'];
											$content['users'][$user[0]]['series'][0]['value'][$i] = $pitches['pitches'];
											$content['users'][$user[0]]['series'][0]['label'] = "Pitched";
											
											$content['users'][$user[0]]['series'][1]['value'][$i] = $pitches['deals'];
											$content['users'][$user[0]]['series'][1]['label'] = "Deals";
											
											//$content['users'][$user[0]]['series'][2]['value'][$i] = $pitches['price'];
											//$content['users'][$user[0]]['series'][2]['label'] = "Price";
											
											$content['users'][$user[0]]['series'][2]['value'][$i] = $pitches['Paid'];
											$content['users'][$user[0]]['series'][2]['label'] = "Paid";
											
											$content['users'][$user[0]]['series'][3]['value'][$i] = $pitches['VIP'];
											$content['users'][$user[0]]['series'][3]['label'] = "VIP";
											
											
											if ($pitches['Paid'] == '' || $pitches['Paid'] == NULL) {
												$pitches['Paid'] = 0;
											}
											
											if ($pitches['VIP'] == '' || $pitches['VIP'] == NULL) {
												$pitches['VIP'] = 0;
											}
											

											$username .= $user[1].',';
											$pitch_num += $pitches['pitches'];
										    $deal_num += $pitches['deals'];
											$total_price += $pitches['price'];
											$total_paid += $pitches['Paid'];
											$total_VIP += $pitches['VIP'];

											$i++;
										} //stat_q fetch
								}  //stat num row end
								            
											$output[$z]['name'] = $user[1];
											$output[$z]['pitch'] = $pitch_num;
											$output[$z]['deals'] = $deal_num;
											$output[$z]['price'] = $total_price;
											$output[$z]['paid'] = $total_paid;
											$output[$z]['vip'] = $total_VIP;
							
											$all_pitch_num += $pitch_num;
										    $all_deal_num += $deal_num;
											$all_total_price += $total_price;
											$all_total_paid += $total_paid;
											$all_total_VIP += $total_VIP;
							
					                    $z++;

					
					}//user while
				}//user num row
				
                                        /*
											$output[$z]['name'] = "Total";
											$output[$z]['pitch'] = $all_pitch_num;
											$output[$z]['deals'] = $all_deal_num;
											$output[$z]['price'] = $all_total_price;
											$output[$z]['paid'] = $all_total_paid;
											$output[$z]['vip'] = $all_total_VIP;
											
											*/
											
		$k = 0;	
		$content['labels'] = array();
		$content['series'] = array();
		
		//data for the total numbers (total pitch etc)
		$pitches = array();	
		$deals = array();	
		//$price = array();
		$paid = array();
		$vip = array();						
	  foreach ($output as $data) {
		    array_push($content['labels'], $data['name']);
			
           array_push($pitches, $data['pitch']);
		   array_push($deals, $data['deals']);
		   //array_push($price, $data['price']);
		   array_push($paid, $data['paid']);
		   array_push($vip, $data['vip']);
		}
							

			$pushdata['label'] = "Pitched";
			$pushdata['value'] = $pitches;
			
			array_push($content['series'], $pushdata);
			
			$pushdata['label'] = "Deals";
			$pushdata['value'] = $deals;
			
			array_push($content['series'], $pushdata);
			
			//$pushdata['label'] = "Price";
			//$pushdata['value'] = $price;
			
			//array_push($content['series'], $pushdata);
			
			$pushdata['label'] = "Paid";
			$pushdata['value'] = $paid;
			
			array_push($content['series'], $pushdata);
			
			$pushdata['label'] = "VIP";
			$pushdata['value'] = $vip;
			
			array_push($content['series'], $pushdata);

	return json_encode($content);	
	 
	 
	 
 }


/*
------------
Graph Data Intervall
---------
*/
 public function get_analytics_graph_data_intervall($date, $country, $teams) {
	 $content = '';
	  $all_pitch_num = 0;
	  $all_deal_num = 0;
	  $all_list_data = '';
	  $all_total_price = 0;
	  $all_total_paid = 0;
	  $all_total_VIP = 0;
	  $username = '';
	  $z = 0;
	  
	  	  if(isset($_SESSION['admin']) && $_SESSION['admin'] > 1) {
		  unset($teams);
		  $teams[0] = $_SESSION['team_id'];
	  }
	 
	 	$users_q = "SELECT u.id, u.username FROM users as u, user_team_connection as utc WHERE u.id=utc.user_id AND u.rank <> 0 AND u.rank <> 5";
					if (isset($teams[0]) && $teams[0] != 'All' && $teams[0] != '') {
						$teamid = -1;
					  foreach ($teams as $team) {
							$teamid ++;
							if ($teamid == 0){
								$plus_q = ' AND (utc.team_id= :tid'.$teamid;
							} else {
								$plus_q .= ' OR utc.team_id= :tid'.$teamid;
							}
						   
						   
					  }
					  $plus_q .= ')';
					  $users_q .= $plus_q;
				  }
				  
		
		 $users = $this->pdo->prepare($users_q);
		 
						  
				  
				if (isset($teams[0]) && $teams[0] != 'All' && $teams[0] != '') {
						$teamid = -1;
					  foreach ($teams as $team) {
						  $teamid++;
							$users->bindValue(':tid'.$teamid, $team, \PDO::PARAM_INT);
						   
					  }
					  
				  }
					 
		 
		 
		 $users->execute();
		 
				if ($users->rowCount() > 0) {
					while($user = $users->fetch()) {
							
							$datestuff = explode(',',$date);
							if (isset($datestuff[1]) && isset($datestuff[2])){
								$days = explode('-', $datestuff[1]);
								
								if (!isset($year)) {
								 $year = '2015';	
								}
								
								$first = $year.'-'.$datestuff[2].'-'.$days[0];
								$last = $year.'-'.$datestuff[2].'-'.$days[1];

						
							$data[0][0] = '';
					
							$i = 0;
							$plus_q = '';
							$s = 0;
							
							
							//Get basic date about a sponsors
											   //pitch
							$pitch_q = "SELECT COUNT(pd.id) as pitches, SUM(pr.deals) as deals, SUM(pr.price) as price, pd.date_of_pitch, (SELECT SUM(prt.deals) FROM pitch_result as prt, pitch_data as pdt WHERE prt.result_type_id=3 AND pdt.user_id=u.id AND pdt.id=prt.pitch_data_id AND pdt.date_of_pitch=pd.date_of_pitch) as Paid, (SELECT SUM(prt.deals) FROM pitch_result as prt, pitch_data as pdt WHERE prt.result_type_id=4 AND pdt.user_id=u.id AND pdt.id=prt.pitch_data_id AND pdt.date_of_pitch=pd.date_of_pitch) as VIP FROM users as u, pitch_data as pd, pitch_result as pr, countries as co, delegate_connection as delc WHERE u.id= :id AND u.rank <> 0 AND u.rank <> 5 AND u.id=pd.user_id AND pr.pitch_data_id=pd.id AND pd.delegate_id=delc.delegate_id AND delc.country_id=co.id AND pd.date BETWEEN :start AND :end";	
							
							 //if we filter (;)) based on country
						  //-------------------------------
						  if (isset($country) && $country != -1 && $country != '') {
							 $plus_q = ' AND co.id= :country';
						
						     $pitch_q .= $plus_q;
							  
						  }
					

						$pitch_q .=' GROUP BY pd.date_of_pitch ORDER BY pd.date';
						
					//echo $pitch_q;
						
							$pitch = $this->pdo->prepare($pitch_q);
							$pitch->bindValue(':id', $user[0], \PDO::PARAM_INT);
							$pitch->bindValue(':start', $first, \PDO::PARAM_STR);
							$pitch->bindValue(':end', $last, \PDO::PARAM_STR);
							 if (isset($country) && $country != -1 && $country != '') {
							   $pitch->bindValue(':country', $country, \PDO::PARAM_INT);
							 }
							

							 $pitch->execute();
							 
							$pitch_num = 0;
							$deal_num = 0;
							$list_data = '';
							$total_price = 0;
						    $total_paid = 0;
							$total_VIP = 0;
					
								if ($pitch->rowCount() > 0) {
									while($pitches = $pitch->fetch()){
										
											$content['users'][$user[0]]['labels'][$i] = $pitches['date_of_pitch'];
											$content['users'][$user[0]]['series'][0]['value'][$i] = $pitches['pitches'];
											$content['users'][$user[0]]['series'][0]['label'] = "Pitched";
											
											$content['users'][$user[0]]['series'][1]['value'][$i] = $pitches['deals'];
											$content['users'][$user[0]]['series'][1]['label'] = "Deals";
											
											//$content['users'][$user[0]]['series'][2]['value'][$i] = $pitches['price'];
											//$content['users'][$user[0]]['series'][2]['label'] = "Price";
											
											$content['users'][$user[0]]['series'][2]['value'][$i] = $pitches['Paid'];
											$content['users'][$user[0]]['series'][2]['label'] = "Paid";
											
											$content['users'][$user[0]]['series'][3]['value'][$i] = $pitches['VIP'];
											$content['users'][$user[0]]['series'][3]['label'] = "VIP";


											$username .= $user[1].',';
											$pitch_num += $pitches['pitches'];
										    $deal_num += $pitches['deals'];
											$total_price += $pitches['price'];
											$total_paid += $pitches['Paid'];
											$total_VIP += $pitches['VIP'];

											$i++;
										} //stat_q fetch
								}  //stat num row end
								            
											$output[$z]['name'] = $user[1];
											$output[$z]['pitch'] = $pitch_num;
											$output[$z]['deals'] = $deal_num;
											$output[$z]['price'] = $total_price;
											$output[$z]['paid'] = $total_paid;
											$output[$z]['vip'] = $total_VIP;
							
											$all_pitch_num += $pitch_num;
										    $all_deal_num += $deal_num;
											$all_total_price += $total_price;
											$all_total_paid += $total_paid;
											$all_total_VIP += $total_VIP;
							
					                    $z++;

								
							}//if isset($datestuff[1]) && isset($datestuff[2])

					 
						

						
					
					}//user while
				}//user row count
				
										    $all_pitch_num += $pitch_num;
										    $all_deal_num += $deal_num;
											$all_total_price += $total_price;
											$all_total_paid += $total_paid;
											$all_total_VIP += $total_VIP;
							
	
											$output[$z]['name'] = "Total";
											$output[$z]['pitch'] = $all_pitch_num;
											$output[$z]['deals'] = $all_deal_num;
											$output[$z]['price'] = $all_total_price;
											$output[$z]['paid'] = $all_total_paid;
											$output[$z]['vip'] = $all_total_VIP;
											
		$k = 0;	
		$content['labels'] = array();
		$content['series'] = array();
		
		$pitches = array();	
		$deals = array();	
		//$price = array();
		$paid = array();
		$vip = array();						
	  foreach ($output as $data) {
		    array_push($content['labels'], $data['name']);
			
           array_push($pitches, $data['pitch']);
		   array_push($deals, $data['deals']);
		 //  array_push($price, $data['price']);
		   array_push($paid, $data['paid']);
		   array_push($vip, $data['vip']);
		}
							

			$pushdata['label'] = "Pitched";
			$pushdata['value'] = $pitches;
			
			array_push($content['series'], $pushdata);
			
			$pushdata['label'] = "Deals";
			$pushdata['value'] = $deals;
			
			array_push($content['series'], $pushdata);
			
			//$pushdata['label'] = "Price";
			//$pushdata['value'] = $price;
			
			//array_push($content['series'], $pushdata);
			
			$pushdata['label'] = "Paid";
			$pushdata['value'] = $paid;
			
			array_push($content['series'], $pushdata);
			
			$pushdata['label'] = "VIP";
			$pushdata['value'] = $vip;
			
			array_push($content['series'], $pushdata);

	return json_encode($content);	
	 
	 
	 
 }

/*
-------------------------
Delegates Data
-------------------------
*/	


/*
///////For DEV Purposes:

SELECT utc.team_id, COUNT(pd.id) as pitches, SUM(pr.deals) as deals, SUM(pr.price) as price, pd.date_of_pitch, (SELECT COUNT(prt.result_type_id) FROM pitch_result as prt, pitch_data as pdt WHERE prt.result_type_id=3 AND pdt.user_id=u.id AND pdt.id=prt.pitch_data_id AND pdt.date_of_pitch=pd.date_of_pitch) as Paid, (SELECT COUNT(prt.result_type_id) FROM pitch_result as prt, pitch_data as pdt WHERE prt.result_type_id=4 AND pdt.user_id=u.id AND pdt.id=prt.pitch_data_id AND pdt.date_of_pitch=pd.date_of_pitch) as VIP FROM users as u, pitch_data as pd, pitch_result as pr, countries as co, delegate_connection as delc, user_team_connection as utc WHERE u.id=pd.user_id AND u.id=utc.user_id AND pr.pitch_data_id=pd.id AND pd.delegate_id=delc.delegate_id AND delc.country_id=co.id GROUP BY pd.date_of_pitch, utc.team_id ORDER BY pd.date

This is a query for getting data grouped by teams rather than individual users

*/


 public function get_analytics_data($date, $country, $teams) {
	 $content = '';
	  $all_pitch_num = 0;
	  $all_deal_num = 0;
	  $all_list_data = '';
	  $all_total_price = 0;
	  $all_total_paid = 0;
	  $all_total_VIP = 0;
	  
	  								 $content .= '
							  <!--The main wrapper -->
							  <div id="AnalyticsWrapper">';
	  
	  					$content .= '
 
							  <!-- User container-->
								  <div id="AllHeader" class="UserContainer">
								    <div >
									  <div class="NameHeader">Team member</div>
									  <div class="PitchHeader">Pitches</div>
									  <div class="DealHeader">Deals</div>
									  <div class="EarnedHeader">Earned</div>
									  <div class="PaidHeader">Paid</div>
									  <div class="VIPHeader">VIP</div>
									 </div> 								
								  </div>							 
									 ';	
	  
	  	  if(isset($_SESSION['admin']) && $_SESSION['admin'] > 1) {
		  unset($teams);
		  $teams[0] = $_SESSION['team_id'];
	  }
	  
	 
	 	$users_q = "SELECT u.id, u.username FROM users as u, user_team_connection as utc WHERE u.id=utc.user_id AND u.rank <> 0 AND u.rank <> 5";
					if (isset($teams[0]) && $teams[0] != 'All' && $teams[0] != '') {
						$teamid = -1;
					  foreach ($teams as $team) {
							$teamid ++;
							if ($teamid == 0){
								$plus_q = ' AND (utc.team_id= :tid'.$teamid;
							} else {
								$plus_q .= ' OR utc.team_id= :tid'.$teamid;
							}
						   
						   
					  }
					  $plus_q .= ')';
					  $users_q .= $plus_q;
				  }
				  
		
		 $users = $this->pdo->prepare($users_q);
		 
						  
				  
					if (isset($teams[0]) && $teams[0] != 'All' && $teams[0] != '') {
						$teamid = -1;
					  foreach ($teams as $team) {
						  $teamid++;
							$users->bindValue(':tid'.$teamid, $team, \PDO::PARAM_INT);
						   
					  }
					  
				  }
					 
		 
		 
		 $users->execute();
		 
				if ($users->rowCount() > 0) {
					while($user = $users->fetch()) {

						
							$data[0][0] = '';
					
							$i = 0;
							$plus_q = '';
							$s = 0;
							
							//Get basic date about a sponsors
											   //pitch
							$pitch_q = "SELECT COUNT(pd.id) as pitches, SUM(pr.deals) as deals, SUM(pr.price) as price, pd.date_of_pitch, (SELECT SUM(prt.deals) FROM pitch_result as prt, pitch_data as pdt WHERE prt.result_type_id=3 AND pdt.user_id=u.id AND pdt.id=prt.pitch_data_id AND pdt.date_of_pitch=pd.date_of_pitch) as Paid, (SELECT SUM(prt.deals) FROM pitch_result as prt, pitch_data as pdt WHERE prt.result_type_id=4 AND pdt.user_id=u.id AND pdt.id=prt.pitch_data_id AND pdt.date_of_pitch=pd.date_of_pitch) as VIP FROM users as u, pitch_data as pd, pitch_result as pr, countries as co, delegate_connection as delc, user_team_connection as utc WHERE u.id= :id AND u.rank <> 0 AND u.rank <> 5 AND u.id=pd.user_id AND u.id=utc.user_id AND pr.pitch_data_id=pd.id AND pd.delegate_id=delc.delegate_id AND delc.country_id=co.id";	
					        //if we (nem jut eszembe, szóval) dátum alapján szűrünk .. oh.. filter.. damn
							//-----------------------------------------
 					    if (isset($date) && $date != '' && $date != 'All') {
							$plus_q = ' AND pd.date_of_pitch LIKE :pitch';
						
						     $pitch_q .= $plus_q;
					
						  }
						  
						  
						  //if we filter (;)) based on country
						  //-------------------------------
						  if (isset($country) && $country != -1 && $country != '') {
							 $plus_q = ' AND co.id= :country';
						
						     $pitch_q .= $plus_q;
							  
						  }
						  
						  
						$pitch_q .=' GROUP BY pd.date_of_pitch ORDER BY pd.date';
						
					
						
							$pitch = $this->pdo->prepare($pitch_q);
							$pitch->bindValue(':id', $user[0], \PDO::PARAM_INT);
							

							 if (isset($date) && $date != ''  && $date != 'All') {
							   $pitch->bindValue(':pitch', $date, \PDO::PARAM_STR);
							 }
							 
							 if (isset($country) && $country != -1 && $country != '') {
							   $pitch->bindValue(':country', $country, \PDO::PARAM_INT);
							 }
							 

							
							 
							 $pitch->execute();
							 
							$pitch_num = 0;
							$deal_num = 0;
							$list_data = '';
							$total_price = 0;
						    $total_paid = 0;
							$total_VIP = 0;
					
					
										if ($pitch->rowCount() > 0) {
										while($pitches = $pitch->fetch()){
											$data[$i][0] = $pitches['date_of_pitch'];
											$data[$i][1] = $pitches['pitches'];
											$data[$i][2] = $pitches['deals'];
											$data[$i][3] = $pitches['price'];
											
											if (!isset($pitches['Paid']) || $pitches['Paid'] == '' || $pitches['Paid'] == NULL ) {
                                                $pitches['Paid'] = 0;
											} 
											
											$data[$i][4] = $pitches['Paid'];
											
											if (!isset($pitches['VIP']) || $pitches['VIP'] == '' || $pitches['VIP'] == NULL) {
												$pitches['VIP'] = 0;
											}									
										
											$data[$i][5] = $pitches['VIP'];	
											
											$list_data .='<li>
											<div class="PitchDate">'.$pitches['date_of_pitch'].'</div> 
											<div class="PitchNum">'.$pitches['pitches'].'</div>
											<div class="DealNum">'.$pitches['deals'].'</div>
											<div class="Price">'.$pitches['price'].'</div>
											<div class="Paid">'.$pitches['Paid'].'</div>
											<div class="VIP">'.$pitches['VIP'].'</div>
										 </li>';
											
											$pitch_num += $pitches['pitches'];
										    $deal_num += $pitches['deals'];
											$total_price += $pitches['price'];
											$total_paid += $pitches['Paid'];
											$total_VIP += $pitches['VIP'];

											$i++;
										} //stat_q fetch
								}  //stat num row end
								
							
											$all_pitch_num += $pitch_num;
										    $all_deal_num += $deal_num;
											$all_total_price += $total_price;
											$all_total_paid += $total_paid;
											$all_total_VIP += $total_VIP;
							
					
								 $content .= '
							  <!-- User container-->
								  <div class="UserContainer" data-unum="'.$user[0].'" onClick="container_display(this);">
								    <div class="TotalContainer">
									  <div class="UserName">'.$user[1].'</div>
									  <div class="TotalPitchNum">'.$pitch_num.'</div>
									  <div class="TotalDealNum">'.$deal_num.'</div>
									  <div class="TotalPrice">'.$total_price.'</div>
									  <div class="TotalPaid">'.$total_paid.'</div>
									  <div class="TotalVIP">'.$total_VIP.'</div>
									 </div> 
									  <ul class="Dates">
									  '.$list_data.'

									 </ul>
								
								  </div>							 
									 ';	
							
							
								


					 
						

						
					
					}
				}
				
							$content .= '	 
							  <!-- User container-->
								  <div id="AllTotal" class="UserContainer">
								    <div class="TotalContainer">
									  <div class="UserName">Total</div>
									  <div class="TotalPitchNum">'.$all_pitch_num.'</div>
									  <div class="TotalDealNum">'.$all_deal_num.'</div>
									  <div class="TotalPrice">'.$all_total_price.'</div>
									  <div class="TotalPaid">'.$all_total_paid.'</div>
									  <div class="TotalVIP">'.$all_total_VIP.'</div>
									 </div> 								
								  </div>							 
									 ';		
				 
				 $content .= '</div>';


	return $content;	
	 
	 
	 
 }
 
/*
--------------------------------
Data Intervall
---------------------------
*/ 
  public function get_analytics_data_intervall($date, $country, $teams) {
	 $content = '';
	 $year = date('Y');
	  $all_pitch_num = 0;
	  $all_deal_num = 0;
	  $all_list_data = '';
	  $all_total_price = 0;
	  $all_total_paid = 0;
	  $all_total_VIP = 0;
	  
	  	  if(isset($_SESSION['admin']) && $_SESSION['admin'] > 1) {
		  unset($teams);
		  $teams[0] = $_SESSION['team_id'];
	  }
	  
	  
	  								 $content .= '
							  <!--The main wrapper -->
							  <div id="AnalyticsWrapper">';
							  
							  
							  	  					$content .= '
 
							  <!-- User container-->
								  <div id="AllHeader" class="UserContainer">
								    <div >
									  <div class="NameHeader">Team member</div>
									  <div class="PitchHeader">Pitches.</div>
									  <div class="DealHeader">Deals</div>
									  <div class="EarnedHeader">Earned</div>
									  <div class="PaidHeader">Paid</div>
									  <div class="VIPHeader">VIP</div>
									 </div> 								
								  </div>							 
									 ';	
	  
	 
	 	$users_q = "SELECT u.id, u.username FROM users as u, user_team_connection as utc WHERE u.id=utc.user_id AND u.rank <> 0 AND u.rank <> 5";
					if (isset($teams[0]) && $teams[0] != 'All' && $teams[0] != '') {
						$teamid = -1;
					  foreach ($teams as $team) {
							$teamid ++;
							if ($teamid == 0){
								$plus_q = ' AND (utc.team_id= :tid'.$teamid;
							} else {
								$plus_q .= ' OR utc.team_id= :tid'.$teamid;
							}
						   
						   
					  }
					  $plus_q .= ')';
					  $users_q .= $plus_q;
				  }
				  
		
		 $users = $this->pdo->prepare($users_q);
		 
						  
				  
					if (isset($teams[0]) && $teams[0] != 'All' && $teams[0] != '') {
						$teamid = -1;
					  foreach ($teams as $team) {
						  $teamid++;
							$users->bindValue(':tid'.$teamid, $team, \PDO::PARAM_INT);
						   
					  }
					  
				  }
					 
		 
		 
		 $users->execute();
		 
				if ($users->rowCount() > 0) {
					while($user = $users->fetch()) {
							
							$datestuff = explode(',',$date);
							if (isset($datestuff[1]) && isset($datestuff[2])){
								$days = explode('-', $datestuff[1]);
								
								$first = $year.'-'.$datestuff[2].'-'.$days[0];
								$last = $year.'-'.$datestuff[2].'-'.$days[1];

						
							$data[0][0] = '';
					
							$i = 0;
							$plus_q = '';
							$s = 0;
							
							
							//Get basic date about a sponsors
											   //pitch
							$pitch_q = "SELECT COUNT(pd.id) as pitches, SUM(pr.deals) as deals, SUM(pr.price) as price, pd.date_of_pitch, (SELECT SUM(prt.deals) FROM pitch_result as prt, pitch_data as pdt WHERE prt.result_type_id=3 AND pdt.user_id=u.id AND pdt.id=prt.pitch_data_id AND pdt.date_of_pitch=pd.date_of_pitch) as Paid, (SELECT SUM(prt.prt.deals) FROM pitch_result as prt, pitch_data as pdt WHERE prt.result_type_id=4 AND pdt.user_id=u.id AND pdt.id=prt.pitch_data_id AND pdt.date_of_pitch=pd.date_of_pitch) as VIP FROM users as u, pitch_data as pd, pitch_result as pr, countries as co, delegate_connection as delc WHERE u.id= :id AND u.rank <> 0 AND u.rank <> 5 AND u.id=pd.user_id AND pr.pitch_data_id=pd.id AND pd.delegate_id=delc.delegate_id AND delc.country_id=co.id AND pd.date BETWEEN :start AND :end";	
							
							 //if we filter (;)) based on country
						  //-------------------------------
						  if (isset($country) && $country != -1 && $country != '') {
							 $plus_q = ' AND co.id= :country';
						
						     $pitch_q .= $plus_q;
							  
						  }
					

						$pitch_q .=' GROUP BY pd.date_of_pitch ORDER BY pd.date';
						
					//echo $pitch_q;
						
							$pitch = $this->pdo->prepare($pitch_q);
							$pitch->bindValue(':id', $user[0], \PDO::PARAM_INT);
							$pitch->bindValue(':start', $first, \PDO::PARAM_STR);
							$pitch->bindValue(':end', $last, \PDO::PARAM_STR);
							 if (isset($country) && $country != -1 && $country != '') {
							   $pitch->bindValue(':country', $country, \PDO::PARAM_INT);
							 }
							

							 $pitch->execute();
							 
							$pitch_num = 0;
							$deal_num = 0;
							$list_data = '';
							$total_price = 0;
							$total_paid = 0;
							$total_VIP = 0;
					
								if ($pitch->rowCount() > 0) {
										while($pitches = $pitch->fetch()){
											$data[$i][0] = $pitches['date_of_pitch'];
											$data[$i][1] = $pitches['pitches'];
											$data[$i][2] = $pitches['deals'];
											$data[$i][3] = $pitches['price'];
											if (!isset($pitches['Paid']) || $pitches['Paid'] != '' || $pitches['Paid'] != NULL ) {
                                                $pitches['Paid'] = 0;
											} 
											
											$data[$i][4] = $pitches['Paid'];
											
											if (!isset($pitches['VIP']) || $pitches['VIP'] == '' || $pitches['VIP'] == NULL) {
												$pitches['VIP'] = 0;
											}									
										
											$data[$i][5] = $pitches['VIP'];	
											
											$list_data .='<li>
											<div class="PitchDate">'.$pitches['date_of_pitch'].'</div> 
											<div class="PitchNum">'.$pitches['pitches'].'</div>
											<div class="DealNum">'.$pitches['deals'].'</div>
											<div class="Price">'.$pitches['price'].'</div>
											<div class="Paid">'.$pitches['Paid'].'</div>
											<div class="VIP">'.$pitches['VIP'].'</div>
										 </li>';
											
											$pitch_num += $pitches['pitches'];
										    $deal_num += $pitches['deals'];
											$total_price += $pitches['price'];
											$total_paid += $pitches['Paid'];
											$total_VIP += $pitches['VIP'];
											


											$i++;
										} //stat_q fetch
								}  //stat num row end
								
										    $all_pitch_num += $pitch_num;
										    $all_deal_num += $deal_num;
											$all_total_price += $total_price;
											$all_total_paid += $total_paid;
											$all_total_VIP += $total_VIP;
							
					
								 $content .= '								 
							  <!-- User container-->
								  <div class="UserContainer" data-unum="'.$user[0].'" onClick="container_display(this);">
								    <div class="TotalContainer">
									  <div class="UserName">'.$user[1].'</div>
									  <div class="TotalPitchNum">'.$pitch_num.'</div>
									  <div class="TotalDealNum">'.$deal_num.'</div>
									  <div class="TotalPrice">'.$total_price.'</div>
									  <div class="TotalPaid">'.$total_paid.'</div>
									  <div class="TotalVIP">'.$total_VIP.'</div>
									 </div> 
									  <ul class="Dates">
									  '.$list_data.'

									 </ul>
								
								  </div>							 
									 ';	
							
							
								
							}

					 
						

						
					
					}
				}
				
											 $content .= '
							  <!-- User container-->
								  <div id="AllTotal" class="UserContainer">
								    <div class="TotalContainer">
									  <div class="UserName">Total</div>
									  <div class="TotalPitchNum">'.$all_pitch_num.'</div>
									  <div class="TotalDealNum">'.$all_deal_num.'</div>
									  <div class="TotalPrice">'.$all_total_price.'</div>
									  <div class="TotalPaid">'.$all_total_paid.'</div>
									  <div class="TotalVIP">'.$all_total_VIP.'</div>
									 </div> 								
								  </div>							 
									 ';		 
				 
$content .= '</div>';

	return $content;	
	 
	 
	 
 }

public function get_callbacks($admin) {
	
	$content = '';
	$selected = '';
	
	  $content = '<option value="" hidden="hidden" selected="selected">Select a Call Back Date</option>'; 
       $content .= '<option value="All">All</option>'; 
	 
		 $country_q = "SELECT pr.callback_date FROM pitch_result as pr, pitch_data as pd, user_team_connection as utc, users as u WHERE pd.id=pr.pitch_data_id AND pd.user_id=utc.user_id AND utc.user_id=u.id AND u.rank <> 0 AND u.rank <> 5";
		 if ($admin > 2) {
			 $country_q .= " AND pd.user_id= :id";
		 }
		 
		 if ($admin == 2) {
			 $country_q .= " AND utc.team_id= :team_id";
		 }
		 
		 $country_q .= " GROUP BY callback_date";
		 
		 $countries = $this->pdo->prepare($country_q);
		  if ($admin > 2) {
			 $countries->bindValue(':id', $admin, \PDO::PARAM_INT);
		 }
		 
		  if ($admin == 2) {
			 $countries->bindValue(':team_id', $_SESSION['team_id'], \PDO::PARAM_INT);
		 }
		 $countries->execute();
		 
					if ($countries->rowCount() > 0) {
					while($country = $countries->fetch()){
						
	                          if ($country['callback_date'] != '') {
								   $content .= '<option value="'.$country['callback_date'].'">'.$country['callback_date'].'</option>';
							  }

                        
						 

					} //personal fetch assoc end
				}  //personal num rows if end
				
        /* $content .= '<option value="Other">Other</option>';*/
	return $content;	
}



public function get_analytics_filters() {
	//displays the day of a specific date (now is set to today)
//echo jddayofweek ( cal_to_jd(CAL_GREGORIAN, date("m"),date("d"), date("Y")) , 0 ); 

	
}


public function get_team_members($team_id) {
	
	$content = '';
	$selected = '';
	
	  $content = '<option value="" hidden="hidden" selected="selected">Select a Team Member</option>'; 
       $content .= '<option value="All">All</option>'; 
	 
		 $country_q = "SELECT u.id, u.username FROM users as u, user_team_connection as utc WHERE u.id=utc.user_id AND u.rank <> 0 AND u.rank <> 5";
		 
		 if ($_SESSION['admin'] == 2 || (isset($team_id) && $team_id != '')) {
			$country_q .= ' AND utc.team_id = :id'; 
		 }

		 
		 $countries = $this->pdo->prepare($country_q);
		 
		 
		 if ($_SESSION['admin'] == 2) {
			  $countries->bindValue(':id', $_SESSION['team_id'], \PDO::PARAM_INT);
		 } else {
			 if (isset($team_id) && $team_id != ''){
				 $countries->bindValue(':id', $team_id, \PDO::PARAM_INT);
			 }
			 
		 }


		 $countries->execute();
		 
					if ($countries->rowCount() > 0) {
					while($country = $countries->fetch()){
						
	                          if ($country['username'] != '') {
								   $content .= '<option value="'.$country['id'].'">'.$country['username'].'</option>';
							  }

                        
						 

					} //personal fetch assoc end
				}  //personal num rows if end
				
        /* $content .= '<option value="Other">Other</option>';*/
	return $content;	
}


public function get_teams() {
	
	$content = '';
	$selected = '';
	
	  $content = '<option value="" hidden="hidden" selected="selected">Select a Team</option>'; 
	  		       if ($_SESSION['admin'] < 2) {
					   $content .= '<option value="All">All</option>'; 
				   }
     
	 
		 $country_q = "SELECT id, team_name FROM teams";
		           if ($_SESSION['admin'] > 1) {
					  $country_q .= " WHERE id = :id"; 
				   }

		 $countries = $this->pdo->prepare($country_q);
		 
		 
		           if ($_SESSION['admin'] > 1) {
					 $countries->bindValue(':id', $_SESSION['team_id'], \PDO::PARAM_INT);
				   }

		 $countries->execute();
		 
					if ($countries->rowCount() > 0) {
					while($country = $countries->fetch()){
						
	                          if ($country['team_name'] != '') {
								   $content .= '<option value="'.$country['id'].'">'.$country['team_name'].'</option>';
							  }

                        
						 

					} //personal fetch assoc end
				}  //personal num rows if end
				
        /* $content .= '<option value="Other">Other</option>';*/
	return $content;	
}


public function get_teams_new_user() {
	
	$content = '';
	$selected = '';
	
	  $content = '<option value="" hidden="hidden" selected="selected">Select a Team</option>'; 

	 
		 $country_q = "SELECT id, team_name FROM teams";
		           if ($_SESSION['admin'] > 1) {
					  $country_q .= " WHERE id = :id"; 
				   }

		 $countries = $this->pdo->prepare($country_q);
		 
		 
		           if ($_SESSION['admin'] > 1) {
					 $countries->bindValue(':id', $_SESSION['team_id'], \PDO::PARAM_INT);
				   }

		 $countries->execute();
		 
					if ($countries->rowCount() > 0) {
					while($country = $countries->fetch()){
						
	                          if ($country['team_name'] != '') {
								   $content .= '<option value="'.$country['id'].'">'.$country['team_name'].'</option>';
							  }

                        
						 

					} //personal fetch assoc end
				}  //personal num rows if end
				
        /* $content .= '<option value="Other">Other</option>';*/
	return $content;	
}

/*
-------------------
Calls functions
-------------------
*/

public function list_calls($user) {
	$content = '';
	$plus_content = 0;
	$username = '';
	if (isset($user) && $user != '' && $user != 'All') {
		$plus_content = 1;
		$content .= '<div style="width:450px; display:inline-block;" id="CallsContainer">';
	}else {
	    $content .= $this->list_calls_teams($_SESSION['team_id']);
		return $content;
		break;
	}
	
	
		$users_q = "SELECT id, username FROM users WHERE rank <> 0 AND u.rank <> 5";
		if (isset($user) && $user != '' && $user != 'All') {
			$users_q .= " AND id= :id";
		}
		
		 $users = $this->pdo->prepare($users_q);
		 if (isset($user) && $user != '' && $user != 'All') {
			 $users->bindValue(':id', $user, \PDO::PARAM_INT);
		}
		 
		 $users->execute();
		 
				if ($users->rowCount() > 0) {
					while($user = $users->fetch()) {
						$username = $user['username'];
						$user_id = $user['id'];
						
						$content .='<div class="UserContainer">';
						
					 $content .='<div class="UserName"><h3>'.$user['username'].'</h3>';
					 $content .= '</div>';
						  
						$content .='<div class="DaysContainer">';
						   $content .='<ul>';
						   		$content .='<li>
							     <div class="DateLi">Date</div>
								 <div>Calls</div>
								 <div>Minutes</div>
								 <div>Pitches</div>
								 <div>Deals</div>
							        </li>';
									
								
		   $dates_q = "SELECT DATE(uc.date) as Date, pd.date_of_pitch FROM user_calls as uc, pitch_data as pd WHERE DATE(uc.date)=DATE(pd.date) GROUP BY DATE(uc.date) ORDER BY uc.date DESC";
		   $dates = $this->pdo->prepare($dates_q);
		   $dates->execute();
								
				if ($dates->rowCount() > 0) {
					
					while($date = $dates->fetch()) {
						
						 $data_q = "SELECT uc.calls as Calls, uc.minutes, SUM(pr.deals) as Deals, COUNT(pd.id) as Pitches, pd.date_of_pitch as Date FROM user_calls as uc, pitch_result as pr, pitch_data as pd WHERE pd.user_id= :user_id AND pd.user_id=uc.user_id AND pr.pitch_data_id=pd.id AND DATE(pd.date)=DATE(uc.date) AND DATE(pd.date)= :date GROUP BY uc.call_date ORDER BY uc.date DESC";
						 
						 $data = $this->pdo->prepare($data_q);
						 $data->bindValue(':user_id', $user['id'], \PDO::PARAM_INT);
						 $data->bindValue(':date', $date['Date'], \PDO::PARAM_STR);
						 $data->execute();
						 	
							if ($data->rowCount() > 0) {
					
					            while($info = $data->fetch()) {
								   $content .='<li>
									 <div class="DateLi">'.$info['Date'].'</div>
									 <div>'.$info['Calls'].'</div>
									 <div>'.$info['minutes'].'</div>
									 <div>'.$info['Pitches'].'</div>
									 <div>'.$info['Deals'].'</div>
										</li>';
									
									
								}//while info fetch
								
							} else {// if date row count
							
								$calls_q = "SELECT uc.calls, uc.minutes FROM user_calls as uc WHERE uc.user_id= :user_id AND DATE(uc.date)= :date";
								 
								 $calls = $this->pdo->prepare($calls_q);
								 $calls->bindValue(':user_id', $user['id'], \PDO::PARAM_INT);
								 $calls->bindValue(':date', $date['Date'], \PDO::PARAM_STR);
								 $calls->execute();
								 
								  if ($calls->rowCount() > 0) {
					
									  while($call = $calls->fetch()) {
										 $content .='<li>
										   <div class="DateLi">'.$date['date_of_pitch'].'</div>
										   <div>'.$call['calls'].'</div>
										   <div>'.$call['minutes'].'</div>
										   <div> - </div>
										   <div> - </div>
											  </li>';
										  
										  
									  }//while calls fetch
								
							     } else {//if calls row count
								 		 $content .='<li>
										   <div class="DateLi">'.$date['date_of_pitch'].'</div>
										   <div> - </div>
										   <div> - </div>
										   <div> - </div>
										   <div> - </div>
											  </li>';
								 
								 }//else ends (no calls row)

							} //else ends (no data row)
						
					}//while date
									
				}//if num row dates
								
									
									
						    $content .='</ul>';
						$content .= '</div>';//Days container ends
						
						$content .= '</div>';//user container ends
					} //while user
					
			   } //if users row count
		$content .= '</div>';
		
		if ($plus_content == 1) {
		$today = date('Y-m-d');	
			
			$today_q = "SELECT uc.calls, uc.minutes FROM user_calls as uc WHERE uc.user_id= :user_id AND DATE(uc.date)= :date";
			 
			 $calls = $this->pdo->prepare($today_q);
			 $calls->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
			 $calls->bindValue(':date', $today, \PDO::PARAM_STR);
			 $calls->execute();
			 
			  if ($calls->rowCount() > 0) {

				  $call = $calls->fetch();
			      $call_num = $call['calls'];
				  $min_num = $call['minutes'];
			 } else {
				$call_num = 0;
				$min_num = 0; 
			 }
			
			
			/*
			  $content .='	 	 
			   <div id="CallInputs">
				
				   <label>Calls made by '.$username.' today:<br /><input id="CallInputField" class="AdminInputField" type="text" placeholder="Call number" value="'.$call_num.'" /></label>
				   <button onClick="save_call_num('.$user_id.');" id="CallSubmit" class="AdminSubmitButton">Change</button>
				   
				   <label>Minutes spoken by '.$username.' today:<br /><input id="MinutesInputField" class="AdminInputField" type="text" placeholder="Call number" value="'.$min_num.'" /></label>
				   <button onClick="save_minutes_num('.$user_id.');" id="MinutesSubmit" class="AdminSubmitButton">Change</button>
			   </div>';
			   */
			   
		}
		
		
  return $content;			   

}


public function list_calls_teams($team_id) {
	$content = '';
	$plus_content = 0;
	$username = '';

	    $content .= '<div id="CallsContainer">';	

	
	
		$users_q = "SELECT u.id, u.username FROM users as u, user_team_connection as utc WHERE u.rank <> 0 AND u.rank <> 5";
		if (isset($team_id) && $team_id != '' && $team_id != 'All') {
			$users_q .= " AND utc.team_id= :id AND utc.user_id=u.id";
		}
		
		 $users = $this->pdo->prepare($users_q);
		 if (isset($team_id) && $team_id != '' && $team_id != 'All') {
			 $users->bindValue(':id', $team_id, \PDO::PARAM_INT);
		}
		 
		 $users->execute();
		 
				if ($users->rowCount() > 0) {
					while($user = $users->fetch()) {
						$username = $user['username'];
						$user_id = $user['id'];
						
						$content .='<div class="UserContainer">';
						
					 $content .='<div class="UserName"><h3>'.$user['username'].'</h3>';
					 
					 
					 						/* Call input field */
								$today = date('Y-m-d');	
			
			$today_q = "SELECT uc.calls, uc.minutes FROM user_calls as uc WHERE uc.user_id= :user_id AND DATE(uc.date)= :date";
			 
			 $calls = $this->pdo->prepare($today_q);
			 $calls->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
			 $calls->bindValue(':date', $today, \PDO::PARAM_STR);
			 $calls->execute();
			 
			  if ($calls->rowCount() > 0) {

				  $call = $calls->fetch();
			      $call_num = $call['calls'];
				  $min_num = $call['minutes'];
			 } else {
				$call_num = 0;
				$min_num = 0; 
			 }
			
			
		
			
			  $content .='	 	 
			   <div class="CallInputs">
				   <div class="CallContainer">
				     <label>Calls:<br /><input class="AdminInputField CallInputField" type="text" placeholder="Call number" value="'.$call_num.'" /></label>
				   </div>
				   
				   <div class="MinuteContainer">
				     <label>Minutes:<br /><input class="AdminInputField MinutesInputField" type="text" placeholder="Call number" value="'.$min_num.'" /></label>
				     <button data-userid="'.$user_id.'" class="AdminSubmitButton MinutesSubmit">Change</button>
				   </div>
			   </div>';
	
						
						
						/*call input fields end */
					 
					 
					 
					 
					 $content .= '</div>';
						  
						$content .='<div class="DaysContainer">';
						   $content .='<ul>';
						   		$content .='<li>
							     <div class="DateLi">Date</div>
								 <div>Calls</div>
								 <div>Minutes</div>
								 <div>Pitches</div>
								 <div>Deals</div>
							        </li>';
									
								
		   $dates_q = "SELECT DATE(uc.date) as Date, pd.date_of_pitch FROM user_calls as uc, pitch_data as pd WHERE DATE(uc.date)=DATE(pd.date) GROUP BY DATE(uc.date) ORDER BY uc.date DESC";
		   $dates = $this->pdo->prepare($dates_q);
		   $dates->execute();
								
				if ($dates->rowCount() > 0) {
					
					while($date = $dates->fetch()) {
						
						 $data_q = "SELECT uc.calls as Calls, uc.minutes, SUM(pr.deals) as Deals, COUNT(pd.id) as Pitches, pd.date_of_pitch as Date FROM user_calls as uc, pitch_result as pr, pitch_data as pd WHERE pd.user_id= :user_id AND pd.user_id=uc.user_id AND pr.pitch_data_id=pd.id AND DATE(pd.date)=DATE(uc.date) AND DATE(pd.date)= :date GROUP BY uc.call_date ORDER BY uc.date DESC";
						 
						 $data = $this->pdo->prepare($data_q);
						 $data->bindValue(':user_id', $user['id'], \PDO::PARAM_INT);
						 $data->bindValue(':date', $date['Date'], \PDO::PARAM_STR);
						 $data->execute();
						 	
							if ($data->rowCount() > 0) {
					
					            while($info = $data->fetch()) {
								   $content .='<li>
									 <div class="DateLi">'.$info['Date'].'</div>
									 <div>'.$info['Calls'].'</div>
									 <div>'.$info['minutes'].'</div>
									 <div>'.$info['Pitches'].'</div>
									 <div>'.$info['Deals'].'</div>
										</li>';
									
									
								}//while info fetch
								
							} else {// if date row count
							
								$calls_q = "SELECT uc.calls, uc.minutes FROM user_calls as uc WHERE uc.user_id= :user_id AND DATE(uc.date)= :date";
								 
								 $calls = $this->pdo->prepare($calls_q);
								 $calls->bindValue(':user_id', $user['id'], \PDO::PARAM_INT);
								 $calls->bindValue(':date', $date['Date'], \PDO::PARAM_STR);
								 $calls->execute();
								 
								  if ($calls->rowCount() > 0) {
					
									  while($call = $calls->fetch()) {
										 $content .='<li>
										   <div class="DateLi">'.$date['date_of_pitch'].'</div>
										   <div>'.$call['calls'].'</div>
										   <div>'.$call['minutes'].'</div>
										   <div> - </div>
										   <div> - </div>
											  </li>';
										  
										  
									  }//while calls fetch
								
							     } else {//if calls row count
								 		 $content .='<li>
										   <div class="DateLi">'.$date['date_of_pitch'].'</div>
										   <div> - </div>
										   <div> - </div>
										   <div> - </div>
										   <div> - </div>
											  </li>';
								 
								 }//else ends (no calls row)

							} //else ends (no data row)
						
					}//while date
									
				}//if num row dates
								
									
									
						    $content .='</ul>';
						$content .= '</div>';//Days container ends

						$content .= '</div>';//user container ends
					} //while user
					
			   } //if users row count
		$content .= '</div>';
		
		if ($plus_content == 1) {

			   
			   
		}
		
		
  return $content;			   

}



public function get_search_data() {
	$values = array();
		             
					    
			$category_q = "SELECT id, company_name FROM company GROUP BY company_name";	
					
		$category = $this->pdo->prepare($category_q);
		$category->execute();

			if ($category->rowCount() > 0) {
					while($cat = $category->fetch()){
						$ids = '';
	
					   $name_q = "SELECT id, company_name FROM company WHERE company_name= :name";	
									
						$name = $this->pdo->prepare($name_q);
						$name->bindValue(':name', $cat['company_name'], \PDO::PARAM_STR);
						$name->execute();
				
							if ($name->rowCount() > 0) {
									while($tags = $name->fetch()){
										$ids .= $tags['id'].',';
									}
							}

						
						$values[] = array('id' => $ids, 'name' => $cat['company_name']);
					}
			}
	
	


	return json_encode($values);
}
 
 
public function add_user(){
	include_once ('vendor/PasswordHash.php');
	
	if (isset($_POST['password']) && isset($_POST['member'])){
		
        $password = create_hash($_POST['password']);
		 $pass = explode(':', $password);   //2 and 3 needed
	     $end_pass = $pass[2].':'.$pass[3];
        
				 
	
	if ($_POST['member'] == 0){
	    //If a team leader	
		 $insert_call = "INSERT INTO users SET username = :username, password = :password, rank = '2'";
		 $call2 = $this->pdo->prepare($insert_call);
		 $call2->bindValue(':username', $_POST['username'], \PDO::PARAM_STR);
		 $call2->bindValue(':password', $end_pass, \PDO::PARAM_STR);
		 $call2->execute();
		 
		 
		 $user_id = $this->pdo->lastInsertId();
		 
		 $insert_team = "INSERT INTO teams SET team_name = :username";
		 $team = $this->pdo->prepare($insert_team);
		 $team->bindValue(':username', $_POST['username'], \PDO::PARAM_STR);
		 $team->execute(); 
		
		 $team_id = $this->pdo->lastInsertId();
		 
		 $insert_tc = "INSERT INTO user_team_connection SET user_id = :id, team_id = :team";
		 $team_connect = $this->pdo->prepare($insert_tc);
		 $team_connect->bindValue(':id', $user_id , \PDO::PARAM_INT);
		 $team_connect->bindValue(':team', $team_id, \PDO::PARAM_INT);
		 $team_connect->execute();  
		 
		 
	}
	
    if ($_POST['member'] == 1){
	    //If a regular user	
		
		 $insert_call = "INSERT INTO users SET username = :username, password = :password, rank = '1'";
		 $call2 = $this->pdo->prepare($insert_call);
		 $call2->bindValue(':username', $_POST['username'], \PDO::PARAM_STR);
		 $call2->bindValue(':password', $end_pass, \PDO::PARAM_STR);
		 $call2->execute();
		 
		 $user_id = $this->pdo->lastInsertId();
		 
		 $insert_tc = "INSERT INTO user_team_connection SET user_id = :id, team_id = :team";
		 $team_connect = $this->pdo->prepare($insert_tc);
		 $team_connect->bindValue(':id', $user_id , \PDO::PARAM_INT);
		 $team_connect->bindValue(':team', $_POST['team'], \PDO::PARAM_INT);
		 $team_connect->execute();
		
	}
	
	}
	return 'Success';
}

public function edit_user(){
	include_once ('vendor/PasswordHash.php');
	
	if (isset($_POST['sId'])){
		
		if(isset($_POST['new_pass']) && $_POST['new_pass'] != '-142HRDoge'){
			$password = create_hash($_POST['password']);
			 $pass = explode(':', $password);   //2 and 3 needed
			 $end_pass = $pass[2].':'.$pass[3];
			 
			 
			 $insert_call = "UPDATE users SET password = :password WHERE id= :id";
			 $call2 = $this->pdo->prepare($insert_call);
			 $call2->bindValue(':password', $end_pass, \PDO::PARAM_STR);
			 $call2->bindValue(':id', $_POST['sId'], \PDO::PARAM_STR);
			 $call2->execute();
		
		
		
		}

	
 }
 
 //Egyelőre a password change van lekezelve.. kell a többi is! 
	return 'Success';
}

public function motivation() {
	
	$current_month = date('m');
	$output = '';
	
	$data_q = "SELECT SUM(pr.deals) as Deals FROM pitch_result as pr, pitch_data as pd WHERE pd.user_id= :user_id AND pr.pitch_data_id=pd.id AND MONTH(pd.date)= :date";
						 
						 $data = $this->pdo->prepare($data_q);
						 $data->bindValue(':user_id', $_SESSION['user_id'], \PDO::PARAM_INT);
						 $data->bindValue(':date', $current_month, \PDO::PARAM_INT);
						 $data->execute();
						 	
							if ($data->rowCount() > 0) {
					
					            $info = $data->fetch();
								
								if ($info[0] < 1){
									$output ='<div id="MotivationalText"><h1>You have this many deals: <span id="MotivationalSpan">0</span></h1></div>';
								}else {
								    $output ='<div id="MotivationalText"><h1>You have this many deals: <span id="MotivationalSpan">'.$info[0].'</span></h1></div>';	
								}
								
								
								
							} else {
								$output ='<div id="MotivationalText"><h1>You have this many deals: <span id="MotivationalSpan">0</span></h1></div>';
							}
							
						return $output;	
	
}

public function get_user_list() {
	$content = '';
		 $users_q = "SELECT id, username FROM users WHERE rank <> 0";
		 $users = $this->pdo->prepare($users_q);
		 $users->execute();
		 
				if ($users->rowCount() > 0) {
					while($user = $users->fetch()) {
						$content .='<div class="UserClass" data-userid="'.$user['id'].'">'.$user['username'].'</div>';
					}
				}
		return $content;		
	
}

public function get_user_data($sId) {
	$content = '';
		 $users_q = "SELECT username, rank FROM users WHERE id = :id";
		 $users = $this->pdo->prepare($users_q);
		 $users->bindValue(':id', $sId, \PDO::PARAM_INT);
		 $users->execute();
		 
				if ($users->rowCount() > 0) {
					$user = $users->fetch();
				}
		return $user;		
	
}
}
?>