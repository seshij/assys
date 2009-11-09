function findTitle (formulario,punto,recurso) {
    param = "directories=no,scrollbars=no,menubar=no,toolbar=no,resizable=no,width=" + 600 + ",height=" + 330;	
    contacts_window = window.open("finder.php?option=finder&form_name="+formulario+"&input_name="+punto+"&finder_name="+recurso+"", "find_dane", param);
    if (!contacts_window.opener) contacts_window.opener = self;
}

function findTitles (formulario,punto_visible,punto_escondido,recurso) {
    param = "directories=no,scrollbars=no,menubar=no,toolbar=no,resizable=no,width=" + 600 + ",height=" + 330;	
    contacts_window = window.open("finder.php?option=finder&form_name="+formulario+"&input_visible_name="+punto_visible+"&input_hidden_name="+punto_escondido+"&finder_name="+recurso+"", "find_dane", param);
    if (!contacts_window.opener) contacts_window.opener = self;
}

function findValues (formulario,punto_visible,punto_escondido,recurso,parametro,inclusion) {
    param = "directories=no,scrollbars=no,menubar=no,toolbar=no,resizable=no,width=" + 600 + ",height=" + 330;	
    contacts_window = window.open("finder.php?option=finder&form_name="+formulario+"&input_visible_name="+punto_visible+"&input_hidden_name="+punto_escondido+"&finder_name="+recurso+"&finder_parameter="+parametro+"&inc="+inclusion+"", "find_dane", param);
    if (!contacts_window.opener) contacts_window.opener = self;
}

function goToHelpLink(recurso){
	param = "directories=no,menubar=no,toolbar=no,resizable=no,width=" + 760 + ",height=" + 450;
	contacts_window = window.open(recurso, "find_dane", param);
	if (!contacts_window.opener) contacts_window.opener = self;
}