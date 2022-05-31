<?php
    session_start();
    include_once('../includes/constants.php');
    include_once('../includes/database.class.php');
    $bdd = new database(HOST, DATA, USER, PASS);
    if (isset($_GET['status']) && !empty($_GET['status']) ) {
        $status = "";
        //verifier que le status qu'est passer en parametre soit c'est traité ou annulé ( on le passe 
        // en get dans la page de details )
        if($_GET['status'] === 'traité'){
            $status = 'traité';
        }elseif ($_GET['status'] === 'annulé') {
            $status = 'annulé';
        }else{
            header("Location:../../manager-account-reports.php?error=true");
        }
        try {
            $idProbleme = htmlspecialchars($_GET['id_probleme']);
            $params= array($status, $idProbleme);
            $bdd->updateStatus($params);
            //date d'expiration programmée a deux jours
            $expDate = date('Y-m-d', time() + 172800);
            $params_expDate = array($expDate, $idProbleme);
            //ajputer la date d'expiration
            $bdd->addExpDate($params_expDate);
            //Redirection vers la page des signalments
            if(isset($_GET['page']) && $_GET['page'] === "reports"){
                header("Location:../../manager-account-reports.php?filter=all");
            }else{
                header("Location:../../manager-account-reports.php?error=true");
            }
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }else{
        header("Location:../../manager-account-reports.php?error=true");
    }