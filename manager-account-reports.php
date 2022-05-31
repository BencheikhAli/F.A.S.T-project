<?php
    session_start(); 
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);
    //Verification que l'utilisateur est un manager
    if(isset($_SESSION['id']) && !empty($_SESSION['id']) && $_SESSION['manager'] == 'ok'){
        $dateNowSql = date('Y-m-d');
        //Verif filter est egal à all dans l url
        if (isset($_GET['filter']) && $_GET['filter'] == 'all') {
            $sql = 'SELECT DISTINCT * from probleme WHERE (? < expiration_date or expiration_date IS NULL) ORDER BY id DESC';
            $params = array($dateNowSql);
        //Verif filter est egal à notall dans l'url
        }elseif (isset($_GET['filter']) && $_GET['filter'] == 'notall') {
            $sql = 'SELECT DISTINCT * from probleme WHERE (? < expiration_date or expiration_date IS NULL) ORDER BY id DESC LIMIT 6';
            $params = array($dateNowSql);
        //Verif date debut et fin est definie
        }elseif (isset($_GET['date-debut']) && !empty($_GET['date-debut']) && isset($_GET['date-fin']) && !empty($_GET['date-fin']) && isset($_GET['filtrer']) && $_GET['filtrer'] == 'filtrer') {
            $sql = 'SELECT DISTINCT * from probleme WHERE (? < expiration_date or expiration_date IS NULL) and date_signalement BETWEEN ? and ? ORDER BY id DESC';
            $date_debut = $_GET['date-debut'];
            $date_fin = $_GET['date-fin'];
            $params = array($dateNowSql,$date_debut,$date_fin);
        //Verif id-manager est dans l'url
        }elseif (isset($_GET['id-manager']) && !empty($_GET['id-manager'])) {
            $sql = 'SELECT DISTINCT * from probleme WHERE id_user = ? and (? < expiration_date or expiration_date IS NULL) ORDER BY id DESC';
            $id_user = $_GET['id-manager'];
            $params = array($id_user, $dateNowSql);
        }else{
            $sql = 'SELECT DISTINCT * from probleme WHERE (? < expiration_date or expiration_date IS NULL) ORDER BY id DESC LIMIT 6';
            $params = array($dateNowSql);
        }
        //Recuperation d'infos avec parametres passés
        $data = $bdd->getInfo($sql, $params);
        //Compte les lignes avec parametres passés
        $row_problem_date = $bdd->getRows($sql, $params);
        $params2 = array($dateNowSql);
        $sql_prblm = 'SELECT * FROM probleme WHERE (? < expiration_date or expiration_date IS NULL)';
        //Compte les lignes avec parametres passés
        $row = $bdd->getRows($sql_prblm, $params2);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
    <title>F.A.S.T - Les Signalements de mon service</title>
</head>
<body>
    <header>
        <!-- Include logo et nav -->
        <?php include_once('./assets/includes/logo-container.php');?>
        <?php include_once('./assets/includes/dropdown-manager.php') ?>
        <?php include_once('./assets/includes/nav-mobile-manager.php');?>
    </header>
    <?php
    //Si aucune ligne trouvée
    if ($row <= 0) {
    ?>
        <span class="space"></span>
        <h1 class="my-reports2">Il n'y a pas de Signalements correspondant à votre site (<?php echo $_SESSION['site'] ?>)</h1>
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
    //Si au moins 1 lignes est trouvée  
    }elseif($row > 0){ 
            //Changements titres pour la page en fonction des filtres
            if (isset($_GET['id-manager']) && !empty($_GET['id-manager'])) {
                ?>
                    <!-- Titre pour le filtrage voir mes signalements seulement -->
                    <h2 class="report_title">Vos Signalements :</h2>
                <?php
            }elseif (isset($_GET['date-debut']) && !empty($_GET['date-debut']) && isset($_GET['date-fin']) && !empty($_GET['date-fin']) && isset($_GET['filtrer']) && $_GET['filtrer'] == 'filtrer') {
                if($row_problem_date == 0) {
                ?>
                <!-- Titre pour le filtrage voir de date lorsqu il n y a pas de résultats -->
                    <h2 class="report_title">Il n'y a de Signalements correspondant à votre site (<?php echo $_SESSION['site'] ?>) entre le <?php echo $_GET['date-debut'] ?> et le <?php echo $_GET['date-fin'] ?> :</h2>
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
                
                }else {
                ?>
                    <!-- Titre pour le filtrage de la date -->
                    <h2 class="report_title">Signalements correspondant à votre site (<?php echo $_SESSION['site'] ?>) entre le <?php echo $_GET['date-debut'] ?> et le <?php echo $_GET['date-fin'] ?> :</h2>
                <?php
                }
            }else {
                ?>
                <!-- Titre pour si il n'y a pas de filtrage -->
                <h2 class="report_title">Signalements correspondant à votre site (<?php echo $_SESSION['site'] ?>) :</h2>
        <?php }?>
        <div align="center" id="filtre-btns">
            <button id="filtre-btn" class="filtre-btn">Filtrer par date</button>
            <div align="center" id="filtre-form">
                <form action="manager-account-reports.php">
                    <label for="date-debut">Date de debut</label>
                    <input type="date" name="date-debut" class="form-input" required>
                    <label for="date-fin">Date de fin</label>
                    <input type="date" name="date-fin" class="form-input" required>
                    <input type="submit" name="filtrer" value="filtrer" class="filtre-form-btn" required>
                </form>
                <button id="cancel-btn" class="filtre-btn">Annuler</button>
            </div>
            <?php
            // Si date de debut et fin sont definies
            if ((isset($_GET['date-debut']) && !empty($_GET['date-debut']) && isset($_GET['date-fin']) && !empty($_GET['date-fin']) && isset($_GET['filtrer']) && $_GET['filtrer'] == 'filtrer') or (isset($_GET['id-manager']) && !empty($_GET['id-manager']))) {
            ?>
                <button id="cancel-filter-btn" class="filtre-btn">Annuler le filtrage</button>
                <script>
                    var cancelFilterBtn = document.getElementById('cancel-filter-btn');
                    cancelFilterBtn.addEventListener('click', ()=>{
                        location.href = "?filter=notall";
                    });
                </script>
            <?php } ?>
            <form action="manager-account-reports.php">
                <input type="hidden" name="id-manager" value = "<?php echo $_SESSION["id"] ?>">
                <input type="submit" value="Voir mes signalements seulement" class="filtre-btn">
            </form>
        </div>
        <span class="space"></span>
            <table class="manager-reports table">
            <?php
            if($row_problem_date != 0) {
            ?>
                <thead>
                    <tr>
                        <th>Identifiant(ou Nom/Prénom)</th>
                        <th>Type de probleme</th>
                        <th>Date de la signalisation</th>
                        <th>Status du signalement</th>
                        <th>Localisation</th>
                        <th>Expiration</th>
                        <th>Plus de details</th>
                    </tr>
                </thead>
            <?php } ?>
            <tbody>
                <?php
                // Remplissage dynamique du tableau des signalements
                foreach ($data as $key => $value) {
                    $sql_user = 'SELECT * FROM user WHERE id = ?';
                    $id_user = array($data[$key]['id_user']);
                    $data_user = $bdd->getInfo($sql_user, $id_user);

                    $date = $data[$key]["date_signalement"];
                    $dateToTime = strtotime($date);
                    //transformer la date et ajouter 12H si c'est en pm
                    $rest = substr($data[$key]['heure_signalement'], -2); 
                    $heureToTime = strtotime($data[$key]["heure_signalement"]);
                    $heure = $rest === 'pm' ? date('H', strtotime('+0 hour',$heureToTime)) : date('H', $heureToTime);
                    //date d'expiration
                    $dateNow = strtotime(date('d-m-Y'));
                    $dateExpiration = strtotime($data[$key]['expiration_date']);
                    if($dateNow < $dateExpiration or empty($dateExpiration)){
                        $nbJours = ($dateExpiration - $dateNow)  / (60 * 60 * 24);
                ?>
                    <tr>
                        <td data-label="Identifiant(ou Nom/Prénom) : "><?php if(!empty($data_user[0]['nom']) && !empty($data_user[0]['prenom'])){ echo $data_user[0]['nom'] . ' ' . $data_user[0]['prenom']; }else{ echo $data_user[0]['identifiant']; } ?></td>
                        <td data-label="Type de probleme : "><?php echo $data[$key]["type_probleme"]; ?></td>
                        <td data-label="Date de la signalisation : "><?php echo "Le ". date('d', $dateToTime) . "-" . date('m', $dateToTime) . "-" .date('Y', $dateToTime) ." à " . $heure ."h". date("i", $heureToTime) ?></td>
                        <td data-label="Status du signalement : " class="status_signalement_reports <?php if($data[$key]['status'] == 'traité'){ echo'status-vert';}elseif($data[$key]['status'] == 'annulé'){ echo'status-rouge';}?>"><?php echo ucfirst($data[$key]["status"]) ?></td>
                        <td data-label="Localisation" : ><?php echo $data[$key]["localisation"] ?></td>
                        <td data-label="Expiration" class="icone"> <?php if($data[$key]['status'] == 'en cours de traitement'){ echo "Pas de date d'expiration";}elseif($data[$key]['status'] == "annulé" || $data[$key]['status'] == "traité" ){ echo "<p style='color:red;'>Temps restant avant l'expiration : $nbJours jours </p>"; } ?></td>
                        <td data-label=""><button class="btn-table" onclick="window.location.href='details.php?id_user=<?php echo $data[$key]['id_user']?>&id_equipement=<?php echo $data[$key]['id_equipement']?>&id_probleme=<?php echo $data[$key]['id']?>&page=reports'"><a href="details.php?id_user=<?php echo $data[$key]['id_user']?>&id_equipement=<?php echo $data[$key]['id_equipement']?>&id_probleme=<?php echo $data[$key]['id']?>&page=reports"><i><img src="./assets/img/details.svg" alt=""></i></a></button></td>
                    </tr>
                <?php 
                    }
                } 
                ?>
        </tbody>
        </table>
        
        <?php
        if (isset($_GET['filter']) && $_GET['filter'] == 'all' && $row > 6) {
        ?>
            <button class="btn manager-reports_btnPlus" id="table-plus" onclick="window.location.href='./manager-account-reports.php?filter=notall'"><a href="./manager-account-reports.php?filter=notall"><i><img src="./assets/img/arrow-up.svg" alt=""></i></a></button>
        <?php
        }elseif($row > 6){
            if ((isset($_GET['date-debut']) && !empty($_GET['date-debut']) && isset($_GET['date-fin']) && !empty($_GET['date-fin']) && isset($_GET['filtrer']) && $_GET['filtrer'] == 'filtrer') or (isset($_GET['id-manager']) and !empty($_GET['id-manager']))) {
                echo " ";
            }else {
            ?>
                <button class="btn manager-reports_btnPlus" id="table-plus" onclick="window.location.href='./manager-account-reports.php?filter=all'"><a href="./manager-account-reports.php?filter=all"><i><img src="./assets/img/arrow-down.svg" alt=""></i></a></button>
            <?php
            }
        }
        ?>
    <?php }?>
    <span class="space"></span>
    <!-- Includes footer -->
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
<script src="assets/js/dropdown.js" defer></script>
<script src="./assets/js/nav.js"></script>
<script src="assets/js/script.js" defer></script>
<script src="assets/js/open-service.js" defer></script>
</html>
<?php }else{
    //Redirection
    header("Location:connexion.php?isset=false");
}?>