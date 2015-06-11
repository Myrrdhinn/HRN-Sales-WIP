 $(document).ready(function(){
	 
	 var returnval = $('#ReturnValue').html();
	 
	 if (returnval == 'Success') {
		 
		 $("#ReturnValue").html('<i class="fa fa-check-circle"></i> The data have been saved!');
		 $("#ReturnValue").css("color","#0FB323");
		 $("#ReturnValue").fadeIn('slow');
		 
		 setTimeout(function () {
		$("#ReturnValue").fadeOut('slow');
       } , 3000); //set timeout function end
	 }
	 
	 
	 
  $('#Member').bind('click', function (e) {
		  
           var check = $(this).prop('checked');
					
				if (check == true) {
					$("#Team").fadeIn();
				} else {
					
					$("#Team").fadeOut();
				}
				
				
		})

	       	/*-----------------------
		 Change inside the element
	    ------------------------	*/
      $('#NewUserSave').bind('click', function (e) {
		  			            //prevents the default form submit
		  			e.preventDefault();
		            e.stopPropagation(); 
		  
		    //if the save button pressed
			var check = $("#Member").prop('checked');
			
			if (typeof check != "undefined" && check == true) {
					var member = 1;
				} else {
					var member = 0;
				}
			
			var username = $("#UserName").val();
			var password = $("#Password").val();
			var password_check = $("#Password_Check").val();
			var team = $("#Team").val();
			
			
			if (typeof password != "undefined" && password != '' && typeof username != "undefined" && username != '') {
			     if (password != password_check){
					 
				window.location.hash = '#ReturnValue';
				$("#ReturnValue").html('<i class="fa fa-exclamation-triangle"></i> The passwords do not match!');
				$("#ReturnValue").css("color","#9B1515");
				$("#ReturnValue").fadeIn('slow');
					 
				
				 $('#Password').css("border","1px solid #9B1515");
				 $('#Password_Check').css("border","1px solid #9B1515");
					 
					 
				 } else {
					 
					 
					 if (member == 1) {
							if (typeof team == "undefined" || team == '') {
				                 $('#Team').css("border","1px solid #9B1515");
							
							
							
			                }  else {
								
								 $.ajax({
									url: 'controllers/ajax.php',
									type: 'POST',
									data: {action:"add_new_admin", member:member, team:team, username:username, password:password},
									success: function(data) {
										
										if (data != '' && typeof data != 'undefined'){
											 //if everything is okay, reload the page.
											  window.location.hash = '#ReturnValue';
											   window.location.reload(true);
										}
									}
								});
								
								
								
								
							}
						 
						 
						 
					 } else {
						 if (typeof team == "undefined" || team == '') {
				            team = 0;
			              }
						 
						   
							$.ajax({
							  url: 'controllers/ajax.php',
							  type: 'POST',
							  data: {action:"add_new_admin", member:member, team:team, username:username, password:password},
							  success: function(data) {
								  
								  if (data != '' && typeof data != 'undefined'){
									   //if everything is okay, reload the page.
										window.location.hash = '#ReturnValue';
										window.location.reload(true);
										 
								  }
							  }
						  });
		
					 }//member == 0
					 
				 } //if we sent the ajax request else end
				
			} else {//if password is undefined 
			    window.location.hash = '#ReturnValue';
				$("#ReturnValue").html('<i class="fa fa-exclamation-triangle"></i> Please, fill out the missing fields!');
				$("#ReturnValue").css("color","#9B1515");
				$("#ReturnValue").fadeIn('slow');
			
				
				if (typeof password != "undefined") {
					
					 $('#Password').css("border","1px solid #9B1515");
				}
				
				if (typeof password_check != "undefined") {
					
					 $('#Password_Check').css("border","1px solid #9B1515");
				}
				
				if (typeof username != "undefined") {
					
					 $("#UserName").css("border","1px solid #9B1515");
				}
				
				
			}
               
		    
			
	   
	
  })  


 });