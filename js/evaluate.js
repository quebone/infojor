var minClassroom = 7;
var maxClassroom = 18;

window.onload = init();

function init() {
	changeStudentsHeight();
	window.onresize = changeStudentsHeight; 
	var firstStudentId = findStudentId(0);
	var firstStudent = document.getElementById('student-' + firstStudentId);
	changeStudent(firstStudent);
}

function changeStudent(student)
{
	disable(document.getElementById('right_column'));
	var classroomId = document.getElementById('classroomId').value;
	var areaId = document.getElementById('areaId').value;
	var reinforceId = document.getElementById('reinforceId').value;
	var studentId = student.getAttribute('id').replace('student-','');
	var dataToSend = 'studentId=' + studentId + '&classroomId=' + classroomId + '&areaId=' + areaId + '&reinforceId=' + reinforceId + '&function=getEvaluationData';
	send(dataToSend, 'presentation/controller/AjaxController.php', showEvaluation);
	unselectStudents();
	selectStudent(student);
	getImage(student);
}

function showEvaluation(evaluation) {
	evaluation = evaluation.replace(/\\n/g,"");
	evaluation = evaluation.replace(/\\t/g,"");
	evaluation = unescape(JSON.parse(evaluation));
	var container = document.getElementById('right_column');
	container.innerHTML = evaluation;
	enable(document.getElementById('right_column'));
	if (document.getElementById('areaId').value == '' && document.getElementById('reinforceId').value == '') {
		enableClassroomSelectors(false);
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
	send(dataToSend, 'presentation/controller/AjaxController.php', showImage);
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
		send(dataToSend, 'presentation/controller/AjaxController.php', redirect);
	}
}

function redirect(toPage) {
	vars = JSON.parse(toPage);
	window.location.href = vars.page;
}

function changePE(pe) {
	var dimensionId = encodeURI(parseInt(pe.name.substr(3)));
	var dataToSend = 'studentId=' + findActiveStudentId() + '&dimensionId=' + dimensionId + '&markId=' + pe.value + '&function=setPartialEvaluation';
	send(dataToSend, 'presentation/controller/AjaxController.php', evaluationChanged);
}

function changeGE(ge) {
	var areaId = encodeURI(parseInt(ge.name.substr(4)));
	var dataToSend = 'studentId=' + findActiveStudentId() + '&areaId=' + areaId + '&markId=' + ge.value + '&function=setGlobalEvaluation';
	send(dataToSend, 'presentation/controller/AjaxController.php', evaluationChanged);
}

function changeObservation(obs) {
	var obsText = encodeURI(obs.value);
	var obsReinforce = obs.name;
	var dataToSend = 'studentId=' + findActiveStudentId() + '&observation=' + obsText + '&reinforceId=' + obsReinforce + '&function=setObservation';
	send(dataToSend, 'presentation/controller/AjaxController.php', evaluationChanged);
}

function evaluationChanged(msg) {
	console.log(msg);
}

function changeStudentsHeight() {
	var windowHeight = window.innerHeight - 220;
	var students = window.document.getElementById('students');
	students.style.height = windowHeight + "px";
}