<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
      //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
      $bdd = new database(HOST, DATA, USER, PASS);
    //Si le token et le selector sont dans l'url
    if (isset($_GET['token']) && isset($_GET['selector'])) {
        //Netoyage des données
        $selector = htmlspecialchars($_GET['selector']);
        $token =  htmlspecialchars($_GET['token']);
        //verifier si le selector et le token sont bien dans la BDD
        $sql = "SELECT * FROM pwdreset WHERE pwdResetSelector = ? and pwdResetToken = ?";
        $params = array($selector, $token);
        //Recup d'infos avec les parametres passés
        $data = $bdd->getInfo($sql, $params);
        //Compte les lignes dans pwdreset avec les parametres passés
        $row = $bdd->getRows($sql, $params);
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F.A.S.T - connexion</title>
    <link rel="stylesheet" href="assets/css/connexion.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <img src="assets/img/fast.png" alt="logo du site" class="logo">
        <h2 class="title-site">F.A.S.T</h2>
    </header>
    <?php
        //Si il n'y a aucune ligne dans pwdreset
        if ($row === 0) {
            //Redirection et message erreur
            header("Location:reset-password-page.php?error=true");
        //Si 1 ligne est trouvée
        } elseif($row === 1) {
            //Verification que les deux mot de passe soient identiques
            //Si non :
            if (isset($_GET['match']) && $_GET['match'] == "false") {
            ?>
                <div class="form-container error">Les mots de passe ne sont pas identiques</div>
                <span class="space"></span> 
            <?php } ?>
                <div class="form-container">
                    <form class="log-form" action="./assets/php/reset-password.php" method="post">
                        <input type="hidden" name="selector" value="<?php echo $selector; ?>">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <input type="password" name="pwd" placeholder = "Entrez votre nouveau mot de passe..." required >
                        <input type="password" name="pwd-repeat" placeholder = "Entrez à nouveau votre nouveau mot de passe..." required>
                        <button class="submit-new-password" type="submit">Changer votre mot de passe</button>
                    </form>
                </div>
               <?php
        }
        // Include Footer
        include_once('./assets/includes/footer.php');
    ?>
</body>
<script src="assets/js/nav.js" defer></script>
</html>
    
<?php }else{
    //Redirection 
    header("Location:reset-password-page.php");
} ?>