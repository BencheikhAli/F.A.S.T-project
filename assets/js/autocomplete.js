const header = document.getElementsByClassName('header__search-content')[0];
const autocomplete_items = document.getElementsByClassName('autocomplete-items')[0];
const names = document.getElementById('json-data');
var searchBar = document.getElementById('searchBar');
var btn_search = document.getElementById('btn-search');
var emptyArray = JSON.parse(names.innerHTML);
var newArray = [];

emptyArray.forEach(element => {
    newArray.push(element.identifiant);
});
// si l'uilisateur click sur n'importe quelle touche
searchBar.onkeyup = (e)=>{
    let userData = e.target.value;
    var emptyArray = [];
    if(userData){
        btn_search.style.cursor = "pointer";
        btn_search.disabled = false;
        btn_search.style.backgroundColor = 'DodgerBlue';
        btn_search.addEventListener("mouseenter", ()=>{
            btn_search.style.backgroundColor = "rgb(58, 147, 236)";
        });
        btn_search.addEventListener('mouseout', ()=>{
            btn_search.style.backgroundColor = "DodgerBlue";
        });
        emptyArray = newArray.filter((data)=>{
            return data.toLocaleLowerCase().startsWith(userData.toLocaleLowerCase());
        });
        emptyArray = emptyArray.map((data)=>{
            return data = "<p class='item'>" + data + "</p>";
        });
        autocomplete_items.classList.replace('autocomplete-items', 'autocomplete-items-active');
        showRes(emptyArray);
    }else if(searchBar.value == ""){
        btn_search.style.cursor = "not-allowed";
        btn_search.disabled = true;
        autocomplete_items.classList.replace('autocomplete-items-active', 'autocomplete-items');
    }
}
//fonction pour voir le resultat
function showRes(list){
    let listData;
    if (!list.length) {
        userData = searchBar.value;
    }else{
        listData =  list.join('');
    }
    autocomplete_items.innerHTML = listData;
    var item = document.querySelectorAll('.item');
    item.forEach(item =>{
        item.addEventListener('click', () =>{
            searchBar.value = item.textContent;
            autocomplete_items.innerHTML = "";
        });
    });
}

btn_search.addEventListener('click',()=>{
    const word = searchBar.value;
    window.location.href = "./search-page.php?identifiant="+word;
});
