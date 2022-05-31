<?php
class database{
    //les variables
    private $host;
    private $dbname;
    private $user;
    private $pass;
    protected $bdd;
    //constucteur
    public function __construct(string $newHost, string $newDbname, string $newUser, string $newPass)
    {
        $this->setHost($newHost);
        $this->setDbname($newDbname);
        $this->setUser($newUser);
        $this->setPass($newPass);
        try {
            //connexion a la BDD avec PDO
            $this->bdd = new PDO('mysql:host='.$this->getHost().";dbname=".$this->getDbname().";charset=utf8",$this->getUser(), $this->getPass());
            $this->bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $th) {
            throw new Exception(__CLASS__ . ' : ' . $th->getMessage());
        }
    }

    //les geters et les seters
    //pour le host
    public function setHost(string $newHost){
        $this->host = $newHost;
    }
    public function getHost(){
        return $this->host;
    }
    //pour le nom de a base de donner
    public function setDbname(string $newDbname){
        $this->dbname = $newDbname;
    }
    public function getDbname(){
        return $this->dbname;
    }
    //pour le user
    public function setUser(string $newUser){
        $this->user = $newUser;
    }
    public function getUser(){
        return $this->user;
    }
    //pour le mot de pass
    public function setPass(string $newPass){
        $this->pass = $newPass;
    }
    public function getPass(){
        return $this->pass;
    }

    //fonction pour avoir les information de n'importe quelle requete
    public function getInfo(string $sql, array $params = []){
        try {
            $req = $this->bdd->prepare($sql);
            $req->execute($params);
            $data = $req->fetchAll();
            return $data;
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }
    //fonction pour avoir les information d'un utilisateur en order decoissant de l'id
    public function getUsers(array $id = []) {
        $sql = 'SELECT * FROM user WHERE id != ? ORDER BY id DESC';
        $qry = $this->bdd->prepare($sql);
        $qry->execute($id);
        $data = $qry->fetchAll();
        return $data;
    }

    //fonction pour la creation de compte admin ou manager
    public function createManagerAdmin(array $params = []){
        try{
            $sql = 'INSERT INTO user(nom, prenom, email, identifiant, password, role, site) VALUES(?, ?, ?, ?, ?, ?, "LPO")';
            $req = $this->bdd->prepare($sql);
            $req->execute($params);
            $data = $req->fetch();
            return $data;
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }

    //fonction pour la creation de compte cariste
    public function createCariste(array $params = []){
        try{
            //étant donné qu'il n'y a qu'une seule instance par site, ce dernier est à modifier selon le site d'activité dans la requête ci-dessous
            $sql = 'INSERT INTO user(identifiant, role, site) VALUES(?, "cariste", "LPO")';
            $req = $this->bdd->prepare($sql);
            $req->execute($params);
            $data = $req->fetch();
            return $data;
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }

    //fonction pour la suppression d'un utilisateur et les signalment qu'il as fait
    public function deleteUser(array $params = []) {
        try {
            $sql_foreign_desable = 'SET FOREIGN_KEY_CHECKS=0';
            $qry_foreign_desable = $this->bdd->prepare($sql_foreign_desable);
            $qry_foreign_desable->execute();
            $sql1 = 'DELETE FROM user WHERE id= ?';
            $sql2 = 'DELETE FROM probleme WHERE id_user = ?';
            $qry1 = $this->bdd->prepare($sql1);
            $qry2 = $this->bdd->prepare($sql2);
            $qry1->execute($params);
            $qry2->execute($params);
            $sql_foreign_enable = 'SET FOREIGN_KEY_CHECKS=1';
            $qry_foreign_enable = $this->bdd->prepare($sql_foreign_enable);
            $qry_foreign_enable->execute();
        }  catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }

    //fonction pour mettre a jour un utilisateur de type manager ou admin
    public function updateUser(array $params = []){
        try{
            $sql = 'UPDATE user SET nom = ?, prenom = ?, identifiant = ?, password = ? where id = ?';
            $req = $this->bdd->prepare($sql);
            $req->execute($params);
        }catch(PDOException $err){
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }

    //fonction pour mettre a jour un cariste
    public function updateCariste(array $params = []){
        try{
            $sql = 'UPDATE user set nom = ?, prenom= ? WHERE id = ?';
            $req = $this->bdd->prepare($sql);
            $req->execute($params);
        }catch(PDOException $err){
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }

    //fonction pour ajouter un signalement dans la BDD
    public function addProbleme(array $paramsEquipment = [], array $paramsProbleme = []){
        try {
            //pour l'equipment
            $sql = 'INSERT INTO equipement(ip_machine, genre, chariot) VALUES(?, ?, ?)';
            $req = $this->bdd->prepare($sql);
            $req->execute($paramsEquipment); 
            //pour le probleme
            $idEquipment = 'SELECT LAST_INSERT_ID();';
            $reqId = $this->bdd->prepare($idEquipment);
            $reqId->execute();
            $idData = $reqId->fetchAll();
            $id = $idData[0]['LAST_INSERT_ID()'];
            $sql2 = 'INSERT INTO probleme(type_probleme, id_equipement, id_user, description, wms, heure_signalement, process, localisation, image, status, del_date) VALUES(?, ' . $id .', ?, ?, ?, ?, ?, ?, ?,"en cours de traitement", ?)';
            $req2 = $this->bdd->prepare($sql2);
            $req2->execute($paramsProbleme);
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }

    //fonction pour supprimer un signalement 
    public function deleteProbleme(array $params =[]) {
        try {
            $sql = 'DELETE FROM probleme WHERE id=?';
            $sql2 = 'DELETE FROM equipement WHERE id=?';
            $qry = $this->bdd->prepare($sql);
            $qry2 = $this->bdd->prepare($sql2);
            $qry->execute($params);
            $qry2->execute($params);
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }

    //fonction pour mettre a jour le statut d'un signalement
    public function updateStatus(array $params = []){
        try {
            $sql = 'UPDATE probleme set status = ? WHERE id = ?';
            $qry = $this->bdd->prepare($sql);
            $qry->execute($params);
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }

    //fonction pour ajouter la date d'expiration d'un signalement
    public function addExpDate(array $params = []){
        try {
            $sql = "UPDATE probleme SET expiration_date = ? WHERE id = ? ";
            $req = $this->bdd->prepare($sql);
            $req->execute($params);
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }

    //fonction pour supprimer le mail d'un utilisateur dans la table pwdreset
    public function deletePwd(string $sql, array $params = []){
        try{
            $req = $this->bdd->prepare($sql);
            $req->execute($params);
            $req->fetchAll();
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }
    //fonction pour mettre a jour le mot de passe d'un utilisateur
    public function UpdatePwd(string $sql, array $params = []){
        try {
            $req = $this->bdd->prepare($sql);
            $req->execute($params);
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }

    //fonction pour avoir le nombre des ligne que n'importe quelle requete retourn
    public function getRows(string $sql, array $params = []){
        try {
            $req = $this->bdd->prepare($sql);
            $req->execute($params);
            $row = $req->rowCount();
            return $row;
        } catch (PDOException $err) {
            throw new Exception(__CLASS__ . ' : '. $err->getMessage());
        }
    }
}
?>