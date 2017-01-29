$( document ).ready(function() {
	$('[data-tab=1]').click(function(){
		$('[data-tab=2]').removeClass('selected');
		$('#tab1').removeClass('tab-hidden');
		$('[data-tab=1]').addClass('selected');
		$('#tab2').addClass('tab-hidden');
    });
    $('[data-tab=2]').click(function(){
		$('[data-tab=1]').removeClass('selected');
		$('#tab2').removeClass('tab-hidden');
		$('[data-tab=2]').addClass('selected');
		$('#tab1').addClass('tab-hidden');
    });

});




