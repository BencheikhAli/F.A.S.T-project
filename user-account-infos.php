<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
     //Verification que l utilisateur est un carriste
    if(isset($_SESSION['id']) && !empty($_SESSION['id']) && $_SESSION['cariste'] == 'ok'){
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
        <div class="logo-container">
            <a href="index.php"><img src="assets/img/fast.png" alt="logo du site" class="logo"></a>
            <h2 class="title-site">F.A.S.T</h2>
        </div>
        <!-- Include nav  -->
        <?php include_once('./assets/includes/dropdown-user.php') ?>
        <?php include_once('./assets/includes/nav-mobile-user.php');?>
    </header>
    <h1 class="title-page">Mes Informations :</h1>
    <?php
        //Si la variable update et que update est égale à true : 
        if(isset($_GET['update']) && $_GET['update'] == 'true'){
    ?>
        <div class="informations error-false">les modifications ont bien été effectuées.</div>
    <?php
        }
    ?>
    <!-- Tuile mes informations -->
    <div class="informations">
        <form action="./assets/php/update-user.php" method="post">
            <input type="hidden" name="id_user" value="<?php echo $_SESSION['id'] ?>" >
            <input type="text" class="infos" name="nom" id="nom" placeholder="cliquez ici pour entrer votre nom" value="<?php if(isset($_SESSION['nom']) && !empty($_SESSION['nom'])){echo "Nom : ".$_SESSION['nom'];}?>" <?php if(isset($_SESSION['nom']) && !empty($_SESSION['nom'])){echo "readonly";}else{ echo "required";} ?>>
            <input type="text" class="infos" name="prenom" id="prenom" placeholder="cliquez ici pour entrer votre prénom" value="<?php if(isset($_SESSION['prenom']) && !empty($_SESSION['prenom'])){echo "Prenom : ".$_SESSION['prenom'];}?>" <?php if(isset($_SESSION['prenom']) && !empty($_SESSION['prenom'])){echo "readonly";}else{ echo "required";} ?>>
            <input type="text" class="infos" value="Identifiant : <?php echo $_SESSION['identifiant'] ?>" readonly>
            <input type="text" class="infos" value="Site : <?php echo strtoupper($_SESSION['site']) ?>" readonly>
            <input type="submit" class="edit-btn" id="confirmer" value="Valider">
        </form>
    </div>
    <script>

        //Script pour l'apparition du bouton confirmer lorsque les deux input sont remplis :

        var nom = document.getElementById('nom');
        var prenom = document.getElementById('prenom');
        var confirmer = document.getElementById('confirmer');
        confirmer.style.display = "none";
    
        nom.onkeyup = () => {
            if (nom.value == "" || prenom.value == "") {
                confirmer.style.display = "none";
            }else{
                confirmer.style.display = "block";
            }
        }
        prenom.onkeyup = () => {
            if (prenom.value == "" || nom.value == "") {
                confirmer.style.display = "none";
            }else{
                confirmer.style.display = "block";
            }
        }

    </script>
    <!-- Include footer -->
    <?php include_once('./assets/includes/footer.php'); ?>

    <script src="assets/js/edit-user.js"></script>
    <script src="assets/js/dropdown.js"></script>
    <script src="assets/js/nav.js" defer></script>
</body>
</html>
<?php }else{
    //Redirection
    header("Location:connexion.php");
} ?>