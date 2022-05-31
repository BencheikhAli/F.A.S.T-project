//les variables pour le filtrage dans la page de reports (manager)
var filtreBtn = document.getElementById('filtre-btn');
var filtreForm = document.getElementById('filtre-form');
var cancelBtn = document.getElementById('cancel-btn');

filtreBtn.addEventListener('click', ()=> {
    filtreForm.style.display="block";
    filtreBtn.style.display="none"
});

cancelBtn.addEventListener('click', ()=> {
    filtreForm.style.display="none";
    filtreBtn.style.display="block";
});