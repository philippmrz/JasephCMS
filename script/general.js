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
    console.log("Redirecting");
    if (delay == null) {
      delay = 0;
    }
    setTimeout(function() {
        window.location.replace(link);
    }, delay*1000);
}

function swapStyle() {
    if (getCookie("theme") == "hacker") {
        setNormalMode();
        setCookie('theme', 'normal');
    } else {
        setHackerMode();
        setCookie('theme', 'hacker');
    }
}

function applyStyle() {
    if (getCookie("theme") == "hacker") {
        setHackerMode();
    } else {
        setNormalMode();
    }
}

function setHackerMode() {
    document.documentElement.style.setProperty('--bg-color', '#202124');
    document.documentElement.style.setProperty('--accent-color', '#20c20e');
    document.documentElement.style.setProperty('--accent-color-light', '#8fe086');
    document.documentElement.style.setProperty('--accent-color-verylight', '#77ff88');
    document.documentElement.style.setProperty('--accent-color-dark', '#009900');
    document.documentElement.style.setProperty('--color', '#e6e6e6');
    document.documentElement.style.setProperty('--title-color', 'rgba(255, 255, 255, 0.6)');
    var x;
    if(x = document.querySelector("#mask")) {
      x.setAttribute('src', 'assets/mask-white.png');
    }
    if(x = document.querySelector("#head-logo")) {
      x.setAttribute('src', 'assets/jaseph_hacker.png');
    }
}

function setNormalMode() {
    document.documentElement.style.setProperty('--bg-color', '#fff');
    document.documentElement.style.setProperty('--accent-color', '#ff7614');
    document.documentElement.style.setProperty('--accent-color-light', '#ff9950');
    document.documentElement.style.setProperty('--accent-color-verylight', '#ffbe90');
    document.documentElement.style.setProperty('--accent-color-dark', '#c2590f');
    document.documentElement.style.setProperty('--color', '#202124');
    document.documentElement.style.setProperty('--title-color', 'rgba(0, 0, 0, 0.6)');
    var x;
    if(x = document.querySelector("#mask")) {
      x.setAttribute('src', 'assets/mask.png');
    }
    if(x = document.querySelector("#head-logo")) {
      x.setAttribute('src', 'assets/jaseph_normal.png');
    }
}

function updateMD() {
    elements = document.documentElement.getElementsByClassName('md');
    for (var i = 0; i < elements.length; i++) {
      parseMD(elements[i]);
    }
}

function parseMD(element) {
    //var bold = /\*\*[^\*]+?\*\*/g;
    var bold = /\*{2}.+?\*{2}\*?/g
    var italic = /\*[^\*]+?\*/g;
    var paragraph = /\n{2,}/g;
    var newline = /[^\S\n]{2}\n/g;
    //TODO REDO: var multilinecode = /(?<!\\)(?<!`)`{3}(?!`)/g;
    /*TODO
    code (`foo`)
    */
    var out;
    while ((out = bold.exec(element.innerHTML)) != null) {
        var word = out[0];
        var index = out['index'];
        var input = out['input'];
        var converted = convertMD(word, index, 'bold');
        console.log("Converted: " + converted);
        element.innerHTML = input.substr(0, index) + converted + input.substr(index + word.length, input.length);
    }
    var out = null;
    while ((out = italic.exec(element.innerHTML)) != null) {
        var word = out[0];
        var index = out['index'];
        var input = out['input'];
        var converted = convertMD(word, index, 'italic');
        console.log("Converted: " + converted);
        element.innerHTML = input.substr(0, index) + converted + input.substr(index + word.length, input.length);
    }
    var out = null;
    while ((out = paragraph.exec(element.innerHTML)) != null) {
        var word = out[0];
        var index = out['index'];
        var input = out['input'];
        var converted = convertMD(word, index, 'paragraph');
        console.log("Converted: " + converted);
        element.innerHTML = input.substr(0, index) + converted + input.substr(index + word.length, input.length);
    }
    var out = null;
    while ((out = newline.exec(element.innerHTML)) != null) {
        var word = out[0];
        var index = out['index'];
        var input = out['input'];
        var converted = convertMD(word, index, 'newline');
        console.log("Converted: " + converted);
        element.innerHTML = input.substr(0, index) + converted + input.substr(index + word.length, input.length);
    }
    console.log(element.innerHTML);
}

function convertMD(word, index, type) {
    console.log("(convertMD): Got it! (" + word + ", " + index + ", " + type + ")");
    switch(type) {
        case 'bold':
        return '<b>' + word.substr(2, word.length-4) + '</b>';
        break;

        case 'italic':
        return '<em>' + word.substr(1, word.length-2) + '</em>';
        break;

        case 'paragraph':
        return '</p><p>';
        break;

        case 'newline':
        return '<br>';
        break;

        case 'code':
        break;

        case 'multilinecode':
        break;
    }
}
