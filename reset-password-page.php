<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F.A.S.T - connexion</title>
    <link rel="stylesheet" href="assets/css/connexion.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <img src="assets/img/fast.png" alt="logo du site" class="logo">
        <h2 class="title-site">F.A.S.T</h2>
    </header>
    <h1 class="title-page">Changer votre mot de passe :</h1>
    <?php 
        //Si reset est dans l'url et est egale à success
        if (isset($_GET['reset']) && $_GET['reset'] == "success") {
    ?>
        <div class="form-container logout">Un e-mail vous a été envoyé. Suivez les instructions du message pour réinitialiser votre mot de passe!!</div>
    <?php 
        //Si error est dans l url et est egale à true       
        }elseif (isset($_GET['error']) && $_GET['error'] == "true") {
    ?>
        <div class="form-container error"> Une erreur est survenue lors de la transmission des donnes, Veuillez réessayer !!</div>
        <span class="space"></span> 
    <?php 
    //Si email est dans l'url et est egale a none
    }elseif (isset($_GET['email']) && $_GET['email'] == "none") { ?>
        <div class="form-container error"> L’adresse e-mail que vous avez saisie n’est associée à aucun compte FM-INFOS</div>
        <span class="space"></span> 
    <?php } ?>

    <span class="space"></span>
    <div class="form-container">
        <form class="log-form" action="./assets/php/reset-request-email.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <span class="space"></span>
            <input class="submit-email" type="submit" value="Envoyer le mail">
        </form>
    </div>
    <button class="btn btn-manager" onclick="window.location.href='connexion.php?type=manager'"><a href="connexion.php?type=manager">retour</a></button>
    <span class="space"></span>
    <?php
    // Include footer
    include_once('./assets/includes/footer.php');
    ?>
</body>
</html>