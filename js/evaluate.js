var minClassroom = 1;
var maxClassroom = 18;

window.onload = init();

String.prototype.stripSlashes = function(){
	return this.replace(/\\(.)/mg, "$1");
}

function init() {
	changeStudentsHeight();
	window.onresize = changeStudentsHeight; 
	var firstStudentId = findStudentId(0);
	var firstStudent = document.getElementById('student-' + firstStudentId);
	changeStudent(firstStudent);
}

function changeStudent(student)
{
	disable(document.getElementById('evaluations'));
	var classroomId = document.getElementById('classroomId').value;
	var areaId = document.getElementById('areaId').value;
	var reinforceId = document.getElementById('reinforceId').value;
	var studentId = student.getAttribute('id').replace('student-','');
	var dataToSend = 'studentId=' + studentId + '&classroomId=' + classroomId + '&areaId=' + areaId + '&reinforceId=' + reinforceId + '&function=getEvaluations';
	console.log(dataToSend);
	send(dataToSend, AJAXCONTROLLER, showEvaluation);
	unselectStudents();
	selectStudent(student);
	getImage(student);
	updateSectionStudent(student);
}

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

function updateSectionStudent(student) {
	var name = student.innerHTML;
	var section = document.getElementById("section").getElementsByTagName("h3")[0];
	section.innerHTML = name;
}

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

function unselectStudents() {
	var students = document.getElementsByClassName('selected');
	students[0].setAttribute('class', '');
}

function selectStudent(student) {
	student.setAttribute('class', 'selected');
}

function findStudentId(offset) {
	var markup = 'student-';
	var pos = document.documentElement.innerHTML.indexOf(markup, offset) + markup.length;
	var studentSize = document.documentElement.innerHTML.indexOf('"', pos) - pos;
	var studentId = document.documentElement.innerHTML.substr(pos, studentSize);
	return studentId;
}

function findActiveStudentId() {
	var posSelected = document.documentElement.innerHTML.indexOf('class="selected"');
	var posLi = document.documentElement.innerHTML.lastIndexOf('<li', posSelected);
	var studentId = findStudentId(posLi);
	return studentId;
}

function findActiveStudent() {
	return document.getElementsByClassName('selected')[0];
}

function getImage(student) {
	var studentId = student.getAttribute('id').replace('student-','');
	var studentId = encodeURI(studentId);
	var dataToSend = 'studentId=' + studentId + '&function=getThumbnail';
	send(dataToSend, AJAXCONTROLLER, showImage);
}

function showImage(base64) {
	var image = document.getElementById('thumbnail');
	image.src = base64;
}

function enableClassroomSelectors(enable) {
	var newValue = enable ? 'visible' : 'hidden';
	var classroomSelectors = document.getElementsByClassName('selector');
	for (var i = 0; i< classroomSelectors.length; i++) {
		classroomSelectors[i].style.visibility = newValue;
	}
}

function nextClassroom() {
	changeClassroom(1);
}

function prevClassroom() {
	changeClassroom(-1);
}

function changeClassroom(offset) {
	currentClassroom = parseInt(document.getElementById('classroomId').value) + offset;
	if (currentClassroom <= maxClassroom && currentClassroom >= minClassroom) {
		document.getElementById('classroomId').value = currentClassroom;
		var toPage = window.location.pathname;
		var dataToSend = 'classroomId=' + currentClassroom + '&page=' + encodeURI(toPage) + '&function=setSession';
		send(dataToSend, AJAXCONTROLLER, redirect);
	}
}

function redirect(toPage) {
	vars = JSON.parse(toPage);
	window.location.href = vars.page;
}

function changePE(pe) {
	var dimensionId = encodeURI(parseInt(pe.name.substr(3)));
	var dataToSend = 'studentId=' + findActiveStudentId() + '&dimensionId=' + dimensionId + '&markId=' + pe.value + '&function=setPartialEvaluation';
	send(dataToSend, AJAXCONTROLLER, evaluationChanged);
}

function changeGE(ge) {
	var areaId = encodeURI(parseInt(ge.name.substr(4)));
	var dataToSend = 'studentId=' + findActiveStudentId() + '&areaId=' + areaId + '&markId=' + ge.value + '&function=setGlobalEvaluation';
	send(dataToSend, AJAXCONTROLLER, evaluationChanged);
}

function changeObservation(obs) {
	var obsText = JSON.stringify(obs.value);
	var obsReinforce = obs.name;
	var dataToSend = 'studentId=' + findActiveStudentId() + '&observation=' + obsText + '&reinforceId=' + obsReinforce + '&function=setObservation';
	console.log(dataToSend);
	send(dataToSend, AJAXCONTROLLER, evaluationChanged);
}

function evaluationChanged(msg) {
	console.log(msg);
}

function changeStudentsHeight() {
	var windowHeight = window.innerHeight - 220;
	var students = window.document.getElementById('students');
	students.style.height = windowHeight + "px";
}