<?php
$datejour = date('Y-m-d');
//accès à la la base de données au préalable puisque ce fichier est un include pour la page d'accueil 
 
//Premiere ligne = nom des champs (
$xls_output = "type de probleme;ip equipement;genre;materiel;nom et prenom(ou pseudo) ;description;wms;date de signalement;heure de signalement;process;localisation";
$xls_output .= "\n"; 
 
//Requetes SQL pour le telechargement du csv pour archivage
 

$sql='SELECT p.type_probleme,e.ip_machine,e.genre,e.chariot,u.nom,u.prenom,u.identifiant,p.description,p.wms,p.date_signalement,p.heure_signalement,p.process,p.localisation FROM probleme p INNER JOIN user u ON u.id = p.id_user INNER JOIN equipement e ON e.id = p.id_equipement WHERE p.del_date <= ?';
$params = array($datejour);
//Recupere les informations que l'on veut récupérer pour l'archivage
$data = $bdd->getInfo($sql, $params);

//Compte le nombre de résultats de la requete précedente
$row= $bdd->getRows($sql, $params);

  if ($row != 0){
    //Boucle sur les resultats
    for ($i=0; $i < $row; $i++) {
      if(is_null($data[$i]['nom']) && is_null($data[$i]['prenom'])) {
        $xls_output .= $data[$i]['type_probleme'].";".$data[$i]['ip_machine'].";".$data[$i]['genre'].";".$data[$i]['chariot'].";".$data[$i]['identifiant'].";".$data[$i]['description'].";".$data[$i]['wms'].";".$data[$i]['date_signalement'].";".$data[$i]['heure_signalement'].";".$data[$i]['process'].";".$data[$i]['localisation']."\n";
      } 
      else {
        $xls_output .= $data[$i]['type_probleme'].";".$data[$i]['ip_machine'].";".$data[$i]['genre'].";".$data[$i]['chariot'].";".$data[$i]['nom']." ".$data[$i]['prenom'].";".$data[$i]['description'].";".$data[$i]['wms'].";".$data[$i]['date_signalement'].";".$data[$i]['heure_signalement'].";".$data[$i]['process'].";".$data[$i]['localisation']."\n";
      }
    }
    // Requete pour supprimer les problèmes avec une date d'expiration egale ou inferieure à la date du jour

  $sql2='DELETE FROM probleme WHERE del_date <= ?';
  $bdd->delExpProblems($sql2, $params);

  //Enregistrement du contenu de $xls-output correspondant a notre fichier exel dans un fichier avec la date du jour qui sera stocké dans un dossier d'archivage à la racine du projet
  $file_name = date('d-m-Y').'.csv';
  file_put_contents('./archivage/'.$file_name,$xls_output);
  }
?>