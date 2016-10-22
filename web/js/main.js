//register\auth form switch
$('.nav-tabs li:first-child').click(function(){
    $('.nav-tabs li:nth-child(2)').removeClass('active');
    $('#tab-2').removeClass('active');
    $(this).addClass('active');
    $('#tab-1').addClass('active');
});
$('.nav-tabs li:nth-child(2)').click(function(){
    $('.nav-tabs li:first-child').removeClass('active');
    $('#tab-1').removeClass('active');
    $(this).addClass('active');
    $('#tab-2').addClass('active');
});