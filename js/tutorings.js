function del(classroomId, teacherId) {
	var cnf = confirm("Segur que vols eliminar la tutoria de " + findName(teacherId) + " a " + findClassroom(classroomId) + "?");
	if (cnf) {
		var dataToSend = "classroomId=" + classroomId + "&teacherId=" + teacherId + "&function=removeTutoring";
		console.log(dataToSend);
		send(dataToSend, AJAXCONTROLLER, deleted);
	}
}

function add() {
	var classroomId = document.getElementById("classroom").value;
	var teacherId = document.getElementById("teacher").value;
	var dataToSend = "classroomId=" + classroomId + "&teacherId=" + teacherId + "&function=addTutoring";
	send(dataToSend, AJAXCONTROLLER, added);
}

function deleted(msg) {
	if (msg != true) {
		showError("No es pot eliminar la tutoria. Possiblement tingui alguna qualificació entrada");
	} else {
		location.reload();
	}
}

function added(msg) {
		if (msg != true) {
		showError("S'ha produït un error al crear la tutoria");
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

function findClassroom(classroomId) {
	var html = document.documentElement.innerHTML;
	var str = "del(" + classroomId;
	var idpos = html.indexOf(str) + str.length + 1;
	var pos = html.lastIndexOf('classroom">', idpos) + 11;
	var len = html.indexOf('<', pos) - pos;
	var name = html.substr(pos, len);
	return name;
}

function importTutoringsFromLastTrimestre() {
	var dataToSend = "function=importTutoringsFromLastTrimestre";
	send(dataToSend, AJAXCONTROLLER, imported);
}

function imported(msg) {
	if (msg != false) {
		alert("S'han importat " + msg + " tutories");
		location.reload();
	} else {
		showError("Error a l'importar les tutories del trimestre anterior");
	}
}