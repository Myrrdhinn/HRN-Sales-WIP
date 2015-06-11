 $(document).ready(function(){
	      //****************************			  
	//INPUT FIELD SEARCH  (rest of it is in the vendor/autocomplete/src)
	//*****************************
		$.ajax({
		url: 'controllers/ajax.php',
		type: 'POST',
		dataType:'json',
		data: {action:"get_search_data"},
		success: function(data) {
		
				
				$("#SearchField").tokenInput(data, {
					theme: "facebook",
					minChars: 2,
					preventDuplicates: true
				});
				 

			

		}
	});

	       	/*-----------------------
		 Change inside the element
	    ------------------------	*/
		/*
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
  */
  
  	   $('#Callbacks').bind('change', function (e) {
		  
		  var callback = $(this).val();
		 		  var tmembers = $(this).val();
		  		  $('#loadingtext').fadeIn();  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'pitch_list_change', callback:callback},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								$('#loadingtext').fadeOut();
                                 $('#Pitch_Container').html(data);
								 
							}
							
						}
					}); 

	
     })  
	 
	 
	   	$('#TeamMembers').bind('change', function (e) {
		  
		  var tmembers = $(this).val();
		  		  $('#loadingtext').fadeIn();  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'pitch_list_change', tmembers:tmembers},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								$('#loadingtext').fadeOut();
                                 $('#Pitch_Container').html(data);
								 
							}
							
						}
					});

	
     })  
  
  
  
  	   	$('#Months').bind('change', function (e) {
		  
		  var month = $(this).val();			
		  $('#loadingtext').fadeIn();  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'pitch_list_change', month:month},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								$('#loadingtext').fadeOut();
                                 $('#Pitch_Container').html(data);
								 
							}
							
						}
					});
					
			  
			  
		
	
     })  
  

  
  
  	   	$('#Teams').bind('change', function (e) {
		  
		  var teams = $(this).val();
		  		  $('#loadingtext').fadeIn();  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'pitch_list_change', teams:teams},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								 $('#loadingtext').fadeOut();
                                 $('#Pitch_Container').html(data);
								 
							}
							
						}
					});
					
		
	
     })  
  
  
    $('#PitchType').bind('change', function (e) {
		  
		  var pitch = $(this).val();			
		  $('#loadingtext').fadeIn();  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'pitch_list_change', pitch:pitch},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								 $('#loadingtext').fadeOut();
                                 $('#Pitch_Container').html(data);
								 
							}
							
						}
					});
					
			  
			  
		
	
     }) 
	 
	 
	     $('#PitchResult').bind('change', function (e) {
		  
		  var result = $(this).val();			
		  $('#loadingtext').fadeIn();  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'pitch_list_change', result:result},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								 $('#loadingtext').fadeOut();
                                 $('#Pitch_Container').html(data);
								 
							}
							
						}
					});
					
			  
			  
		
	
     }) 
	
	
	     $('#ClearFilters').bind('click', function (e) {
		 	$('#loadingtext').fadeIn();	  
		    location.reload();	  
		
	
     }) 	
	
	  
	   
      });


function edit_data(elem) {
		    //if the save button pressed
			
			            //prevents the default form submit

		  
		  //get the data
		  var pitch_num = $(elem).data('pitchnum');
		  

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
	
}

function change_order(order){
		$('#loadingtext').fadeIn();
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'pitch_list_order_change', order:order},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								$('#loadingtext').fadeOut();
                                 $('#Pitch_Container').html(data);
								 
							}
							
						}
					});
					
			  
			  
		
	
}