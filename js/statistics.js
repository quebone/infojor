function createAllSummaryTables() {
	var dataToSend = 'function=createAllSummaryTables';
	send(dataToSend, AJAXCONTROLLER, done);
	disable(document.getElementsByTagName('main')[0]);
//	showError("Espereu mentre es crea l'arxiu");
	document.getElementsByTagName('body')[0].style.cursor = "wait";
}

function done(msg) {
	console.log(msg);
	document.getElementsByTagName('body')[0].style.cursor = "default";
	enable(document.getElementsByTagName('main')[0]);
//	cleanError();
	window.location.replace('files/taula-resum.xls')
}