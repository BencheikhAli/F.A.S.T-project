<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
    //Verification que l'utilisateur est un manager
    if(isset($_SESSION['id']) && !empty($_SESSION['id']) && $_SESSION['manager'] == 'ok'){
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/compte-utilisateur.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
    <title>F.A.S.T - Mes infos </title>
</head>

<body>
    <header>
        <!-- Include logo et nav -->
        <?php include_once('./assets/includes/logo-container.php');?>
        <?php include_once('./assets/includes/dropdown-manager.php');?>
        <?php include_once('./assets/includes/nav-mobile-manager.php');?>
    </header>
    <h1 class="title-page">Mes Informations :</h1>
    <?php
        //Si la variable error est dans l url et est egale a true
        if(isset($_GET['error']) && $_GET['error'] == 'true'){
    ?>
        <div class="informations error-true">une erreur s'est produite lors de l'envoi des donnees.</div>
        <span class="space"></span>
    <?php
        //Si la varible error est dans l'url et est égale a false
        }elseif(isset($_GET['error']) && $_GET['error'] == 'false'){
    ?>
        <div class="informations error-false">les modifications ont bien été effectuées.</div>
        <span class="space"></span>
    <?php
        //Si la variable match est dans l'url :
        }elseif (isset($_GET['match']) && $_GET['match']) {
    ?>
        <div class="informations error-true">
            <p>Les mots de passe ne sont pas identiques</p>
        </div>
    <?php        
        }
    ?>
    <!-- TUILE DES INFORMATIONS DU MANAGER -->
    <form class="informations" action="./assets/php/update-user.php" method="post">
        <input type="hidden" name="id" class="input-edit" value="<?php echo $_SESSION["id"]; ?>">
        <div class="infos">
            <label for="name">Nom:</label>
            <input type="text" name="name" class="input-edit" value="<?php echo ucwords($_SESSION["nom"]); ?>" required readonly>
        </div>
        <div class="infos">
            <label for="fname">Prenom:</label>
            <input type="text" name="fname" class="input-edit" value="<?php echo ucwords($_SESSION["prenom"]); ?>" required readonly>
        </div>
        <div class="infos">
            <label for="pseudo">Identifiant:</label>
            <input type="text" name="pseudo" class="input-edit" value="<?php echo ucwords($_SESSION["identifiant"]); ?>" required readonly>
        </div>
        <div class="infos">
            <label for="email">Email:</label>
            <input type="text" name="email" class="input-non-edit" value="<?php echo $_SESSION["email"]; ?>" required readonly>
        </div>
        <div class="infos">
            <label for="site">Site:</label>
            <input type="text" name="site" class="input-non-edit" value="<?php echo $_SESSION["site"]; ?>" required readonly>
        </div>
        <div class="info passwd-manager">
            <!-- Form pour changer le mot de passe -->
            <input type="password" class="infos" name="password_change_1" id="password_change_1" placeholder="Mot de passe" required>
            <input type="password" class="infos" name="password_change_2" id="password_change_2" placeholder="repeter le mot de passe" required>
        </div>
        <button id="cancel_change" class="edit-btn">Annuler</button>
        <span class="space"></span>
        <input type="submit" id="confirm-btn" class="edit-btn" value="Confirmer">
        <span class="space"></span>
        <button id="edit-btn" class="edit-btn">Modifier</button>
    </form>
    <!-- Include footer -->
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
<script src="assets/js/dropdown.js"></script>
<script src="assets/js/edit.js"></script>
<script src="assets/js/nav.js" defer></script>
</html>
<?php }else{
    //Redirection
    header("Location:connexion.php");
} ?>