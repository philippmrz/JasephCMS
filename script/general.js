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

 function deleteCookie(cname) {
   document.cookie = cname + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT; path=';
 }

function logout() {
    deleteCookie("identifier");
    deleteCookie("hashed_password");
    deleteCookie("PHPSESSID");
    location = location;
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
 }

// Color Magic

function hexToRgb(hex) { //Requires '#'
    var r = parseInt(hex.substr(1,2), 16);
    var g = parseInt(hex.substr(3,2), 16);
    var b = parseInt(hex.substr(5,2), 16);
    return [r, g, b];
}

function hexToHsl(hex) {
    var arr = hexToRgb(hex);
    var r = arr[0];
    var g = arr[1];
    var b = arr[2];
    return rgbToHsl(r, g, b);
}

function rgbToHsl(r, g, b) {
    r /= 255;
    g /= 255;
    b /= 255;
    var max = Math.max(r, g, b), min = Math.min(r, g, b);
    var h, s, l = (max + min) / 2;

    if(max == min){
        h = s = 0; // achromatic
    } else {
        var d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        switch(max){
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }
        h /= 6;
    }
    return [h, s, l];
}

function hslToRgb(h, s, l) {
    var r, g, b;
    if (s == 0) {
        r = g = b = l; // achromatic
    } else {
        function hue2rgb(p, q, t) {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1/6) return p + (q - p) * 6 * t;
            if (t < 1/2) return q;
            if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        }
        var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        var p = 2 * l - q;

        r = hue2rgb(p, q, h + 1/3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1/3);
    }
    return [ Math.round(r * 255), Math.round(g * 255), Math.round(b * 255) ];
}

function rgbToHex(r, g, b) {
    r = r.toString(16);
    g = g.toString(16);
    b = b.toString(16);
    r = r.length == 1 ? "0" + r : r;
    g = g.length == 1 ? "0" + g : g;
    b = b.length == 1 ? "0" + b : b;
    return '#' + r + g + b;
}

function hslToHex(h, s, l) {
    var arr = hslToRgb(h, s, l);
    var r = arr[0];
    var g = arr[1];
    var b = arr[2];
    return rgbToHex(r, g, b);
}

function calculatePalette(hex) { //Returns the original hex color, a light, verylight, and a dark variant as an array
    var hsl = hexToHsl(hex);
    var h = hsl[0];
    var s = hsl[1];
    var l = hsl[2];
    //light
    var light = hslToRgb(h,s*0.75,l*1.3);
    //verylight
    var verylight = hslToRgb(h,s*0.5,l*1.5);
    //dark
    var dark = hslToRgb(h,s,l*0.5);
    return [hexToRgb(hex), light, verylight, dark];
}

function parseRgb(array) {
    return "rgb(" + array[0] + ", " + array[1] + ", " + array[2] + ")";
}

function setPalette(palette) {
    x = document.documentElement.style;
    x.setProperty('--accent-color', parseRgb(palette[0]));
    x.setProperty('--accent-color-light', parseRgb(palette[1]));
    x.setProperty('--accent-color-verylight', parseRgb(palette[2]));
    x.setProperty('--accent-color-dark', parseRgb(palette[3]));
}

function swapStyle() {
    if (getCookie("theme") == "hacker") {
        setCookie('theme', 'normal');
    } else {
        setCookie('theme', 'hacker');
    }
    applyPalette();
    applyStyle();
}

function applyPalette() {
  if (getCookie("theme") == "hacker") {
    if (!getCookie('color-hacker')) {
      setCookie('color-hacker', '#20c20e');
    }
    setPalette(calculatePalette(getCookie('color-hacker')));
  } else {
    if (!getCookie('color')) {
      setCookie('color', '#ff7614');
    }
    setPalette(calculatePalette(getCookie('color')));
  }
}

function applyStyle() {
    var x = document.getElementById('color-slider');
    if (getCookie("theme") == "hacker") {
        document.documentElement.style.setProperty('--bg-color', '#202124');
        document.documentElement.style.setProperty('--color', '#e6e6e6');
        document.documentElement.style.setProperty('--table-accent-color', '#313235');
        document.documentElement.style.setProperty('--title-color', '#999');
        if (x) {
          x.value = hexToHsl(getCookie('color-hacker'))[0] * 360;
        }
    } else {
        document.documentElement.style.setProperty('--bg-color', '#fff');
        document.documentElement.style.setProperty('--color', '#202124');
        document.documentElement.style.setProperty('--table-accent-color', '#ccc');
        document.documentElement.style.setProperty('--title-color', '#333');
        if (x) {
          x.value = hexToHsl(getCookie('color'))[0] * 360;
        }
    }
    applyPalette();
    updateSliderBG();
    rez();
}

function rez() {
    if (window.innerWidth <= 900) {
        var y = document.getElementById('navbar');
        if (y) {
            y.style.gridTemplateColumns = 'repeat(' + y.childElementCount + ', 1fr)';
        }
    }
}

// Header Mask JS

function toggleDesignMenu() {
  document.getElementById('design-menu').classList.toggle('show');
}

function updateSliderBG() {
  var x = document.getElementById('color-slider');
  if (x) {
    x.style.background = 'hsl(' + x.value + ', 100%, 50%)';
  }
}

function yuh() {
  var x = document.getElementById('color-slider').value;
  updateSliderBG();
  if (getCookie('theme') == 'hacker') {
    setCookie('color-hacker', hslToHex(x/360, 1, 0.5));
  } else {
    setCookie('color', hslToHex(x/360, 1, 0.5));
  }
  applyPalette();
}

window.addEventListener('resize', rez);

// MARKDOWN formatting using Showdown
showdown.setOption('strikethrough', true);
showdown.setOption('tables', true);
showdown.setOption('smoothLivePreview', true);
showdown.setOption('noHeaderId', true);
showdown.setOption('simplifiedAutoLink', true);

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
