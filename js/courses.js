window.onload = init();

function init() {
	var selector = document.getElementById("active-trimestre");
	selector.value = selector.getAttribute("value");
}

function edit(courseId) {
	var year = document.getElementById("course-" + courseId).value;
	var isActive = document.getElementById("active-" + courseId).checked;
	var dataToSend = "courseId=" + courseId + "&year=" + year + "&isActive=" + isActive + "&function=updateCourse";
	send(dataToSend, AJAXCONTROLLER, edited);
}

function del(courseId) {
	var isActive = document.getElementById("active-" + courseId).checked
	if (isActive) {
		alert("No es pot eliminar el curs actiu");
	} else {
		var dataToSend = "courseId=" + courseId + "&function=deleteCourse";
		send(dataToSend, AJAXCONTROLLER, deleted);
	}
}

function createCourse(course) {
	var year = document.getElementById("new-course").value;
	if (year.length == 0) {
		showError("El curs no pot estar buit");
	} else {
		var dataToSend = "year=" + year + "&function=createCourse";
		send(dataToSend, AJAXCONTROLLER, created);
	} 
}

function changeTrimestre(trimestre) {
	var trimestreId = trimestre.value;
	var dataToSend = "trimestreId=" + trimestreId + "&function=setActiveTrimestre";
	send(dataToSend, AJAXCONTROLLER, edited);
}

function edited(msg) {
	console.log("updated " + msg);
}

function deleted(msg) {
	if (msg == false) {
		alert("No s'ha pogut eliminar el curs perquè conté associacions (tutories, valoracions...)");
	} else {
		location.reload();
	}
}

function created(msg) {
	if (msg != true) {
		console.log(msg);
		showError("S'ha produït un error al crear el curs");
	} else {
		location.reload();
	}
}
