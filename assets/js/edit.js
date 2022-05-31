//les variables
var input = document.getElementsByClassName('input-edit');
var divInfos = document.getElementsByClassName('infos');
var edit = document.getElementById('edit-btn');
var cancel = document.getElementById('cancel_change');
var confirme = document.getElementById('confirm-btn');
var divPasswd = document.getElementsByClassName('passwd-manager')[0];
var pass1 = document.getElementById('password_change_1');
var pass2 = document.getElementById('password_change_2');

cancel.style.display = "none";
edit.addEventListener('click', editInfos);
cancel.addEventListener('click', cancelChange);
confirme.addEventListener('click', confirmInfos);

//fonction pour pouvoir modifier les information
function editInfos(e) {
    e.preventDefault();
    for (let i = 0; i < input.length; i++) {
        input[i].readOnly = false;
        for (let i = 3; i < 5; i++) {
            divInfos[i].style.backgroundColor = "#DDD";
        }
        edit.style.display="none";
        cancel.style.display = "block";
        confirme.style.display="block";
    }
    divPasswd.style.display = "block";

}
//fonction pour confirmer les information
function confirmInfos() {
    for (let i = 0; i < 3; i++) {
        if (pass1.value != "" && pass2 != "") {
            divPasswd.style.display = "none";
            input[i].readOnly = true;
            for (let i = 3; i < 5; i++) {
                divInfos[i].style.backgroundColor = "white";
            }
            edit.style.display="block";
            confirme.style.display="none";
        }
    }
}
//fonction pour annuler les modification
function cancelChange(){
    cancel.style.display = "none";
    for (let i = 0; i < 3; i++) {
        divPasswd.style.display = "none";
        input[i].readOnly = true;
        for (let i = 3; i < 5; i++) {
            divInfos[i].style.backgroundColor = "white";
        }
        edit.style.display="block";
        confirme.style.display="none";
    }
    window.location.reload();
}