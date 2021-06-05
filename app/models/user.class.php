<?php 

  class User extends Model {

    public function __construct($pdo){
      parent::__construct($pdo);
      
      $this->load('user','id','login','fullname');
    }

    public static function connexion($pdo, $login, $password) {
      $sql="SELECT * FROM user WHERE (`login`='$login' OR `email`='$login') AND `pwd`='".sha1($password)."'";

      try {
        $query = $pdo->query($sql);

        $data = $query->fetch(PDO::FETCH_OBJ);

        return $data;
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }

    // NODE
    public static $USER_NODE_ID = "id";
    public static $USER_NODE_LOGIN = "login";
    public static $USER_NODE_EMAIL = "email";
    public static $USER_NODE_PWD = "pwd";
    public static $USER_NODE_FULLNAME = "fullname";
    public static $USER_NODE_DATE_CREATION = "date_creation";
    public static $USER_NODE_VALIDATION_CODE = "validation_code";
    public static $USER_NODE_FILEBASE64 = "filebase64";


    public static $USER_NODE_PRENOM = "prenom";
    public static $USER_NODE_NOM = "nom";
    public static $USER_NODE_NEW_MDP = "newMdp";
    public static $USER_NODE_ANNEE_NAISS = "annee_naiss";
    public static $USER_NODE_NUM_PIECE = "num_piece";
    public static $USER_NODE_NUMERO = "numero";
    public static $USER_NODE_VERIFIE = "verifie";
    public static $USER_NODE_VERIF_NUM = "verif_num";
    public static $USER_NODE_STARS = "stars";
    public static $USER_NODE_AVATAR = "avatar";
  }
?>