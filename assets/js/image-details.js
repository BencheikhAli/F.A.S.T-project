//fonction pour revnir en arriere
function goBack() {
    window.history.back();
}
//l'image dans la page details
var detail_image = document.getElementById('detail_image');
//Les autres variables
var btns = document.getElementsByClassName('btns')[0];
var main = document.getElementById('main-page');
var imagediv = document.getElementById('image-div');
var image = document.getElementById('image');
var imgZoom = document.getElementById('imgZoom');
//les button qui s'affiche dans l'image
var closebtn = document.getElementById('close');
var zoombtn = document.getElementById('zoom');
var zoomoutbtn = document.getElementById('zoomout');
var leftbtn = document.getElementById('left');
var rightbtn = document.getElementById('right');
//passer l'image en full screen quand on click
detail_image.addEventListener('click', ()=>{
    if(detail_image.classList.contains("detail_image")){
        detail_image.classList.replace('detail_image', 'full-screen');
        btns.classList.toggle('show-btns');
    }
});

//remettre l'image a la taille normale avec le button close
closebtn.addEventListener('click', ()=> {
    if (detail_image.classList.contains('full-screen')) {
        detail_image.classList.replace('full-screen', 'detail_image');
        location.reload();
    }else if(detail_image.classList.contains("detail_image")){
        detail_image.classList.replace('detail_image', 'full-screen');     
    }
    btns.classList.remove('show-btns');
});
//le button pour le zomm dans l'image
zoombtn.addEventListener('click', ()=> {
    detail_image.style.webkitTransform = "scale(1.8)";
    detail_image.style.transform = "scale(1.8)";
});
//le button pour le dezommer dans l'image
zoomoutbtn.addEventListener('click', ()=> {
    detail_image.style.webkitTransform = "scale(1.8)";
    detail_image.style.transform = "scale(1.08)";
});

//les deux button pour tourner l'image
var angle = 0;
leftbtn.addEventListener('click', ()=> {
    angle -= 90;
    detail_image.style.transform="rotate("+angle+"deg)";
});

rightbtn.addEventListener('click', ()=> {
    angle += 90;
    detail_image.style.transform="rotate("+angle+"deg)";
});
