window.onload = init();

function init() {
	var classroomId = 1;
	getClassroomStudents(classroomId);
}

// canvia de classe
function changeClassroom(elem) {
	var classroomId = elem.value;
	getClassroomStudents(classroomId);
	document.getElementById("classroom").value = classroomId;
}

// envia l'identificador de classe al controlador perquè recuperi la llista dels seus alumnes
function getClassroomStudents(classroomId) {
	disable(document.getElementById('students'));
	var dataToSend = "classroomId=" + classroomId + "&function=getClassroomStudents";
	send(dataToSend, AJAXCONTROLLER, studentsReceived);
}

// envia l'identificador d'alumne al controlador perquè l'elimini
function deleteStudent(studentId) {
	var name = document.getElementById("name-" + studentId).value;
	var surnames = document.getElementById("surnames-" + studentId).value;
	var cnf = confirm("Segur que vols eliminar l'estudiant " + name + " " + surnames + "?\n(aquesta operació no es pot desfer)");
	if (cnf) {
		var dataToSend = "studentId=" + studentId + "&function=deleteStudent";
		console.log(dataToSend);
		send(dataToSend, AJAXCONTROLLER, studentDeleted);
	}
}

// envia les dades al controlador perquè creï un nou alumne
function addStudent() {
	var name = document.getElementById("name").value;
	var surnames = document.getElementById("surnames").value;
	var classroomId = document.getElementById("classroom").value;
	var dataToSend = "name=" + name + "&surnames=" + surnames + "&classroomId=" + classroomId + "&function=addStudent";
	send(dataToSend, AJAXCONTROLLER, studentAdded);
}

// envia les dades d'un alumne al controlador perquè l'actualitzi
function updateStudent(elem) {
	var studentId = getStudentId(elem.id);
	var name = document.getElementById("name-" + studentId).value;
	var surnames = document.getElementById("surnames-" + studentId).value;
	var classroomId = document.getElementById("classroom-" + studentId).value;
	var dataToSend = "studentId=" + studentId + "&name=" + encodeURI(name) + "&surnames=" + encodeURI(surnames) + "&classroomId=" + classroomId + "&function=updateStudent";
	console.log(dataToSend);
	send(dataToSend, AJAXCONTROLLER, studentUpdated);
}

// envia un nom d'arxiu al controlador perquè n'importi els alumnes
function importStudentsFromFile() {
	var file = document.getElementById("file_selector").value;
	if (file.length == 0) {
		alert("Has d'escollir un arxiu");
	} else {
		var cnf = confirm("Segur que vols substituir els alumnes de P3 del curs actual pels del fitxer " + file + "?");
		if (cnf) {
			var dataToSend = "file=" + encodeURI(file) + "&function=importStudentsFromFile";
			send(dataToSend, AJAXCONTROLLER, fileLoaded);
		}
	}
}

// demana al controlador que carregui els alumnes del trimestre anterior
function importStudentsFromLastTrimestre() {
	var cnf = confirm("Segur que vols substituir els alumnes de la classe pels del darrer trimestre?");
	if (cnf) {
		var dataToSend = "function=importStudentsFromLastTrimestre";
		send(dataToSend, AJAXCONTROLLER, fileLoaded);
	}
}

// retorn de canvi de classe; trasllada les dades dels alumnes a la pàgina
function studentsReceived(students) {
	var container = document.getElementById("students");
	container.innerHTML = students;
	var studentsClassroom = document.getElementsByClassName("classroom");
	for (var i=0; i<studentsClassroom.length; i++) {
		studentsClassroom[i].value = document.getElementById("classroom-selector").value;
	}
	enable(document.getElementById('students'));
}

// retorn d'importació d'arxiu d'alumnes
function fileLoaded(msg) {
	alert("S'han importat " + msg + " alumnes");
}

// retorn d'alumne afegit; torna a carregar els alumnes
function studentAdded(msg) {
	classroomId = document.getElementById("classroom-selector").value;
	getClassroomStudents(classroomId);
}

// retorn d'estudiant actualitzat
function studentUpdated(msg) {
//	console.log(msg);
}

// retorn d'estudiant eliminat
function studentDeleted(deleted) {
	if (deleted) {
		classroomId = document.getElementById("classroom-selector").value;
		getClassroomStudents(classroomId);
	} else {
		alert("No s'ha pogut eliminar l'alumne perquè aquest trimestre ja ha estat avaluat");
	}
}

// retorna l'identificador d'un estudiant
function getStudentId(input) {
	return input.substr(input.indexOf("-") + 1);
}
