<?php 
namespace HRNSales\pitches;
use HRNSales\config as config;
include_once('config.php');	
	
class pitches extends config {
	 
	//This is the function what collets all the sponsors to the content multi dimensional array.
	public function list_pithces($category, $value, $admin) {
		$data[0][0] = '';
		$i = 0;
		$s = 0;
		$plus_q = '';
		
		$content = '';
		//Get basic date about a sponsors
		                    //pitch date            Deleagate name,     Title          country   pitch type, result type, deals,   callback      reason
		$pitch_q = "SELECT pr.id, pd.date_of_pitch, de.first_name, de.last_name, de.title, co.country_code, pt.type as ptype, re.type as retype, pr.deals, pr.price, pr.callback_date, pr.reason, cy.company_name, u.username FROM pitch_data as pd, delegates as de, delegate_connection as delc, countries as co, pitch_type as pt, result_type as re, pitch_result as pr, company as cy, users as u, user_team_connection as utc WHERE pd.user_id=u.id AND pd.delegate_id=delc.delegate_id AND delc.country_id=co.id AND delc.delegate_id=de.id AND utc.user_id=u.id AND delc.company_id=cy.id AND pd.pitch_type_id=pt.id AND pr.pitch_data_id=pd.id AND pr.result_type_id=re.id";	
	

	
	if ($admin > 2) {
		$plus_q .= ' AND u.id= :id';
		$pitch_q .=	$plus_q;
	}
	
	if ($admin == 2) {
		$plus_q .= ' AND utc.team_id= :id';
		$pitch_q .=	$plus_q;
	}
	
	 //ideiglenesen csak callback datere csinálok szűrőt
	if(isset($category) && $category != '') {
		$cats = explode(',',$category);
		$val = explode(',',$value);
		$cat_val = 0;

		foreach ($cats as $cat) {
			
			if ($cat == 'team') {
			    $plus_q .= ' AND utc.team_id= :team_id';
				$data_team_id = $val[$cat_val];
				$pitch_q .=	$plus_q;
				$cat_val++;
			}
			
			if ($cat == 'user') {
			    $plus_q .= ' AND u.id= :user_id';
				$data_user_id = $val[$cat_val];
				$pitch_q .=	$plus_q;
				$cat_val++;
			}
			
			if ($cat == 'callback'){
				
				$plus_q .= ' AND pr.callback_date= :callback';
				$data_callback = $val[$cat_val];
				$pitch_q .=	$plus_q;
				$cat_val++;
				
			}
			
			
		}//foreach ends
		
		
	}//if isset category
				
	$pitch_q .= " ORDER BY pd.date DESC";
	
		$pitch = $this->pdo->prepare($pitch_q);
		if ($admin > 2) {
			$pitch->bindValue(':id', $admin, \PDO::PARAM_INT);
		}
		
		if ($admin == 2) {
			$pitch->bindValue(':id', $_SESSION['team_id'], \PDO::PARAM_INT);
		}
		if(isset($data_team_id) && $data_team_id != '') {
			$pitch->bindValue(':team_id', $data_team_id, \PDO::PARAM_STR);
		}
		
		if(isset($data_callback) && $data_callback != '') {
			$pitch->bindValue(':callback', $data_callback, \PDO::PARAM_STR);
		}
	    if(isset($data_user_id) && $data_user_id != '') {
			$pitch->bindValue(':user_id', $data_user_id, \PDO::PARAM_INT);
		}
		
		$pitch->execute();

			if ($pitch->rowCount() > 0) {
					while($pitches = $pitch->fetch()){
						$data[$i][0] = $pitches['date_of_pitch'];
						$data[$i][1] = $pitches['username'];
						$data[$i][2] = $pitches['country_code'];
						$data[$i][3] = $pitches['company_name'];
						$data[$i][4] = $pitches['first_name'].' '.$pitches['last_name'];
						$data[$i][5] = $pitches['title'];
						$data[$i][6] = $pitches['ptype'];
						$data[$i][7] = $pitches['callback_date'];
						$data[$i][8] = $pitches['retype'];
						$data[$i][9] = $pitches['price'];
						$data[$i][10] = $pitches['deals'];
						$data[$i][11] = $pitches['reason'];
						$data[$i][12] = $pitches['id'];
						
						
					

						
						$i++;
					} //stat_q fetch
			}  //stat num row end
			
			
		$content .='<div class="PitchTable">';
    	 $content .='<div id="HeaderCol" class="TableCol">';
		   $content .='<div class="TableRow HeaderRow">Date of Pitch</div>';
		   $content .='<div class="TableRow HeaderRow">Team memb.</div>';
		   $content .='<div class="TableRow HeaderRow">Country</div>';
		   $content .='<div class="TableRow HeaderRow">Company</div>';
		   $content .='<div class="TableRow HeaderRow">Delegate</div>';
		   $content .='<div class="TableRow HeaderRow">Title</div>';
		   $content .='<div class="TableRow HeaderRow">Pitched</div>';
		   $content .='<div class="TableRow HeaderRow">Call Back Date</div>';
		   $content .='<div class="TableRow HeaderRow">Result</div>';
		    $content .='<div class="TableRow HeaderRow">Price</div>';
		   $content .='<div class="TableRow HeaderRow">Number of Deals</div>';
		   $content .='<div class="TableRow HeaderRow">Reason</div>';
		 
		 $content .='</div>'; //header col end
		
		foreach ($data as $pitch) {
			if (isset($pitch[12])){
				$content .='<div class="TableCol" data-pitchnum="'.$pitch[12].'">';
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