$( document ).ready(function() {
	$('[data-court-id]').click(function(){
		event.preventDefault();
		var idCourt = $(this).attr('data-court-id');
		// alert(idCourt);
		
			$.ajax({
	          type: "POST",
	          url: "/site/delete_court",
	          data: "id="+idCourt,
	          success: function(data){    
	          if(data != 'ошибка')
	          	$('[data-court='+data+']').remove();
			  else 
			  	alert('ошибка');
	          }
	        });
    });
});




