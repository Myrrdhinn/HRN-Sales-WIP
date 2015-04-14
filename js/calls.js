 $(document).ready(function(){
	 



	 
	   	$('#TeamMembers').bind('change', function (e) {
		  
		  var team = $(this).val();

		  
		  if (typeof team != 'undefined') {

			  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'callrate_user_filter', team:team},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 $('#Pitch_Container').html(data);								
								 
							}
							
						}
					});
					
			  
			  
		  }
	
     })  
  
  
  
	   
	   
      });


function save_call_num(id) {

	
	var num = $('#CallInputField').val();
	
	if (typeof num != "undefined" && num !=0 && typeof id !="undefined" && id != 0) {
		
					  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'save_call_num', num:num, id:id},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								 $("#ReturnValue").html('<i class="fa fa-check-circle"></i> The data have been saved!');
								 $("#ReturnValue").css("color","#0FB323");
								 $("#ReturnValue").fadeIn('slow');
								 
								 
								 /*
								 ----------------
								 And we need to refresh the data on the page
																 */
										 var team = $('#TeamMembers').val();
								
										  
										  if (typeof team != 'undefined') {
								
											  
													$.ajax({
														url: 'controllers/ajax.php',
														type: 'POST',
														data: {action:'callrate_user_filter', team:team},
														success: function(data) {
															
															if (data != '' && typeof data != 'undefined'){
																 $('#Pitch_Container').html(data);								
																 
															}
															
														}
													});
													
											  
											  
										  }
																 
								 /*
								 --------------------------
								 */
								 setTimeout(function () {
								$("#ReturnValue").fadeOut('slow');
							   } , 3000); //set timeout function end					
														 
													}
													
													
													
							
						}
					});
		
		
	}
	
}