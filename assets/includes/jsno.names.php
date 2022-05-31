<?php
  //include des constants
  include_once('constants.php');
  //include de la class database
  include_once('database.class.php');
  $mydb = new dataBase(HOST, DATA, USER, PASS);
  //tous les identifiants dans la BDD pour la bare de recherche autocomplete
  $sql = "SELECT identifiant from user WHERE id != ?";
  $params = array($_SESSION['id']);
  $dataJson = $mydb->getInfo($sql, $params);
  ?>
  <p id="json-data">
      <?php echo json_encode($dataJson);?>
  </p>