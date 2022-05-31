<?php
    session_start();
    include_once('../includes/constants.php');
    include_once('../includes/database.class.php');
    $bdd = new database(HOST, DATA, USER, PASS);
    if (isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
        //nettoyer les données avec htmlspecialchars
        $characters = " \n\r\t\v\0";
        $pseudo = htmlspecialchars(str_replace('http://','',str_replace(' ','',trim( $_POST['pseudo'],$characters))));
        //chercher le pseudo dans la BDD
        $sql_user = 'SELECT * FROM user WHERE identifiant = ?';
        $pseudo = strtoupper($pseudo);
        $params = array($pseudo);
        $data = $bdd->getInfo($sql_user, $params);
        $row = $bdd->getRows($sql_user, $params);
        //si le pseudo n'existe pas dans la BDD on fait une creation de compte
        if ($row === 0) {
            try {
                //creation de compte cariste dans la BDD
                $params = array($pseudo);
                $bdd->createCariste($params);
                //recuperer le derniere id entrer dans la BDD
                $sql_user = 'SELECT * FROM user WHERE id = ? AND role = "cariste"';
                $sql_id = "SELECT LAST_INSERT_ID()";
                $last_id = $bdd->getInfo($sql_id);
                $params_user = array($last_id[0]['LAST_INSERT_ID()']);
                $data_user = $bdd->getInfo($sql_user, $params_user);
                //les session avec les information de cariste
                $_SESSION['id'] = $data_user[0]['id'];
                $_SESSION['identifiant'] = $data_user[0]['identifiant'];
                $_SESSION['nom'] = $data_user[0]['nom'];
                $_SESSION['prenom'] = $data_user[0]['prenom'];
                $_SESSION['role'] = $data_user[0]['role'];
                $_SESSION['site'] = $data_user[0]['site'];
                $_SESSION['cariste'] = 'ok';
                header("Location:../../index.php");
            } catch (PDOException $err) {
                throw new Exception(__CLASS__ . ' : '. $err->getMessage());
            }
        //si le pseudo existe dans la BDD on fait une connexion
        }elseif ($row === 1) {
            $sql_cariste = 'SELECT * FROM user WHERE identifiant = ? and role = "cariste"';
            $data_cariste = $bdd->getInfo($sql_cariste, $params);
            $row_cariste = $bdd->getRows($sql_cariste, $params);
            //on verifier que le pseudo qu'on a recuperée n'appartient pas a un admin ou un manager
            if ($row_cariste === 1) {
                $_SESSION['id'] = $data[0]['id'];
                $_SESSION['identifiant'] = $data[0]['identifiant'];
                $_SESSION['nom'] = $data[0]['nom'];
                $_SESSION['prenom'] = $data[0]['prenom'];
                $_SESSION['role'] = $data[0]['role'];
                $_SESSION['site'] = $data[0]['site'];
                $_SESSION['cariste'] = 'ok';
                header("Location:../../index.php");
            //si le pseudo appartient a un admin ou un manager, Redirection vers la page de connexion
            //avec un message d'erreur.
            }else{
                header("Location:../../connexion.php?identifiant=false&type=cariste");
            }
        }else{
            header("Location:../../connexion.php?error=true&type=cariste");
        }
    }else{
        header("Location:../../connexion.php?error=true&type=cariste");
    }
?>