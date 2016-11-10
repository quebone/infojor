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
	var classroomId = encodeURI(document.getElementById('classroomId').value);
	var studentId = student.getAttribute('id').replace('student-','');
	var studentId = encodeURI(studentId);
	var dataToSend = 'studentId=' + studentId + '&classroomId=' + classroomId + '&function=getEvaluationData';
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
	setDimensionTooltip();
}

function unselectStudents() {
	var students = document.getElementsByClassName('selected');
	students[0].setAttribute('class', '');
}

function selectStudent(student) {
	student.setAttribute('class', 'selected');
}

function findStudentId(offset = 0) {
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

function changePE(pe) {
	var dimensionId = encodeURI(parseInt(pe.name.substr(3)));
	var dataToSend = 'studentId=' + findActiveStudentId() + '&dimensionId=' + dimensionId + '&markId=' + pe.value + '&function=setPartialEvaluation';
	send(dataToSend, 'presentation/controller/AjaxController.php', evaluationChanged);
}

function changeGE(ge) {
	var areaId = encodeURI(parseInt(ge.name.substr(4)));
	var dataToSend = 'studentId=' + findActiveStudentId() + '&areaId=' + areaId + '&markId=' + ge.value + '&function=setGlobalEvaluation';
	console.log(dataToSend);
	send(dataToSend, 'presentation/controller/AjaxController.php', evaluationChanged);
}

function changeObservation(obs) {
	var obsText = JSON.stringify(obs.value);
	var dataToSend = 'studentId=' + findActiveStudentId() + '&observation=' + obsText + '&function=setObservation';
	console.log(dataToSend);
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