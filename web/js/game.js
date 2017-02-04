$( document ).ready(function() {
	$('#toApply').click(function(){
		var typeSport = $('#kind select').val();
		var timeFilter = $('[data-time]').attr('data-time');
		$('[data-near]').attr('data-near', 'no');
		var districtFilter = $('#district select').val();
		$('[data-district]').attr("data-district", districtFilter);
		var min = $('#min').val();
		var max = $('#max').val();

		if((max<min)&&(max != ''))
			$('#errorPeople').html('Не правильно');
		else{
			$('#errorPeople').html('');
			if((min == '')&&(max == ''))
				$('[data-people]').attr("data-people", 'no');
			else
			{
				if(min == '')
					min = 0;

				if(max == '')
					max = 0;

				$('[data-people]').attr("data-people", min+'-'+max);
			}
			
			var peopleFilter = $('[data-people]').attr('data-people');
			$('[data-sport]').attr("data-sport", typeSport);
			$.ajax({
	          type: "POST",
	          url: "/game/apply",
	          data: "typeSport="+typeSport+"&&timeFilter="+timeFilter+"&&peopleFilter="+peopleFilter+"&&districtFilter="+districtFilter,
	          success: function(data){   
	          	var result = data.split(' | ');
	          	num = Number(result[0]);
	          	if(num == 0)
	          	{
	          		$('.game-list').html('<div class="nophoto">'+
                        '<i class="fa fa-futbol-o fa-spin fa-4x fa-fw" aria-hidden="true"></i><br>'+
                        'Игры не найдены.<br>'+
                        'Измени настройки поиска или cоздай игру сам<br>  на любой площадке<br>'+
                        '<a href="/court" class="mid-blue-btn">Найти площадку</a>'+
                    '</div>');
                    	$.each(markers, function (index, value) {
			            		markers[index].setVisible(false);
			            })
	          	}else{
	          		$('.game-list').html(result[1]);
	          		// $('.game-list').append('<button class="mid-blue-btn" id="more" onclick="more()">Еще</button>');

		            $('[data-num-game]').attr("data-num-game", num);
		            $.each(markers, function (index, value) {
		            	markers[index].setVisible(false);
		            })

		            var gameId = result[2].split(' ');
		            for (var i = 0; i < gameId.length; i++) {
		            	$.each(markers, function (index, value) {
			            	if(value['id'] == gameId[i])
			            		markers[index].setVisible(true);
			            })
		            }
	          	}
	          }
	        });

		}
    });

    $('#near').click(function(){
    	if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
            	var lat = position.coords.latitude;
            	var lon = position.coords.longitude;

            	// alert('Широта: '+lat+'; Долгота: '+lon);

            	$('[data-near]').attr('data-near', 'yes');
				$.ajax({
					type: "POST",
		          	url: "/game/near",
		          	data: "lat="+lat+"&&lon="+lon,
			          success: function(data){
			          	var result = data.split(' | ');
			          	num = Number(result[0]);
		          		if(num == 0)
			          	{
			          		$('.game-list').html('<div class="nophoto">'+
		                        '<i class="fa fa-futbol-o fa-spin fa-4x fa-fw" aria-hidden="true"></i><br>'+
		                        'Игры не найдены.<br>'+
		                        'Измени настройки поиска или cоздай игру сам<br>  на любой площадке<br>'+
		                        '<a href="/court" class="mid-blue-btn">Найти площадку</a>'+
		                    '</div>');
			          	}else{
				          	$('.game-list').html(result[1]);

				          	$.each(markers, function (index, value) {
				            	markers[index].setVisible(false);
				            })

				            var gameId = result[2].split(' ');

				            for (var i = 0; i < gameId.length; i++) {
				            	$.each(markers, function (index, value) {
					            	if(value['id'] == gameId[i])
					            		markers[index].setVisible(true);
					            })
				            }
				        }
			          }
		        });

            }, function() {
                    alert("Ошибка: в Вашем браузере данная функция недоступна!");
                }
            );
         }else{
           // Browser doesn't support Geolocation
           alert("Ошибка: Ваш браузер не поддерживает геолокацию!");
         }
    	
    });

    $('.reset').click(function(){
    	$('[data-time]').attr("data-time", 'no');
    	$('[data-sport]').attr('data-sport', 'no');
    	$('[data-people]').attr('data-people', 'no');
    	$('[data-near]').attr('data-near', 'no');
    	$('.gameTime').removeClass('timeSelected');
    	$('#sportList option:selected').removeAttr('selected');
    	$('#district option:selected').removeAttr('selected');
    	$('#errorPeople').html('');
    	$('#min').val('');
    	$('#max').val('');

		$.ajax({
          url: "/game/reset",
          success: function(data){
          	var result = data.split(' | ');
          	num = Number(result[0]);
          		if(num == 0)
	          	{
	          		$('.game-list').html('<div class="nophoto">'+
                        '<i class="fa fa-futbol-o fa-spin fa-4x fa-fw" aria-hidden="true"></i><br>'+
                        'Игры не найдены.<br>'+
                        'Измени настройки поиска или cоздай игру сам<br>  на любой площадке<br>'+
                        '<a href="/court" class="mid-blue-btn">Найти площадку</a>'+
                    '</div>');
	          	}else{
		          	$('.game-list').html(result[1]);
		          	// $('.game-list').append('<button class="mid-blue-btn" id="more" onclick="more()">Еще</button>');
		          	
		            $('[data-num-game]').attr("data-num-game", num);
		           
		            $.each(markers, function (index, value) {
		            	markers[index].setVisible(false);
		            })

		            var gameId = result[2].split(' ');

		            for (var i = 0; i < gameId.length; i++) {
		            	$.each(markers, function (index, value) {
			            	if(value['id'] == gameId[i])
			            		markers[index].setVisible(true);
			            })
		            }
		        }

          }
        });
    });

    $('.gameTime').click(function(){
    	if($(this).hasClass('timeSelected'))
    	{
    		$('.gameTime').removeClass('timeSelected');
    		$('[data-time]').attr("data-time", 'no');
    	}else{
    		$('.gameTime').removeClass('timeSelected');
    		$(this).addClass('timeSelected');
    		var time = $(this).text();
    		$('[data-time]').attr("data-time", time);
    	}
    	
    }); 
    $('.openFilters').click(function(){
    	if($(".filters").hasClass("filterHidden"))
    		$(".filters").removeClass("filterHidden");
  		else
  			$(".filters").addClass("filterHidden");
	});	
	$('.games').scroll(function() {
		var div = $('.games'),
	    div_sh = $(div)[0].scrollHeight,
	    div_h = div.height();
	    if ($(this).scrollTop() >= div_sh - div_h) {
	        // console.log('конец');
	        more();
	    }		 
	});
});




