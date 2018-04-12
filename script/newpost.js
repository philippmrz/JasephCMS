/*
 * This javascript file makes use of the following libraries:
 * Showdown
 * ..Github:(https://github.com/showdownjs/showdown)
 *
 */

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

window.onload = function() {
    document.getElementById("contentArea").addEventListener("keyup", function(e) {
        if(e.ctrlKey && e.keyCode == 13) {
            document.getElementById("newpost").submit();
        }
    });
}
