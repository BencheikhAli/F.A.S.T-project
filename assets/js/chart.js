//les variables
let container = document.getElementsByClassName('container')[0];
let btnGoogleSheets = document.getElementById('google-link-btn');
let linkContainer = document.getElementsByClassName('link-export')[0];
let closebtn = document.getElementsByClassName('closebtn')[0];

btnGoogleSheets.addEventListener('click', ()=>{
    container.style.height = "200px";
    linkContainer.style.display = "block";
});

closebtn.addEventListener('click', ()=>{
    container.style.height = "100px";
    linkContainer.style.display = "none";
});

//fonction pour coper le text
function copy(){
    var clipboard = navigator.clipboard;
    let href = location.href.substr(0, location.href.length - 5) + "?filter=all";
    var text = '=IMPORTHTML("'+ href +'", "table", 4)';
    
    if (clipboard == undefined) {
        console.log(typeof(clipboard))
        confirm('Votre navigateur ne support pas la fonction "clipboard", veuillez telecharger le fichier en format CSV' )
    } else {
        // le text est a changer en fonction de site
        var tooltip = document.getElementById("myTooltip");
        clipboard.writeText(text);
        tooltip.innerHTML = "Le lien a été copié";
    }
}
// text en haut de button
function outFunc() {
    var tooltip = document.getElementById("myTooltip");
    tooltip.innerHTML = "copier le lien ";
}
// les status des problemes ***************************************************************************************************
let ctxStatus = document.getElementById('chartStatus');
let labelsStatus = JSON.parse(document.getElementById('labels-status').innerHTML);
let scoreStatus =  JSON.parse(document.getElementById('score-status').innerHTML)
let colroStatus =[
    '#a05195',
    '#d45087',
    '#f95d6a'
];
let chartStatus = new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        datasets: [{
        data: scoreStatus,
        backgroundColor: colroStatus
        }],
        labels: labelsStatus
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem, data, value){
                        const label = chartStatus.data.labels[tooltipItem.dataIndex];
                        const percentage = chartStatus.data.datasets[0].data[tooltipItem.dataIndex];
                        return " "+label + ": " + percentage + "%";
                    }
                }
            }
        }
    }
});
// les types de  probleme ***************************************************************************************************
let ctxProbleme = document.getElementById('chartProblems');
let labelsProblemes = JSON.parse(document.getElementById('labels-probleme').innerHTML);
let scoreProblemes = JSON.parse(document.getElementById('score-probleme').innerHTML);
var colorEtat = [];
for (let i = 0; i < labelsProblemes.length; i++) {
    let colorCodeEtat = '#';
    colorCodeEtat += Math.random().toString(16).slice(2,8);
    colorEtat.push(colorCodeEtat);
}
let colorHexEtat = colorEtat;
let chartProblems = new Chart(ctxProbleme, {
type: 'pie',
data: {
    datasets: [{
    data: scoreProblemes,
    backgroundColor: colorHexEtat
    }],
    labels: labelsProblemes
},
options: {
    plugins: {
        tooltip: {
            callbacks: {
                label: function(tooltipItem, data, value){
                    const label = chartProblems.data.labels[tooltipItem.dataIndex];
                    const percentage = chartProblems.data.datasets[0].data[tooltipItem.dataIndex];
                    return " "+label + ": " + percentage + "%";
                }
            }
        }
    }
}
});
// les localisation des probleme ***************************************************************************************************
let ctxLocations = document.getElementById('chartLocations');
let test = document.getElementById('chartLocations');
let labelsLocations = JSON.parse(document.getElementById('labels-location').innerHTML);
let scoreLocations = JSON.parse(document.getElementById('score-location').innerHTML);
var colorTable = [];
for (let i = 0; i < labelsLocations.length; i++) {
    let colorCode = '#';
    colorCode += Math.random().toString(16).slice(2,8);
    colorTable.push(colorCode);
}
let colorHex = colorTable;
let chartLocations = new Chart(ctxLocations, {
type: 'bar',
data: {
    datasets: [{
    data: scoreLocations,
    backgroundColor: colorHex
    }],
    labels: labelsLocations
},
options: {
    onClick: graphClick,
    indexAxis: 'y',
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            callbacks: {
                label: function(tooltipItem, data, value){
                    const label = chartLocations.data.labels[tooltipItem.dataIndex];
                    const percentage = chartLocations.data.datasets[0].data[tooltipItem.dataIndex];
                    return " "+label + ": " + percentage + "%";
                }
            }
        }
    }
}
});

// les profondeurs ***************************************************************************************************
if (!document.getElementById("chartProfondeur")) {
    console.log("does not exist");
}else{
    let ctxProfondeur = document.getElementById('chartProfondeur');
    let labelsProfondeur = JSON.parse(document.getElementById('labels-profondeur').innerHTML);
    let ScoreProfondeur = JSON.parse(document.getElementById('score-profondeur').innerHTML);
    var colorProfondeur = [];
    for (let i = 0; i < labelsProfondeur.length; i++) {
        let colorCodeProfondeur = '#';
        colorCodeProfondeur += Math.random().toString(16).slice(2,8);
        colorProfondeur.push(colorCodeProfondeur);
    }
    let colorHexProfondeur = colorProfondeur;
    let chartProfondeur = new Chart(ctxProfondeur, {
    type: 'pie',
    data: {
        datasets: [{
        data: ScoreProfondeur,
        backgroundColor: colorHexProfondeur
        }],
        labels: labelsProfondeur
    },
    options: {
        plugins: {
        tooltip: {
            callbacks: {
                label: function(tooltipItem, data, value){
                    const label = chartProfondeur.data.labels[tooltipItem.dataIndex];
                    const percentage = chartProfondeur.data.datasets[0].data[tooltipItem.dataIndex];
                    return " "+label + ": " + percentage + "%";
                }
            }
        }
    }
    }
    });
}

//fonction quand onclick sur une Allée dans le graph des localisation
function graphClick(event, array){
    if (array[0]) {
        const index = array[0].element.$context.index
        if (labelsLocations[index].substr(0, 5) === "Allée") {
            location.href = "?profondeur="+labelsLocations[index]+"#chartProfondeur";
        }else{
            console.log(labelsLocations[index]);
        }
    }
}