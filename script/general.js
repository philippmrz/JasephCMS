/*
 * This javascript file makes use of the following libraries:
 * Showdown
 * ..Github:(https://github.com/showdownjs/showdown)
 *
 */

function setCookie(cname, val, time) { // for time use new Date(years, months, days, hours, minutes, seconds, milliseconds);
    var now = new Date();
    if (time != null) {
        var expiry = new Date(now.getTime() + time);
        document.cookie = cname + "=" + val + "; expires=" + expiry.toUTCString() + "; path=";
    } else {
        document.cookie = cname + "=" + val + "; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=";
    }
    //DEBUG console.log("Set value of cookie '" + cname + "' to '" + val + "'");
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return null;
}

function listCookies() {
    var theCookies = document.cookie.split(';');
    var aString = '';
    for (var i = 1 ; i <= theCookies.length; i++) {
        aString += i + ' ' + theCookies[i-1] + "\n";
    }
    return aString;
}

function deleteCookie(cname) {
  document.cookie = cname + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT; path=';
}

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
    //DEBUG console.log("Deleted all cookies.");
}

function logout() {
    deleteCookie("logcheck");
    deleteCookie("uname");
    deleteCookie("identifier");
    deleteCookie("token");
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
    document.documentElement.style.setProperty('--title-color', 'rgba(255, 255, 255, 0.6)');
    document.documentElement.style.setProperty('--accent-color', '#20c20e');
    document.documentElement.style.setProperty('--accent-color-light', '#8fe086');
    document.documentElement.style.setProperty('--accent-color-verylight', '#77ff88');
    document.documentElement.style.setProperty('--accent-color-dark', '#009900');
    var x = document.querySelector('#mask');
    if (x) {
      x.setAttribute('src', 'assets/mask_white.png');
    }
}

function setNormalMode() {
    document.documentElement.style.setProperty('--bg-color', '#fff');
    document.documentElement.style.setProperty('--color', '#202124');
    document.documentElement.style.setProperty('--table-accent-color', '#cccccc');
    document.documentElement.style.setProperty('--title-color', 'rgba(0, 0, 0, 0.6)');
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
            if (getCookie('theme') == 'hacker') {
                x.setAttribute('src', 'assets/icon_hacker.png');
            } else {
              x.setAttribute('src', 'assets/icon.png');
            }
            document.getElementById('logo-wrapper').removeAttribute('href');
            document.getElementById('logo-wrapper').setAttribute('onclick', 'navigation();');
        } else {
            if (getCookie('theme') == 'hacker') {
                x.setAttribute('src', 'assets/jaseph_hacker.png');
            } else {
                x.setAttribute('src', 'assets/jaseph_normal.png');
            }
            document.getElementById('logo-wrapper').setAttribute('href', 'index');
            document.getElementById('logo-wrapper').removeAttribute('onclick');
        }
    }
}

window.addEventListener('resize', rez);

var nav = false;

function navigation() {
    var x = document.querySelector('#navbar');
    if (nav) {
        x.style.transform = 'translateY(-5vh)';
        nav = false;
    } else {
        x.style.transform = 'translateY(10vh)';
        nav = true;
    }
}

//MARKDOWN formatting using Showdown
showdown.setOption('strikethrough', true);
showdown.setOption('tables', true);
showdown.setOption('smoothLivePreview', true);

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
}
