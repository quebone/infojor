window.onload = init();

function init() {
	var degreeId = document.getElementById("degree-selector").value;
	getCycles(degreeId);
}

// carregar la llista de cicles de l'etapa actual
function getCycles(degreeId) {
	var dataToSend = "degreeId=" + degreeId + "&function=getCycles";
	send(dataToSend, AJAXCONTROLLER, cyclesReceived);
	var dataToSend = "degreeId=" + degreeId + "&function=getAreas";
	send(dataToSend, AJAXCONTROLLER, areasReceived);
}

// carregar la llista d'àrees de l'etapa actual
function areasReceived(msg) {
	var selector = createSelector(JSON.parse(msg), "area-selector");
}

function cyclesReceived(msg) {
	var cycles = JSON.parse(msg);
	var selector = createSelector(cycles, "cycle-selector");
	changeCycle(selector);
	createCyclesCheckboxes(cycles);
}

// crear el selector amb els cicles de l'etapa actual
function createSelector(data, elemName) {
	var selector = document.getElementById(elemName);
	removeOptions(selector);
	for (var i=0; i < data.length; i++) {
		var opt = document.createElement("option");
		opt.value = data[i].id;
		opt.text = data[i].name;
		selector.add(opt);
	}
	return selector;
}

// crear els checkbox de cicles per les noves dimensions
function createCyclesCheckboxes(cycles) {
	var container = document.getElementById("cycles");
	clean(container);
	for (var i=0; i<cycles.length; i++) {
		var checkbox = document.createElement("input");
		checkbox.type = "checkbox";
		checkbox.value = cycles[i].id;
		checkbox.checked = true;
		var descr = document.createElement("label");
		descr.innerHTML = cycles[i].name;
		container.appendChild(checkbox);
		container.appendChild(descr);
	}
}

function changeCycle(elem) {
	cycleId = elem.value;
	getCycleDimensions(cycleId);
}

// canvia d'etapa
function changeDegree(elem) {
	var degreeId = elem.value;
	getCycles(degreeId);
	document.getElementById("degree-selector").value = degreeId;
}

// envia l'identificador de classe al controlador perquè recuperi la llista dels seus alumnes
function getCycleDimensions(cycleId) {
	disable(document.getElementById('dimensions'));
	var degreeId = document.getElementById("degree-selector").value;
	var dataToSend = "degreeId=" + degreeId + "&cycleId=" + cycleId + "&function=listAllDimensions";
	send(dataToSend, AJAXCONTROLLER, scopesReceived);
}

function cyclesAsString(cycles) {
	var objs = new Array;
	for (var i=0; i<cycles.length; i++) {
		if (cycles[i].checked)
			objs.push(cycles[i].value);
	}
	return JSON.stringify(objs);
}

function updateDimension(dimId) {
	var row = document.getElementById("dim-" + dimId);
	var title = JSON.stringify(row.getElementsByClassName("titol")[0].value);
	var description = JSON.stringify(row.getElementsByClassName("descripcio")[0].value);
	var cycles = row.getElementsByClassName("cycles")[0].childNodes;
	cycles = cyclesAsString(cycles);
	var isActive = row.getElementsByClassName("isActive")[0].childNodes[0].checked;
	var dataToSend = "dimensionId=" + dimId + "&name=" + title + "&description=" + description + "&cycles=" + cycles + "&isActive=" + isActive + "&function=updateDimension";
	send(dataToSend, AJAXCONTROLLER, updated);
}

function deleteDimension(dimId) {
	var cnf = confirm("Segur que vols eliminar aquesta dimensió?");
	if (cnf) {
		var dataToSend = "dimensionId=" + dimId + "&function=deleteDimension";
		send(dataToSend, AJAXCONTROLLER, deleted);
	}
}

function addDimension() {
	if (document.getElementById("title").value.length == 0) {
		showError("El títol no pot estar en blanc");
		return;
	}
	cleanError();
	var title = JSON.stringify(document.getElementById("title").value);
	var description = JSON.stringify(document.getElementById("description").value);
	var area = document.getElementById("area-selector").value;
	var cycles = document.getElementById("cycles").childNodes;
	cycles = cyclesAsString(cycles);
	var dataToSend = "name=" + title + "&description=" + description + "&cycles=" + cycles + "&areaId=" + area + "&function=addDimension";
	send(dataToSend, AJAXCONTROLLER, added);
}

function scopesReceived(scopes) {
	var container = document.getElementById("dimensions");
	container.innerHTML = scopes;
	enable(document.getElementById('dimensions'));
}

function deleted(msg) {
	if (msg == true) {
		getCycles(document.getElementById("degree-selector").value);
	} else {
		alert("No s'ha pogut eliminar la dimensió. Potser té alguna valoració assignada");
	}
}

function updated(msg) {
//	console.log(msg);
}

function added(msg) {
	document.getElementById("title").value = "";
	document.getElementById("description").value = "";
	getCycles(document.getElementById("degree-selector").value);
}
