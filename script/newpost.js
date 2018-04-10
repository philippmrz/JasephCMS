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
    document.getElementById('preview').innerHTML = '<p>' + document.getElementById('contentArea').value + '</p>';
    updateMD();
}

window.onload = function() {
    document.getElementById("contentArea").addEventListener("keyup", function(e) {
        if(e.ctrlKey && e.keyCode == 13) {
            document.getElementById("newpost").submit();
        }
    });
}
