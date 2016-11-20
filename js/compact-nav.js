window.onload = init;

function init() {
	initmenu();
}

function initmenu() {
	initCompactMenu();
	highlightAll();
	var nav = document.getElementsByTagName("nav")[0].getElementsByTagName("ul")[0];
	var submenus = nav.getElementsByTagName("ul");
	for (var i = 0; i < submenus.length; i++) {
		var parentMenu = submenus[i].parentNode;
		var parentWidth = parentMenu.offsetWidth;
		parentMenu.onmouseover = function() {showSubmenu(this);};
		parentMenu.onmouseout = function() {hideSubmenu(this);};
	}
}

function initCompactMenu() {
	var nav = document.getElementsByTagName("nav")[0];
	if (nav.getAttribute("class") == "compact") {
	var selector = document.getElementsByClassName("menu-selector")[0];
		selector.onmouseover = function () {
			this.style.background = "url('presentation/images/menu/menu-on.png')";
		}
		selector.onmouseout = function () {
			this.style.background = "url('presentation/images/menu/menu-off.png')";
		}
		var submenu = nav.getElementsByTagName("ul")[0];
		selector.onclick = function () {
			switchDisplay(submenu);
		}
	}
}

function highlightAll() {
	var menuitems = document.getElementsByTagName("nav")[0].getElementsByTagName("li");
	for (var i = 0; i < menuitems.length; i++) {
		menuitems[i].onmouseover = function () {highlight(this, true)};
		menuitems[i].onmouseout = function () {highlight(this, false)};
	}
}

function highlight(elem, highlighted) {
	if (highlighted == true) {
		elem.setAttribute("class", "highlighted");
	} else {
		elem.removeAttribute("class");
	}
}

function showSubmenu(submenu) {
	submenu.children[0].style.display = 'block';
	highlight(submenu, true);

}

function hideSubmenu(submenu) {
	submenu.children[0].style.display = 'none';
	highlight(submenu, false);
}

function switchDisplay(submenu) {
	var on = submenu.style.display == 'block'; 
	submenu.style.display = on ? 'none' : 'block';
}