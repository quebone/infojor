// envia el text del missatge al controlador perquè enviï un correu als administradors
function sendNewMessage() {
	var msg = document.getElementById('message'); 
	var txt = msg.value;
	if (txt.length == 0) {
		showError("No es poden enviar missatges en blanc");
	} else {
		cleanError();
		var dataToSend = 'message=' + JSON.stringify(txt) + '&function=sendMessage';
		send(dataToSend, AJAXCONTROLLER, messageSent);
		alert("El missatge ha estat enviat correctament als administradors");
		msg.value = null;
		msg.focus();
	}
}

// retorn d'enviament
function messageSent(msg) {
}
