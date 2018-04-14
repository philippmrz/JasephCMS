function rezUserlist() {
    var x = document.querySelectorAll('td.userlist-role input');
    if (window.innerWidth <= 900) {
        for (var i = 0; i < x.length; i++) {
            x[i].disabled = true;
        }
    } else {
        for (var i = 0; i < x.length; i++) {
            x[i].disabled = false;
        }
    }
    var x = document.querySelectorAll('td.userlist-manage select');
    if (window.innerWidth <= 900) {
        for (var i = 0; i < x.length; i++) {
            x[i].disabled = false;
        }
    } else {
        for (var i = 0; i < x.length; i++) {
            x[i].disabled = true;
        }
    }
}

window.addEventListener('resize', rezUserlist);

window.onload(rezUserlist);
