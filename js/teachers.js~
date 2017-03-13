// envia l'identificador de mestre al controlador per saber quina pàgina carregar segons si el mestre existeix
function edit(teacherId) {
	var dataToSend = "teacherId=" + teacherId + "&function=editUser";
	send(dataToSend, AJAXCONTROLLER, redirect)
}

// envia l'identificador de mestre al controlador perquè l'esborri
function del(teacherId) {
	user = findName(teacherId);
	cnf = confirm("Segur que vols eliminar l'usuari " + user + "?");
	if (cnf == true) {
		var dataToSend = "teacherId=" + teacherId + "&function=deleteUser";
		send(dataToSend, AJAXCONTROLLER, teacherDeleted);
	}
}

// demana al controlador que creï un nou usuari
function add() {
	var dataToSend = "function=addUser";
	send(dataToSend, AJAXCONTROLLER, redirect)
}

// retorn de mestre eliminat
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

// cerca el nom del mestre corresponent a un identificador
function findName(teacherId) {
	var html = document.documentElement.innerHTML;
	var str = "del(" + teacherId + ")";
	var idpos = html.indexOf(str) + str.length + 1;
	var pos = html.indexOf('"', idpos) + 1;
	var len = html.indexOf('"', pos) - pos;
	var name = html.substr(pos, len);
	return name;
}