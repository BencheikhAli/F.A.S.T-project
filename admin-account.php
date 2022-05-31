<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
    //Verification que l'utilisateur est un admin
    if(isset($_SESSION['id']) && !empty($_SESSION['id']) && $_SESSION['admin'] == 'ok'){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
    <title>F.A.S.T - Page Admin</title>

</head>
<body>
    <header>
        <!-- Includes logo et nav -->
        <?php include_once('./assets/includes/logo-container.php');?>
        <a href="logout.php" class="logout-admin"><i class="fa fa-sign-out" aria-hidden="true"></i>Deconnexion</a>
        
    </header>

    <h1 class="title-page">Page Administrateur :</h1>

    <main>
        <!-- BOUTTONS ADMIN -->
        <a href="register-page.php"><div class="add">Ajouter un utilisateur</div></a>
        <a href="admin-account-delete.php"><div class="del">Supprimer un utilisateur</div></a>
        <a href="admin-account-users.php"><div class="all">Voir tous les utilisateurs</div></a>
    </main>

    <!-- Includes footer -->
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
</html>
<?php }else{
    //Redirection
    header("Location:connexion.php");
} ?>