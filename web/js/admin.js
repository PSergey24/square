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
		var description = $('[data-tr = '+tr+'] .item-description').html();
		var district = $('[data-tr = '+tr+'] .item-district').attr('data-item-district');
		var type = $('[data-tr = '+tr+'] .item-type').attr('data-item-type');




		$('#input-address').attr("value",address);
		$('#input-name').attr("value",name);
		$('#input-area').attr("value",area);
		$('#input-description').attr("value",description);
		$('#input-district').attr("data-input-district",district);
		$('#input-type').attr("data-input-type",type);
		$('#input-id').attr("value",id);
		$('#input-tr').attr("value",tr);
		$('#input-lat').attr("value",lat);
		$('#input-lon').attr("value",lon);

		for(var i=1;i<19;i++)
		{
			if(i == district)
				$('#input-district [value='+i+']').attr('selected',true);
			else
				$('#input-district [value='+i+']').removeAttr('selected');
		}

		for(var i=1;i<4;i++)
		{
			if(i == type)
				$('#input-type [value='+i+']').attr('selected',true);
			else
				$('#input-type [value='+i+']').removeAttr('selected');
		}
		// $('#input-district [selected="selected"]').remove('selected');
		// $('#input-type [selected="selected"]').remove('selected');

		// $('#input-district [value = '+district+']').attr('selected',true);
		// $('#input-type [value = '+type+']').attr('selected',true);

    });

    $('#deleteCourt').click(function(){
		
		var trNum = $('#input-tr').attr('value');
		var id = $('#input-id').attr('value');

		// alert(id + ' ' + trNum);
		
			$.ajax({
	          type: "POST",
	          url: "/admin/delete_court",
	          data: "id="+id+"&&tr="+trNum,
	          success: function(data){    
	          	if(data != 'ошибка')
	          	$('[data-tr='+data+']').remove();
			  else 
			  	alert('ошибка');
			  
	          }
	        });
    });

    $('#input-district').change(function(){
		var val = $(this).val();
		$(this).attr('data-input-district',val);	
    });

	$('#input-type').change(function(){
		var val = $(this).val();
		$(this).attr('data-input-type',val);	
    });
    $('#input-area').change(function(){
		var val = $(this).val();
		$(this).attr('value',val);	
    });
    $('#input-name').change(function(){
		var val = $(this).val();
		$(this).attr('value',val);	
    });	
    $('#input-address').change(function(){
		var val = $(this).val();
		$(this).attr('value',val);	
    });

    $('#addCourt').click(function(){
		
		var trNum = $('#input-tr').attr('value');
		var id = $('#input-id').attr('value');
		var address = $('#input-address').attr('value');
		var name = $('#input-name').attr('value');
		var area = $('#input-area').attr('value');
		var district = $('#input-district').attr('data-input-district');
		var type = $('#input-type').attr('data-input-type');
		var lat = $('#input-lat').attr('value');
		var lon = $('#input-lon').attr('value');
		var description = $('#input-description').attr('value');

		// alert(district);
		
			$.ajax({
	          type: "POST",
	          url: "/admin/add_court",
	          data: "id="+id+"&&tr="+trNum+"&&address="+address+"&&name="+name+"&&area="+area+"&&district="+district+"&&type="+type+"&&lat="+lat+"&&lon="+lon+"&&description="+description,
	          success: function(data){    
	          if(data != 'ошибка')
	          { 
	          	$('[data-tr='+data+']').remove();
	          }
			  else 
			  	alert('ошибка');
			  
	          }
	        });
    });

    $('.deleteAvatar').click(function(){
		
		var tr = $(this).attr('data-tr-num');
		var picture = $('[data-tr = '+tr+'] img').attr('data-picture');
		var id= $('[data-tr = '+tr+']').attr('data-id-user');
		
			$.ajax({
	          type: "POST",
	          url: "/admin/delete_avatar",
	          data: "id="+id+"&&tr="+tr+"&&picture="+picture,
	          success: function(data){    
	          if(data != 'ошибка')
	          {
	          	$('[data-tr='+data+'] img').attr('src','/img/uploads/default_avatar.jpg');
	          }
			  else 
			  	alert('ошибка');
			  
	          }
	        });
    });
	

});




