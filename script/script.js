function setCookie(cname, val, time) {
  var now = new Date();
  if(time != null) {
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
    for(var i = 0; i < ca.length; i++) {
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

function swapStyle() {
  if(document.getElementById('swapper').innerHTML == 'Normie Mode') {
    setCookie("theme", "normal");
  } else {
    setCookie("theme", "hacker");
  }
  updateStyle();
}

function updateStyle() {
  if(getCookie("theme") == "hacker") {
    document.getElementById('swapper').innerHTML = 'Normie Mode';
    document.getElementById('pagestyle').setAttribute('href', 'style/hacker.css');
  } else {
    document.getElementById('swapper').innerHTML = 'Hacker Mode';
    document.getElementById('pagestyle').setAttribute('href', 'style/normal.css');
  }
  console.log("Updated style.");
}

window.onload = updateStyle;
