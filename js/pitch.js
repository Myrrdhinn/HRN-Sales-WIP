 $(document).ready(function(){
	 

	       	/*-----------------------
		 Change inside the element
	    ------------------------	*/
      $('.TableCol').bind('click', function (e) {
		    //if the save button pressed
			
			            //prevents the default form submit
		  			e.preventDefault();
		            e.stopPropagation(); 
		  
		  //get the data
		  var pitch_num = $(this).data('pitchnum');
		  

			  		  	   //check if the fields are filled out or not
			if ((typeof pitch_num != "undefined") && pitch_num != '') {

	
					 //if they are, send the data to the ajax file
						 
					 $.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:"edit_pitch", pitch_num:pitch_num},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								 //if everything is okay, reload the page.
								   window.location.replace("new");
							}
						}
					});

				}//call_num != undefined 
	
  })  
  
  	   $('#Callbacks').bind('change', function (e) {
		  
		  var callback = $(this).val();
		  
		  if (typeof callback != 'undefined') {
			  if (callback == 'All') {
				    var data = 'All'; 
			  } else {
				  data = callback;
			  }
			
			  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'pitches_callback_filter', data:data},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 $('#Pitch_Container').html(data);
								 
							}
							
						}
					});
					
			  
			  
		  }
	
     })  
  
	   
	   
      });

