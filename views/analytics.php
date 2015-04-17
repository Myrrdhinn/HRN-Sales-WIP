<?php 
use HRNSales\NewData as newData;
use HRNSales\main as main;

   if (!isset($_SESSION['user'])) {
	  	 require_once('login.php');
   } else {
	   
	   $content ='
	   <!--Jquery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link href="css/admin_general.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin_edit_general.css" />
<link rel="stylesheet" href="css/admin_index.css" />
<link rel="stylesheet" href="css/analytics.css" />

    <script> 
        var allofit = {};
    </script>


 <!--Graph library-->
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>
<link rel="stylesheet" href="css/graph_analytics.css" />
<script src="js/graph_analytics.js"></script> 

<script src="js/analytics.js"></script>

</head>
<body>

 <!--Main Wrapper-->
	<div class="wrapper">
	  <h1 class="WrapperMainH1">HR Tech Europe - Sales | Analytics </h1>
	   
	<!--Form container-->
	 <div id="container">';
if (isset($_SESSION['admin'])) {
			include_once('controllers/main.php');
			$main = new main\main;
			
		 if (isset($_SESSION['Result']) && $_SESSION['Result'] != '') {
				$content .='<div id="ReturnValue" style="display:none">'.$_SESSION['Result'].'</div>';
				$_SESSION['Result'] = '';
			} else {
				$content .='<div id="ReturnValue" style="display:none"></div>';
			}		
			
	
    $content .='	 	 	 
	 
      <div id="MenuIconContainer">';
	  

			 $content .= '<a title="Back to Main Page" href="index"><img alt="Back to Main Page" class="MenuIcon" src="img/icons/main.png" onmouseover="this.src=';
			 $content .="'img/icons/main_hover.png';";
			 $content .='" onmouseout="this.src=';
			 $content .="'img/icons/main.png';";
			 $content .='" ></a>';
		
	
  $content .='</div>';
  
   // $content .='<p class="WelcomeTexT">Change the desirable goals:</p>';
  
  
  
   $content .='
   <div id="FilterContainer">';
   
   //Date filters
     $content .='
	 <div class="DateFilterContainer">  
	   <select id="Days" class="SelectClass">
	        <option value="-1" hidden="hidden">Day</option>
			<option value="All">All</option>
			<option value="1">01</option>
			<option value="2">02</option>
			<option value="3">03</option>
			<option value="4">04</option>
			<option value="5">05</option>
			<option value="6">06</option>
			<option value="7">07</option>
			<option value="8">08</option>
			<option value="9">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
	   </select>
	   
	   	   <select id="Weeks" class="SelectClass">
	        <option value="-1" hidden="hidden">Week</option>
			<option value="All">All</option>

	   </select>
	   
	   	<select id="Months" class="SelectClass">
		    <option value="-1" hidden="hidden">Month</option>
			<option value="All">All</option>
			<option value="January">January</option>
			<option value="February">February</option>
			<option value="March">March</option>
			<option value="April">April</option>
			<option value="May">May</option>
			<option value="June">June</option>
			<option value="July">July</option>
			<option value="August">August</option>
			<option value="September">September</option>
			<option value="October">October</option>
			<option value="November">November</option>
			<option value="December">December</option>
		
	   </select>
	   
	    <select id="Years" class="SelectClass">
		<option value="2015">2015</option>
		<option value="2016">2016</option>
	   </select>            
      </div>
   '; 
   
   //Country filter
        $content .='
	 <div class="CountryFilterContainer">  
	   <select id="Country" class="SelectClass">
			<option value="All">All</option>';
           $content .= $main->get_countries('');
	  $content .=' </select>        
      </div>';
	  if (isset($_SESSION['admin'])) {
	  
	  $content .='<div class="TeamFilterContainer">
	  	   <label id="TeamFilterLabel">Filter by Team(s):<br /><select multiple="multiple" id="Teams" class="SelectClass">';
				
			  
			  $content .= $main->get_teams();
			  $content .='
				</select> </label>';
				
					  	  $content .='<div style="display:none" class="TeamCompareContainer">
	  	   <label>Work in Progress<br /><select multiple="multiple" id="Compare" class="SelectClass">';
				
			  
			  $content .= $main->get_teams();
			  $content .='
				</select> </label>   
				
				
	  
	  </div>   
				
				
	  
	  </div>'; 
	  
	  
	  }
   
   // $content .= $main->get_analytics_filters();
   
   $content .='
   </div>
     </div">';
  
     $content .='
	 <!-- ;) -->
	 <div id="MainAnalContent">';
	 
	 if ($_SESSION['admin'] > 2){
		 $team_id[0] = $_SESSION['team_id'];
		 $content .= $main->get_analytics_data('','',''); 
	 } else {
		$content .= $main->get_analytics_data('','','');  
	 }
	 
  
     
	   //$content .= $main->get_analytics_graph_data('','',''); 

   $content .='</div>';
  	   	  $content .='<div id="GraphContainer"><svg class="chart"></svg></div>';
	 $content .='

	   <!-- End of Form Container-->
	 </div>
	 
	<!--End of Main Wrapper-->
	</div>
	';  
   }
   }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>HR Tech Europe - Sales</title>
<?php
echo $content;

?> 
</body>
</html>
