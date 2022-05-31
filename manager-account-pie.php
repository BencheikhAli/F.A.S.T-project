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
        //Si filter est dans l'url et est egale à all
        if (isset($_GET['filter']) && $_GET['filter'] == 'all') {
            $sql_probleme = 'SELECT * FROM probleme ORDER BY id DESC';
            $params = array();
        //Si filter est dans l'url et est égale à notall
        }elseif (isset($_GET['filter']) && $_GET['filter'] == 'notall') {
            $sql_probleme = 'SELECT * FROM probleme ORDER BY id DESC LIMIT 6';
            $params = array();
        //Si date-debut et date-fin sont dans l'url
        }elseif (isset($_GET['date-debut']) && !empty($_GET['date-debut']) && isset($_GET['date-fin']) && !empty($_GET['date-fin']) && isset($_GET['filtrer']) && $_GET['filtrer'] == 'filtrer') {
            $sql_probleme = 'SELECT DISTINCT * from probleme WHERE (? < expiration_date or expiration_date IS NULL) and date_signalement BETWEEN ? and ? ORDER BY id DESC';
            $date_debut = $_GET['date-debut'];
            $date_fin = $_GET['date-fin'];
            $params = array($dateNowSql,$date_debut,$date_fin);
        }else{
            $sql_probleme = 'SELECT * FROM probleme ORDER BY id DESC LIMIT 6';
            $params = array();
        }

        //Si profondeur est dans l'url et est pas nul
        if(isset($_GET['profondeur']) && !empty($_GET['profondeur'])){
            //Netoyage de données
            $profondeur = htmlspecialchars(trim($_GET['profondeur'])."%");
            $sql_profondeur = 'SELECT localisation, (COUNT(localisation) * 100 / (SELECT COUNT(localisation) FROM probleme WHERE localisation LIKE ? )) as profondeur from probleme WHERE localisation LIKE ? GROUP BY localisation';
            $params_profondeur = array($profondeur, $profondeur);
            //Recuperation d'infos avec les parametres passés
            $data_profondeur = $bdd->getInfo($sql_profondeur, $params_profondeur);
            //Compte le nombre de ligne avec les parametres passés
            $rows_profondeur = $bdd->getRows($sql_profondeur, $params_profondeur);
        }
        //Recuperation d'infos avec les parametres passés
        $data_probleme = $bdd->getInfo($sql_probleme, $params);
        //Compte le nombre de ligne avec les parametres passés
        $rows_probleme_filter = $bdd->getRows($sql_probleme, $params);
        $sql_probleme_row = "SELECT * FROM probleme";
        //compte les lignes
        $rows_probleme = $bdd->getRows($sql_probleme_row);
        //les localisations dans la BDD
        $sql_locations = 'SELECT localisation, substring(localisation,1,10) as allée,COUNT(*) as nb_prof_dif, (COUNT(substring(localisation,1,10)) * 100 / (SELECT COUNT(*) FROM probleme)) as score FROM probleme GROUP by substring(localisation,1,10);';
        $data_locations = $bdd->getInfo($sql_locations);
        $rows_locations = $bdd->getRows($sql_locations);
        //les type des probleme
        $sql_probleme_pie = 'SELECT type_probleme, (COUNT(type_probleme) * 100 / (SELECT COUNT(*) FROM probleme)) as scoreProbleme from probleme GROUP BY type_probleme';
        $data_probleme_pie = $bdd->getInfo($sql_probleme_pie);
        $rows_probleme_pie = $bdd->getRows($sql_probleme_pie);
        //les etat de probleme
        $sql_status = 'SELECT status, (COUNT(status) * 100 / (SELECT COUNT(*) FROM probleme)) as percent from probleme GROUP BY status';
        $data_status = $bdd->getInfo($sql_status);
        $rows_status = $bdd->getRows($sql_status);
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>F.A.S.T - Diagrammes </title>
</head>
<body>
    <header>
        <?php include_once('./assets/includes/logo-container.php');?>
        <?php include_once('./assets/includes/dropdown-manager.php');?>
        <?php include_once('./assets/includes/nav-mobile-manager.php');?>
    </header>
    <?php if($rows_probleme > 0 or $rows_probleme_filter > 0){
        //Si date de debut et fin sont définis
        if (isset($_GET['date-debut']) && !empty($_GET['date-debut']) && isset($_GET['date-fin']) && !empty($_GET['date-fin']) && isset($_GET['filtrer']) && $_GET['filtrer'] == 'filtrer' && $rows_probleme_filter > 0) {  
            ?>
                <h1 class="report_title"> Tableau de tout les problemes (<?php echo $_SESSION['site'] ?>) : entre <?php echo $_GET['date-debut'] ?> et <?php echo $_GET['date-fin'] ?> </h1>
            <?php }elseif($rows_probleme_filter == 0){
            ?>
                <h1 class="report_title"> Il y'a pas de signalment dans le site (<?php echo $_SESSION['site'] ?>) : entre <?php echo $_GET['date-debut'] ?> et <?php echo $_GET['date-fin'] ?> </h1>
            <?php    
            }else{
            ?>
                <h1 class="report_title">Tableau de tout les problemes (<?php echo $_SESSION['site'] ?>) : </h1>
            <?php    
            } ?>        
        <div align="center">
        <button id="filtre-btn" class="filtre-btn btn-export">Filtrer le tableux par date</button>
        <!-- Si date de debut et fin sont definis-->
        <?php if (isset($_GET['date-debut']) && !empty($_GET['date-debut']) && isset($_GET['date-fin']) && !empty($_GET['date-fin']) && isset($_GET['filtrer']) && $_GET['filtrer'] == 'filtrer') {  
        ?>
            <button id="cancel-filter-btn" class="filtre-btn btn-export">Annuler le filtrage</button>
            <script>
            var cancelFilterBtn = document.getElementById('cancel-filter-btn');
            cancelFilterBtn.addEventListener('click', ()=>{
                location.href = "?filter=notall";
            });
        </script>
        <?php
        }
        ?>
        <div align="center" id="filtre-form">
            <form action="manager-account-pie.php">
                <!-- Filtre par date -->
                <label for="date-debut">Date de debut</label>
                <input type="date" name="date-debut" class="form-input" required>
                <label for="date-fin">Date de fin</label>
                <input type="date" name="date-fin" class="form-input" required>
                <input type="submit" name="filtrer" value="filtrer" class="filtre-form-btn">
            </form>
            <button id="cancel-btn" class="filtre-btn btn-export">Annuler</button>
        </div>
    </div>
        <span class="space"></span>
    <table id="dataTable" class="table">
        <?php
        if ($rows_probleme_filter !== 0) {
         ?>   
        <thead>
          <tr>
            <th>Identifiant(ou Nom/Prénom)</th>
            <th>TR/TE</th>
            <th>IP de l'équipement</th>
            <th>Type de probleme</th>
            <th>WMS/Process</th>
            <th>Materiel</th>
            <th>Localisation</th>
            <th>Date</th>
            <th>Heure</th>
          </tr>
        </thead>
        <?php
        }
        ?>
        <tbody>
    <?php 
    // Remplissage du tableau dynamiquement avec les problemes dans la base de données
    foreach ($data_probleme as $key => $value) {
        $sql_equipment = 'SELECT * FROM equipement WHERE id = ?';
        $id_equipment = array($data_probleme[$key]['id_equipement']);
        $data_equipment = $bdd->getInfo($sql_equipment, $id_equipment);
        $sql_user = 'SELECT * FROM user WHERE id = ?';
        $id_user = array($data_probleme[$key]['id_user']);
        $data_user = $bdd->getInfo($sql_user, $id_user);

        $date = $data_probleme[$key]["date_signalement"];
        $dateToTime = strtotime($date);
        //transformer la date et ajouter 12H si cest en pm
        $rest = substr($data_probleme[$key]['heure_signalement'], -2); 
        $heureToTime = strtotime($data_probleme[$key]["heure_signalement"]);
        $heure = $rest === 'pm' ? date('H', strtotime('+0 hour',$heureToTime)) : date('H', $heureToTime);
        
    ?>
          <tr>
            <td data-label="Identifiant(ou Nom/Prénom) : "><?php if(!empty($data_user[0]['nom']) && !empty($data_user[0]['prenom'])){ echo $data_user[0]['nom'] . ' ' . $data_user[0]['prenom']; }else{ echo $data_user[0]['identifiant']; } ?></td>
            <td data-label="TR/TE : "><?php echo $data_equipment[0]['genre'] ?></td>
            <td data-label="IP de l'équipement : "><?php echo $data_equipment[0]['ip_machine'] ?></td>
            <td data-label="Type de probleme : "><?php echo $data_probleme[$key]['type_probleme'] ?></td>
            <td data-label="Application/Process : "><?php echo  $data_probleme[$key]['wms'] . "/" . $data_probleme[$key]['process'] ?></td>
            <td data-label="Materiel : "><?php echo $data_equipment[0]['chariot'] ?></td>
            <td data-label="Localisation : "><?php echo $data_probleme[$key]['localisation'] ?></td>
            <td data-label="Date : "><?php echo date('d', $dateToTime) . "/" . date('m', $dateToTime) . "/" .date('Y', $dateToTime) ?></td>
            <td data-label="Heure : "><?php echo $heure ."h". date("i", $heureToTime) ?></td>
          </tr>
    <?php    
    } ?>
    </tbody>
    </table>
    <?php
        if ($rows_probleme_filter !== 0) {
    ?> 
    <div class="btns-export">
        <button class="btn btn-export"  onclick="window.location.href='#id01'"><a href="#id01">Exporter</a></button>
        <?php
        //Verif filter dans l'url
        if (isset($_GET['filter']) && $_GET['filter'] == 'all' && $rows_probleme > 6 && !isset($_GET['date-debut'])) {
        ?>
            <button class="btn btn-export" id="table-plus" onclick="window.location.href='?filter=notall'"><a href="?filter=notall"><i><img src="./assets/img/arrow-up.svg" alt=""></i></a></button>
        <?php
        //Si 6 ignes ou plus trouvés
        }elseif($rows_probleme > 6 && !isset($_GET['date-debut'])){
        ?>
            <button class="btn btn-export" id="table-plus" onclick="window.location.href='?filter=all'"><a href="?filter=all"><i><img src="./assets/img/arrow-down.svg" alt=""></i></a></button>
        <?php
        }
        ?>
    </div>
    <div id="id01" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="container"> 
                    <a href="#" class="closebtn"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                    <div>
                        <button id="btnExportToCsv" type="button" class="btn btn-export">CSV</button>
                        <button type="button" class="btn btn-export" id="google-link-btn">google sheets</button>
                    </div>
                    <div class="link-export">
                        <h4>Cliquez sur l'icon pour copier le lien et puis collez-le dans une colone dans votre fichier google sheets</h4>
                        <button class="btn btn-export" onclick='copy()' onmouseout="outFunc()">
                            <span class="tooltiptext" id="myTooltip">copier le lien</span>
                            <i class="fa fa-files-o" aria-hidden="true"></i>
                        </button>
                    </div>
                </div> 
            </div>
        </div>
    </div> 
    <?php
    }
    // DIAGRAMMES DES LOCALISATIONS
    ?> 
    <span class="space"></span>
    <hr>
    <span class="space"></span>
    <h1 class="report_title">Diagrammes en temps réel des signalements : </h1>
    <span class="big_space"></span>
    <div class="pie_box">
        <div>
            <h2 class="pie_title">Les types des problemes : </h2>
            <canvas id="chartProblems" class="chart_probleme"></canvas>
        </div>
        <div>
            <h2 class="pie_title">Les status des problemes : </h2>
            <canvas id="chartStatus" class="chart_status"></canvas>
        </div>
    </div>
    <span class="big_space"></span>
    <div>
        <h2 class="pie_title">Localisation des problemes <span>(cliquez sur les allées pour voir les profondeurs )</span> : </h2>
        <canvas id="chartLocations" class="chart_location"></canvas>
    </div>  
    <!-- Verif profondeur dans l'url -->   
    <?php if(isset($_GET['profondeur']) && !empty($_GET['profondeur'])){ ?>
        <div>
            <h2 class="pie_title">Les profondeurs de l'<?php echo htmlspecialchars($_GET['profondeur']); ?> <span></span> : </h2>
            <canvas id="chartProfondeur" class="chart_probleme"></canvas>
        </div>
    <?php }}else{
    ?>
    <span class="space"></span>
    <h1 class="my-reports2">Il n'y a pas de Signalements correspondant à votre site (<?php echo $_SESSION['site'] ?>) </h1>
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
    }
    $scoreLocation = array();
    $locations = array();
    for ($i=0; $i < $rows_locations; $i++) { 
        array_push($scoreLocation, round($data_locations[$i]['score']));
        array_push($locations, $data_locations[$i]['allée']);
    }
    $scoreStatus = array();
    $status = array();
    for ($i=0; $i < $rows_status; $i++) { 
        array_push($scoreStatus, round($data_status[$i]['percent']));
        array_push($status, $data_status[$i]['status']);
    }

    $scoreProbleme = array();
    $probleme = array();
    for ($i=0; $i < $rows_probleme_pie; $i++) { 
        array_push($scoreProbleme, round($data_probleme_pie[$i]['scoreProbleme']));
        array_push($probleme, $data_probleme_pie[$i]['type_probleme']);
    }

    $scoreProfondeur = array();
    $profondeur = array();
    if(isset($_GET['profondeur']) && !empty($_GET['profondeur'])){
        for ($i=0; $i < $rows_profondeur; $i++) { 
            array_push($scoreProfondeur, round($data_profondeur[$i]['profondeur']));
            array_push($profondeur, substr($data_profondeur[$i]['localisation'], 10));
        }
    }
    ?>
    <!-- les labels et le percentage des localisation des signalements -->
    <p id="labels-location">
        <?php  echo json_encode($locations);  ?>
    </p>
    <p id="score-location">
        <?php  echo json_encode($scoreLocation);  ?>
    </p>
    <!-- les labels et le percentage des status des signalements -->
    <p id="labels-status">
        <?php  echo json_encode($status);  ?>
    </p>
    <p id="score-status">
        <?php  echo json_encode($scoreStatus);  ?>
    </p>
    <!-- les labels et le percentage des types des signalements -->
    <p id="labels-probleme">
        <?php  echo json_encode($probleme);  ?>
    </p>
    <p id="score-probleme">
        <?php  echo json_encode($scoreProbleme);  ?>
    </p>
    <!-- les labels et le percentage pour les profondeur -->
    <p id="labels-profondeur">
        <?php  echo json_encode($profondeur);  ?>
    </p>
    <p id="score-profondeur">
        <?php  echo json_encode($scoreProfondeur);  ?>
    </p>
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
<script src="assets/js/dropdown.js" defer></script>
<script src="./assets/js/export-csv.js" defer></script>
<script src="./assets/js/chart.js" defer></script>
<script src="assets/js/nav.js" defer></script>
<script src="assets/js/script.js" defer></script>
</html>
<?php }else{
    header("Location:connexion.php");
} ?>