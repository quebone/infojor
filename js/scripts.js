const AJAXCONTROLLER = 'presentation/controller/AjaxController.php';

window.onload = init();

function init() {
	
}

function send(dataToSend, receiver, returnFunction) {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			returnFunction(xmlhttp.responseText);
		}
	}
	xmlhttp.open("POST", receiver, true);
	//Must add this request header to XMLHttpRequest request for POST
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	dataToSend += '&caller=' +  capitalizeFirstLetter(getCurrentPageName());
	xmlhttp.send(dataToSend);
}

function loadPHP() {
}

function capitalizeFirstLetter(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

function getCurrentPageName() {
	var path = window.location.pathname;
	var page = path.split("/").pop();
	if (page.length == 0) return 'index';
	return page.split(".")[0];
}

function showError(message) {
	var error = document.getElementById('error');
	error.innerHTML = message;
}

function cleanError() {
	var error = document.getElementById('error');
	error.innerHTML = "";
}

function logout() {
	window.location.replace('login.php');
}

function sendMessage() {
	window.location.replace('sendmessage.php');
}

function main() {
	window.location.replace('main.php');
}

function editPersonalData() {
	window.location.replace('userdata.php');
}

function listAllTeachers() {
	window.location.replace('teachers.php');
}

function listClassroomStudents() {
	window.location.replace('students.php');
}

function listAllClassrooms() {
	window.location.replace('tutorings.php');
}

function listAllSpecialities() {
	window.location.replace('specialities.php');
}

function listAllReinforcings() {
	window.location.replace('reinforcings.php');
}

function listAllDimensions() {
	window.location.replace('dimensions.php');
}

function listCourses() {
	window.location.replace('courses.php');
}

function printClassroom() {
	var classroom = document.getElementById("classroomId").value;
	if (classroom != null) {
		var dataToSend = 'classroomId=' + encodeURI(classroom) + '&function=printReport';
		send(dataToSend, AJAXCONTROLLER, openReport);
	}
}

function printStudent() {
	var student = document.getElementsByClassName('selected')[0].id;
	var pos = student.lastIndexOf('-');
	var studentId = student.substr(pos + 1);
	if (student != null) {
		var dataToSend = 'classroomId=&studentId=' + encodeURI(studentId) + '&function=printReport';
		send(dataToSend, AJAXCONTROLLER, openReport);
	}
}

function openReport(msg) {
	window.open('report.php')
}

function disable(elem) {
	elem.style.opacity = 0.4;
	elem.style.pointerEvents = 'none';
}

function enable(elem) {
	elem.style.opacity = 1;
	elem.style.pointerEvents = 'auto';
}

