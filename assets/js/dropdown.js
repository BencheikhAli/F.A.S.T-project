//les variables
var dropdown = document.getElementById('d');
var options = document.getElementById('o');
var input = document.getElementById('v');

//le changement de la class show pour voir le menu derulant
dropdown.addEventListener('click', ()=> {
   dropdown.classList.toggle('show');
});

options.addEventListener('click', (e)=>{
    const listItem = e.target;
    const value = listItem.attributes.opt.value;
    input.value = value;
});