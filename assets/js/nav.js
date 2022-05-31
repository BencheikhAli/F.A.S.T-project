//le menu qui s'affiche quand on'est sur mobile
function toggleMenu(){
    const navbar = document.querySelector('.navbar');
    const burger = document.querySelector('.burger-container');
    burger.addEventListener('click', () => {
        navbar.classList.toggle('show-nav');
    })
}
toggleMenu();