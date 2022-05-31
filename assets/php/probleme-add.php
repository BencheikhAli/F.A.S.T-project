<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('../includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('../includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);

    //On verifie si on a bien renseigné le genre, la location et l'ip machine qui font partie des champs obligatoires en dehors des choix multiples
    if (isset($_POST['genre']) && isset($_POST['location']) && isset($_POST['ip_machine'])) {
        //Si oui on rentre dans la boucle 
        //On verifie ici si l'utilisateur a choisi un choix proposé ou a selectionné la case "autre" et à rentré autre chose. On le verifie pour chacun des choix multiples
        $problemeType = empty($_POST['type_probleme_other']) ? $_POST['type_probleme'] : $_POST['type_probleme_other'];
        $applicationProcess = empty($_POST['application_process_other']) ? $_POST['application_process'] : $_POST['application_process_other'];
        $materiel = empty($_POST['materiel_other']) ? $_POST['materiel'] : $_POST['materiel_other'];
        $wms = empty($_POST['wms_other']) ? $_POST['wms'] : $_POST['wms_other'];


        //On nettoie les données rentrés par l'utilisateur
        $problemeType = htmlspecialchars($problemeType);
        $idUser = $_SESSION['id'];
        $applicationProcess = htmlspecialchars($applicationProcess);
        $materiel = htmlspecialchars($materiel);
        $description = htmlspecialchars($_POST['description']);
        $fileName = null;
        $location = htmlspecialchars($_POST['location']);
        $genre = htmlspecialchars($_POST['genre']);
        $ipMachine = htmlspecialchars($_POST['ip_machine']);

        //le code bare de la localisation
        $total_barcode = "";
        // On verifie si la chaine commence par "QUAI","k" ou "w"
        $barcode_type_quai = strpos($location, "QUAI");
        $barcode_type_oxygen = strpos($location,"k");
        $barcode_type_oxygen2 = strpos($location,"w");

        //Ici on gère la localisation et plus précisement on gère le scannage de la localisation ( on traduit les données récupérés par des localisations)
        //Si les données récupérées commencent par "w"
        if ($barcode_type_oxygen2 === 0) {
            //On prend les données à- partir du deuxieme caractère
            $bc = substr($location,1);
            //code barre traduit en Localisation 
            $total_barcode = $bc;
        //Si les données récupérées commencent par "QUAI"
        }elseif ($barcode_type_quai === 0) {
            //On découpe la chaine récupérée en trois parties comme ceci :
            //1ère partie =  du premier au quatrième caractère -> QUAI
            $bc_1 = substr($location,0,4);
            //2eme partie = Le cinquieme caractère (qui est une lettre)
            $bc_2 = substr($location,4,1);
            //3eme partie = Du sixieme caractère à la fin de la chaine (qui correspond à un nombre)
            $bc_3 = substr($location,5,3);
            //Code barre traduit en localisation
            $total_barcode = $bc_1 ." ".$bc_2 ." ".$bc_3;
        //Si les données récupérées commence par un "k"
        }elseif ($barcode_type_oxygen === 0) {
            //On récupère la chaine à-partir du deuxieme caractere
            $bc = substr($location,1);
            //On découpe alors cette chaine récupérée (6 caractères) en deux parties :
            // 1ere partie = on recupère les trois premiers caractères de la chaine
            $bc_1 = substr($bc,0,3);
            // 2eme partie = on recupère les trois derniers caractères de la chaine
            $bc_2 = substr($bc,3,3);
            //Code barre traduit en localisation
            $total_barcode = "Allée ".$bc_1." profondeur ".$bc_2;
        //Si les données récupérées ne correspondent pas à ces cas mais qu'elle fait 11 caractères
        }elseif(strlen($location) === 11 ) {
            // On récupère les 4 premiers caractères ( nombre )
            $bc_1 = substr($location,0,4);
            // On récupère ensuite le cinquième ( lettre )
            $bc_2 = substr($location,4,1);
            // On recupere enfin les 3 derniers caractères ( nombre ) 
            $bc_3 = substr($location,5,3);
            // Code barre traduit en localisation
            $total_barcode = "Allée ".$bc_1." ".$bc_2." profondeur ".$bc_3;
        //Si le code récupéré ne correspond à aucun de ces cas:
        }else{
            //redirection vers la page d'accueil avec un message d'erreur sur la localisation
            header("Location:../../index.php?location=error");
        }
        
        //Ici on gère la pièce jointe si on en récupère une ( image )
        //Si on en récupère une :
        if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
            //On récupère les données dans $_FILES donc celle de notre pièce jointe
            $tmpname = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $error = $_FILES['file']['error'];
            $type = $_FILES['file']['type'];
            //On gère l'extension du fichier en pièce jointe
            $tabExtension = explode('.',$name);
            $extension = strtolower(end($tabExtension));
            // Tableaux des extensions autorisées
            $extensionAutorisées = ['jpg','jpeg','gif','png','jfif', 'jpe', 'bmp'];
            $tailleMax = 400000;
            //Si l'extension est une extension autorisée et que la taille maximale n'est pas dépassée :
            if (in_array($extension, $extensionAutorisées) && $size <= $tailleMax && $error == 0 ) {
                echo "fefzdfdfgdg";
                //On crée un id unique pour le nom
                $uniqueName = uniqid('', true);
                //On crée un nom pour le fichier avec l'id unique et l'extension du fichier
                $fileName = $uniqueName .'.'.$extension;
                //On deplace la pièce jointe dans le dossier image
                move_uploaded_file($tmpname, '../img/' .$fileName);
                $fileName = $fileName;
                echo $fileName;
            //Si l'extension n'est pas une extension autorisée ou que la taille maximale est dépassée :
            }
            else{
                //redirection vers la page d'accueil avec un message d'erreur
                header("Location:../../index.php?file=error");
            }
        }

        $heure = date('h:i a', time());
        //Definition de la date d'expiration de deux mois (au dela duquel ils seront archivés dans le dossier archivage puis supprimé de la base de données )
        $del_date = date('Y-m-d',strtotime("+ 2 month"));
        //Si il y a une localisation qui est traduite du code barre :
        if ($total_barcode != "") {
            //Si la description ne dépasse pas 255 caractères
            if(strlen($description) <= 255 ){
                try {
                    //Tableaux pour l'ajout d'un problème
                    $paramsEquipment = array($ipMachine, $genre, $materiel);
                    $paramsProbleme = array($problemeType, $idUser, $description, $wms, $heure, $applicationProcess, $total_barcode, $fileName, $del_date);

                    //Appel de la fonction d'ajout de problème de la classe database
                    $bdd->addProbleme($paramsEquipment, $paramsProbleme);
                    if ($_SESSION['role'] == 'manager') {
                        //Redirection vers l'accueil avec message de confirmation d'ajout du probleme
                        header("Location:../../index.php?add=true&m=ok");
                    }elseif ($_SESSION['role'] == 'cariste') {
                        //Redirection vers l'accueil avec message de confirmation d'ajout du probleme
                        header("Location:../../index.php?add=true&c=ok");
                    }
                } catch (Exception $th) {
                    echo $th->getMessage();
                }
            }else{
                //Redirection vers l'accueil avec message erreur description
                header("Location:../../index.php?description=error");
            }
        }else{
            //Redirection vers l'accueil avec message erreur localisation
            header("Location:../../index.php?location=error");
        }
    }else{
        //redirection vers l'accueil avec message erreur
        header("Location:../../index.php?error=true");
    }

?>