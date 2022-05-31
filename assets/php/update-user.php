<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('../includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('../includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);
    //Si l'utilisateur est un cariste
    if (isset($_SESSION['cariste']) && $_SESSION['cariste'] == 'ok') {
        if(isset($_POST['nom']) && isset($_POST['prenom'])){
            $id_user = $_POST['id_user'];
            //Netoyage des données
            $nom = htmlspecialchars(strtolower($_POST['nom']));
            $prenom = htmlspecialchars(strtolower($_POST['prenom']));
            
            $params = array($nom, $prenom, $id_user);
            //mettre a jour les information dans la BDD
            $bdd->updateCariste($params);
            //mettre a jour les information dans la page user-account-infos.php
            $sql = 'SELECT * FROM user WHERE id = ?';
            $params_id = array($id_user);
            $data = $bdd->getInfo($sql, $params_id);
            $_SESSION['id'] = $data[0]['id'];
            $_SESSION['nom'] = $data[0]['nom'];
            $_SESSION['prenom'] = $data[0]['prenom'];
            $_SESSION['identifiant'] = $data[0]['identifiant'];
            $_SESSION['role'] = $data[0]['role'];
            $_SESSION['site'] = $data[0]['site'];
            $_SESSION['cariste'] = 'ok';
            //Redirection vers user-account-infos avec un message de confirmation de modification
            header("Location:../../user-account-infos.php?update=true");
        }else{
            //Redirection vers user-account-infos avec message d'erreur
            header("Location:../../user-account-infos.php?update=false");   
        }
    //Si l'utilisateur est un manager
    }elseif (isset($_SESSION['manager']) && $_SESSION['manager'] == 'ok') {
        $id = $_POST['id'];
        //Netoyage des données
        $nom = htmlspecialchars($_POST['name']);
        $prenom = htmlspecialchars($_POST['fname']);
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $pass1 = htmlspecialchars($_POST['password_change_1']);
        $pass2 = htmlspecialchars($_POST['password_change_2']);

        //Si le mot de passe est rentré et que la confirmation correspond au mot de passe :
        if($pass1 === $pass2){
            //On hache le mot de passe rentré avec BCRYPT
            $passCrypte = password_hash($pass1, PASSWORD_BCRYPT);
            //On met a jour les inforamtions dans la base de données
            $params2 = array($nom, $prenom, $pseudo, $passCrypte, $id);
            $bdd->updateUser($params2);
            //mettre a jour les information dans la page manager-account-infos.php
            $sql = 'SELECT * FROM user WHERE id = ?';
            $params3 = array($id);
            $data = $bdd->getInfo($sql, $params3);
            $_SESSION['id'] = $data[0]['id'];
            $_SESSION['nom'] = $data[0]['nom'];
            $_SESSION['prenom'] = $data[0]['prenom'];
            $_SESSION['role'] = $data[0]['role'];
            $_SESSION['email'] = $data[0]['email'];
            $_SESSION['identifiant'] = $data[0]['identifiant'];
            //Redirection vers manager-account-infos avec un message de confirmation de modification
            header("Location:../../manager-account-infos.php?error=false");
        }else{
            //Redirection vers manager-account-infos avec un message d'erreur car les mots de passe ne correspondent pas
            header("Location:../../manager-account-infos.php?match=false");
        }
    }else{
        //Redirection vers l'accueil
        header("Location:../../index.php");
    }
?>