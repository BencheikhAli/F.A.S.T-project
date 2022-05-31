<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);
    //Verification que l'utilisateur est de type manager
    if(isset($_SESSION['id']) && !empty($_SESSION['id']) && $_SESSION['manager'] == 'ok'){
        //Netoyage des données
        $page = htmlspecialchars($_GET['page']);
        $idProbleme = htmlspecialchars($_GET['id_probleme']);
        $idEquipment = htmlspecialchars($_GET['id_equipement']);
        $idUser = htmlspecialchars($_GET['id_user']);
        //Requete qui sert à prendre un maximum d'infos a partir de la base de données
        $sql = 'SELECT * from user as u 
        INNER JOIN probleme p 
        ON u.id = p.id_user
        INNER JOIN equipement e
        ON e.id = p.id_equipement
        WHERE u.id = ? and p.id = ? and e.id = ?';
    
        $params1 = array($idUser, $idProbleme, $idEquipment);
        //Recup d'informations avec les paramètres passés
        $data = $bdd->getInfo($sql, $params1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js" integrity="sha512-Wt1bJGtlnMtGP0dqNFH1xlkLBNpEodaiQ8ZN5JLA5wpc1sUlk/O5uuOMNgvzddzkpvZ9GLyYNa8w2s7rqiTk5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>FM Info - details</title>
</head>
<body>
    <header>
        <!-- Includes logo et nav -->
        <?php include_once('./assets/includes/logo-container.php');?>
        <?php include_once('./assets/includes/dropdown-manager.php') ?>
        <?php include_once('./assets/includes/nav-mobile-manager.php');?>
    </header>
    <h2>Détails:</h2>
    <?php 
    //Date et heure
    $date = $data[0]["date_signalement"];
    $dateToTime = strtotime($date);
    $rest = substr($data[0]['heure_signalement'], -2); 
    $heureToTime = strtotime($data[0]["heure_signalement"]);
    $heure = $rest === 'pm' ? date('H', strtotime('+0 hour',$heureToTime)) : date('H', $heureToTime);
    ?>
    <section>
        <!-- TUILE DES DETAILS D'UN SIGNALEMENT -->
        <div class="my_reported_problems detail <?php if($data[0]['status'] == 'en cours de traitement'){echo 'status-blue'; }elseif($data[0]['status'] == 'traité'){ echo'status-vert';}elseif($data[0]['status'] == 'annulé'){ echo'status-rouge';}?>">
            <div class="problem_box">
                <input type="text" value="Identifiant : <?php echo $data[0]['identifiant'] ?>" readonly>
                <input type="text" value="Nom : <?php if(!empty($data[0]['nom'])){ echo $data[0]['nom'];}else{echo "?";}?>" readonly>
                <input type="text" value="Prenom : <?php if(!empty($data[0]['prenom'])){ echo $data[0]['prenom'];}else{echo "?";}?>" readonly>
                <input type="text" value="site : <?php echo $data[0]['site'] ?> " readonly>
                <input type="text" value="Type de probleme : <?php echo $data[0]['type_probleme'] ?>" readonly>
                <input type="text" value="Process : <?php echo $data[0]['process'] ?>" readonly>
                <input type="text" value="WMS : <?php echo ucwords($data[0]['wms']) ?>" readonly>
                <input type="text" value="Genre : <?php echo $data[0]['genre'] ?>" readonly>
                <input type="text" value="IP de l'équipement : <?php echo $data[0]['ip_machine'] ?>" readonly>
                <input type="text" value="Status : <?php echo $data[0]['status'] ?>" readonly>
                <input type="text" value="Date/heure : <?php echo "Le ". date('d', $dateToTime) . "-" . date('m', $dateToTime) . "-" .date('Y', $dateToTime) ." à " . $heure ."h". date("i", $heureToTime) ?>" readonly>
                <input type="text" value="Localisation : <?php echo $data[0]['localisation'] ?>" readonly>
                <?php if(!empty($data[0]['description'])){ ?> ​<textarea rows="4" cols="60" readonly> Description : <?php echo $data[0]['description'] ?></textarea><?php } ?>
                <?php 
                //Si une image est associé au probleme
                if(isset($data[0]['image']) && !empty($data[0]['image'])){?>
                    <div class="image-detail-container">
                        <img id="detail_image" class="detail_image" src="assets/img/<?php if(!empty($data[0]['image'])){echo $data[0]['image'];} ?>" alt="l'image de probleme">
                        <div class="btns">
                            <!-- BOUTTONS POUR CONTROLER L'IMAGE DU DETAIL -->
                            <button id="close">╳</button>
                            <button id="zoom">+2</button>
                            <button id="zoomout">-2</button>
                            <button id="left">⟲</button>
                            <button id="right">⟳</button>
                        </div>
                    </div>
                <?php } ?>
                <?php if($data[0]['status'] == 'en cours de traitement'){?>
                    <div class="details-btns">
                        <!-- BOUTTONS STATUS CHANGEMENT -->
                        <button class="btn open-service">Ouvrir un ticket</button>
                        <button class="btn-success" onclick="window.location.href='./assets/php/change-status.php?id_probleme=<?php echo $idProbleme?>&status=traité&page=<?php echo $page ?>'"><a href="./assets/php/change-status.php?id_probleme=<?php echo $idProbleme?>&status=traité&page=<?php echo $page ?>">Mettre en traité</a></button>
                        <button class="btn-danger" onclick="window.location.href='./assets/php/change-status.php?id_probleme=<?php echo $idProbleme?>&status=annulé&page=<?php echo $page ?>'"><a href="./assets/php/change-status.php?id_probleme=<?php echo $idProbleme?>&status=annulé&page=<?php echo $page ?>">Annuler</a></button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <button class="<?php if($data[0]['status'] == 'en cours de traitement'){echo 'btn'; }elseif($data[0]['status'] == 'traité'){ echo'btn-success';}elseif($data[0]['status'] == 'annulé'){ echo'btn-danger';}?>" onclick="goBack()"><i><img src="./assets/img/arrow-left.svg" alt=""></i></button>
    <span class="space"></span>
    <!-- Include footer -->
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
<script src="assets/js/dropdown.js"></script>
<script src="assets/js/open-service.js" defer></script>
<script src="assets/js/nav.js" defer></script>
<script src="assets/js/image-details.js" defer></script>
</html>
<?php }else{
    //Redirection
    header("Location:connexion.php");
} ?>