/*
 * This javascript file makes use of the following libraries:
 * Showdown
 * ..Github:(https://github.com/showdownjs/showdown)
 *
 */

function rezNewpost() {
    var submit = document.getElementById('btnpost');
    var expand = document.getElementById('newpost-expand');
    var expand_submit = document.getElementById('newpost-expand-submit');
    var expand_newdraft = document.getElementById('newpost-expand-newdraft');
    var expand_drafts = document.getElementById('newpost-expand-drafts');
    if (window.innerWidth <= 900) {
        submit.style.visibility = 'hidden';
        expand.style.visibility = 'visible';
        applyExpand();
    } else {
        submit.style.visibility = 'visible';
        expand.style.visibility = 'hidden';
        expand_submit.style.visibility = 'hidden';
        expand_newdraft.style.visibility = 'hidden';
        expand_drafts.style.visibility = 'hidden';
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
        expand_submit.classList.add('expanded');
        expand_newdraft.classList.add('expanded');
        expand_drafts.classList.add('expanded');

    } else {
        expand_submit.classList.remove('expanded');
        expand_newdraft.classList.remove('expanded');
        expand_drafts.classList.remove('expanded');
        expand_submit.style.visibility = 'hidden';
        expand_newdraft.style.visibility = 'hidden';
        expand_drafts.style.visibility = 'hidden';
    }
}

function toggleExpand() {
    if (on) {
        on = false;
    } else {
        on = true;
    }
    console.log(on);
    applyExpand();
}

 function updateCharsLeft(maxAmt, type) {
    if(type == "title") {
        var cur = document.getElementById("titleField").value.length;
        document.getElementById("titlecharswrapper").innerHTML = maxAmt - cur;
    } else if(type == "content") {
        var cur = document.getElementById("contentArea").value.length;
        document.getElementById("contentcharswrapper").innerHTML = maxAmt - cur;
    }
}

function refreshContentArea() {
    updateCharsLeft(10000, 'contentArea');
    var text = document.getElementById('contentArea').value;
    var target = document.getElementById('preview');
    var converter = new showdown.Converter();
    var output = converter.makeHtml(text);
    target.innerHTML = output;
}

window.addEventListener('resize', rezNewpost);

window.onload = function() {
    document.getElementById("contentArea").addEventListener("keyup", function(e) {
        if(e.ctrlKey && e.keyCode == 13) {
            document.getElementById("newpost").submit();
        }
    });
    rezNewpost();
}
