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
	xmlhttp.send(dataToSend);
}

function loadPHP() {
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

function main() {
	window.location.replace('main.php');
}

function disable(elem) {
	elem.style.opacity = 0.4;
	elem.style.pointerEvents = 'none';
}

function enable(elem) {
	elem.style.opacity = 1;
	elem.style.pointerEvents = 'auto';
}

