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
        if (isset($_GET['identifiant'])) {
            //Netoyage de données
            $identifiant = htmlspecialchars($_GET['identifiant']);

            $sql = 'SELECT * FROM user WHERE identifiant=?';
            $params = array($identifiant);
            //Recuperation infos avec parametres passés
            $data = $bdd->getInfo($sql, $params);
            //Compte les lignes avec les parametres passés
            $row = $bdd->getRows($sql, $params);
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/search.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
    <title>F.A.S.T - Recherche collaborateurs</title>
</head>
<body>
<header>
    <!-- Include nav et logo -->
        <?php include_once('./assets/includes/logo-container.php');?>
        <?php include_once('./assets/includes/dropdown-admin.php') ?>
        <?php include_once('./assets/includes/nav-mobile-admin.php');?>
</header>
    <?php
    //Si row =0 et que l'identifiant  n'est pas definie dans l url
    if ((isset($row) && $row === 0) or !isset($identifiant)) {
    ?>
    <?php 
    //Si l'identifiant est definie dans l'url
    if(isset($_GET['identifiant'])){ ?>
        <h1 class="title-page">Aucun resultat trouver pour <?php echo $identifiant; ?></h1>
    <?php } ?>    
        <span class="big-space"></span>
        <div class="div-error">
            <img class="img-error" src="./assets/img/error.png" alt="error">
        </div>
        <span class="big-space"></span>
        <button class="btn danger" onclick="window.history.back();">retour a la page precedente</button>
    <?php
    //Au moins 1 ligne trouvée
    }elseif ($row > 0) {
    ?>
    <h1 class="title-page">Le resultat trouver pour <?php echo $identifiant; ?></h1>
    <div class="user-container">  
        <form class="user" method="post" action="./assets/php/delete-user.php">
                <h3 class="user-item">Identifiant : <?php echo $data[0]['identifiant'] ?></h3>
                <h3 class="user-item">Prenom : <?php if(isset($data[0]['prenom']) && !empty($data[0]['prenom'])){echo $data[0]['prenom'];}else{ echo "?";}?> </h3>
                <h3 class="user-item">Nom : <?php if(isset($data[0]['nom']) && !empty($data[0]['nom'])){echo $data[0]['nom'];}else{ echo "?";}?></h3>
                <h3 class="user-item">Role : <?php echo $data[0]["role"] ?></h3>
                <h3 class="user-item">Site : <?php echo $data[0]["site"] ?></h3>
                <input type="hidden" name="id-user" value="<?php echo $data[0]['id'] ?>">
                <button class="delete">Supprimer</button>
                <p class="question">Etes vous sur ?</p>
                <div class="hidden-div">
                    <button class="cancel" >non</button>
                    <input type="submit" id="delbtn" class="delbtn" value="oui">
                </div>
        </form>
    </div>
    <button class="btn" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
    <script>
        function goBack() {
        window.history.back();
        }
    </script>
<?php
    } 
    // Include Footer
?>
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
<script src="assets/js/dropdown.js"></script>
<script src="assets/js/delete.js"></script>
<script src="assets/js/nav.js" defer></script>
</html>
<?php }else{
    //Redirection
    header("Location:connexion.php");
} ?>