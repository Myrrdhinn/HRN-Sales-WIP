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

