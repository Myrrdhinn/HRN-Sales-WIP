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
  
  
  
   $('.CallSubmit').bind('click', function (e) {
	
		e.preventDefault();
	    e.stopPropagation();
	
	var num = $(this).siblings('label').children('.CallInputField').val();
	var id= $(this).data('userid');

	
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
	
  
 })  	
 
 
 
    $('.MinutesSubmit').bind('click', function (e) {
	
		e.preventDefault();
	    e.stopPropagation();
		
	var callnum = $(this).parent('.MinuteContainer').siblings('.CallContainer').children('label').children('.CallInputField').val();
	var id= $(this).data('userid');

	var minutenum = $(this).siblings('label').children('.MinutesInputField').val();
	
	if (typeof id !="undefined" && id != 0) {
		
					  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'save_minutes_num', callnum:callnum, minutenum:minutenum, id:id},
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
	
  
 })  
    
	   
      });

