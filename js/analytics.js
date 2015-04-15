 
 $(document).ready(function(){

   var d = new Date();
    var n = d.getMonth();
	 n++;
	 
var year = new Date().getFullYear();	 
var first_week = rangeWeek(year+'/0'+n+'/1'); //last day of the first week with date
var first_day = first_week.substring(8,10); //day of the first week
var first_month = first_week.substring(4,7); //month of the first day


var days = daysInMonth(n,year); //days on this month


					 if (first_day != '01') {

						  $('#Weeks')
						 .append($("<option></option>")
						 .attr("value","01-"+first_day)
						 .text("01-"+first_day));
					 }


                   for (i = 0; i < 3; i++) { 
				    var day = (parseInt(first_day)+7 * i+1).toString();
					var next =(parseInt(first_day)+7 * i +7).toString();
				   
                     $('#Weeks')
					 .append($("<option></option>")
					 .attr("value",day+"-"+next)
					 .text(day+"-"+next));
                     }

			next++;
					 
				     $('#Weeks')
					 .append($("<option></option>")
					 .attr("value",next+"-"+days)
					 .text(next+"-"+days));	
	 
	       	/*-----------------------
		 Change inside the element
	    ------------------------	*/

	   
	   
	   $('#Days').bind('change', function (e) {
			var month = $('#Months').val();
			 
			 if(month == -1 || month == 'All') {
				 month = '';
			 }
			 
			var country = $('#Country').val();
			
			if(country == -1 || country == 'All' || typeof country == 'undefined') {
				 country = '';
			 }
			 
		  
		  var day = $(this).val();
		  
		  if (typeof day != 'undefined') {
			  if (day == 'All') {
				    var data = 'All'; 
			  } else {
				  var data = '%'+day+' '+month+'%';   
			  }
			
			  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'analytics_date_filter', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 $('#MainAnalContent').html(data);
								 
							}
							
						}
					});
					
										/*Graph*/
					
								  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						dataType: "json",
						data: {action:'analytics_graph_filter', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 show_graph(data);								
								 allofit.data = data;								
								 
							}
							
						}
					});
					
					
			  
			  
		  }
	
     })  
	 
	 /*
	 ------------
	 Weeks
	 -------------
	 */
	 $('#Weeks').bind('change', function (e) {
		 
		  var country = $('#Country').val();
			
			if(country == -1 || country == 'All' || typeof country == 'undefined') {
				 country = '';
			 }
			 
			var weeks = $(this).val();
			var month = $('#Months').val();
			 
			 if(month == -1 || month == 'All' || typeof month == 'undefined') {
				    var d = new Date();
                    var n = d.getMonth();
	                   n++;
				 month = n;
			 } else {
				 month = getMonthFromString(month);
			 }
			 
           if (weeks != 'All') {
            //we need to reset the days
			$('#Days')
	            .find('option')
                .remove()
                 .end();
				 
				    $('#Days')
					 .append($("<option></option>")
					 .attr("value",-1)
					 .attr("hidden","hidden")
					 .text("Day"));
					 
					$('#Days')
					 .append($("<option></option>")
					 .attr("value","All")
					 .text("All"));
					 
					var line_number = weeks.search("-"); 
					var start = weeks.substr(0, line_number);
					var end = weeks.substr(line_number+1,weeks.length);
					end++; 
					 
				for (i = parseInt(start); i < parseInt(end); i++) { 
				 	$('#Days')
					 .append($("<option></option>")
					 .attr("value",i)
					 .text(i));
				 }
					 
		   } else {
			   			$('#Days')
	            .find('option')
                .remove()
                 .end();
				 
				    $('#Days')
					 .append($("<option></option>")
					 .attr("value",-1)
					 .attr("hidden","hidden")
					 .text("Day"));
					 
					$('#Days')
					 .append($("<option></option>")
					 .attr("value","All")
					 .text("All"));
			   
			   
			   	for (i = 1; i < 32; i++) { 
				 	$('#Days')
					 .append($("<option></option>")
					 .attr("value",i)
					 .text(i));
				 }
		   }
			
		  //------------------------
		  if (typeof weeks != 'undefined') {
			  if (weeks == 'All') {
				   //if weeks = all
				  var data = 'All'; 
				  
				  	$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'analytics_date_filter', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 $('#MainAnalContent').html(data);
								 
							}
							
						}
					});
					
				  					/*Graph*/
					
								  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						dataType: "json",
						data: {action:'analytics_graph_filter', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 show_graph(data);								
								 allofit.data = data;							
								 
							}
							
						}
					});
				  
				 
				   //if weeks = all end
			  } else {
				  
				  //if weeks != all	
				  var data = 'intervall,'+weeks+','+month;   
			  
			 
			
			  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'analytics_date_filter_intervall', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 $('#MainAnalContent').html(data);
								 
							}
							
						}
					});
					
										/*Graph*/
								  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						dataType: "json",
						data: {action:'analytics_graph_filter_intervall', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 show_graph(data);								
								 allofit.data = data;							
								 
							}
							
						}
					});
					
				}//if weeks != all	end
			  
			  
		  }
	
     })  
	 
	    
		
	   $('#Months').bind('change', function (e) {
		   	var country = $('#Country').val();
			
			if(country == -1 || country == 'All' || typeof country == 'undefined') {
				 country = '';
			 }
			 
		   var month = $(this).val();
		   
		   			//delete the old options
			    $('#Weeks')
	            .find('option')
                .remove()
                 .end();
				 
				 //reset days
				 $('#Days')
	            .find('option')
                .remove()
                 .end();
				 
				    $('#Days')
					 .append($("<option></option>")
					 .attr("value",-1)
					 .attr("hidden","hidden")
					 .text("Day"));
					 
					$('#Days')
					 .append($("<option></option>")
					 .attr("value","All")
					 .text("All"));
				 
				 for (i = 1; i < 32; i++) { 
				 	$('#Days')
					 .append($("<option></option>")
					 .attr("value",i)
					 .text(i));
				 }
	 
				 
	   if (month != 'All') {
		   //Update the week select box
			var year = new Date().getFullYear();	 
			var first_week = rangeWeek(year+'/'+month+'/1'); //last day of the first week with date
			var first_day = first_week.substring(8,10); //day of the first week
			var first_month = first_week.substring(4,7); //month of the first day
			
			
			var days = daysInMonth(getMonthFromString(month),year); //days on this month
			

				 //add the temp ones:
				   $('#Weeks')
					 .append($("<option></option>")
					 .attr("value",-1)
					 .attr("hidden","hidden")
					 .text("Week"));
					 
					$('#Weeks')
					 .append($("<option></option>")
					 .attr("value","All")
					 .text("All"));
				 
				 
		         //start the date stuff
					 
					 if (first_day != '01') {

						  $('#Weeks')
						 .append($("<option></option>")
						 .attr("value","01-"+first_day)
						 .text("01-"+first_day));
					 }


                   for (i = 0; i < 3; i++) { 
				    var day = (parseInt(first_day)+7 * i+1).toString();
					var next =(parseInt(first_day)+7 * i +7).toString();
				   
                     $('#Weeks')
					 .append($("<option></option>")
					 .attr("value",day+"-"+next)
					 .text(day+"-"+next));
                     }

			next++;
					 
				     $('#Weeks')
					 .append($("<option></option>")
					 .attr("value",next+"-"+days)
					 .text(next+"-"+days));	
	 
		   }
		   
		   //-----------------
			var days = $('#Days').val();
			 
			 if(days == -1 || days == 'All') {
				 days = '';
			 }
		  
		  
		  
		  if (typeof month != 'undefined') {
			  if (month == 'All') {
				    var data = 'All'; 
			  } else {
				  var data = '%'+days+' '+month+'%';   
			  }
			
			  
			  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'analytics_date_filter', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 $('#MainAnalContent').html(data);
							}
							
						}
					});
					
										/*Graph*/
					
								  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						dataType: "json",
						data: {action:'analytics_graph_filter', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 show_graph(data);								
								 allofit.data = data;							
								 
							}
							
						}
					});
			  
			  
			  
		  }
	
     }) 
	 
	 
	 //Country change
	 $('#Country').bind('change', function (e) {
				var month = $('#Months').val();
				
				 
				 if(month == -1) {
					 month = '';
				 }
				 
				var days = $('#Days').val();
				 
				 if(days == -1 || days == 'All') {
					 days = '';
				 }
			  
			
		  
		  var country = $(this).val();
		  
		  if (typeof country != 'undefined') {
			  
			  if (country == 'All') {
				    country = ''; 
			  }
			  var weeks = $('#Weeks').val();
			  
			  
			  if(days == '' && typeof weeks != 'undefined' && weeks != 'All' && weeks != '' && weeks !=-1 && month != 'All') {
				  if (month != '') {
					  month = getMonthFromString(month);
				  } else {
				    var d = new Date();
                    var n = d.getMonth();
	                   n++;
				       month = n;
					  
				  }
				   
			        var data = 'intervall,'+weeks+','+month; 

					
					$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						data: {action:'analytics_date_filter_intervall', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 $('#MainAnalContent').html(data);
								 
							}
							
						}
					});
					
										/*Graph*/
					
								  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						dataType: "json",
						data: {action:'analytics_graph_filter_intervall', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 show_graph(data);								
								 allofit.data = data;							
								 
							}
							
						}
					});
			  
		    }  else {
					 //if the weeks in undefined or not specified
					if ((month == '' && days == '') || month == 'All' || days == 'All') {
						 var data = 'All'; 
					} else {
						var data = '%'+days+' '+month+'%'; 	
					}
					  
					  
							$.ajax({
								url: 'controllers/ajax.php',
								type: 'POST',
								data: {action:'analytics_date_filter', data:data, country:country},
								success: function(data) {
									
									if (data != '' && typeof data != 'undefined'){
										 $('#MainAnalContent').html(data);
										 
									}
									
								}
							});
							
							
												/*Graph*/
					
								  		$.ajax({
						url: 'controllers/ajax.php',
						type: 'POST',
						dataType: "json",
						data: {action:'analytics_graph_filter', data:data, country:country},
						success: function(data) {
							
							if (data != '' && typeof data != 'undefined'){
                                 show_graph(data);								
								 allofit.data = data;							
								 
							}
							
						}
					});
					
			  }//if not intervall else ends
			  
		  }//type of country not undefined
	
     })  
	   
      });


