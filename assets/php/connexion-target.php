<?php
    session_start();
    include_once('../includes/constants.php');
    include_once('../includes/database.class.php');
    $bdd = new database(HOST, DATA, USER, PASS);
    if (isset($_POST['pseudo']) && isset($_POST['pass'])) {
        //nettoyer les données avec htmlspecialchars
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $pass = htmlspecialchars($_POST['pass']);
        //chercher le pseudo dans la BDD
        $sql_user = 'SELECT * FROM user WHERE identifiant = ? AND (role = "manager" OR role = "admin")';
        $params = array($pseudo);
        $data = $bdd->getInfo($sql_user, $params);
        $row = $bdd->getRows($sql_user, $params);
        //si le pseudo existe dans la BDD
        if ($row === 1) {
            //verifier le mot de passe de l'utilisateur
            if (password_verify($pass, $data[0]['password'])) {
                $_SESSION['id'] = $data[0]['id'];
                $_SESSION['nom'] = $data[0]['nom'];
                $_SESSION['site'] = $data[0]['site'];
                $_SESSION['role'] = $data[0]['role'];
                $_SESSION['email'] = $data[0]['email'];
                $_SESSION['prenom'] = $data[0]['prenom'];
                $_SESSION['password'] = $data[0]['password'];
                $_SESSION['identifiant'] = $data[0]['identifiant'];
                // si l'utilisateur est un manager
                if($data[0]['role'] == 'manager'){
                    $_SESSION['manager'] = 'ok';
                    header("Location:../../manager-account-reports.php");
                // si l'utilisateur est un admin
                }elseif($data[0]['role'] == 'admin'){
                    $_SESSION['admin'] = 'ok';
                    header("Location:../../admin-account.php");
                }else{
                    header("Location:../../connexion.php?row=0&type=manager");
                }
            //si le mot de passe n'est pas valide
            }else{
                header("Location:../../connexion.php?password=false&type=manager");
            }
        //si le pseudo n'existe pas dans la BDD
        }else{
            header("Location:../../connexion.php?row=0&type=manager");
        }
    }else{
      header("Location:../../connexion.php");
    }
?>