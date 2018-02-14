function setCookie(cname, val, time) { // for time use new Date(years, months, days, hours, minutes, seconds, milliseconds);
    var now = new Date();
    if (time != null) {
        var expiry = new Date(now.getTime() + time);
        document.cookie = cname + "=" + val + "; expires=" + expiry.toUTCString() + ";";
    } else {
        document.cookie = cname + "=" + val + "; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=";
    }
    console.log("Set value of cookie '" + cname + "' to '" + val + "'");
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

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
    console.log("Deleted all cookies.");
}

function updateStyle() {
    if (getCookie("theme") == "hacker") {
        setNormalMode();
        setCookie('theme', 'normal');
    } else {
        setHackerMode();
        setCookie('theme', 'hacker');
    }
}

window.onload = function () {
    if (getCookie("theme") == "hacker") {
        setHackerMode();
    }
};

function setHackerMode() {
    document.documentElement.style.setProperty('--background-color', '#212121');
    document.documentElement.style.setProperty('--accent-color', 'rgb(32,194,14)');
    document.documentElement.style.setProperty('--color', 'rgb(32,194,14)');
    document.querySelector("#mask").setAttribute('src', 'assets/mask-white.png');
    document.querySelector("#head-logo").setAttribute('src', 'assets/jaseph_hacker.png');
    document.querySelector('#head-wrap').style.boxShadow = 'none';
    document.querySelector('#head-wrap').style.backgroundColor = 'black';
}

function setNormalMode() {
    document.documentElement.style.setProperty('--background-color', 'white');
    document.documentElement.style.setProperty('--accent-color', 'rgb(255, 118, 20)');
    document.documentElement.style.setProperty('--color', '#212121');
    document.querySelector("#mask").setAttribute('src', 'assets/mask.png');
    document.querySelector("#head-logo").setAttribute('src', 'assets/jaseph_normal.png');
    document.querySelector('#head-wrap').style.boxShadow = '0 2px 6px 0 rgba(0, 0, 0, .12), inset 0 -1px 0 0 #dadce0';
    document.querySelector('#head-wrap').style.backgroundColor = 'white';
}