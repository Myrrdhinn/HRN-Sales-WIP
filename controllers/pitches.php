<?php 
namespace HRNSales\pitches;
use HRNSales\config as config;
include_once('config.php');	
	
class pitches extends config {
	 
	//This is the function what collets all the sponsors to the content multi dimensional array.
	public function list_pithces($category, $value) {
		$data[0][0] = '';
		$i = 0;
		$s = 0;
		$plus_q = '';
		
		$content = '';
		//Get basic date about a sponsors
		                    //pitch date            Deleagate name,     Title          country   pitch type, result type, deals,   callback      reason
		$pitch_q = "SELECT pr.id, pd.date_of_pitch, de.first_name, de.last_name, de.title, co.country_code, pt.type as ptype, re.type as retype, pr.deals, pr.callback_date, pr.reason, cy.company_name, u.username FROM pitch_data as pd, delegates as de, delegate_connection as delc, countries as co, pitch_type as pt, result_type as re, pitch_result as pr, company as cy, users as u WHERE pd.user_id=u.id AND pd.delegate_id=delc.delegate_id AND delc.country_id=co.id AND delc.delegate_id=de.id AND delc.company_id=cy.id AND pd.pitch_type_id=pt.id AND pr.pitch_data_id=pd.id AND pr.result_type_id=re.id";	
	
		//test
    if (isset($category[0]) && isset($value[0])) {
		foreach ($category as $cat) {
			$plus_q .= ' AND ';
			$plus_q .='(';
			
			foreach ($value[$s] as $val) {
			  $plus_q .= $cat.'='.$val;	
			  $plus_q .= ' OR ';
			}
			
			$plus_q = substr($plus_q,0,-4);
			$plus_q .= ')';
			$s++;
		}
	}
	
		
	//$plus_q = " AND (u.id=1 OR u.id=2)";	
	$pitch_q .=	$plus_q;
				
	//$pitch_q .= " ORDER BY pd.date DESC";
	
		$pitch = $this->pdo->prepare($pitch_q);
		//$stat->bindValue(':category', $category, \PDO::PARAM_INT);
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
						$data[$i][9] = $pitches['deals'];
						$data[$i][10] = $pitches['reason'];
						$data[$i][11] = $pitches['id'];
						
					

						
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
		   $content .='<div class="TableRow HeaderRow">No. of Deals</div>';
		   $content .='<div class="TableRow HeaderRow">Reason</div>';
		 
		 $content .='</div>'; //header col end
		
		foreach ($data as $pitch) {
			$content .='<div class="TableCol" data-pitchnum="'.$pitch[11].'">';
			$s = 0;
		    foreach ($pitch as $pt) {
				if ($s != 11) {
					$content .='<div class="TableRow">'.$pt.'</div>';
				}
				$s++;
			}
			$content .='</div>';
		}
  $content .='</div>';	
			
		return $content;
}

function sponsors_list(){

			$content = '';
	//Gets all of the locations
	$sponsors = $this->dbc->query(
					sprintf("SELECT id FROM sponsors"));	
					if ($sponsors->num_rows > 0) {
					while($data = $sponsors->fetch_assoc()){
						
					  $name = $this->dbc->query(
							sprintf("SELECT id, name FROM sponsors_name WHERE sponsors_id = '%s' ORDER BY date DESC LIMIT 0,1",
								$this->dbc->real_escape_string($data['id'])
							)
							   );	
								if (mysqli_num_rows($name)) {
								while($sName = $name->fetch_assoc()){
									$content .= '<option value="'.$data['id'].'">'.$sName['name'].'</option>';
								} //personal fetch assoc end
							}  //personal num rows if end
						
						
					}
				}
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
 

function send_book_sponsor_mail() {
	
	    if(!isset($_POST['fname']) ||
 
        !isset($_POST['lname']) ||
 
        !isset($_POST['email']) ||
 
        !isset($_POST['phone']) ||
 
        !isset($_POST['company'])) {
			

 
    } else {
	 
     //it's working! :) Just character encoding is not good :(
 
    // EDIT THE 2 LINES BELOW AS REQUIRED
 
   $email_to = "balazs.pentek@hrneurope.com";
 
    $email_subject = "Book a Sponsor Form submit";
 

 
    $first_name = utf8_decode($_POST['fname']); // required
 
    $last_name = utf8_decode($_POST['lname']); // required
 
    $email_from = $_POST['email']; // required
 
    $telephone = $_POST['phone']; // not required
 
    //$comments = utf8_decode($_POST['message']); // required
	
	$day = $_POST['day'];
	
	$time = $_POST['time'];
	
	$sponsor = $_POST['sponsor'];
	
	$company = utf8_decode($_POST['company']); // required
 

    $email_message = "Form details below.\n\n";
 
 

 
     
 
    $email_message .= "First Name: ".$this->sanitize($first_name)."\n";
 
    $email_message .= "Last Name: ".$this->sanitize($last_name)."\n";
 
    $email_message .= "Email: ".$this->sanitize($email_from)."\n";
 
    $email_message .= "Telephone: ".$this->sanitize($telephone)."\n";
 
   // $email_message .= "Comments: ".$this->sanitize($comments)."\n";
	
   $email_message .= "Company: ".$this->sanitize($company)."\n";
   
   $email_message .= "Day: ".$this->sanitize($day)."\n";
   
   $email_message .= "Time: ".$this->sanitize($time)."\n";
   
   $email_message .= "Sponsor name: ".$this->sanitize($sponsor)."\n";
 
     
 
     


		// create email headers
 
$headers = 
 
'X-Mailer: PHP/' . phpversion();
 
mail($email_to, $email_subject, $email_message, $headers);


$thanks_text = '<!-- Thank You Header -->
<div id="ThankYouHeaderContainer">
  <div id="ThankYouHeaderInnerContainer">
    <img src="img/thankyou/thankyou.png" alt="Thank You!" />

    <p class="ThanksMessage">Someone from HR Tech Europe or the vendor company will be in touch shortly.</p>
  </div>
</div>
<!-- END Thank You Header -->';
echo $thanks_text;

	}


	
}
 
 
function sponsors_time_list() {
  $day = "10/14/2011";
  
 $output = '';
$startTime = date(strtotime($day." 7:00"));
$endTime = date(strtotime($day." 18:00"));

$timeDiff = round(($endTime - $startTime)/60/60);

$startHour = date("G", $startTime);
$endHour = $startHour + $timeDiff; 
$AmPm = "AM";

for ($i=$startHour; $i <= $endHour; $i++)
{
     for ($j = 0; $j <= 55; $j+=5)
        {
			
                $time = $i.":".str_pad($j, 2, '0', STR_PAD_LEFT);
				
				if (date(strtotime($day." ".$time)) <= $endTime) {
					
					$temp = date("g", strtotime($day." ".$time));
					
					if ($temp == '12') {
						$AmPm = "PM";
					}
					
					$output .='<option value="'.date("g:i", strtotime($day." ".$time)).$AmPm.'">'.date("g:i", strtotime($day." ".$time)).$AmPm.'</option>';
					
				  }

                
        }
}
   return $output;	
} 
 
function modal_display($tag) {
	
	/*
	----------------
	GET THE DATA
	---------------
	*/
	$sId = -1;
	
	$sp_id = $this->dbc->query(
			sprintf("SELECT sponsors_id FROM sponsors_data WHERE sponsors_tag = '%s' ORDER BY date DESC",
			$this->dbc->real_escape_string($tag)
				)
				   );	
					if (mysqli_num_rows($sp_id)) {
					while($sponsors_id = $sp_id->fetch_assoc()){
						
							$stat = $this->dbc->query(
								  sprintf("SELECT status_id FROM sponsors_status WHERE sponsors_id = '%s' ORDER BY date DESC LIMIT 0,1",
									  $this->dbc->real_escape_string($sponsors_id['sponsors_id'])
								  )
									 );	
									  if (mysqli_num_rows($stat)) {
									  while($sStatus = $stat->fetch_assoc()){
										  if ($sStatus['status_id'] == 1 || $sStatus['status_id'] == 2){
											  $sId = $sponsors_id['sponsors_id'];
										  }
									  } //personal fetch assoc end
								  }  //personal num rows if end
						
						if ($sId > -1) {
							break;
						}

					} //personal fetch assoc end
				}  //personal num rows if end
	
	
	
	
	
	$i = 0;	
		
		

						
		if ($sId > -1) {	
				   
		$content[$i][0] = $sId;
	//Get the names					   
	 $name = $this->dbc->query(
				sprintf("SELECT id, name FROM sponsors_name WHERE sponsors_id = '%s' ORDER BY date DESC LIMIT 0,1",
				    $this->dbc->real_escape_string($sId)
				)
				   );	
					if (mysqli_num_rows($name)) {
					while($sName = $name->fetch_assoc()){
						$content[$i][8] = $sName['name'];
						$content[$i][9] = $sName['id'];
					} //personal fetch assoc end
				}  //personal num rows if end
						   
		//Get the personal data				   
		 $personal = $this->dbc->query(
				sprintf("SELECT sponsors_bio, sponsors_url, sponsors_tag, sponsors_rank FROM sponsors_data WHERE sponsors_id = '%s' ORDER BY date DESC LIMIT 0,1",
				    $this->dbc->real_escape_string($sId)
				)
				   );	
					if (mysqli_num_rows($personal)) {
					while($personals = $personal->fetch_assoc()){
						
					   //$b = str_replace(array("'szuunet'"), array("<br />"), $personals['bio']);
					   //$bi = str_replace(array("'szuunet'"), array("<br />"), $personals['bio_important']); //We need this to display <br /> -s.. cause we used htmlspecial chars aaand sprintf in
					                                                                                         // the upload :D Can't be safe enough :P
					   
					   $bio = htmlspecialchars_decode($personals['sponsors_bio']);
					   
						$content[$i][1] = $personals['sponsors_url'];
						$content[$i][2] = $personals['sponsors_rank'];
						$content[$i][3] = $bio;
						$content[$i][4] = $personals['sponsors_tag'];
						
					} //personal fetch assoc end
				}  //personal num rows if end
				
															  $content[$i][5] = '';
															  $content[$i][6] = '';
															  $haveit = ''; //this will contain what links we already displayed
				
								  //Get links		
											   $links = $this->dbc->query(
													  sprintf("SELECT slt.type, sl.link_url FROM sponsors_links as sl, speakers_link_types as slt WHERE sl.sponsors_id = '%s' AND sl.speakers_link_types_id=slt.id ORDER BY sl.date DESC",
														  $this->dbc->real_escape_string($sId)
													  )
														 );	
														 ///Sooo.. this is the link section
														  if (mysqli_num_rows($links)) {
														  //we define the content 5 and 6 so we can append to them later

															  
															  //we fetch the links from the database
														  while($slinks = $links->fetch_assoc()){
															  $nope = 0; //we need this to decide if we want to add the new to the content variable or not
															  if (isset($haveit) && $haveit != '') { //we check if there's a link or not displayed
																  $isItYes = explode(';',$haveit); //we explode it to get all the displayed links in an array
																  foreach ($isItYes As $yes) { //we go through it
																	  if ($yes == $slinks['type']) { //if the link type from the database is the same as we displayed before...
																		  $nope = 1;  //nope
																	  }
																  }
																  if ($nope == 0) {  // if we don't have this link already displayed, we add it to the content
																	 $content[$i][5] .= $slinks['type'].';';
																	 $content[$i][6] .= $slinks['link_url'].';';
																	 $haveit .= $slinks['type'].';'; //we also add it to the "displayed" list
																  }
																  
															  } else {  //if there's no list yet :D
																 $content[$i][5] .= $slinks['type'].';';
																 $content[$i][6] .= $slinks['link_url'].';';
																 $haveit .= $slinks['type'].';';
															  }
									  
														  }
													  }                  
									  
													  
									  
														  //Get image data
											   $pictures = $this->dbc->query(
													  sprintf("SELECT image_url FROM sponsors_image WHERE sponsors_id = '%s' ORDER BY date DESC LIMIT 0,1",
														  $this->dbc->real_escape_string($sId)
													  )
														 );	
														  if (mysqli_num_rows($pictures)) {
														  while($pic = $pictures->fetch_assoc()){
															  //$content[$i][10] = $pic['image_name'];
															  $content[$i][7] = $pic['image_url'];
														  
															  
														  }
													  } 
													               

				
								                
						$i++;
					} //if status = 1

			
	
	
	
  /*
  --------------
  DISPLAY THE DATA
  ---------------
  */

if (isset($content)) {
   foreach ($content as $sponsor) {
			 $go = 1;
			 
			 if (isset($sponsor[6])){ //we break down the links to an array
				  $links = explode(';',$sponsor[6]);
			      $link_types = explode(';',$sponsor[5]);
			 }
			 
			 
			 $num = 0;
			  foreach ($sponsor As $set) {
			      if (!isset($set)){
				        $sponsor[$num] = '';
			        }	
				  $num++;		
			   }
			   
				
				

			 			 		  /*
		  							    $content[$i][0] =  sponsors_id
										$content[$i][1] =  sponsors_url
										$content[$i][2] =  sponsors_type_id (platinum, gold etc)
										$content[$i][3] =  sponsors_bio
										$content[$i][4] =  sponsors_tag
										$content[$i][5] =  sponsors_link_types
										$content[$i][6] =  sponsors_link_urls
										$content[$i][7] =  sponsors_picture
										$content[$i][8] =  sponsors_name
										$content[$i][9] =  sponsors_name_id
										$content[$i][10] = sponsor_type_name
										$content[$i][11] = //sponsors_id (HTML id tag)
										$content[$i][12] = AlaCarte sponsor text
										$content[$i][13] = Alacarte or not? :D
										$content[$i][14] = // alacarte connection id
			 
			 */ 			

 /*------------------------
  Normal user
 -------------------------
 */
					$output = '<!-- '.$sponsor[8].' Modal -->
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="img/sponsors/modal-close.png" alt="modal-close-button"></button>';
			  if (isset($sponsor[7])) {
		  $output .= '<div class="ModalSponsorPhoto" style="background-image:url(img/sponsors/'.$sponsor[7].')"></div>';
	          } else {
				   $output .= '<div class="ModalSpeakerPhoto"></div>';
			  }
       
	  
	       $output .= '<div class="ModalOriginalContent">';
			  $sponsor_tag = "";
			  $sa = preg_replace('/[^A-Za-z]/', '', $sponsor[8]); // Removes special chars.
			  $sponsor_tag_array = explode(" ",$sa);
			  foreach ($sponsor_tag_array as $comp) {
				  $sponsor_tag .= ucfirst($comp); 
			  }
						
	  			
  			$google = 'onClick="_gaq.push([';
			$google .= "'_trackEvent', 'SponsorCompanySite', 'ExternalForward', '".$sponsor_tag."']);";
			$google .= '"';
        
       $output .='<div class="ModalSponsorBioContainer">
	   <form class="SponsorModalEdit">
	      
          <p id="'.$sponsor[4].'_Name" style="display:none" class="ModalSponsorName">'.$sponsor[8].'</p>
		

		  <a '.$google.' id="'.$sponsor[4].'_CompanyLink" class="ModalSponsorCompanyLink" target="_blank" href="'.$sponsor[1].'">Visit Company Website <i class="fa fa-angle-double-right"></i></a>
		
          <div class="ModalDivider"></div>';		  
		  $s = 0;
		  
		  if (isset($link_types)){
			 foreach ($link_types As $types) {
			   if ($types) {
				   if ($links[$s] != ''){
				
			$comp_social = ucfirst($types).'-'.$sponsor_tag;	   
			$google = 'onClick="_gaq.push([';
			$google .= "'_trackEvent', 'SponsorSocialSite', 'ExternalForward', '".$comp_social."']);";
			$google .= '"';
					   
					   $url_raw = $this->social_link_decode($links[$s]); //this is needed to decode the link from the database
					   
				    //$output .='<p class="SocialIcons"><a href="'.$links[$s].'" target="_blank"><i class="fa fa-'.$types.' "></i></a></p>'; 
					$output .='<p id="'.$sponsor[4].'_'.$types.'" class="SocialIcons"><a '.$google.' href="'.$url_raw.'" target="_blank"><i class="fa fa-'.$types.' "></i></a></p>'; 
				   }
					   $s++;
			         }
				}
				unset($link_types);
				unset($links);
		  }	   
		
          $output .='<div id="'.$sponsor[4].'_Bio" class="ModalSponsorBio RobotoText"> '.$sponsor[3].'</div>';
		   $output .= '<div class="GetFormButton" onClick="ShowForm()">Arrange a Meeting</div>';

  $output .='</form>
        </div>

		
		</div>';
	 $output .="	
		<!-- Google Captcha -->
<script src='https://www.google.com/recaptcha/api.js?hl=en'></script>";

		$output .='<!--Sponsors form-->
		<div style="display:none" class="SponsorsFormContainer">
		
      <div id="SponsorsFormInnerContainer"> <a id="book-the-sponsor"></a>
	  <p class="ModalFormText">If you are attending HR Tech Europe, you can schedule an appointment with this vendor at the expo by leaving your details below.</p>
	  <p id="MissingText" style="display:none"></p>
        <!-- BEGINNING of : Book the Sponsor FORM -->
        <form action="" method="POST">
          <div class="row">
            <div class="large-6 columns SponsorFormColumn">
              <input class="SponsorFormInputClass" required="required" placeholder="First Name"  id="first_name" maxlength="40" name="first_name" size="20" type="text" />
              <input class="SponsorFormInputClass" required placeholder="Last Name" id="last_name" maxlength="80" name="last_name" size="20" type="text" />
              <input class="SponsorFormInputClass" required placeholder="Email Address"  id="email" maxlength="80" name="email" size="20" type="text" />
              <select style="display:none;"   id="lead_source" name="lead_source" placeholder="Lead Source">
                <option selected="selected" value="HRTechLondon2015-BookTheSponsor">HRTechLondon2015-BookTheSponsor</option>
              </select>
            </div>
            <div class="large-6 columns SponsorFormColumn">
              <input class="SponsorFormInputClass" required placeholder="Phone Number"  id="phone" maxlength="40" name="phone" size="20" type="text" />
              <input class="SponsorFormInputClass" required placeholder="Company Site"  id="company" maxlength="40" name="company" size="20" type="text" />
              <input class="SponsorFormInputClass" required placeholder="Job Title" id="title" maxlength="40" name="title" size="20" type="text" />
			  <input style="display:none"  id="sponsor_name" maxlength="40" name="sponsor_name" size="20" type="text" value="'.$sponsor[8].'" />
			  
			  
            </div>
          </div>
		  			<div id="SponsorFormTimeContainer"><label class="SponsorFormLabel">Preferred day and time:
					<select id="SponsorDaySelect">
					  <option value="1">Day 1</option>
					  <option value="2">Day 2</option>
					</select>
					 <select id="SponsorTimeSelect" name="SponsorTimeselect">';
			$output .= $this->sponsors_time_list();
			$output .='</select></label>
			 </div>
          <div class="row">
		  <div id="ChaptchaDiv" class="g-recaptcha" data-sitekey="6LdQDgMTAAAAAAf_SEWUQpEvpbcSV3o98_Kvo2S5"></div>
            <div class="large-12 column">
              <div class="SponsorFormSubmitButton" onClick="SponsorFormSubmit(this); _gaq.push([';
			  $output .= "'_trackEvent', 'sponsors', 'FormSubmission', 'InquirySent']);";
			  $output .='">Submit</div>
            </div>
          </div>
        </form>
        <!-- END of : Book the Sponsor FORM --->
      </div>
		</div>
		<!--Sponsors form end-->
      </div>
    </div>
  </div>
<!-- end '.$sponsor[0].' Modal --> ';			
						


		echo $output; 
		 
	}//foreach content ends
		} //if isset content ends

}
	
}

//Get the tag of the sponsor to display it in a modal
 if(isset($_POST['action']) && $_POST['action'] == 'BookSponsorForm' && isset($_POST['email'])){
	$the_main = new sponsors_main();
    $the_main->send_book_sponsor_mail();
}// modal display end


//Get the tag of the sponsor to display it in a modal
 if(isset($_POST['action']) && $_POST['action'] == 'modal_display' && isset($_POST['sTag'])){
	$the_main = new sponsors_main();
    $the_main->modal_display($_POST['sTag']);
}// modal display end
?>	