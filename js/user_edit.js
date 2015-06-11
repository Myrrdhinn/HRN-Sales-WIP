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
	 
	 

	       	/*-----------------------
		 Change inside the element
	    ------------------------	*/
      $('#EditUserData').bind('click', function (e) {
		  			            //prevents the default form submit
		  			e.preventDefault();
		            e.stopPropagation(); 
		  
		    //if the save button pressed
			var check = $("#Member").prop('checked');
			

			var sId = $(this).data('userid');
			var password = $("#Password").val();
			var password_check = $("#Password_Check").val();
			var user_type = $("#UserType").val();
			
			  //if new password is set
			if (typeof password != "undefined" && password != '') {
				 //check if the two password fields match
			       if (password != password_check){
					 
					  window.location.hash = '#ReturnValue';
					  $("#ReturnValue").html('<i class="fa fa-exclamation-triangle"></i> The passwords do not match!');
					  $("#ReturnValue").css("color","#9B1515");
					  $("#ReturnValue").fadeIn('slow');
						   
					  
					   $('#Password').css("border","1px solid #9B1515");
					   $('#Password_Check').css("border","1px solid #9B1515");
						   
						   
					} else {
						//if they match, we send the password with the ajax request
					   var new_pass = password;

				     }
				
			} else {
			  //if the password is not filled in
			    var new_pass = '-142HRDoge'; //a this is unlikely to be a password 	
			}
               
	
	  $.ajax({
		  url: 'controllers/ajax.php',
		  type: 'POST',
		  data: {action:"edit_user_data_save", new_pass:new_pass, user_type:user_type, sId:sId},
		  success: function(data) {
			  
			  if (data != '' && typeof data != 'undefined'){
				   //if everything is okay, reload the page.
					window.location.hash = '#ReturnValue';
					 window.location.reload(true);
			  }
		  }
	  });	

			
	   
	
  })  


 });