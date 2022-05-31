<?php
    session_start();
    //On inclut la fonction de detection automatique de l'ip
    include_once('./assets/php/getIp.php');
    //On inclut les constantes dans la page qui nous serviront à la connexion avec la base de données
    include_once('./assets/includes/constants.php');
    //On inclut la classe database.class.php qui nous servira à communiquer avec la base de données
    include_once('./assets/includes/database.class.php');
    //Création de la variable $bdd qui correspond à un objet de type database ( on passe les constantes en paramètres pour la connexion )
    $bdd = new database(HOST, DATA, USER, PASS);
    //On inclut la page export pour l'archivage et la purge de la base de données
    include_once('./assets/includes/export.php');
    //Si la variable ip est dans l'url et est non nulle :
    if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
        //Si l'utilisateur n'est pas un admin :
        if ($_SESSION['role'] != 'admin') {
            $date = date('Y-m-d');
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F.A.S.T - Accueil</title>
    <link rel="stylesheet" href="assets/css/accueil.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
</head>

<!-- Appel fonction recuperation genre a partir de l'ip -->
<body onload="getGenreWithIp()">
    <header>
        <div class="logo-container">
            <a href="index.php"><img src="assets/img/fast.png" alt="logo du site" class="logo"></a>
            <h2 class="title-site">F.A.S.T</h2>
        </div>
        <?php
            //L'utilisateur est un manager :
            if ($_SESSION['role'] == 'manager') {
                // Include nav manager
                include_once('assets/includes/dropdown-manager.php');
                include_once('assets/includes/nav-mobile-manager.php');
            //L'utilisateur est un cariste :
            }elseif ($_SESSION['role'] == 'cariste' || $_SESSION['role'] == 'invite') {
                // Include nav cariste
                include_once('assets/includes/dropdown-user.php');
                include_once('assets/includes/nav-mobile-user.php');
            //L'utilisateur est un admin
            }elseif ($_SESSION['role'] == 'amdin') {
                // Include nav admin
                include_once('assets/includes/dropdown-admin.php');
                include_once('assets/includes/nav-mobile-admin.php');
            }
        ?>
    </header>
    <h1 class="title-page">Accueil :</h1>
    <main id="page-content">
        <h2 class="report">Signaler une anomalie ou un probleme :</h2>
        <?php
            //Si la variable file est dans l 'url
            if (isset($_GET['file'])) {
        ?>
            <div class="form-container error">
                <p>Envoi de pièce jointe impossible !! Merci de joindre une piece joint de type (jpg, jpeg, gif, png)</p>
            </div>
        <?php
            //Si la variable description est dans l'url     
            }elseif(isset($_GET['description'])){
        ?>
            <div class="form-container error">
                <p>vous avez dépassé le nombre maximum de caractere (255)</p>
            </div>
        <?php
            //Verification des variables add et m dans l'url
            }elseif (isset($_GET['add']) && $_GET['add'] == 'true' && isset($_GET['m']) && $_GET['m'] == 'ok') {
        ?>
            <div class="form-container add-true">
                <p>le signalement a bien été enregistré !! <a href="./manager-account-reports.php">Voir les signalements</a></p>
            </div>
        <?php
            //Verification des variables add et c dans l'url        
            }elseif(isset($_GET['add']) && $_GET['add'] == 'true' && isset($_GET['c']) && $_GET['c'] == 'ok'){
        ?>
            <div class="form-container add-true">
                <p>le signalement a bien été enregistré !! <a href="./user-account-myreports.php">Voir mes signalements</a></p>
            </div>
        <?php
            //Si variable location dans l'url
            }elseif(isset($_GET['location'])){
        ?>
            <div class="form-container error">
                <p>Le site ne prend pas en compte ce type de code bare pour la localisation !!!</p>
            </div>
        <?php
            }
        ?>
        <div class="form-container">
        <form action="./assets/php/probleme-add.php" method="POST" enctype="multipart/form-data">   
            <div class="q">
                <h3>Type de probleme :</h3>
                <div class="radio-buttons">
                    <label class="custom-radio">
                        <input type="radio" name="type_probleme" value="lenteur" required >
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/lenteur.svg" alt=""></i>
                                <br>
                                <h3>Lenteur</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="type_probleme" value="bug d'affichage">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/monitor.svg" alt=""></i>
                                <br>
                                <h3>Bug d'affichage</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="type_probleme" value="deconnexion">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/plug.svg" alt=""></i>
                                <h3>Deconnexion</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="type_probleme" value="ecran blanc puis deconnexion">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <div>
                                    <i><img src="./assets/img/monitor.svg" alt=""></i>
                                    <i><img src="./assets/img/plug.svg" alt=""></i>
                                </div>
                                <h3>Ecran blanc puis deconnexion</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="type_probleme" value="lenteur puis deconnexion">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <div>
                                    <i><img src="./assets/img/lenteur.svg" alt=""></i>
                                    <i><img src="./assets/img/plug.svg" alt=""></i>
                                </div>
                                <h3>Lenteur puis deconnexion</h3>
                            </div>
                        </span>
                    </label>
                    <label id="autre-btn" class="custom-radio">
                        <input type="radio" name="type_probleme">
                        <span id="autre" class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/dots.svg" alt=""></i>
                                <h3>Autre</h3>
                            </div>
                        </span>
                    </label>
                    <div class="other-div" id="other">
                        <input type="text" name="type_probleme_other" id="probleme-type-other" class="other-input" placeholder="Precisez.....">
                        <button class="cancel-btn" id="cancel">Annuler</button>
                    </div>
                </div>
            </div>
            <div class="q">
                <h3>Application / process :</h3>
                <div class="radio-buttons">
                    <label class="custom-radio">
                        <input type="radio" name="application_process" value="prep+" required >
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <!-- <i class="fa fa-cogs" aria-hidden="true"></i> -->
                                <i><img src="./assets/img/cogs.svg" alt=""></i>
                                <h3>Prep +</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="application_process" value="recept">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <!-- <i class="fa fa-cogs" aria-hidden="true"></i> -->
                                <i><img src="./assets/img/cogs.svg" alt=""></i>
                                <h3>Recept</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="application_process" value="GDG">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/cogs.svg" alt=""></i>
                                <h3>GDG</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="application_process" value="chargement">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/cogs.svg" alt=""></i>
                                <h3>Chargement</h3>
                            </div>
                        </span>
                    </label>
                    <label id="autre-btn2"class="custom-radio">
                        <input type="radio" name="application_process">
                        <span id="autre2" class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/dots.svg" alt=""></i>
                                <h3>Autre</h3>
                            </div>
                        </span>
                    </label>
                    <div class="other-div" id="other2">
                        <input type="text" name="application_process_other" id="application-process-other" class="other-input" placeholder="Precisez.....">
                        <button class="cancel-btn" id="cancel2">Annuler</button>
                    </div>
                </div>
            </div>
            <div class="q">
                <h3>Materiel :</h3>
                <div class="radio-buttons">
                    <label class="custom-radio">
                        <input type="radio" name="materiel" value="R50" required >
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/meteriel.svg" alt=""></i>
                                <h3>R50</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="materiel" value="PGT2">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/meteriel.svg" alt=""></i>
                                <h3>PGT2</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="materiel" value="R90">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/meteriel.svg" alt=""></i>
                                <h3>R90</h3>
                            </div>
                        </span>
                    </label>
                    <label id="autre-btn3" class="custom-radio">
                        <input type="radio" name="materiel">
                        <span id="autre3" class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/dots.svg" alt=""></i>
                                <h3>Autre</h3>
                            </div>
                        </span>
                    </label>
                    <div class="other-div" id="other3">
                        <input type="text" name="materiel_other" id="materiel-other" class="other-input" placeholder="Precisez.....">
                        <button class="cancel-btn" id="cancel3">Annuler</button>
                    </div>
                </div>
            </div>
            <div class="q">
                <h3>Wms :</h3>
                <div class="radio-buttons">
                    <label class="custom-radio">
                        <input type="radio" name="wms" value="Reflex" class="inputbtn" required>
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/wms.svg" alt=""></i>
                                <h3>Reflex</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="wms" value="oxygen" class="inputbtn">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/wms.svg" alt=""></i>
                                <h3>Oxygen</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="wms" value="opaleng" class="inputbtn">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/wms.svg" alt=""></i>
                                <h3>OpaleNg</h3>
                            </div>
                        </span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="wms" value="cimsup" class="inputbtn">
                        <span class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon"> 
                                <i><img src="./assets/img/wms.svg" alt=""></i>
                                <h3>Cimsup</h3>
                            </div>
                        </span>
                    </label>
                    <label id="autre-btn4" class="custom-radio">
                        <input type="radio" name="wms" class="inputbtn">
                        <span id="autre4" class="radio-btn"><i class="check-svg"><img src="./assets/img/check.svg" alt=""></i>
                            <div class="problems-icon">
                                <i><img src="./assets/img/dots.svg" alt=""></i>
                                <h3>Autre</h3>
                            </div>
                        </span>
                    </label>
                    <div class="other-div" id="other4">
                        <input type="text" name="wms_other" id="wms-other" class="other-input" placeholder="Precisez.....">
                        <button class="cancel-btn" id="cancel4">Annuler</button>
                    </div>
                </div>
            </div>
            <input type="text" id="ip_machine" name="ip_machine" placeholder="IP de l'équipement" value="<?php echo getIp(); ?>" class="ip_machine" pattern="(10)(\.([2]([0-5][0-5]|[01234][6-9])|[1][0-9][0-9]|[1-9][0-9]|[0-9])){3}" required >
            <select name="genre" id="genre" class="tr-te" required>
                <option value="">Genre</option>
                <option value="TR">TR</option>    
                <option value="TE">TE</option>
                <option value="PC">PC</option>
            </select>
            <input type="text" id="localisation" name="location" placeholder="Cliquez puis scannez votre localisation ici..." class="localisation" required >
            <label for="description"><p class="ptextarea">Caractères restants :<span id="characters">0</span>/255</p></label>
            <textarea name="description" placeholder="Description" id="desc" pattern="[A-Za-z]{255}" class="description" ></textarea>
            <h3 class="p-j-title">Piece Jointe (facultatif) :</h3>
            <input type="file" size=40 name="file" accept='image/jpg, image/jpeg, image/gif, image/png, image/jfif, image/jpe, image/bmp' class="p-j">
            <span class="space"></span>
            <button type="submit" class="sign-btn">Signaler</button>
        </form>
        </div>
        <script>
            // Programmation javascript pour auto focus le champ localisation après avoir selectionné le wms
            var inputs = document.getElementsByClassName('inputbtn');
            var localisation = document.getElementById('localisation');
            for (var i = 0; i < inputs.length-1; i++) {
                inputs[i].addEventListener('click', () => {
                    localisation.focus();
                });
            }
        </script>

    </main>
    <?php 
    // Include Footer
    include_once('assets/includes/footer.php'); ?>
</body>
    <script src="assets/js/accueil.js" defer></script>
    <script src="assets/js/nav.js"></script>
    <script src="assets/js/dropdown.js"></script>
    <script src="assets/js/desc-number.js" defer></script>
    <script src="./assets/js/ajax-genre.js" defer></script>
    <script src="./assets/js/localStorage-accueil.js" defer></script>
</html>
<?php
}else{
    //Redirection
    header("Location:admin-account.php");
}
}else{
    //Redirection
    header("Location:connexion.php");
} ?>