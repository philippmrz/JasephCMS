/*
 * This javascript file makes use of the following libraries:
 * Showdown
 * ..Github:(https://github.com/showdownjs/showdown)
 *
 */

function addClass(elem, classname) {
    if (!elem.classList.contains(classname)) {
        elem.classList.add(classname);
    }
}

function removeClass(elem, classname) {
    if (elem.classList.contains(classname)) {
        elem.classList.remove(classname);
    }
}

function rezNewpost() {
    var expand = document.getElementById('newpost-expand');
    var expand_submit = document.getElementById('newpost-expand-submit');
    var expand_newdraft = document.getElementById('newpost-expand-newdraft');
    var expand_drafts = document.getElementById('newpost-expand-drafts');
    if (window.innerWidth <= 900) {
        expand.style.visibility = 'visible';
        applyExpand();
    } else {
        expand.style.visibility = 'hidden';
        expand_submit.style.visibility = 'visible';
        expand_newdraft.style.visibility = 'visible';
        expand_drafts.style.visibility = 'visible';
        addClass(expand_submit,'expanded');
        addClass(expand_newdraft,'expanded');
        addClass(expand_drafts,'expanded');
    }
}

var on = false;

function applyExpand() {
    var expand_submit = document.getElementById('newpost-expand-submit');
    var expand_newdraft = document.getElementById('newpost-expand-newdraft');
    var expand_drafts = document.getElementById('newpost-expand-drafts');
    if (on) {
        expand_submit.style.visibility = 'visible';
        expand_newdraft.style.visibility = 'visible';
        expand_drafts.style.visibility = 'visible';
        addClass(expand_submit,'expanded');
        addClass(expand_newdraft,'expanded');
        addClass(expand_drafts,'expanded');

    } else {
        removeClass(expand_submit,'expanded');
        removeClass(expand_newdraft,'expanded');
        removeClass(expand_drafts,'expanded');
        expand_submit.style.visibility = 'hidden';
        expand_newdraft.style.visibility = 'hidden';
        expand_drafts.style.visibility = 'hidden';
    }
}

function toggleExpand() {
    on = !on;
    applyExpand();
}

var maxAmtTitle = 200;
var maxAmtContent = 10000;

 function updateCharsLeft() {
    var cur = document.getElementById("titleField").value.length;
    document.getElementById("titlecharswrapper").innerHTML = maxAmtTitle - cur;
    var cur = document.getElementById("contentArea").value.length;
    document.getElementById("contentcharswrapper").innerHTML = maxAmtContent - cur;
}

function refreshContentArea() {
    updateCharsLeft();
    var text = escapeHtml(document.getElementById('contentArea').value);
    var target = document.getElementById('preview');
    var converter = new showdown.Converter();
    var output = converter.makeHtml(text);
    target.innerHTML = output;
}

function addCtrlEnterListener() {
    document.getElementById("contentArea").addEventListener("keyup", function(e) {
        if(e.ctrlKey && e.keyCode == 13) {
            document.getElementById('newpost-expand-submit').click();
        }
    });
}

window.addEventListener('resize', rezNewpost);

window.onload = function() {
    addCtrlEnterListener();
    rezNewpost();
}
