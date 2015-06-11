<?php 
namespace HRNSales\pitches;
use HRNSales\config as config;
include_once('config.php');	
	
class pitches extends config {
	
	
	 
	//This is the function what collets all the sponsors to the content multi dimensional array.
	public function list_pithces($callback, $tmember, $teams, $admin, $company, $month, $order, $order_type, $pitch_type, $result_type) {
		$data[0][0] = '';
		$i = 0;
		$s = 0;
		$plus_q = '';
		
		$content = '';
		
	 $_SESSION['PitchSelectedCallback'] = $callback;
	 $_SESSION['PitchSelectedTmembers'] = $tmember;
	 $_SESSION['PitchSelectedTeams'] = $teams;
	 $_SESSION['PitchFilterCompany'] = $company;
	 $_SESSION['PitchSelectedMonth'] = $month;
	 $_SESSION['PitchFilterOrder'] = $order;
	 $_SESSION['PitchSelectedOrderType'] = $order_type;
	 $_SESSION['PitchSelectedPitchType'] = $pitch_type;
	 $_SESSION['PitchSelectedResultType'] = $result_type;
		
		
		$currentMonth = date('m'); 
		//Get basic date about a sponsors
		
/*
	$pitch_q = "SELECT pr.id, pd.date_of_pitch, de.first_name, de.last_name, de.title, co.country_code, pt.type as ptype, re.type as retype, pr.deals, pr.price, pr.callback_date, pr.reason, cy.company_name, cy.id as company_id, u.username FROM pitch_data as pd INNER JOIN users as u ON pd.user_id=u.id, delegates as de INNER JOIN delegate_connection as delc ON delc.delegate_id=de.id, countries as co, pitch_type as pt, result_type as re, pitch_result as pr, company as cy, user_team_connection as utc WHERE  pd.delegate_id=delc.delegate_id AND delc.country_id=co.id  AND utc.user_id=u.id AND u.rank <> 0 AND u.rank <> 5 AND delc.company_id=cy.id AND pd.pitch_type_id=pt.id AND pr.pitch_data_id=pd.id AND pr.result_type_id=re.id";

*/	



		                    //pitch date            Deleagate name,     Title          country   pitch type, result type, deals,   callback      reason
		$pitch_q = "SELECT pr.id, pd.date_of_pitch, pt.type as ptype, re.type as retype, pr.deals, pr.price, pr.callback_date, pr.reason, u.username, pd.delegate_id FROM pitch_data as pd INNER JOIN users as u ON pd.user_id=u.id, pitch_type as pt, result_type as re, pitch_result as pr, user_team_connection as utc WHERE utc.user_id=u.id AND u.rank <> 0 AND u.rank <> 5 AND pd.pitch_type_id=pt.id AND pr.pitch_data_id=pd.id AND pr.result_type_id=re.id";	

    if(isset($callback) && $callback != 'All' && $callback != '') {
			$pitch_q .=' AND pr.callback_date= :callback';
		}
		
    if(isset($tmember) && $tmember != 'All' && $tmember != '') {
			$pitch_q .=' AND u.id= :user_id';
		}
						
    if(isset($teams) && $teams != 'All' && $teams != '') {
			$pitch_q .=' AND utc.team_id= :team_id';
		}
				
	if(isset($month) && $month != 'All' && $month != '') {
			$pitch_q .=" AND MONTH(pd.date) = :month";
		}
		
    if(isset($result_type) && $result_type != 'All' && $result_type != '') {
			$pitch_q .=" AND re.id = :retype";
		}
		
    if(isset($pitch_type) && $pitch_type != 'All' && $pitch_type != '') {
			$pitch_q .=" AND pt.id = :ptype";
		}
	
	if ($admin > 2) {
		$plus_q .= ' AND u.id= :id';
		$pitch_q .=	$plus_q;
	}
	
	if ($admin == 2) {
		$plus_q .= ' AND utc.team_id= :id';
		$pitch_q .=	$plus_q;
	}
	

	
	
	if (isset($order) && $order != ''){
	
		 switch($order) {
			  case 'PitchDate':
			     $order_val = 'pd.date';
			  
			    break;	
			  case 'TeamUser':
			     $order_val = 'u.username';
			  
			    break;				
			  case 'Country':
			     $order_val = 'co.country_code';
			  
			    break;
			  case 'Company':
			     $order_val = 'cy.company_name';
			  
			    break;
			  case 'Delegate':
			     $order_val = 'de.last_name';
			  
			    break;	
			  case 'Title':
			     $order_val = 'de.title';
			  
			    break;	
			  case 'Pithced':
			     $order_val = 'pt.type';
			  
			    break;	
			  case 'Callback':
			     $order_val = 'pr.callback_date';
			  
			    break;
			  case 'Result':
			     $order_val = 're.type';
			  
			    break;	
			  case 'Price':
			     $order_val = 'pr.price';
			  
			    break;					
			  case 'DealNum':
			     $order_val = 'pr.deals';
			  
			    break;	
			  case 'Reason':
			     $order_val = 'pr.reason';
			  
			    break;
			  case 'pd.date':
			     $order_val = 'pd.date';
			  
			    break;	
			  case 'Date':
			     $order_val = 'pd.date';
			  
			    break;																																			
			}
		
		
	} else {
		 $_SESSION['PitchFilterOrder'] = 'Date';
		 $order_val = 'pd.date';
	}
	
	
	$pitch_q .= " ORDER BY ".$order_val." ".$order_type;
	
	
		$pitch = $this->pdo->prepare($pitch_q);
		if ($admin > 2) {
			$pitch->bindValue(':id', $admin, \PDO::PARAM_INT);
		}
		
		if ($admin == 2) {
			$pitch->bindValue(':id', $_SESSION['team_id'], \PDO::PARAM_INT);
		}
		
		if(isset($month) && $month != 'All' && $month != '') {
			$pitch->bindValue(':month', $month, \PDO::PARAM_INT);
			
		}
		
	    if(isset($result_type) && $result_type != 'All' && $result_type != '') {
			$pitch->bindValue(':retype', $result_type, \PDO::PARAM_INT);
		}
		
         if(isset($pitch_type) && $pitch_type != 'All' && $pitch_type != '') {
			$pitch->bindValue(':ptype', $pitch_type, \PDO::PARAM_INT);
		}
		
		 if(isset($teams) && $teams != 'All' && $teams != '') {
			$pitch->bindValue(':team_id', $teams, \PDO::PARAM_STR);
			
		}
		
		 if(isset($callback) && $callback != 'All' && $callback != '') {
			$pitch->bindValue(':callback', $callback, \PDO::PARAM_STR);
			
		}
	 if(isset($tmember) && $tmember != 'All' && $tmember != '') {
			$pitch->bindValue(':user_id', $tmember, \PDO::PARAM_INT);
			
		}
		
		$pitch->execute();

			if ($pitch->rowCount() > 0) {
					while($pitches = $pitch->fetch()){
						
						
						//get additional data
					   $pitch_data_q = "SELECT co.country_code, de.first_name, de.last_name, de.title, cy.company_name, cy.id as company_id FROM delegates as de INNER JOIN delegate_connection as delc ON delc.delegate_id=de.id, countries as co, company as cy WHERE delc.delegate_id = :del_id AND delc.country_id=co.id AND delc.company_id=cy.id";
					   
					   $pitch_data = $this->pdo->prepare($pitch_data_q);
					   $pitch_data->bindValue(':del_id', $pitches['delegate_id'], \PDO::PARAM_INT);
					   $pitch_data->execute();
					   
								  if ($pitch_data->rowCount() > 0) {
								  while($pData = $pitch_data->fetch()){
									  
										  if (isset($company) && $company != '' && $company[0] != ''){
											 foreach ($company as $comp){
												   if ($comp == $pData['company_id']) {
															$data[$i][0] = $pitches['date_of_pitch'];
															$data[$i][1] = $pitches['username'];
															$data[$i][2] = $pData['country_code'];
															$data[$i][3] = $pData['company_name'];
															$data[$i][4] = $pData['first_name'].' '.$pData['last_name'];
															$data[$i][5] = $pData['title'];
															$data[$i][6] = $pitches['ptype'];
															$data[$i][7] = $pitches['callback_date'];
															$data[$i][8] = $pitches['retype'];
															$data[$i][9] = $pitches['price'];
															$data[$i][10] = $pitches['deals'];
															$data[$i][11] = $pitches['reason'];
															$data[$i][12] = $pitches['id'];
															$i++;
													   
												   }
												 
												 
												 
											 }
											  
										  } else {
										  $data[$i][0] = $pitches['date_of_pitch'];
										  $data[$i][1] = $pitches['username'];
										  $data[$i][2] = $pData['country_code'];
										  $data[$i][3] = $pData['company_name'];
										  $data[$i][4] = $pData['first_name'].' '.$pData['last_name'];
										  $data[$i][5] = $pData['title'];
										  $data[$i][6] = $pitches['ptype'];
										  $data[$i][7] = $pitches['callback_date'];
										  $data[$i][8] = $pitches['retype'];
										  $data[$i][9] = $pitches['price'];
										  $data[$i][10] = $pitches['deals'];
										  $data[$i][11] = $pitches['reason'];
										  $data[$i][12] = $pitches['id'];
											 $i++;
										  
										  }
														
									  
									  

								  } //pData fetch
							  }  //pitch data num rows
							 
						

					} //stat_q fetch
			}  //stat num row end
			
			

			
		//pr.id, pd.date_of_pitch, de.last_name, de.title, co.country_code, pt.type as ptype, re.type as retype, pr.deals, pr.price, pr.callback_date, pr.reason, cy.company_name, cy.id as company_id, u.username
			
		$content .='<div class="PitchTable">';
    	 $content .='<div id="HeaderCol" class="TableCol">';
		  
		   $variable_helper = "'PitchDate'"; 
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Date of Pitch</div>';
		   
		   $variable_helper = "'TeamUser'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Team memb.</div>';
		   
		   $variable_helper = "'Country'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Country</div>';
		   
		   $variable_helper = "'Company'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Company</div>';
		   
		   $variable_helper = "'Delegate'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Delegate</div>';
		   
		   $variable_helper = "'Title'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Title</div>';
		   
		   $variable_helper = "'Pithced'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Pitched</div>';
		   
		   $variable_helper = "'Callback'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Call Back Date</div>';
		   
		   $variable_helper = "'Result'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Result</div>';
		   
		   $variable_helper = "'Price'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Price</div>';
		   
		   $variable_helper = "'DealNum'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Number of Deals</div>';
		   
		   $variable_helper = "'Reason'";
		   $content .='<div onClick="change_order('.$variable_helper.')" class="TableRow HeaderRow">Reason</div>';
		 
		 $content .='</div>'; //header col end
		
		
		
		foreach ($data as $pitch) {
			if (isset($pitch[12])){
				$content .='<div class="TableCol" data-pitchnum="'.$pitch[12].'" onClick="edit_data(this)">';
			}else {
				$content .='<div class="TableCol">';
			}
			
			$s = 0;
		    foreach ($pitch as $pt) {
				if ($s != 12) {
					$content .='<div class="TableRow">'.$pt.'</div>';
				}
				$s++;
			}
			$content .='</div>';
		}
  $content .='</div>';	
			
		return $content;
}

 
 
   function sanitize($data){
       //$data = htmlentities(strip_tags(trim($data)));
		
		$bad = array("content-type","bcc:","to:","cc:","href","$","SELECT","<",">",";","INSERT INTO","UPDATE","DELETE");
		
		$data = str_replace($bad,"",$data);
		
        $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                   '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
    ); 
    $data = preg_replace($search, '', $data); 
        return $data;
    }
 


}
?>	