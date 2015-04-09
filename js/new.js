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
		  
		  //get the data
		  var FirstName = $('#FirstName').val();
		  var LastName = $('#LastName').val();
		  var DelegateTitle = $('#DelegateTitle').val();
		  var CompanyName = $('#CompanyName').val();
		  var Country = $('#Country').val();
		  var PitchType = $('#PitchType').val();
		  var CallBackDate = $('#CallBackDate').val();
		  var PitchResult = $('#PitchResult').val();
		  var NumberOfDeals = $('#NumberOfDeals').val();
		  var Reason = $('#Reason').val();
		  
		  
		  
		  
		  
		  	   //check if the fields are filled out or not
			if ((typeof FirstName != "undefined") && FirstName != '' && (typeof LastName != "undefined") && LastName != '' 
			&&  (typeof DelegateTitle != "undefined") && DelegateTitle != '' && (typeof CompanyName != "undefined") && CompanyName != '' &&
			(typeof Country != "undefined") && Country != '' && (typeof PitchType != "undefined") && PitchType != '' && (typeof PitchResult != "undefined") && PitchResult != '' &&
			(typeof NumberOfDeals != "undefined") && NumberOfDeals != '') {
				
				if (Country == "Other") {
					 //if the person wants to upload a new country
					 var NewCountryName = $('#NewCountryName').val();
					 var NewCountryCode = $('#NewCountryCode').val(); 
					
					 if ((typeof NewCountryName != "undefined") && NewCountryName != '' && (typeof NewCountryCode != "undefined") && NewCountryCode != '') {
						 //if the fields are filled
						 
														 //if they are, send the data to the ajax file
								 
										 $.ajax({
								url: 'controllers/ajax.php',
								type: 'POST',
								data: {action:"Save_Pitch", FirstName:FirstName, LastName:LastName, DelegateTitle:DelegateTitle, 
								CompanyName:CompanyName, Country:Country, PitchType:PitchType, CallBackDate:CallBackDate, 
								PitchResult:PitchResult, NumberOfDeals:NumberOfDeals, Reason:Reason, NewCountryName:NewCountryName, NewCountryCode:NewCountryCode},
								success: function(data) {
									
									if (data != '' && typeof data != 'undefined'){
										 //if everything is okay, reload the page.
										   window.location.hash = '#ReturnValue';
										   window.location.reload(true);
									}
								}
							});
								
						 
						 
					 }  else {
					    //if the country IS Other and the fields are empty
						
									
						//if stuff is missing
							window.location.hash = '#ReturnValue';
							$("#ReturnValue").html('<i class="fa fa-exclamation-triangle"></i> Please, fill out the missing fields!');
							$("#ReturnValue").css("color","#9B1515");
							$("#ReturnValue").fadeIn('slow');
							
							
							if (typeof NewCountryName == "undefined" || NewCountryName == '') {
								$('#NewCountryName').css("border","1px solid #9B1515");
							} else {
								$('#NewCountryName').css("border","1px solid #cccccc");
							}
				
				
							if (typeof NewCountryCode == "undefined" || NewCountryCode == '') {
								$('#NewCountryCode').css("border","1px solid #9B1515");
							} else {
								$('#NewCountryCode').css("border","1px solid #cccccc");
							}
				
						
						
					 }
				} else { //if the country is NOT Other
				
						 //if they are, send the data to the ajax file
						 
								 $.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:"Save_Pitch", FirstName:FirstName, LastName:LastName, DelegateTitle:DelegateTitle, 
						CompanyName:CompanyName, Country:Country, PitchType:PitchType, CallBackDate:CallBackDate, 
						PitchResult:PitchResult, NumberOfDeals:NumberOfDeals, Reason:Reason},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
								 //if everything is okay, reload the page.
								   window.location.hash = '#ReturnValue';
								   window.location.reload(true);
							}
						}
					});
					
				}//if the country is NOT other ELSE end
				
			} else {
		
			    //if stuff is missing
				window.location.hash = '#ReturnValue';
				$("#ReturnValue").html('<i class="fa fa-exclamation-triangle"></i> Please, fill out the missing fields!');
				$("#ReturnValue").css("color","#9B1515");
				$("#ReturnValue").fadeIn('slow');
				
		if (Country == "Other") {
				
				if (typeof NewCountryName == "undefined" || NewCountryName == '') {
					$('#NewCountryName').css("border","1px solid #9B1515");
				} else {
					$('#NewCountryName').css("border","1px solid #cccccc");
				}
	
	
				if (typeof NewCountryCode == "undefined" || NewCountryCode == '') {
					$('#NewCountryCode').css("border","1px solid #9B1515");
				} else {
					$('#NewCountryCode').css("border","1px solid #cccccc");
				}
	
		   } //if country == other end
				
			    if (typeof FirstName == "undefined" || FirstName == '') {
					$('#FirstName').css("border","1px solid #9B1515");
				} else {
					$('#FirstName').css("border","1px solid #cccccc");
				}
	
	
				if (typeof LastName == "undefined" || LastName == '') {
					$('#LastName').css("border","1px solid #9B1515");
				} else {
					$('#LastName').css("border","1px solid #cccccc");
				}
	
	
			    if (typeof DelegateTitle == "undefined" || DelegateTitle == '') {
					$('#DelegateTitle').css("border","1px solid #9B1515");
				} else {
					$('#DelegateTitle').css("border","1px solid #cccccc");
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
				
				
				if (typeof NumberOfDeals == "undefined" || NumberOfDeals == '') {
					$('#NumberOfDeals').css("border","1px solid #9B1515");
				} else {
					$('#NumberOfDeals').css("border","1px solid #cccccc");
				}

			}
	   
	
  })  
	   
	   
      });
