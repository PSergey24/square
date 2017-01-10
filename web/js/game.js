$( document ).ready(function() {
	$('#toApply').click(function(){
		var typeSport = $('#kind select').val();
		var timeFilter = $('[data-time]').attr('data-time');
		var min = $('#min').val();
		var max = $('#max').val();

		$('[data-people]').attr("data-people", min+'-'+max);
		$('[data-sport]').attr("data-sport", typeSport);
		$.ajax({
          type: "POST",
          url: "/game/apply",
          data: "typeSport="+typeSport+"&&timeFilter="+timeFilter+"&&min="+min+"&&max="+max,
          success: function(data){     
          	var result = data.split(' | ');
          	$('.game-list').html(result[1]);
          	num = Number(result[0]);
            $('[data-num-game]').attr("data-num-game", num);
          }
        });

    });

	$('#more').click(function(){
		var numGame = $('[data-num-game]').attr('data-num-game');
		var dataSport = $('[data-sport]').attr('data-sport');
		var timeFilter = $('[data-time]').attr('data-time');
		// alert('еще: '+numGame+' сейчас выведено');

		$.ajax({
          type: "POST",
          url: "/game/more",
          data: "numGame="+numGame+"&&dataSport="+dataSport+"&&timeFilter="+timeFilter,
          success: function(data){
          	var result = data.split(' | ');
          	$('.game-list').append(result[1]);
          	var num = $('[data-num-game]').attr('data-num-game');
          	num = Number(num) + Number(result[0]);
            $('[data-num-game]').attr("data-num-game", num);
          }
        });
    });

    $('.reset').click(function(){
    	$('[data-time]').attr("data-time", 'no');
    	$('[data-sport]').attr('data-sport', 'no');
    	$('[data-people]').attr('data-people', 'no');
    	$('.gameTime').removeClass('timeSelected');
    	$('#sportList option:selected').removeAttr('selected');

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




