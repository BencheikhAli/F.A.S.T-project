//les variables
var typeProbleme = document.getElementsByName('type_probleme');
var process = document.getElementsByName('application_process');
var materiel = document.getElementsByName('materiel');
var wms = document.getElementsByName('wms');
var localisation = document.getElementById("localisation");
var desc = document.getElementById('desc');
var btnSubmit = document.getElementsByClassName('sign-btn')[0];
//enregistrer les donnes dans le local storage quand on click sur btnSubmit
btnSubmit.addEventListener('click', ()=>{
    for (let i = 0; i < typeProbleme.length; i++) {
        if (typeProbleme[i].checked) {
            localStorage.setItem('type_probleme', typeProbleme[i].value);
        }
    }
    for (let i = 0; i < process.length; i++) {
        if (process[i].checked) {
            localStorage.setItem('application_process', process[i].value);
        }
    }
    for (let i = 0; i < materiel.length; i++) {
        if (materiel[i].checked) {
            localStorage.setItem('materiel', materiel[i].value);
        }
    }
    for (let i = 0; i < wms.length; i++) {
        if (wms[i].checked) {
            localStorage.setItem('wms', wms[i].value);
        }
    }
    localStorage.setItem("localisation", localisation.value);
    localStorage.setItem("desc", desc.value);
});

pageUrl = location.href;
pageUrlParametre = pageUrl.split("?");
//recuperer les donnes de localstorage
if (pageUrlParametre[1] == "description=error" || pageUrlParametre[1] == "file=error" || pageUrlParametre[1] == "location=error") {
    for (let i = 0; i < typeProbleme.length; i++) {
        if (typeProbleme[i].value == localStorage.getItem("type_probleme")) {
            typeProbleme[i].checked = true;
        }
    }
    for (let i = 0; i < process.length; i++) {
        if (process[i].value == localStorage.getItem("application_process")) {
            process[i].checked = true;
        }
    }
    for (let i = 0; i < materiel.length; i++) {
        if (materiel[i].value == localStorage.getItem("materiel")) {
            materiel[i].checked = true;
        }
    }
    for (let i = 0; i < wms.length; i++) {
        if (wms[i].value == localStorage.getItem("wms")) {
            wms[i].checked = true;
        }
    }
    localisation.value = localStorage.getItem('localisation');
    desc.value = localStorage.getItem('desc');
}