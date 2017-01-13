$( document ).ready(function() {
	$('#toApply').click(function(){
		var typeSport = $('#kind select').val();
		var timeFilter = $('[data-time]').attr('data-time');
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
	          }
	        });

		}
    });

	$('#more').click(function(){
		var numGame = $('[data-num-game]').attr('data-num-game');
		var dataSport = $('[data-sport]').attr('data-sport');
		var timeFilter = $('[data-time]').attr('data-time');
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
	          data: "numGame="+numGame+"&&dataSport="+dataSport+"&&timeFilter="+timeFilter+"&&peopleFilter="+peopleFilter+"&&districtFilter="+districtFilter,
	          success: function(data){
	          	var result = data.split(' | ');
	          	$('.game-list').append(result[1]);
	          	var num = $('[data-num-game]').attr('data-num-game');
	          	num = Number(num) + Number(result[0]);
	            $('[data-num-game]').attr("data-num-game", num);
	          }
	        });
		}
    });

    $('.reset').click(function(){
    	$('[data-time]').attr("data-time", 'no');
    	$('[data-sport]').attr('data-sport', 'no');
    	$('[data-people]').attr('data-people', 'no');
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


});




