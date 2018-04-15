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