function container_display(e) {
		 var dates = $(e).children('.Dates');
		 var id = $(e).data('unum');
		
			
		  if (dates.css('display') == 'none'){
			  $(e).children('.TotalContainer').css('background-color','rgba(27, 173, 179, 0.65)');
			   $(e).children('.TotalContainer').css('font-weight',600);
			  dates.fadeIn('slow');
			
			  show_graph(allofit.data.users[id]);
		  } else {
			  dates.fadeOut('slow');
			  $(e).children('.TotalContainer').css('background-color','rgba(34, 116, 116, 0.11)');
			  $(e).children('.TotalContainer').css('font-weight',300);
			  show_graph(allofit.data);
		  }
		  
	
}


//Date stuff :)
function rangeWeek (dateStr) {
    if (!dateStr) dateStr = new Date().getTime();
    var dt = new Date(dateStr);
    dt = new Date(dt.getFullYear(), dt.getMonth(), dt.getDate());
    dt = new Date(dt.getTime() - (dt.getDay() > 0 ? (dt.getDay() - 1) * 1000 * 60 * 60 * 24 : 6 * 1000 * 60 * 60 * 24));
    return /*(dt.toString()).substring(0, 15)+','+*/((new Date(dt.getTime() + 1000 * 60 * 60 * 24 * 7 - 1)).toString()).substring(0, 15);
}


function daysInMonth(month,year) {
    return new Date(year, month, 0).getDate();
}

function getMonthFromString(mon){

   var d = Date.parse(mon + "1, 2012");
   if(!isNaN(d)){
      return new Date(d).getMonth() + 1;
   }
   return -1;
 }
