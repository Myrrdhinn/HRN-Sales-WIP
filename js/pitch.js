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
		  
		  var team = $('#TeamMembers').val();
		  
		  if (typeof callback != 'undefined') {
			  if (callback == 'All') {
				    var data = 'All'; 
			  } else {
				  
				  if (typeof team !='undefined' && team != '' && team !=-1 && team !='All'){
					  category = 'user,callback';
					  data = team+','+callback;
				  } else {
					 category = 'callback'
					 data = callback; 
				  }
				  
			  }
			
			  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'pitches_filter', data:data, category:category},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 $('#Pitch_Container').html(data);
								 
							}
							
						}
					});
					
			  
			  
		  }
	
     })  
	 
	 
	   	$('#TeamMembers').bind('change', function (e) {
		  
		  var team = $(this).val();
		  var callback = $('#Callbacks').val();
		  
		  if (typeof team != 'undefined') {
			  if (team == 'All') {
				    var data = 'All'; 
			  } else {
				   if (typeof callback !='undefined' && callback != '' && callback !=-1 && callback !='All'){
					  category = 'user,callback';
					  data = team+','+callback;
				  } else {
					 category = 'user'
					 data = team; 
				  }
				  
			  }
			
			  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'pitches_filter', data:data, category:category},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 $('#Pitch_Container').html(data);
								 
							}
							
						}
					});
					
			  
			  
		  }
	
     })  
  
	   
	   
      });

