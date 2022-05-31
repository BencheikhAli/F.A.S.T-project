//les variable
var lname = document.getElementsByClassName('input-reg')[0];
var fname = document.getElementsByClassName('input-reg')[1];
var email = document.getElementsByClassName('input-reg')[2];
if (document.getElementsByClassName('input-reg')[3]) {
    var role = document.getElementsByClassName('input-reg')[3];
}
var btnSubmit = document.getElementById("validate");
//enregistrer les donnes dans le local storage quand on click sur btnSubmit
btnSubmit.addEventListener('click', ()=>{
    localStorage.setItem('name', lname.value);
    localStorage.setItem('fname', fname.value);
    localStorage.setItem('email', email.value);
    if (role) {
        localStorage.setItem('role', role.value);
    }
});

pageUrl = location.href;
pageUrlParametre = pageUrl.split("?");
//recuperer les donnes de localstorage quand on'as valide=false ou error=true dans le URL de la page
if (pageUrlParametre[1] == "valide=false" || pageUrlParametre[1] == "error=true") {
    var lname = document.getElementsByClassName('input-reg')[0];
    var fname = document.getElementsByClassName('input-reg')[1];
    if (document.getElementsByClassName('input-reg')[3]) {
         var role = document.getElementsByClassName('input-reg')[3];
    }
    lname.value = localStorage.getItem('name');
    fname.value = localStorage.getItem('fname');
    if (role) {
        role.value = localStorage.getItem('role');
    }
}