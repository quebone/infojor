function resetPassword() {
	var cnf = confirm("Segur que vols restablir la contrasenya?");
	if (cnf == true) {
		var teacherId = document.getElementById("teacherId").value;
		var dataToSend = "teacherId=" + teacherId + "&function=resetPassword";
		send(dataToSend, AJAXCONTROLLER, passwordSet)
	}
}

function sendUserData() {
	var teacherId = document.getElementById("teacherId").value;
	var name = encodeURI(document.getElementById("name").value);
	var surnames = encodeURI(document.getElementById("surnames").value);
	var email = encodeURI(document.getElementById("email").value);
	var phone = encodeURI(document.getElementById("phone").value);
	var username = encodeURI(document.getElementById("username").value);
	var isAdmin = document.getElementById("isAdmin").checked;
	if (name.length == 0 || surnames.length == 0 || email.length == 0 || username.length == 0) {
		showError("Alguns camps no són correctes");
	} else {
		var dataToSend = "teacherId=" + teacherId + "&name=" + name + "&surnames=" + surnames + "&email=" + email + "&phone=" + phone +
			"&username=" + username + "&isAdmin=" + isAdmin + "&function=updateTeacher";
		console.log(dataToSend);
		send(dataToSend, AJAXCONTROLLER, teacherUpdated)
	}
}

function cancel() {
	window.location.replace('teachers.php');
}

function passwordSet(input) {
	alert("La contrasenya s'ha restablert");
}

function teacherUpdated(input) {
	console.log(input);
	window.location.replace('teachers.php');
}