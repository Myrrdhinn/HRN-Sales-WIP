 $(document).ready(function(){
	 
var callnum = {};	 
callnum.calls = $('#CallInputField').val();	


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
      $('#CallSubmit').bind('click', function (e) {
		    //if the save button pressed
			
			            //prevents the default form submit
		  			e.preventDefault();
		            e.stopPropagation(); 
		  
		  //get the data
		  var call_num = $('#CallInputField').val();
		  var less = 0;
		  
		  if (call_num != callnum.calls) {
			  		  	   //check if the fields are filled out or not
			if ((typeof call_num != "undefined") && call_num != '') {
				
				 if (call_num < callnum.calls){
					 less = 1;
					 
				 }
	
					 //if they are, send the data to the ajax file
						 
					 $.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:"save_call_number", call_num:call_num, less:less},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								 //if everything is okay, reload the page.
								   window.location.reload(true);
							}
						}
					});

				}//call_num != undefined 
			  
		  } //if call num != callnum.calls
		  
		  

				
				
				
	
  })  
	   
	   
      });

