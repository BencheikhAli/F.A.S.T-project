<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('../includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('../includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);
    //Si on recupere avec post un id de user :
    if (isset($_POST['id-user'])) {
        try {
            //Netoyage données
            $id = htmlspecialchars($_POST['id-user']);

            $params = array($id);
            //Supression dans la table user a partir des parametres passés
            $del = $bdd->deleteUser($params);
            //Redirection  avec confirmation de suppression
            header("Location:../../admin-account-delete.php?delete=true");
        }  catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    //Si on ne récupere pas d'id user :
    }
    else {
        //Redirection avec message erreur
        header("Location:../../admin-account-delete.php?error=true");
    }
?>