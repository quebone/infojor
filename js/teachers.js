function edit(teacherId) {
	var dataToSend = "teacherId=" + teacherId + "&function=editUser";
	send(dataToSend, AJAXCONTROLLER, redirect)
}

function del(teacherId) {
	user = findName(teacherId);
	cnf = confirm("Segur que vols eliminar l'usuari " + user + "?");
	if (cnf == true) {
		var dataToSend = "teacherId=" + teacherId + "&function=deleteUser";
		send(dataToSend, AJAXCONTROLLER, teacherDeleted);
	}
}

function add() {
	var dataToSend = "function=addUser";
	send(dataToSend, AJAXCONTROLLER, redirect)
}

function teacherDeleted(deleted) {
	if (deleted) {
		location.reload();
	} else {
		alert("No s'ha pogut eliminar el mestre perquè té avaluacions entrades");
	}
}

function redirect(toPage) {
	window.location.href = toPage;
}

function findName(teacherId) {
	var html = document.documentElement.innerHTML;
	var str = "del(" + teacherId + ")";
	var idpos = html.indexOf(str) + str.length + 1;
	var pos = html.indexOf('"', idpos) + 1;
	var len = html.indexOf('"', pos) - pos;
	var name = html.substr(pos, len);
	return name;
}