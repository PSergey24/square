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

    $('.edit').click(function(){
		
		var tr = $(this).attr('data-tr-num');
		var lat = $('[data-tr = '+tr+']').attr("data-lat");
		var lon = $('[data-tr = '+tr+']').attr("data-lon");

		var id = $('[data-tr = '+tr+'] .item-id').html();
		var address = $('[data-tr = '+tr+'] .item-address').html();
		var name = $('[data-tr = '+tr+'] .item-name').html();
		var area = $('[data-tr = '+tr+'] .item-area').html();
		var district = $('[data-tr = '+tr+'] .item-district').html();
		var type = $('[data-tr = '+tr+'] .item-type').html();

		$('#input-address').attr("value",address);
		$('#input-name').attr("value",name);
		$('#input-area').attr("value",area);
		$('#input-district').attr("value",district);
		$('#input-type').attr("value",type);
		$('#input-id').attr("value",id);
		$('#input-lat').attr("value",lat);
		$('#input-lon').attr("value",lon);

		// var geocoder = new google.maps.Geocoder;
  //           geocoder.geocode({'location': latlng}, function(results, status) {
  //           if (status === 'OK') {
  //           	alert('ok');
  //               //set address string to input
  //               // $('#court-address').val(results[0].formatted_address);
  //           //                        console.log(results);
  //           }
  //       });
		// alert(id);

		// alert(idDistrict);
		

    });

	

});




