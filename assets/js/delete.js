//les variables
var deleteBtn = document.getElementsByClassName('delete');
var confirmBtn = document.querySelectorAll('delbtn');
var hiddenDiv = document.getElementsByClassName('hidden-div');
var cancel = document.getElementsByClassName("cancel");
var question = document.getElementsByClassName("question");

//la suprission d'un compte et l'affichage des buttons "vous etes sur"
for (let i = 0; i < deleteBtn.length; i++) {
    deleteBtn[i].addEventListener('click', function(event){
        event.preventDefault();
        question[i].style.display="block";
        hiddenDiv[i].style.display="flex";
        deleteBtn[i].style.display="none";
    })
}

for (let i = 0; i < deleteBtn.length; i++) {
    cancel[i].addEventListener('click', function(event){
        event.preventDefault();
        question[i].style.display="none";
        hiddenDiv[i].style.display="none";
        deleteBtn[i].style.display="flex";
    })
};
