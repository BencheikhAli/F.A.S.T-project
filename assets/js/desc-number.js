//Les variables
const inputtext = document.getElementById("desc");
//le nombre max de caractères pour la discreption "A NE PAS CHANGER "
const maxlength = 255;

//fonction pour faire le calcule de nombre de caractères restants
inputtext.onkeyup = () => {
    const counter = inputtext.value.length;
    const characterleft = maxlength -counter;
    document.getElementById("characters").innerHTML = counter;
    if(characterleft<0) {
        inputtext.style.border = "5px solid red";
    } else {
        inputtext.removeAttribute("style");
    }
}