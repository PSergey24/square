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
	          // alert(data);   
	          	var result = data.split(' | ');
	          	$('.game-list').html(result[1]);
	          	num = Number(result[0]);
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
	        });

		}
    });

	$('#more').click(function(){
		var numGame = $('[data-num-game]').attr('data-num-game');
		var dataSport = $('[data-sport]').attr('data-sport');
		var timeFilter = $('[data-time]').attr('data-time');
		var nearFilter = $('[data-near]').attr('data-near');
		var min = $('#min').val();
		var max = $('#max').val();
		var districtFilter = $('[data-district]').attr('data-district');

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

			$.ajax({
	          type: "POST",
	          url: "/game/more",
	          data: "numGame="+numGame+"&&dataSport="+dataSport+"&&timeFilter="+timeFilter+"&&peopleFilter="+peopleFilter+"&&districtFilter="+districtFilter+"&&nearFilter="+nearFilter,
	          success: function(data){
	          	var result = data.split(' | ');
	          	$('.game-list').append(result[1]);
	          	var num = $('[data-num-game]').attr('data-num-game');
	          	num = Number(num) + Number(result[0]);
	            $('[data-num-game]').attr("data-num-game", num);


	            var gameId = result[2].split(' ');

	            for (var i = 0; i < gameId.length; i++) {
	            	$.each(markers, function (index, value) {
		            	if(value['id'] == gameId[i])
		            		markers[index].setVisible(true);
		            })
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
			          	$('.game-list').html(result[0]);

			          	$.each(markers, function (index, value) {
			            	markers[index].setVisible(false);
			            })

			            var gameId = result[1].split(' ');

			            for (var i = 0; i < gameId.length; i++) {
			            	$.each(markers, function (index, value) {
				            	if(value['id'] == gameId[i])
				            		markers[index].setVisible(true);
				            })
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
          	$('.game-list').html(result[1]);
          	num = Number(result[0]);
            $('[data-num-game]').attr("data-num-game", num);
            // alert('метка: '+markers[1]);


           
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
        });
    });


  //   $('.plus').on('click', function(){
  //   	var game = $(this).attr("data-id-game-plus");
  //   	var symbol = $('[data-id-game-plus='+game+'] span').html();
  //   	alert(game+' | '+symbol);

		// $.ajax({
		// 	type: "POST",
	 //        url: "/game/player",
	 //        data: "game="+game+"&&symbol="+symbol,
	 //        success: function(data){
	 //        	if(data == 'Вы не авторизованы')
	 //        		alert(data);
	 //        	else{
	 //        		var result = data.split('|');


	 //        		if(result[2] == '-')
	 //        			$('[data-id-game-plus='+result[0]+'] span').html('+');
	 //        		else
	 //        			$('[data-id-game-plus='+result[0]+'] span').html('-');

	 //        		$('[data-id-game='+result[0]+'] .circle a').remove();
	 //        		$('[data-id-game='+result[0]+'] .count').html(result[1]);
	 //        		$('[data-id-game='+result[0]+'] .circle').append(result[3]);


	 //        	}
	 //        },
	 //        error:  function(data){
	 //        		alert('Ошибка: '+data);
	 //        }
  //       });
  //   });

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
    






});




