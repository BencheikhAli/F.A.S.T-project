<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
     $bdd = new database(HOST, DATA, USER, PASS);
    //Si l'utilisateur est de type admin :
    if(isset($_SESSION['id']) && !empty($_SESSION['id']) && $_SESSION['admin'] == 'ok'){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/delpage.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
    <title>F.A.S.T - Supprimer un utilisateur</title>

</head>
<body>
    <header>
        <!-- Includes des nav et du logo -->
        <?php include_once('./assets/includes/logo-container.php');?>
        <?php include_once('./assets/includes/dropdown-admin.php');?>
        <?php include_once('./assets/includes/nav-mobile-admin.php');?>
    </header>
    <h1 class="title-page">Supprimer un utilisateur FM-INFO :</h1>
    <?php
        //Si delete = true dans l url
        if(isset($_GET['delete']) && $_GET['delete'] == "true"){
        //Affichage du message
    ?>
            <div class="add true">
                <p>la suppression du compte a été effectuée </p>
            </div>
            <span class="space"></span>
    <?php
        //Si delete = false dans l url
        }if(isset($_GET['delete']) && $_GET['delete'] == "false"){
        //Affichage message erreur
    ?>
            <div class="add false">
                <p>la suppression du compte a rencontré un problème</p>
            </div>
            <span class="space"></span>
    <?php
        }
    ?>
    <!-- Includes pour la fonction de recherche -->
    <?php include_once('./assets/includes/search.php'); ?>
    <?php include_once('./assets/includes/jsno.names.php'); ?>
    <span class="space"></span>
    <div class="user-container">
        <?php
        $id = array($_SESSION['id']);
        //Fonction recuperation de user avec l'id
        $data =$bdd->getUsers($id);
        if (count($data) != 0) {
            for ($i=0; $i < count($data); $i++) { 
                //Affichage tuile utilisateur avec boutton de suppression
                echo '<form class="user" method="POST" action="./assets/php/delete-user.php">
                        <h3 class="user-item">Identifiant : ' .$data[$i]["identifiant"]. '</h2>
                        <h3 class="user-item">Role : ' .$data[$i]["role"]. '</h2>
                        <h3 class="user-item">Service : ' .$data[$i]["site"]. '</h2>
                        <input type="hidden" name="id-user" value="' .$data[$i]['id']. '">
                        <button class="delete">Supprimer</button>
                        <p class="question">Etes vous sur ?</p>
                        <div class="hidden-div">
                            <button class="cancel" >non</button>
                            <input type="submit" id="delbtn" class="delbtn" value="oui">
                        </div>
                    </form>';
            }
        }else{
        ?>
            <h1 class="title-page">Pas d'utilisateurs dans la base de données :</h1>
            <style>
                footer {
                    position: fixed;
                    display: flex;
                    justify-content: space-between;
                    background-color: #040176;
                    left: 0;
                    bottom: 0;
                    width: 100%;
                    padding: 20px;
                }
            </style>
        <?php   
        }
        ?>
    </div>
    <!-- Include du footer -->
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
<script>
    // APPEL AJAX FILTRAGE PAR ROLE
    var filter = document.getElementById("filter");
    filter.addEventListener('change', ()=>{
        xhr = new XMLHttpRequest();
        xhr.open("GET", './assets/php/get-genre.php?filter=' + filter.value + '&page=delpage', true);
        xhr.send();
        xhr.onreadystatechange = function(){
            if(xhr.status == 200 || xhr.readyState == 4){
                document.getElementsByClassName("user-container")[0].innerHTML = "";
                document.getElementsByClassName("user-container")[0].innerHTML = xhr.responseText;
            }
        }
    });
</script>
<script src="assets/js/dropdown.js"></script>
<script src="./assets/js/autocomplete.js" defer></script>
<script src="assets/js/delete.js"></script>
<script src="assets/js/nav.js" defer></script>
</html>
<?php }else{
    //Redirection 
    header("Location:connexion.php");
} ?>