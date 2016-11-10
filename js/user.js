// sends form data through AJAX
function sendData() {
	console.log('sending data');
	var dataToSend = 'function=getTeacher';
	send(dataToSend, 'presentation/controller/AjaxController.php', getTeacher);
}

// given a user id, decides what to do
function login(userid) {
	console.log(userid);
	if (userid > 0) {
		window.location.replace('index.php');
	} else {
		showError("Hi ha un error amb el nom d'usuari o la contrasenya");
	}
}

// given user data, writes it to the template
function getTeacher(data) {
	console.log(data);
	var teacher = JSON.parse(data);
	for (var id in teacher) {
		if (teacher.hasOwnProperty(id)) {
			var elem = document.getElementById(id);
			if (elem != null) {
				elem.innerHTML = teacher[id];
			}
		}
	}
}