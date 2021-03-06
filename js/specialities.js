function del(areaId, teacherId) {
	var cnf = confirm("Segur que vols eliminar l'especialitat de " + findName(teacherId) + " a " + findArea(areaId) + "?");
	if (cnf) {
		var dataToSend = "areaId=" + areaId + "&teacherId=" + teacherId + "&function=removeSpeciality";
		send(dataToSend, AJAXCONTROLLER, deleted);
	}
}

function add() {
	var areaId = document.getElementById("area").value;
	var teacherId = document.getElementById("teacher").value;
	var dataToSend = "areaId=" + areaId + "&teacherId=" + teacherId + "&function=addSpeciality";
	send(dataToSend, AJAXCONTROLLER, added);
}

function deleted(msg) {
	if (msg != true) {
		showError("No es pot eliminar l'especialitat. Possiblement tingui alguna qualificació entrada");
	} else {
		location.reload();
	}
}

function added(msg) {
		if (msg != true) {
		showError("S'ha produït un error al crear l'especialitat");
	} else {
		location.reload();
	}
}

// cerca el nom del mestre corresponent a un identificador
function findName(teacherId) {
	var html = document.documentElement.innerHTML;
	var str = "," + teacherId + ")";
	var idpos = html.indexOf(str) + str.length + 1;
	var pos = html.lastIndexOf('teacher">', idpos) + 9;
	var len = html.indexOf('<', pos) - pos;
	var name = html.substr(pos, len);
	return name;
}

function findArea(areaId) {
	var html = document.documentElement.innerHTML;
	var str = "del(" + areaId;
	var idpos = html.indexOf(str) + str.length + 1;
	var pos = html.lastIndexOf('area">', idpos) + 7;
	var len = html.indexOf('<', pos) - pos;
	var name = html.substr(pos, len);
	return name;
}

function importSpecialitiesFromLastTrimestre() {
	var dataToSend = "function=importSpecialitiesFromLastTrimestre";
	send(dataToSend, AJAXCONTROLLER, imported);
}

function imported(msg) {
	if (msg != false) {
		alert("S'han importat " + msg + " especialitats");
		location.reload();
	} else {
		showError("Error a l'importar les especialitats del trimestre anterior");
	}
}