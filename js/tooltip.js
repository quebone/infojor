var intval;

function setDimensionTooltip() {
	var dimensions = document.getElementsByClassName('dimension');
	for (var i = 0; i < dimensions.length; i++) {
		dimensions[i].addEventListener('mouseover', showTooltip, false);
		dimensions[i].addEventListener('mouseout', function(){restartTimeOut(1000)}, false);
	}
}

function showTooltip(event) {
	var tooltip = document.getElementById("tooltip");
	tooltip.innerHTML = event.target.getAttribute('descr');
	if (tooltip.innerHTML.length == 0) {
		return;
	}
	rect = event.target.getBoundingClientRect();
	tooltip.style.top = event.clientY - (rect.bottom - rect.top) / 2 - 15 + "px";
	tooltip.style.left = event.clientX - (rect.right - rect.left) / 2 + "px";
	tooltip.style.visibility = 'visible';
	tooltip.style.opacity = .9;
	clearTimeout(intval);
	intval = setTimeout(1000, function () {
		event.target.addEventListener('mouseout', function() {restartTimeOut(event.target)}, false);
	});
}

function restartTimeOut(event) {
	clearTimeout(intval);
	intval = setTimeout(function() {hideTooltip(event)}, 300);
}

function hideTooltip(event) {
	var tooltip = document.getElementById("tooltip");
    tooltip.style.transition = 'opacity 1s';
	tooltip.style.visibility = 'hidden';
	tooltip.style.opacity = 0;
}
