<?php
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('../includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('../includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);
    //Si le nouveau mot de passe est rentré ainsi que le répétition
    if (isset($_POST['pwd']) && !empty($_POST['pwd']) && isset($_POST['pwd-repeat']) && !empty($_POST['pwd-repeat'])) {
        //Netoyage des données
        $password = htmlspecialchars($_POST["pwd"]);
        $passwordRepeat = htmlspecialchars($_POST["pwd-repeat"]);
        $selector = htmlspecialchars($_POST['selector']);
        $token =  htmlspecialchars($_POST['token']);
        //Si les deux mot de passe correspondent
        if($password === $passwordRepeat){
            $sql = "SELECT * FROM pwdreset WHERE pwdResetSelector = ? and pwdResetToken = ?";
            $params = array($selector, $token);
            //Recupere les infos dans pwdreset avec les parametres passées
            $data = $bdd->getInfo($sql, $params);
            //Compte le nombre de ligne présente dans pwdreset avec les parametres passées
            $row = $bdd->getRows($sql, $params);
            //Si le nombre de ligne est egale a 0 :
            if($row === 0){
                //Redirection vers reset-password-page avec message erreur
                header("Location:../../reset-password-page.php?error=true");
            //Si le nombre de ligne est superieur a 0
            }else{
                $tokenEmail = $data[0]['pwdResetEmail'];
                $sql2 = 'SELECT * FROM user WHERE email=?';
                $params2 =array($tokenEmail);
                //Recupere les infos dans user avec les parametres passés
                $data2 = $bdd->getInfo($sql2, $params2);
                //Compte le nombre de ligne présente dans user avec les parametres passés
                $row2 = $bdd->getRows($sql2, $params2);
                //Si on a 0 lignes dans user
                if ($row === 0) {
                    //Redirection avec message erreur
                    header("Location:../../reset-password-page.php?error=true");
                //Si on a plus de 0 lignes
                } else {
                    $sql3 = 'UPDATE user SET password = ? WHERE email = ?';
                    $newPwdHash = password_hash($password, PASSWORD_BCRYPT);
                    $params3 =array($newPwdHash ,$tokenEmail);
                    //On met a jour le mot de passe dans la base de données avec les parametres passés
                    $data3 = $bdd->UpdatePwd($sql3, $params3);

                    $sql4 = "DELETE FROM pwdreset WHERE pwdResetEmail=?";
                    $params4 =array($tokenEmail);
                    //On supprime dans pwdreset la demande de modification de mot de passe apres la modification
                    $data4 = $bdd->deletePwd($sql4, $params4);
                    //Redirection vers la page de connexion avec message de confirmation de la modif du mot de passe
                    header("Location:../../connexion.php?type=manager&newpwd=passwordupdated");
                }
            }
        }else{
            //Redirection et message erreur
            header("Location:../../create-new-password.php?selector=".$selector."&token=".$token."&match=false");
        }
    }else{
        //Redirection
        header("Location:../../connexion.php");
    }
?>