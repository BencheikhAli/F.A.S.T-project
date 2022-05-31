<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('../includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('../includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);
//Si on a l'adresse IP générée dans le champ associé
if(isset($_GET['ip_machine']) && !empty($_GET['ip_machine'])){
    //Nettoie les données
    $ip = htmlspecialchars($_GET['ip_machine']);
    $res = array();
    try{
        $sql = 'SELECT DISTINCT ip_machine, genre, chariot FROM equipement where ip_machine = ? ORDER BY id DESC';
        $params = array($ip);
        //Recuperation d'infos dans equipements avec les paramètres passés
        $data = $bdd->getInfo($sql, $params);
        //Compte le nombre de ligne dans equipement avec les paramètres passés
        $row = $bdd->getRows($sql, $params);
        //Si on a 1 ligne ou plus :
        if($row > 0){
            //On implémente le tableau $res
            array_push($res, $data[0]['genre'], $data[0]['chariot']);
            echo $res[0].$res[1];
        }
    }catch(PDOException $err){
        echo $err->getMessage();
    }

//POUR LA PAGE ADMIN
//Si on a un filtre
}elseif (isset($_GET['filter']) && !empty($_GET['filter'])) {
    //Netoyage des données
    $filter = htmlspecialchars($_GET['filter']);
    $page = htmlspecialchars($_GET['page']);
    try{
        //Si le filtre n'est pas "all"
        if($filter != "all"){
            $sql = 'SELECT * FROM user where role = ? and id != ?';
            $params = array($filter, $_SESSION['id']);
        //Si le filtre est "all"
        }else{
            $sql = 'SELECT * FROM user WHERE id != ? ORDER BY id DESC';
            $params = array($_SESSION['id']);
        }
        //On recupere les infos dans user avec les parametres passés
        $data = $bdd->getInfo($sql, $params);
        //On compte le nombre de ligne dans user avec les parametres passés
        $row = $bdd->getRows($sql, $params);
        //Si on a 1 ou plusieurs lignes dans user
        if($row > 0){
            //Si on est sur la page "voir tous les collaborateurs" :
            if (isset($page) && $page == "alluser") {
                for ($i=0; $i < $row; $i++) {
                    //verif si le prenom et le nom existent
                    $prenom = !empty($data[$i]["prenom"]) ? $data[$i]['prenom'] : "?" ;
                    $nom = !empty($data[$i]["nom"]) ? $data[$i]['nom'] : "?" ;
                    //Affichage d'une tuile utilisateur
                    echo '<div class="user">
                            <h3 class="user-item">Identifiant : ' .$data[$i]["identifiant"]. '</h3>
                            <h3 class="user-item">Prenom : ' .$prenom. '</h3>
                            <h3 class="user-item">Nom : ' .$nom. '</h3>
                            <h3 class="user-item">Role : ' .$data[$i]["role"]. '</h3>
                            <h3 class="user-item">Site : ' .$data[$i]["site"]. '</h3>
                        </div>';
                }
            //Si on est sur la page "supprimer un collaborateur" :
            }elseif (isset($page) && $page == 'delpage') {
                for ($i=0; $i < $row; $i++) { 
                    //Affichage d'une tuile utilisateur avec bouton de suppression
                    echo '<form class="user" method="post" action="./assets/php/delete-user.php">
                            <h3 class="user-item">Identifiant : ' .$data[$i]["identifiant"]. '</h2>
                            <h3 class="user-item">Role : ' .$data[$i]["role"]. '</h2>
                            <h3 class="user-item">Service : ' .$data[$i]["site"]. '</h2>
                            <input type="hidden" name="id-user" value="' .$data[$i]['id']. '">
                            <button class="delete">Supprimer</button>
                            <p class="question">Etes vous sur ?</p>
                            <div class="hidden-div">
                                <button class="cancel" >non</button>
                                <input type="submit" id="delbtn" class="delbtn" value="oui">
                            </div>
                          </form>';
                }
            }
        //Si le nombre de ligne est egale a 0=
        }else{
            echo "
            <h1 class='title-page'>Pas d'utilisateurs dans la base de données :</h1>
            <style>footer{
                position: fixed;
                display: flex;
                justify-content: space-between;
                background-color: #040176;
                left: 0;
                bottom: 0;
                width: 100%;
                padding: 20px;
            }</style>";
        }
    }catch(PDOException $err){
        echo $err->getMessage();
    }
}