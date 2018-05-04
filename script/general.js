/*
 * This javascript file makes use of the following libraries:
 * Showdown
 * ..Github:(https://github.com/showdownjs/showdown)
 *
 */

function logout() {
    deleteCookie("identifier");
    deleteCookie("hashed_password");
    deleteCookie("PHPSESSID");
    location = location;
}

function redirect(link, delay) { // Delay in seconds
    //DEBUG console.log("Redirecting to " + link);
    if (delay == null) {
        delay = 0;
    }
    setTimeout(function() {
        window.location.replace(link);
    }, delay*1000);
}

function swapStyle() {
    if (getCookie("theme") == "hacker") {
        setCookie('theme', 'normal');
    } else {
        setCookie('theme', 'hacker');
    }
    applyStyle();
}

function applyStyle() {
    if (getCookie("theme") == "hacker") {
        setHackerMode();
    } else {
        setNormalMode();
    }
    rez();
}

function setHackerMode() {
    document.documentElement.style.setProperty('--bg-color', '#202124');
    document.documentElement.style.setProperty('--color', '#e6e6e6');
    document.documentElement.style.setProperty('--table-accent-color', '#313235');
    document.documentElement.style.setProperty('--title-color', '#999');
    document.documentElement.style.setProperty('--accent-color', '#20c20e');
    document.documentElement.style.setProperty('--accent-color-light', '#73d469');
    document.documentElement.style.setProperty('--accent-color-verylight', '#acffa3');
    document.documentElement.style.setProperty('--accent-color-dark', '#090');
    var x = document.querySelector('#mask');
    if (x) {
        x.setAttribute('src', 'assets/mask_white.png');
    }
}

function setNormalMode() {
    document.documentElement.style.setProperty('--bg-color', '#fff');
    document.documentElement.style.setProperty('--color', '#202124');
    document.documentElement.style.setProperty('--table-accent-color', '#ccc');
    document.documentElement.style.setProperty('--title-color', '#333');
    document.documentElement.style.setProperty('--accent-color', '#ff7614');
    document.documentElement.style.setProperty('--accent-color-light', '#ff9950');
    document.documentElement.style.setProperty('--accent-color-verylight', '#ffbe90');
    document.documentElement.style.setProperty('--accent-color-dark', '#c2590f');
    var x = document.querySelector('#mask');
    if (x) {
        x.setAttribute('src', 'assets/mask.png');
    }
}

function rez() {
    var x = document.querySelector('#head-logo');
    if (x) {
        if (window.innerWidth <= 900) {
            var y = document.getElementById('navbar');
            if (y) {
              y.style.gridTemplateColumns = 'repeat(' + y.childElementCount + ', 1fr)';
            }
            if (getCookie('theme') == 'hacker') {
                x.setAttribute('src', 'assets/icon_hacker.png');
            } else {
                x.setAttribute('src', 'assets/icon.png');
            }
        } else {
            if (getCookie('theme') == 'hacker') {
                x.setAttribute('src', 'assets/jaseph_hacker.png');
            } else {
                x.setAttribute('src', 'assets/jaseph_normal.png');
            }
        }
    }
}

window.addEventListener('resize', rez);

//MARKDOWN formatting using Showdown
showdown.setOption('strikethrough', true);
showdown.setOption('tables', true);
showdown.setOption('smoothLivePreview', true);
showdown.setOption('noHeaderId', true);

function updateMD() {
    elements = document.getElementsByClassName('md');
    for (var i = 0; i < elements.length; i++) {
        runMD(elements[i]);
    }
}

function runMD(source, target) {
    var text = source.innerHTML;
    var converter = new showdown.Converter();
    var output = converter.makeHtml(text);
    if (target) {
        target.innerHTML = output;
    } else {
        source.innerHTML = output;
    }
}

window.onload = function() {
    rez();
    updateMD();
};
