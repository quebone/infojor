// valors mínim i màxim que pot prendre la variable classe
var minClassroom = 1;
var maxClassroom = 18;

var avatar = 'images/ic_person_black_24px.svg';

window.onload = init();

String.prototype.stripSlashes = function(){
	return this.replace(/\\(.)/mg, "$1");
}

function init() {
//	changeStudentsHeight();
//	window.onresize = changeStudentsHeight; 
	var firstStudentId = findStudentId(0);
	var firstStudent = document.getElementById('student-' + firstStudentId);
	changeStudent(firstStudent);
}

// al seleccionar un alumne, envia les dades al controlador perquè recuperi les seves qualificacions actuals 
function changeStudent(student) {
	var studentId = student.getAttribute('id').replace('student-','');
	unselectStudents();
	selectStudent(student);
	getImage(student);
	updateSectionStudent(student);
	changeStudentFromId(studentId);
}

function changeStudentFromSelect(elem) {
	changeStudentFromId(elem.value);
}

function changeStudentFromId(studentId) {
	disable(document.getElementById('evaluations'));
	var classroomId = document.getElementById('classroomId').value;
	var areaId = document.getElementById('areaId').value;
	var reinforceId = document.getElementById('reinforceId').value;
	var dataToSend = 'studentId=' + studentId + '&classroomId=' + classroomId + '&areaId=' + areaId + '&reinforceId=' + reinforceId + '&function=getEvaluations';
	send(dataToSend, AJAXCONTROLLER, showEvaluation);
}

// retorn de recuperació de les qualificacions; insereix les qualificacions al contenidor html corresponent
function showEvaluation(evaluation) {
	evaluation = unescape(JSON.parse(evaluation));
	var container = document.getElementById('evaluations');
	container.innerHTML = evaluation;
	parseObservations();
	enable(document.getElementById('evaluations'));
	var section = document.getElementById('section_name').value;
	if (section == 'tutorings') {
		enableClassroomSelectors(false);
	}
}

// actualitza la capçalera de les qualificacions amb el nom de l'estudiant
function updateSectionStudent(student) {
	var name = student.innerHTML;
	var section = document.getElementById("section").getElementsByTagName("span")[0];
	section.innerHTML = name;
}

// converteix els caràcters especials (\n) a intel·ligibles per l'usuari
function parseObservations() {
	var observations = document.getElementsByTagName("textarea");
	for (var i = 0; i < observations.length; i++) {
		try {
			observations[i].innerHTML = JSON.parse(observations[i].innerHTML);
		} catch (err) {
			console.log(err);
		}
	} 
}

// neteja la selecció de la llista d'alumnes
function unselectStudents() {
	var students = document.getElementsByClassName('selected');
	students[0].setAttribute('class', '');
}

// mostra un alumne com a seleccionat
function selectStudent(student) {
	student.setAttribute('class', 'selected');
}

// cerca l'identificador d'un alumne
function findStudentId(offset) {
	var markup = 'student-';
	var pos = document.documentElement.innerHTML.indexOf(markup, offset) + markup.length;
	var studentSize = document.documentElement.innerHTML.indexOf('"', pos) - pos;
	var studentId = document.documentElement.innerHTML.substr(pos, studentSize);
	return studentId;
}

// cerca l'identificador de l'alumne seleccionat
function findActiveStudentId() {
	var posSelected = document.documentElement.innerHTML.indexOf('class="selected"');
	var posLi = document.documentElement.innerHTML.lastIndexOf('<li', posSelected);
	var studentId = findStudentId(posLi);
	return studentId;
}

// cerca l'alumne seleccionat
function findActiveStudent() {
	return document.getElementsByClassName('selected')[0];
}

// envia l'identificador d'alumne al controlador perquè en retorni la imatge associada
function getImage(student) {
	var studentId = student.getAttribute('id').replace('student-','');
	var studentId = encodeURI(studentId);
	var dataToSend = 'studentId=' + studentId + '&function=getThumbnail';
	send(dataToSend, AJAXCONTROLLER, showImage);
}

// retorn de recuperació d'imatge
function showImage(base64) {
	var image = document.getElementById('thumbnail');
	if (base64 == "") {
		image.src = avatar;
	} else {
		image.src = base64;
	}
}

// activar o desactivar els selectors de classe anterior i posterior
function enableClassroomSelectors(enable) {
	var newValue = enable ? 'visible' : 'hidden';
	var classroomSelectors = document.getElementsByClassName('selector');
	for (var i = 0; i< classroomSelectors.length; i++) {
		classroomSelectors[i].style.visibility = newValue;
	}
}

// canvia la classe i la passa al controlador perquè en recuperi la llista d'alumnes
function changeClassroom(elem) {
	currentClassroom = elem.value;
	if (currentClassroom <= maxClassroom && currentClassroom >= minClassroom) {
		document.getElementById('classroomId').value = currentClassroom;
		var toPage = window.location.pathname;
		var dataToSend = 'classroomId=' + currentClassroom + '&page=' + encodeURI(toPage) + '&function=setSession';
		send(dataToSend, AJAXCONTROLLER, redirect);
	}
}

// carrega una pàgina nova
function redirect(toPage) {
	vars = JSON.parse(toPage);
	window.location.href = vars.page;
}

// envia les dades al controlador perquè canviï una qualificació parcial
function changePE(pe) {
	var trimestral = pe.name.indexOf("dim") == 0;
	var pos = (trimestral ? 3 : 5);
	var functionName = (trimestral ? 'setPartialEvaluation' : 'setFinalPartialEvaluation');
	var dimensionId = encodeURI(parseInt(pe.name.substr(pos)));
	var dataToSend = 'studentId=' + findActiveStudentId() + '&dimensionId=' + dimensionId + '&markId=' + pe.value + '&function=' + functionName;
	send(dataToSend, AJAXCONTROLLER, evaluationChanged);
}

// envia les dades al controlador perquè canviï una qualificació global
function changeGE(ge) {
	var trimestral = ge.name.indexOf("area") == 0;
	var pos = (trimestral ? 4 : 5);
	var functionName = (trimestral ? 'setGlobalEvaluation' : 'setFinalGlobalEvaluation');
	var areaId = encodeURI(parseInt(ge.name.substr(pos)));
	var dataToSend = 'studentId=' + findActiveStudentId() + '&areaId=' + areaId + '&markId=' + ge.value + '&function=' + functionName;
	send(dataToSend, AJAXCONTROLLER, evaluationChanged);
}

// envia les dades al controlador perquè canviï una observació
function changeObservation(obs) {
	var obsText = JSON.stringify(obs.value);
	var obsReinforce = obs.name;
	var dataToSend = 'studentId=' + findActiveStudentId() + '&observation=' + obsText + '&reinforceId=' + obsReinforce + '&function=setObservation';
	send(dataToSend, AJAXCONTROLLER, evaluationChanged);
}

// retorn de canvi de qualificació
function evaluationChanged(msg) {
//	console.log(msg);
}

// adapta la llista d'alumnes a l'alçada de la pàgina
function changeStudentsHeight() {
	var windowHeight = window.innerHeight - 220;
	var students = window.document.getElementById('students');
	students.style.height = windowHeight + "px";
}