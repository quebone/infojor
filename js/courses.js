window.onload = init();

// canvia el valor del selector de trimestre
function init() {
	var selector = document.getElementById("active-trimestre");
	selector.value = selector.getAttribute("value");
}

// recupera el valor de l'any i el passa al controlador
function edit(courseId) {
	var year = document.getElementById("course-" + courseId).value;
	var isActive = document.getElementById("active-" + courseId).checked;
	var dataToSend = "courseId=" + courseId + "&year=" + year + "&isActive=" + isActive + "&function=updateCourse";
	send(dataToSend, AJAXCONTROLLER, edited);
}

// si el curs no està actiu el passa al controlador perquè l'elimini
function del(courseId) {
	var isActive = document.getElementById("active-" + courseId).checked
	if (isActive) {
		alert("No es pot eliminar el curs actiu");
	} else {
		var dataToSend = "courseId=" + courseId + "&function=deleteCourse";
		send(dataToSend, AJAXCONTROLLER, deleted);
	}
}

// passa el valor de l'any al controlador perquè creï un nou curs
function createCourse(course) {
	var year = document.getElementById("new-course").value;
	if (year.length == 0) {
		showError("El curs no pot estar buit");
	} else {
		var dataToSend = "year=" + year + "&function=createCourse";
		send(dataToSend, AJAXCONTROLLER, created);
	} 
}

// passa el trimestre al controlador perquè l'assigni com a actiu
function changeTrimestre(trimestre) {
	var trimestreId = trimestre.value;
	var dataToSend = "trimestreId=" + trimestreId + "&function=setActiveTrimestre";
	send(dataToSend, AJAXCONTROLLER, edited);
}

// retorn de curs editat
function edited(msg) {
//	console.log("updated " + msg);
}

// retorn de curs eliminat; en cas afirmatiu, recarrega la pàgina
function deleted(msg) {
	if (msg == false) {
		alert("No s'ha pogut eliminar el curs perquè conté associacions (tutories, valoracions...)");
	} else {
		location.reload();
	}
}

// retorn de curs creat; en cas afirmatiu, recarrega la pàgina
function created(msg) {
	if (msg != true) {
		showError("S'ha produït un error al crear el curs");
	} else {
		location.reload();
	}
}
