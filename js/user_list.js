 $(document).ready(function(){
	 	 
	 
	 
  $('.UserClass').bind('click', function (e) {
		  
           var sId = $(this).data('userid');
		   
				 $.ajax({
					url: 'controllers/ajax.php',
					type: 'POST',
					data: {action:"user_edit_request", sId:sId},
					success: function(data) {
						
				    setTimeout(function () {
                         window.location.replace("user_edit");
                      }, 200); //will call the function after 1 secs.
					  
					  
					}
				});
				
				
		})



 });