//
$('#court-name').on('keyup', function() {

    var court_name = $('#court-name').val();
    var court_description = $('#court_description');

    if (court_name != '')
        court_description.text(court_name);
    else
        court_description.text($('#court-name').attr('placeholder'))
});




