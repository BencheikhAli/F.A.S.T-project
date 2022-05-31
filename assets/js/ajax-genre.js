//fonction AJAX pour avoir le genre du materiel aparir de l'adresse IP
function getGenreWithIp(){
    var ip = document.getElementById("ip_machine");
    var materiels = document.getElementsByName('materiel');
    xhr = new XMLHttpRequest();
    xhr.open("GET", './assets/php/get-genre.php?ip_machine=' + ip.value, true);
    xhr.send();
    xhr.onreadystatechange = function(){
        if(xhr.status == 200 || xhr.readyState == 4){
            var resGenre = xhr.responseText;
            document.getElementById("genre").value = resGenre.substring(0, 2);
            console.log(resGenre);
            for (var i = 0; i < materiels.length-1; i++) {
                if (resGenre.substring(2, resGenre.length) == materiels[i].value) {
                    materiels[i].checked = true;
                }
            }
        }
    }
};
