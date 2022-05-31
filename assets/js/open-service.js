//ouvrir service now (application d'ouverture des tickets)
var btnService = document.getElementsByClassName('open-service');
for (var i = 0; i < btnService.length; i++) {
    (function(index) {
         btnService[index].addEventListener("click", function() {
            window.open('https://fmlogistic.service-now.com/', '_blank');
          })
    })(i);
 }