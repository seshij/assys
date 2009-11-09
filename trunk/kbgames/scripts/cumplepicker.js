$(function() {
//	$.datepick.setDefaults({useThemeRoller: true});
	$('#fecha_nacimiento').datepick({minDate: new Date(1909, 1 - 1, 1),
        maxDate: new Date().getFullYear(),
        showOn: 'both',
        buttonImageOnly: true,
        buttonImage: 'images/backend/calendar-blue.gif'});

        $('#fecha_lanzamiento').datepick({minDate: new Date(1909, 1 - 1, 1),
        maxDate: new Date().getFullYear(),
        showOn: 'both',
        buttonImageOnly: true,
        buttonImage: 'images/backend/calendar-blue.gif'});
});