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
	 
	 /*
	// Function to autmatically set today's date to the date input field
	 
    Date.prototype.toDateInputValue = (function() {
       var local = new Date(this);
       local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
       return local.toJSON().slice(0,10);
     });

       $("#CallBackDate").val(new Date().toDateInputValue());
	   
	   */
	   
	   
	       //if they are red (content is missing), but the content changes
	   $('.AdminInputField').bind('change', function () {
		   var content = $(this).val();
		   
		   if (content !== '' && typeof content != 'undefined') {
			   $(this).css("border","1px solid #cccccc");
		   }
	   
	   
	     }) 
		 //if they are red (content is missing), but the content changes
		 $('.SelectClass').bind('change', function () {
		   var content = $(this).val();
		   
		   if (content !== '' && typeof content != 'undefined') {
			   $(this).css("border","1px solid #cccccc");
		   }
	   
	   
	     }) 
		 
		 //if they want to add a new country
		 $('#Country').bind('change', function () {
		   var content = $(this).val();
		   
		   if (content == 'Other') {
			   $('#UploadCountry').fadeIn('slow');
		   } else {
			   var style = $('#UploadCountry').attr('style');
			   if (typeof stlye =='undefined') {
				   $('#UploadCountry').fadeOut('slow');
			   }
			   
		   }
	   
	   
	     }) 
		 
		 
		 
	   
	       	/*-----------------------
		 Change inside the element
	    ------------------------	*/
      $('#NewPitchSave').bind('click', function (e) {
		    //if the save button pressed
			
			            //prevents the default form submit
		  			e.preventDefault();
		            e.stopPropagation(); 
		          save_data("Save_Pitch");
		
	   
	
  })  
  
  	       	/*-----------------------
		 Change inside the element
	    ------------------------	*/
      $('#EditPitchSave').bind('click', function (e) {
		    //if the save button pressed
			
			            //prevents the default form submit
		  			e.preventDefault();
		            e.stopPropagation(); 
		          save_data("Edit_Pitch_Data");
		
	   
	
  })  
  
  
 	       //On pitch type select filed change
	   $('#PitchType').bind('change', function () {
		   var content = $(this).val();
		   
		   if (content !== '' && typeof content != 'undefined' && content == 1) {
			  $('#PitchResult option').each(function() {
				    var val = $(this).attr('value');
					
					if (val == 4) {
					  	$(this).attr('selected','selected');
					}
				});  
				$('#SellPrice').val(0);  
			    $('#DealHolder').fadeIn();
		   }
	   
	   
	     })  
		 
		  $('#PitchResult ').bind('change', function () {
		   var content = $(this).val();
		   
		   if (content !== '' && typeof content != 'undefined' && (content == 4 || content == 3)) {
 
			    $('#DealHolder').fadeIn();
		   } else {
			    $('#DealHolder').fadeOut();
		   }
	   
	   
	     })  
		 
		
  
  
	$('textarea[maxlength]').keyup(function(){
		//get the limit from maxlength attribute
		var limit = parseInt($(this).attr('maxlength'));
		//get the current text inside the textarea
		var text = $(this).val();
		//count the number of characters in the text
		var chars = text.length;
		
		//check if there are more characters then allowed
		if(chars > limit){
			//and if there are use substr to get the text before the limit
			var new_text = text.substr(0, limit);
			
			//and change the current text with the new text
			$(this).val(new_text);
		}
	});
  
	   
	   
      });


