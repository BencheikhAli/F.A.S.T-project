<?php 
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('../includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('../includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);
    //Si un email est rentré dans le champ
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        //Netoyage données
        $email = htmlspecialchars($_POST['email']);
        $sqlEmail = "SELECT * FROM user WHERE email = ?";
        $paramsEmail = array($email);
        //Compte le nombre de compte avec cette adresse mail
        $rowsEmail = $bdd->getRows($sqlEmail, $paramsEmail);
        $emailExplode = explode("@", $email);
        $domain = array_pop($emailExplode);
        //Si un compte est trouvé avec ce mail :
        if($rowsEmail === 1){
            //selector et le token
            $selector = bin2hex(random_bytes(8));
            $token = bin2hex(random_bytes(32));
            //url pour le mail
            $url = "/create-new-password.php?selector=" .$selector. "&token=" .$token;
            //on fait un delete si le mail existe deja dans la table pwdreset
            $sql = 'DELETE FROM pwdreset WHERE pwdResetEmail = ?';
            $params = array($email);
            $data = $bdd->deletePwd($sql, $params);

            //enregestrer dans la BDD les info information
            $sql2 = 'INSERT INTO pwdreset (pwdResetEmail ,pwdResetSelector ,pwdResetToken) VALUES (? ,? ,?)';
            $params2 = array($email ,$selector ,$token);
            $bdd->getInfo($sql2, $params2);
            //les donnes de mail
            $subject = "Changement de mot de passe F.A.S.T";
            $message = "<p>Nous avons pris en compte votre souhait de changer votre mot de passe.Vous trouverez ci-dessous le lien pour changer votre mot de passe.Si vous n'avait pas fait cette demande vous pouvez ignorer cet email</p>";
            $message .= "<p>Voici le lien pour changer votre mot de passe : </br>";
            $message .= "<a href = '" .$url. "'> " .$url. " </a></p>";
            $headers =  'MIME-Version: 1.0' . "\r\n"; 
            $headers .= 'From: F.A.S.T <norepy@fast.com>' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
            var_dump(mail( $email, $subject, $message, $headers));
            //redirection vers reset-password-page avec notification d'envoi
            header("Location:../../reset-password-page.php?reset=success");
        }else{
            //Redirection avec message erreur mail 
            header("Location:../../reset-password-page.php?email=none");
        }
    }else {
        //Redirection vers page de connexion
        header("Location:../../connexion.php");
    }