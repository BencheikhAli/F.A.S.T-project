<?php
    session_start();
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);
    //Verification que l'utilisateur est un carriste
    if(isset($_SESSION['id']) && !empty($_SESSION['id']) && $_SESSION['cariste'] == 'ok'){
        //Requete pour afficher les problemes qui n'ont pas expirés
        $sql = 'SELECT * FROM probleme WHERE id_user = ? and (? < expiration_date or expiration_date is NULL) ORDER BY id DESC';
        $dateNowSql = date('Y-m-d');
        $params = array($_SESSION['id'], $dateNowSql);
        //Recuperation d'infos avec les parametres passés
        $data = $bdd->getInfo($sql, $params);
        //Compte les lignes avec les parametres passés
        $row = $bdd->getRows($sql, $params);
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/compte-utilisateur.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
    <title>F.A.S.T - Mes Signalements </title>
</head>

<body>
    <header>
        <div class="logo-container">
            <a href="index.php"><img src="assets/img/fast.png" alt="logo du site" class="logo"></a>
            <h2 class="title-site">F.A.S.T</h2>
        </div>
        <!-- Include nav et logo -->
        <?php include_once('./assets/includes/dropdown-user.php') ?>
        <?php include_once('./assets/includes/nav-mobile-user.php');?>
    </header>
    <main id="page-content">
    <?php
    //Si aucune ligne n'est trouvée
    if ($row == 0) {
    ?>
        <span class="big_space"></span>
        <h1 class="my-reports2">Vous n'avez pas de signalement</h1>
        <style>
        footer {
            position: fixed;
            display: flex;
            justify-content: space-between;
            background-color: #040176;
            left: 0;
            bottom: 0;
            width: 100%;
            padding: 20px;
        }
    </style>
    <?php
    //Si au moins une ligne est trouvée 
    }elseif($row > 0){
        //Requete pour récuperer le nombre de signalment effectués aujourd'hui
        $sql_today = 'SELECT * FROM probleme WHERE id_user = ? and date_signalement = ? ';
        $params_today = array($_SESSION['id'], $dateNowSql);
        $row_today = $bdd->getRows($sql_today, $params_today);
    ?>
    <h1 class="h1-myreports">Mes Signalements :</h1>
    <h2 class="my-reports h2-myreports"><?php echo $row_today ?> Signalement(s) effectué(s) aujourd'hui</h2>
    <?php
    //Creation de tuiles dynamique (nombre en fonction du nombre de ligne trouvés précedemment )
    foreach ($data as $key => $value) {
        $date = $data[$key]["date_signalement"];
        $dateToTime = strtotime($date);
        //transformer la date et ajouter 12H si il est en pm
        $rest = substr($data[$key]['heure_signalement'], -2); 
        $heureToTime = strtotime($data[$key]["heure_signalement"]);
        $heure = $rest === 'pm' ? date('H', strtotime('+0 hour',$heureToTime)) : date('H', $heureToTime);
        //nombre de jours restant avnt l'expiration
        $dateNowToTime = strtotime($dateNowSql);
        $dateExpiration = strtotime($data[$key]['expiration_date']);
        $nbJoursTimestmp = $dateExpiration - $dateNowToTime;
        $nbJours = $nbJoursTimestmp / 86400;
?>
    <form class="report <?php if($data[$key]['status'] == 'en cours de traitement'){echo 'status-blue'; }elseif($data[$key]['status'] == 'traité'){ echo'status-vert';}elseif($data[$key]['status'] == 'annulé'){ echo'status-rouge';}?>" action="assets/php/ask-ticket.php" method="post">
        <div class="pbdate">
            <input type="text" name="type_probleme" value="<?php echo $data[$key]['type_probleme']; ?>" class="pb" id="pb" readonly>
            <input type="text" name="date_signalement" value="<?php echo "Le ". date('d', $dateToTime) . "-" . date('m', $dateToTime) . "-" .date('Y', $dateToTime) ." à" ?> <?php echo $heure ."h". date("i", $heureToTime) ?>" class="date" id="date" readonly>
        </div>
        <div class="locstatus">
            <input type="text" name="status" value="<?php echo $data[$key]['status'] ?>" class="status" id="status" readonly>
            <input type="text" name="localisation" value="<?php echo $data[$key]['localisation'] ?>" class="localisation" id="s" readonly>
        </div>
        <input type="hidden" name="id_user" value="<?php echo $data[$key]['id_user']; ?>" class="pb" id="user" readonly>
        <input type="hidden" name="id_probleme" value="<?php echo $data[$key]['id']; ?>" class="pb" id="idpb" readonly>
        <?php if($data[$key]['status'] == "annulé" || $data[$key]['status'] == "traité" ){ echo "<p class='exp-date'>Temps restant avant l'expiration : $nbJours jours </p>"; } ?>
    </form>
    <?php
    }
        //Si au moins 4 lignes sont trouvées
        if($row > 3){
    ?>
            <!-- BOUTTONS POUR VOIR PLUS OU MOINS DE SIGNALEMENTS -->
            <div class="btn-container">
                <button id="all-btn" class="btn btn_more"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                <button id="less-btn" class="btn btn_more"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
            </div>
    <?php
        }
    }
    ?>
    </main>
    <!-- Includes footer -->
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
    <script src="assets/js/dropdown.js" defer></script>
    <script src="assets/js/nav.js" defer></script>
    <script src="./assets/js/see-more-reports.js"></script>
</html>
<?php }else{
    //Redirection
    header("Location:connexion.php");
} ?>