function save_data(mode) {
	
  //get the data
		  var name = $('#Name').val();
		  var DelegateTitle = $('#DelegateTitle').val();
		  var CompanyName = $('#CompanyName').val();
		  var Country = $('#Country').val();
		  var PitchType = $('#PitchType').val();
		  var CallBackDate = $('#CallBackDate').val();
		  var PitchResult = $('#PitchResult').val();
		  var NumberOfDeals = $('#NumberOfDeals').val();
		  var Reason = $('#Reason').val();
		  var Edit = $('#Edit_val').val();
		  var Price = $('#SellPrice').val(); 
		  var DateOfPitch = $('#DateOfPitch').val();
		  
		  if ((typeof Edit == "undefined") || Edit == '') {
			  Edit = -1;
		  }
		  
		       //Ha VIP a pitch akkor a price mindig 0
		  if (PitchResult == 4){
			  Price = 0;

		  }
		  
		  
		  	   //check if the fields are filled out or not
			if ((typeof name != "undefined") && name != '' &&  (typeof DelegateTitle != "undefined") && DelegateTitle != '' && (typeof CompanyName != "undefined") && CompanyName != '' &&
			(typeof Country != "undefined") && Country != '' && (typeof PitchType != "undefined") && PitchType != '' && (typeof PitchResult != "undefined") && PitchResult != '' && (typeof DateOfPitch != "undefined") && DateOfPitch != '') {
				       
                   if (typeof NumberOfDeals == "undefined" || typeof Price == "undefined" || PitchResult == 3 || PitchResult == 4){
							 if ($.isNumeric(NumberOfDeals) == false || $.isNumeric(Price) == false || (Price == 0 && PitchResult == 3) || ((PitchResult == 3 || PitchResult == 4) && NumberOfDeals == 0) ){
								 
								 
									  if ($.isNumeric(NumberOfDeals) == false) {
										  $('#NumberOfDeals').css("border","1px solid #9B1515");
									  } else {
										  $('#NumberOfDeals').css("border","1px solid #cccccc");
									  }
											
									  if ($.isNumeric(Price) == false || (Price == 0 && PitchResult == 3)) {
										  $('#SellPrice').css("border","1px solid #9B1515");
									  } else {
										  $('#SellPrice').css("border","1px solid #cccccc");
									  }	
									  
									  if ((PitchResult == 3 || PitchResult == 4) && NumberOfDeals == 0)	 {
										  
									    $('#NumberOfDeals').css("border","1px solid #9B1515");
									  } else {
										  $('#NumberOfDeals').css("border","1px solid #cccccc");
									  }
								 
							 } else {
								 
									 $.ajax({
									  url: 'controllers/ajax.php',
									  type: 'POST',
									  data: {action:mode, name:name, DelegateTitle:DelegateTitle, 
									  CompanyName:CompanyName, Country:Country, PitchType:PitchType, CallBackDate:CallBackDate, 
									  PitchResult:PitchResult, NumberOfDeals:NumberOfDeals, Reason:Reason, Edit:Edit, Price:Price, DateOfPitch:DateOfPitch},
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
				
						 //if they are, send the data to the ajax file
						 
								 $.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:mode, name:name, DelegateTitle:DelegateTitle, 
						CompanyName:CompanyName, Country:Country, PitchType:PitchType, CallBackDate:CallBackDate, 
						PitchResult:PitchResult, NumberOfDeals:NumberOfDeals, Reason:Reason, Edit:Edit, Price:Price, DateOfPitch:DateOfPitch},
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
		
		        
			    //if stuff is missing
				window.location.hash = '#ReturnValue';
				$("#ReturnValue").html('<i class="fa fa-exclamation-triangle"></i> Please, fill out the missing fields!');
				$("#ReturnValue").css("color","#9B1515");
				$("#ReturnValue").fadeIn('slow');
				
				
			    if (typeof name == "undefined" || name == '') {
					$('#Name').css("border","1px solid #9B1515");
				} else {
					$('#Name').css("border","1px solid #cccccc");
				}
	
	
	
			    if (typeof DelegateTitle == "undefined" || DelegateTitle == '') {
					$('#DelegateTitle').css("border","1px solid #9B1515");
				} else {
					$('#DelegateTitle').css("border","1px solid #cccccc");
				}
	
				 if (typeof DateOfPitch == "undefined" || DateOfPitch == '') {
					$('#DateOfPitch').css("border","1px solid #9B1515");
				} else {
					$('#DateOfPitch').css("border","1px solid #cccccc");
				}
	
	
				if (typeof CompanyName == "undefined" || CompanyName == '') {
					$('#CompanyName').css("border","1px solid #9B1515");
				} else {
					$('#CompanyName').css("border","1px solid #cccccc");
				}
				
				
				if (typeof Country == "undefined" || Country == '') {
					$('#Country').css("border","1px solid #9B1515");
				} else {
					$('#Country').css("border","1px solid #cccccc");
				}
				
				
				if (typeof PitchType == "undefined" || PitchType == '') {
					$('#PitchType').css("border","1px solid #9B1515");
				} else {
					$('#PitchType').css("border","1px solid #cccccc");
				}
				
				
				if (typeof PitchResult == "undefined" || PitchResult == '') {
					$('#PitchResult').css("border","1px solid #9B1515");
				} else {
					$('#PitchResult').css("border","1px solid #cccccc");
				}
				
			   if ($.isNumeric(NumberOfDeals) == false) {
					$('#NumberOfDeals').css("border","1px solid #9B1515");
				} else {
					$('#NumberOfDeals').css("border","1px solid #cccccc");
				}
					  
				if ($.isNumeric(Price) == false || (Price == 0 && PitchResult == 3)) {
					$('#SellPrice').css("border","1px solid #9B1515");
				} else {
					$('#SellPrice').css("border","1px solid #cccccc");
				}	
				
				

			}	
	
	
}