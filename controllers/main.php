<?php 
namespace HRNSales\main;
use HRNSales\config as config;
include_once('config.php');	
if(!isset($_SESSION)) {
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
	
	if (isset($_POST['NewCountryName']) && isset($_POST['NewCountryCode'])) {
		
		 $country_q = "INSERT INTO countries SET country_name = :country_name, country_code = :country_code";
		 $country_insert = $this->pdo->prepare($country_q);
		 
		 $country_insert->bindValue(':country_name', $_POST['NewCountryName'], \PDO::PARAM_STR);
		 $country_insert->bindValue(':country_code', $_POST['NewCountryCode'], \PDO::PARAM_STR);
		 
		 $country_insert->execute();
		 
            $country = $this->pdo->lastInsertId();
		
		
		
	} else {
		$country = $_POST['Country'];
	}
	

	$date = date("j F Y");
	
	      $this->pdo->beginTransaction();

		 $delegate_q = "INSERT INTO delegates SET first_name = :first_name, last_name = :last_name, title = :title";
		 $delegate = $this->pdo->prepare($delegate_q);
		 
		 $delegate->bindValue(':first_name', $_POST['FirstName'], \PDO::PARAM_STR);
		 $delegate->bindValue(':last_name', $_POST['LastName'], \PDO::PARAM_STR);
		 $delegate->bindValue(':title', $_POST['DelegateTitle'], \PDO::PARAM_STR);
		 
		 $delegate->execute();
		 
            $delegate_id = $this->pdo->lastInsertId(); 
		 
		 $company_q = "INSERT INTO company SET company_name = :company_name";
		 $company = $this->pdo->prepare($company_q);
		 
		 $company->bindValue(':company_name', $_POST['CompanyName'], \PDO::PARAM_STR);
		 
		 $company->execute();

		    $company_id = $this->pdo->lastInsertId();
			
		 $connection_q = "INSERT INTO delegate_connection SET country_id = :country_id, delegate_id = :delegate_id, company_id = :company_id";
		 $connection = $this->pdo->prepare($connection_q);
		 
		 $connection->bindValue(':country_id', $country, \PDO::PARAM_INT);
		 $connection->bindValue(':delegate_id', $delegate_id, \PDO::PARAM_INT);
		 $connection->bindValue(':company_id', $company_id, \PDO::PARAM_INT);
		 
		 $connection->execute();
		 
		    $connection_id = $this->pdo->lastInsertId();
		 
		 
		 $pitch_data_q = "INSERT INTO pitch_data SET date_of_pitch = :pitch_date, user_id = :user_id, delegate_id = :delegate_id, pitch_type_id = :pitch_type";
		 $pitch_data = $this->pdo->prepare($pitch_data_q);
		 
		 $pitch_data->bindValue(':pitch_date', $date, \PDO::PARAM_STR);
		 $pitch_data->bindValue(':user_id', $_SESSION['user_id'], \PDO::PARAM_INT);
		 $pitch_data->bindValue(':delegate_id', $delegate_id, \PDO::PARAM_INT);
		 $pitch_data->bindValue(':pitch_type', $_POST['PitchType'], \PDO::PARAM_INT);
		 
		 $pitch_data->execute();
		 
		 	$pitch_data_id = $this->pdo->lastInsertId();
			
			
		 $pitch_result_q = "INSERT INTO pitch_result SET pitch_data_id = :data_id, result_type_id = :result_type, deals = :deal_nums, callback_date = :callback, reason = :reason";
		 $pitch_result = $this->pdo->prepare($pitch_result_q);
		 
		 $pitch_result->bindValue(':data_id', $pitch_data_id, \PDO::PARAM_INT);
		 $pitch_result->bindValue(':result_type', $_POST['PitchResult'], \PDO::PARAM_INT);
		 $pitch_result->bindValue(':deal_nums', $_POST['NumberOfDeals'], \PDO::PARAM_INT);
		 $pitch_result->bindValue(':callback', $_POST['CallBackDate'], \PDO::PARAM_STR);
		 $pitch_result->bindValue(':reason', $_POST['Reason'], \PDO::PARAM_STR);
		 
		 $pitch_result->execute();
		 
		    $pitch_result_id = $this->pdo->lastInsertId();
		 
		 $this->pdo->commit();
		 
		 return $pitch_result_id;
	
}


public function get_pitch_edit_data($pitch_num) {
			$data[0][0] = '';
		$i = 0;
		
		//Get basic date about a sponsors
		                    //pitch date            Deleagate name,     Title          country   pitch type, result type, deals,   callback      reason
		$pitch_q = "SELECT pr.id, pd.date_of_pitch, de.first_name, de.last_name, de.title, co.country_code, pt.type as ptype, re.type as retype, pr.deals, pr.callback_date, pr.reason, cy.company_name, u.username FROM pitch_data as pd, delegates as de, delegate_connection as delc, countries as co, pitch_type as pt, result_type as re, pitch_result as pr, company as cy, users as u WHERE pd.user_id=u.id AND pd.delegate_id=delc.delegate_id AND delc.country_id=co.id AND delc.delegate_id=de.id AND delc.delegate_id=cy.id AND pd.pitch_type_id=pt.id AND pr.pitch_data_id=pd.id AND pr.result_type_id=re.id AND pr.id= :id ORDER BY pd.date DESC";	
					
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
	
	if (isset($_POST['call_num'])) {
		
		   $insert_call = "UPDATE user_calls SET calls = :calls WHERE user_id = :user_id AND call_date = CURDATE()";
		   $call2 = $this->pdo->prepare($insert_call);
		   $call2->bindValue(':calls', $_POST['call_num'], \PDO::PARAM_INT);
		   $call2->bindValue(':user_id', $_SESSION['user_id'], \PDO::PARAM_INT);
		   $call2->execute();
		   
			 return 'Success';
			 
			 
	}

	
}

}
?>