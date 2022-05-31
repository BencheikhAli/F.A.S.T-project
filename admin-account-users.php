<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);
    //Verification que l'utilisateur est un admin
    if(isset($_SESSION['id']) && !empty($_SESSION['id']) && $_SESSION['admin'] == 'ok'){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/alluser.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
    <title>F.A.S.T - Page tous les utilisateurs</title>
</head>
<body>
    <header>
        <!-- Includes logo et nav -->
        <?php include_once('./assets/includes/logo-container.php');?>
        <?php include_once('./assets/includes/dropdown-admin.php');?>
        <?php include_once('./assets/includes/nav-mobile-admin.php');?>
    </header>
    <h1 class="title-page">Tous les utilisateurs FM-INFO :</h1>
    <!-- Includes fonction recherche -->
    <?php include_once('./assets/includes/search.php'); ?>
    <?php include_once('./assets/includes/jsno.names.php'); ?>
    <span class="space"></span>
    <div class="user-container">
        <?php
            $id = array($_SESSION['id']);
            //Fonction de recuperation du user avec l'id
            $data =$bdd->getUsers($id);
        if (count($data) != 0) {
            for ($i=0; $i < count($data); $i++) { 
                //Verif que le nom et le prenom existent
                $prenom = !empty($data[$i]["prenom"]) ? $data[$i]['prenom'] : "?" ;
                $nom = !empty($data[$i]["nom"]) ? $data[$i]['nom'] : "?" ;
                //Affichage de la tuile utilisateur 
                echo '<div class="user">
                        <h3 class="user-item">Identifiant : ' .$data[$i]["identifiant"]. '</h3>
                        <h3 class="user-item">Prenom : ' .$prenom. '</h3>
                        <h3 class="user-item">Nom : ' .$nom. '</h3>
                        <h3 class="user-item">Role : ' .$data[$i]["role"]. '</h3>
                        <h3 class="user-item">Site : ' .$data[$i]["site"]. '</h3>
                      </div>';
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
    <!-- Include footer -->
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
<script>
    // APPEL AJAX POUR LE ROLE
    var filter = document.getElementById("filter");
    filter.addEventListener('change', ()=>{
        xhr = new XMLHttpRequest();
        xhr.open("GET", './assets/php/get-genre.php?filter=' + filter.value + '&page=alluser', true);
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
<script src="assets/js/nav.js" defer></script>
</html>
<?php }else{
    //Redirection
    header("Location:connexion.php");
} ?>