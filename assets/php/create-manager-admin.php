<?php
    session_start();
    include_once('../includes/constants.php');
    include_once('../includes/database.class.php');
    $bdd = new database(HOST, DATA, USER, PASS);
    if (isset($_POST['name']) && isset($_POST['fname']) && isset($_POST['email']) && isset($_POST['role'])) {
        //nettoyer les données avec htmlspecialchars
        $prenom = htmlspecialchars(strtolower($_POST['name']));
        $nom = htmlspecialchars(strtolower($_POST['fname']));
        $role = htmlspecialchars($_POST['role']);
        $email = htmlspecialchars($_POST['email']);
        $idefntifiant = explode("@", $email);
        //verifier si il y'a deja un utilisateur dans la BDD avec cet identifiant
        $pseudo = strtoupper($idefntifiant[0]);
        $sql_pseudo = "SELECT * FROM user WHERE identifiant = ?";
        $params_pseudo = array($pseudo);
        $row_pseudo = $bdd->getRows($sql_pseudo, $params_pseudo);
        //si il y a pas d'utilisateur avec le meme identifiant
        if($row_pseudo === 0){
            $pseudo = strtoupper($idefntifiant[0]);
        //si il y a deja un utilisateur avec le meme identifiant
        //on prends les deux premier carachtere de son prenom et on les 
        //concatene avec le nom
        }else{
            $pseudo = strtoupper(substr($prenom, 0, 2) . $nom);
            $sql_pseudo = "SELECT * FROM user WHERE identifiant = ?";
            $params_pseudo = array($pseudo);
            $row_pseudo = $bdd->getRows($sql_pseudo, $params_pseudo);
            //si il y a deja un utilisateur avec le meme identifiant que nous avons concatiner ,
            //on ajoute la troisieme lettre de prenom 
            if($row_pseudo !== 0){
                $pseudo = strtoupper(substr($prenom, 0, 3) . $nom);
            }
        }
        //on crypte de le mot de passe avec la fonction password_hash et PASSWORD_BCRYPT comme algorithme de hachage
        //le password est pareil que le pseudo
        $pass = ucwords(strtolower($pseudo));
        $passwdCrypte = password_hash($pass, PASSWORD_BCRYPT);
        //verifier que le mail n'est pas utiliser par un autre utilisateur
        $sql = 'SELECT * FROM user where email= ?';
        $params = array($email);
        $rows = $bdd->getRows($sql, $params);
        // si le mail n'est pas utiliser
        if ($rows === 0) {
            $domain = array_pop(explode('@', $email));
            if ($domain === "fmlogistic.com") {
                try {
                    $_SESSION['id_user_add'] = $pseudo; 
                    $_SESSION['pass_user_add'] = $pass;
                    $params2 = array($nom, $prenom, $email, strtoupper($pseudo), $passwdCrypte, $role);
                    $bdd->createManagerAdmin($params2); 
                    header("Location:../../register-page.php?add=true");

                } catch (PDOException $err) {
                    throw new Exception(__CLASS__ . ' : '. $err->getMessage());
                }
            }else{
                header("Location:../../register-page.php?valide=false");
            }
        // si le mail est deja utiliser
        }else {
            header("Location:../../register-page.php?error=true");
        }
    }else{
        header("Location:../../register-page.php");
    }
?>