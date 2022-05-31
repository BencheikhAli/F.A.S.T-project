//les vaiable
var signalements = document.getElementsByClassName("report");
var voirTout = document.getElementById("all-btn");
var voirMoins = document.getElementById("less-btn");
var count = 3;

voirMoins.style.display = "none";
voirMoins.addEventListener("click", showLess);
voirTout.addEventListener("click", showAll);

//limit des signalement dans la page de user-account-myreports.php
var limit = 3;
if (signalements.length > limit) {
    voirTout.style.display = " block";
    for (let i = 3; i < signalements.length; i++) {
        signalements[i].style.display = "none";
    }
}

//fonction pour voir tous les signalement
function showAll() {
    for (let i = 0; i < signalements.length; i++) {
        signalements[i].style.display = "block";
        voirTout.style.display = "none";
        voirMoins.style.display = " block";
        count = signalements.length;
    }
}
//fonction pour voir moins de signalement
function showLess() {
    for (let i = 3; i < signalements.length; i++) {
        signalements[i].style.display = "none";
    }
    count = 3;
    voirMoins.style.display="none";
    voirTout.style.display="block";
}