function load(pageName) {
	section = pageName.substr(0, pageName.indexOf('-'));
	id = encodeURI(pageName.substr(pageName.indexOf('-') + 1));
	sectionId = (section == 'tutorings' ? 'classroomId' : (section == 'specialities' ? 'areaId' : 'reinforceId'));
	var dataToSend = sectionId +'=' + id + '&page=' + section + '&function=setSession';
	send(dataToSend, 'presentation/controller/AjaxController.php', redirect);
}

function redirect(toPage) {
	vars = JSON.parse(toPage);
	window.location.href = vars.page + '.php';
}