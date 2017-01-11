window.onload = init;

// a la pantalla de login no s'ha de mostrar el menú
function init() {
	document.getElementsByClassName("compact")[0].style.display = "none";
}

// envia les dades d'usuari al controlador perquè comprovi si són correctes
function sendUserData() {
	var user = encodeURI(document.getElementById('username').value);
	var password = encodeURI(document.getElementById('password').value);
	if (user.length <= 0 || password.length <= 0) {
		showError("El nom d'usuari i la contrasenya no poden estar en blanc");
		return;
	}
	var dataToSend = 'username=' + user + '&password=' + password + '&function=login';
	send(dataToSend, AJAXCONTROLLER, login);
}

// retorn del registre
function login(userid) {
//	console.log(userid);
	if (userid > 0) {
		window.location.replace('main.php');
	} else {
		showError("Hi ha un error amb el nom d'usuari o la contrasenya");
	}
}