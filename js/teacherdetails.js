// envia l'identificador d'usuari al controlador perquè en restableixi la contrasenya
function resetPassword() {
	var cnf = confirm("Segur que vols restablir la contrasenya?");
	if (cnf == true) {
		var teacherId = document.getElementById("teacherId").value;
		var dataToSend = "teacherId=" + teacherId + "&function=resetPassword";
		send(dataToSend, AJAXCONTROLLER, passwordSet)
	}
}

// envia les dades al controlador perquè actualitzi un mestre
function sendUserData() {
	var teacherId = document.getElementById("teacherId").value;
	var name = encodeURI(document.getElementById("name").value);
	var surnames = encodeURI(document.getElementById("surnames").value);
	var email = encodeURI(document.getElementById("email").value);
	var phone = encodeURI(document.getElementById("phone").value);
	var username = encodeURI(document.getElementById("username").value);
	var isAdmin = document.getElementById("isAdmin").checked;
	var isActive = document.getElementById("isActive").checked;
	if (name.length == 0 || surnames.length == 0 || email.length == 0 || username.length == 0) {
		showError("Alguns camps no són correctes");
	} else {
		var dataToSend = "teacherId=" + teacherId + "&name=" + name + "&surnames=" + surnames + "&email=" + email + "&phone=" + phone +
			"&username=" + username + "&isAdmin=" + isAdmin + "&isActive=" + isActive + "&function=updateTeacher";
		send(dataToSend, AJAXCONTROLLER, teacherUpdated)
	}
}

// si l'usuari prem el botó de cancel·lar, carreguem la llista de mestres
function cancel() {
	window.location.replace('teachers.php');
}

// retorn de restabliment de contrasenya
function passwordSet(input) {
	alert("La contrasenya s'ha restablert");
}

// retorn d'actualització d'un mestre
function teacherUpdated(input) {
	window.location.replace('teachers.php');
}