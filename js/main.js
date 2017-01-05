function load(pageName) {
	var section = pageName.substr(0, pageName.indexOf('-'));
	var id = pageName.substr(pageName.indexOf('-') + 1);
	var sectionId = (section == 'tutorings' ? 'classroomId' : (section == 'specialities' ? 'areaId' : 'reinforceId'));
	var dataToSend = sectionId +'=' + id + '&page=' + section + '&section=' + section + '&function=setSession';
//	console.log(dataToSend);
	send(dataToSend, AJAXCONTROLLER, redirect);
}

function redirect(toPage) {
	var vars = JSON.parse(toPage);
	console.log(toPage);
	window.location.href = 'evaluate.php';
}