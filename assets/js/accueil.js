//LES VARIABLES
var autre = document.getElementById("autre");
var autreBtn = document.getElementById("autre-btn");
var autre_input = document.getElementById("other");
var cancel =document.getElementById("cancel");
//les buttons autres de type de probleme
var problemeTypeOther = document.getElementById('probleme-type-other');
var problemeType = document.getElementsByName('type_probleme');

//les buttons autres d'application et process
var applicationProcessOther = document.getElementById('application-process-other');
var applicationProcess = document.getElementsByName('application_process');

//les buttons autres de materiel
var materielOther = document.getElementById('materiel-other');
var materiel = document.getElementsByName('materiel');

//les buttons autres de WMS
var wmsOther = document.getElementById('wms-other');
var wms = document.getElementsByName('wms');

var autre2 = document.getElementById("autre2");
var autreBtn2 = document.getElementById("autre-btn2");
var autre_input2 = document.getElementById("other2");
var cancel2 = document.getElementById("cancel2");

var autre3 = document.getElementById("autre3");
var autreBtn3 = document.getElementById("autre-btn3");
var autre_input3 = document.getElementById("other3");
var cancel3 = document.getElementById("cancel3");

var autre4 = document.getElementById("autre4");
var autreBtn4 = document.getElementById("autre-btn4");
var autre_input4 = document.getElementById("other4");
var cancel4 = document.getElementById("cancel4");

//les fonction quand un utilisateur click sur autre dans les choix de l'acceuil
//et quand on annule
autre.addEventListener('click', ()=>{
    autreBtn.style.visibility = "hidden";
    autre_input.style.display="block";
    problemeType.forEach(element => {
        element.disabled = true;
        element.checked = false;
    });
});
cancel.addEventListener('click',(e)=>{
    e.preventDefault();
    autreBtn.style.visibility = "visible";
    autre_input.style.display="none";
    problemeTypeOther.value = "";
    problemeType.forEach(element => {
        element.disabled = false;
    });
})

autre2.addEventListener('click', ()=>{
    autreBtn2.style.visibility = "hidden";
    autre_input2.style.display="block";
    applicationProcess.forEach(element => {
        element.disabled = true;
        element.checked = false;
    });
});
cancel2.addEventListener('click',(e)=>{
    e.preventDefault();
    autreBtn2.style.visibility = "visible";
    autre_input2.style.display="none";
    applicationProcessOther.value = "";
    applicationProcess.forEach(element => {
        element.disabled = false;
    });
})


autre3.addEventListener('click', ()=>{
    autreBtn3.style.display = "none";
    autre_input3.style.display="block";
    materiel.forEach(element => {
        element.disabled = true;
        element.checked = false;
    });
});
cancel3.addEventListener('click',(e)=>{
    e.preventDefault();
    autreBtn3.style.display = "block";
    autre_input3.style.display="none";
    materielOther.value = "";
    materiel.forEach(element => {
        element.disabled = false;
    });
})

autre4.addEventListener('click', ()=>{
    autreBtn4.style.visibility = "hidden";
    autre_input4.style.display="block";
    wms.forEach(element => {
        element.disabled = true;
        element.checked = false;
    });
});
cancel4.addEventListener('click',(e)=>{
    e.preventDefault();
    autreBtn4.style.visibility = "visible";
    autre_input4.style.display="none";
    wmsOther.value = "";
    wms.forEach(element => {
        element.disabled = false;
    });
})
