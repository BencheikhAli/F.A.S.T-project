<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
     //Verification que l'utilisateur est un manager ou un admin
    if(isset($_SESSION['id']) && !empty($_SESSION['id']) && (isset($_SESSION['admin']) || isset($_SESSION['manager']))){
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F.A.S.T - Inscription utilisateur </title>
    <link rel="stylesheet" href="assets/css/register.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- L'utilisateur est un admin -->
    <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 'ok'){?>
    <header>
        <!-- Include nav et logo -->
        <?php include_once('./assets/includes/logo-container.php');?>
        <?php include_once('./assets/includes/dropdown-admin.php') ?>
        <?php include_once('./assets/includes/nav-mobile-admin.php');?>
    </header>
    <?php
    //L'utilisateur est un manager
    }elseif(isset($_SESSION['manager']) && $_SESSION['manager'] == 'ok'){ ?>
    <header>
        <!-- Include nav et logo-->
        <?php include_once('./assets/includes/logo-container.php');?>
        <?php include_once('./assets/includes/dropdown-manager.php') ?>
        <?php include_once('./assets/includes/nav-mobile-manager.php');?>
    </header>
    <?php } ?>

    <h1 class="title-page">Ajouter un collaborateur FM : </h1>

    <?php
        //Verif error est dans l url et egale à true
       if (isset($_GET["error"]) && $_GET['error'] == 'true') {
    ?>
        <div class="add false">Cette adresse e-mail est déjà utilisée par quelqu'un d'autre, creation impossible !</div>
    <?php  
        //Verif add est dans l url et egale à true     
       }elseif (isset($_GET['add']) && $_GET['add'] == 'true') {
    ?>
        <div class="add true">
            <p>l'ajoute de utilisateur a bien été fait !! </p>
            <p>Attention, la premiere lettre de mot de passe est en majuscule !! </p>
            <p>Identifiant : <?php echo $_SESSION['id_user_add'] ?> </p>
            <p>Mot de passe : <?php echo $_SESSION['pass_user_add'] ?></p>
        </div>
    <?php 
        //Verif valide est dans l url et egale à false     
       }elseif (isset($_GET['valide']) && $_GET['valide'] == 'false') {
    ?>
        <div class="add false">Le format de votre mail n'est pas bon, veuillez entrer une adresse mail FMlgoistic !</div>
    <?php
       }
    ?>
    <form method="POST" action="assets/php/create-manager-admin.php">
        <input type="text" class="input-reg" name="name" placeholder="Nom...." pattern="[a-zA-Z]{3, 30}" required>
        <input type="text" class="input-reg" name="fname" placeholder="Prenom...." pattern="[a-zA-Z]{3, 30}" required>
        <input type="email" class="input-reg" name="email" placeholder="Email...." pattern="/([a-zA-Z0-9]+)([\.{1}])?([a-zA-Z0-9]+)\@fmlogistic([\.])com/g" required>
        <?php 
        //L'utilisateur est un admin
        if(isset($_SESSION['admin']) && $_SESSION['admin'] == 'ok'){ ?>
        <select name="role" class="input-reg" required>
            <option value="">Role</option>
            <option value="manager">Manager</option>
            <option value="admin">Administrateur</option>
        </select>
        <?php }else{?>
            <input type="hidden" name="role" value="manager">
        <?php } ?>    
        <input type="submit" id="validate" class="reg-btn" name="validate" value="Valider" required>
    </form>
    <!-- Include Footer -->
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
<script src="assets/js/dropdown.js"></script>
<script src="./assets/js/localStorage-register.js" defer></script>
<script src="assets/js/nav.js" defer></script>
</html>
<?php }else{
    //Redirection
    header("Location:connexion.php");
} ?>