$( document ).ready(function() {
	$('.add').click(function(){
		
		var idPhoto = $(this).attr('data-id-photo');
		var trNum = $(this).attr('data-num-tr');

		// alert(idPhoto + ' ' + trNum);
		
			$.ajax({
	          type: "POST",
	          url: "/admin/add_photo",
	          data: "id="+idPhoto+"&&tr="+trNum,
	          success: function(data){    
	          if(data != 'ошибка')
	          	$('[data-tr='+data+']').remove();
			  else 
			  	alert('ошибка');
	          }
	        });
    });

    $('.delete').click(function(){
		
		var idPhoto = $(this).attr('data-id-photo');
		var trNum = $(this).attr('data-num-tr');

		// alert(idPhoto + ' ' + trNum);
		
			$.ajax({
	          type: "POST",
	          url: "/admin/delete_photo",
	          data: "id="+idPhoto+"&&tr="+trNum,
	          success: function(data){    
	          if(data != 'ошибка')
	          	$('[data-tr='+data+']').remove();
			  else 
			  	alert('ошибка');
			  
	          }
	        });
    });

    $('.show').click(function(){
		
		var idDistrict = $(this).attr('data-district-id');

		// alert(idDistrict);
		
			$.ajax({
	          type: "POST",
	          url: "/admin/show",
	          data: "id="+idDistrict,
	          success: function(data){              
			  	// alert(data);
			  	$('.courtDistrict').html(data);
			  
	          }
	        });
    });

	

});




