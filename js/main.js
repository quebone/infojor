function load(pageName) {
	section = pageName.substr(0, pageName.indexOf('-'));
	id = pageName.substr(pageName.indexOf('-') + 1);
	sectionId = (section == 'tutorings' ? 'classroomId' : (section == 'specialities' ? 'areaId' : 'reinforceId'));
	var dataToSend = sectionId +'=' + encodeURI(id) + '&page=' + encodeURI(section) + '&function=setSession';
//	console.log(pageName);
//	console.log(dataToSend);
	send(dataToSend, 'presentation/controller/AjaxController.php', redirect);
}

function redirect(toPage) {
	vars = JSON.parse(toPage);
	console.log(toPage);
	window.location.href = vars.page + '.php';
}