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

// full excel resum
function createSummaryTable(classroomId) {
	var dataToSend = 'classroomId=' + classroomId + '&function=createSummaryTable';
	send(dataToSend, AJAXCONTROLLER, done);
	document.getElementsByTagName('body')[0].style.cursor = "wait";
	disable(document.getElementsByTagName('main')[0]);
}

function done(msg) {
	document.getElementsByTagName('body')[0].style.cursor = "default";
	enable(document.getElementsByTagName('main')[0]);
	window.location.replace('files/taula-resum.xls')
}