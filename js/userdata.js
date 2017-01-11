// envia les dades al controlador perquè actualitzi un usuari
function sendUserData() {
	var userId = document.getElementById("userId").value;
	var name = encodeURI(document.getElementById("name").value);
	var surnames = encodeURI(document.getElementById("surnames").value);
	var email = encodeURI(document.getElementById("email").value);
	var phone = encodeURI(document.getElementById("phone").value);
	var username = encodeURI(document.getElementById("username").value);
	var password = encodeURI(document.getElementById("password").value);
	var password_repeat = encodeURI(document.getElementById("password-repeat").value);
	if (name.length == 0 || surnames.length == 0 || email.length == 0 || username.length == 0 || password.length == 0) {
		showError("Alguns camps no són correctes");
	} else if (password != password_repeat) {
		showError("Les 2 contrasenyes són diferents");
	} else {
		var dataToSend = "userId=" + userId + "&name=" + name + "&surnames=" + surnames + "&email=" + email + "&phone=" + phone +
			"&username=" + username + "&password=" + password + "&function=updatePersonalData";
		send(dataToSend, AJAXCONTROLLER, userUpdated)
	}
}

// en cas de cancel·lar, carreguem la pàgina principal
function cancel() {
	window.location.replace('main.php');
}

// retorn d'usuari actualitzat
function userUpdated(input) {
	window.location.replace('main.php');
}
