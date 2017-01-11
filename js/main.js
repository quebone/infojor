// envia les dades al controlador per saber quina pàgina cal carregar 
function load(pageName) {
	var section = pageName.substr(0, pageName.indexOf('-'));
	var id = pageName.substr(pageName.indexOf('-') + 1);
	var sectionId = (section == 'tutorings' ? 'classroomId' : (section == 'specialities' ? 'areaId' : 'reinforceId'));
	var dataToSend = sectionId +'=' + id + '&page=' + section + '&section=' + section + '&function=setSession';
	send(dataToSend, AJAXCONTROLLER, redirect);
}

// retorn de càrrega de pàgina
function redirect(toPage) {
	var vars = JSON.parse(toPage);
	window.location.href = 'evaluate.php';
}