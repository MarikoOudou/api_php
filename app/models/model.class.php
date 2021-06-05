<?php 

  class Model {

    protected $_db;
    protected $_table;
    protected $_id;
    protected $_img;
    protected $_title;
    public $errors = array();

    public function __construct($pdo){
      $this->set_db($pdo);
    }

    public function load($table, $id, $title, $img) {
      $this->set_table($table);
      $this->set_id($id);
      $this->set_title($title);
      $this->set_img($img);
    }

    public function insert($infos) {
      $sql = "INSERT INTO ".$this->_table." (";
      
      foreach ($infos as $k => $v) {
        $sql .= "`".$k."`, ";
      }
      
      $sql= substr($sql,0,-2);
      
      $sql.= ") VALUES (";
      
      foreach ($infos as $k => $v) {
        $sql .= "'".utf8_decode(addslashes($v))."', ";
      }
      
      $sql = substr($sql,0,-2);
      
      $sql .= ")";
    
      try {
        $query = $this->_db->query($sql);
        $last_id = $this->_db->lastInsertId();

        return $last_id;
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }

    public function update($infos) {
      $id = $infos[$this->_id];
      
      unset($infos[$this->_id]);
      unset($infos[$this->_img]);

      $sql = "UPDATE ".$this->_table." SET ";
      foreach ($infos as $key => $value) {
        $sql .= "`".$key."`='".utf8_decode(addslashes($value))."', ";
      }
      $sql = substr($sql, 0, -2)." WHERE `".$this->_id."` = '$id'";

      try {
        $query = $this->_db->query($sql);
        
        return true;
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }

    public function updateImg($info) {
      
      $nomImage = $info[$this->_img];
      $last_id = $info[$this->_id];        
            
      $sql = "UPDATE ".$this->_table." SET `".$this->_img."` = '$nomImage' WHERE `".$this->_id."` = '$last_id'";

      try {
        $query = $this->_db->query($sql);
        return true;
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }

    public function getByCriteria($datas, $parPage = 0, $pdo = PDO::FETCH_OBJ) {
      $sql = "SELECT * FROM ".$this->_table;

                if (count($datas) > 0) {
                    $sql .= " WHERE ";
                    foreach ($datas as $k => $v) {
                        $sql .= "`" . $k . "` = '" . $v . "' AND ";
                    }

                    $sql = substr($sql, 0, -5);
                }

      if ($parPage != 0) {
        $total = $this->_db->query($sql);

        $max = $total->rowCount($sql);

          $nbPages = ceil($max/$parPage);

          // Détermination de la page courante
          if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages){ 
                      
              $currentPage = $_GET['page'];
          }
          else {
              $currentPage = 1;
          }
          
          // Détermination du début de chaque page ainsi que celle de la fin
          if ($currentPage == 1){ 
              $debut = $currentPage; 
          }
          else{
               $debut = ($parPage * ($currentPage-1) + 1);
          }
          
          if ($currentPage == $nbPages){ 
              $fin = $max; 
          }
          else{
               $fin = ($debut + $parPage - 1);
          }
          
          //Numéro page
          if (!isset($_GET['page']))
              $page = 1;
          else
              $page = $_GET['page'];

          $sql .= " LIMIT ".(($currentPage-1)*$parPage).",".$parPage;
      }

      try {
        $query = $this->_db->query($sql);
        
        $datas = array();
        $datas['sql'] = $sql;
        if ($parPage != 0) $datas['max'] = $max;
        if ($parPage != 0) $datas['nbPages'] = $nbPages;
        if ($parPage != 0) $datas['currentPage'] = $currentPage;
        $datas['count'] = $query->rowCount($sql);
        $datas['datas'] = array();

        while ($data = $query->fetch($pdo)) {
          $datas['datas'][] = $data;
        }

        return $datas;
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }
    
    public static function getPaginate($db, $sql, $parPage = 0, $pdo = PDO::FETCH_OBJ) {

      if ($parPage != 0) {
        $total = $db->query($sql);

        $max = $total->rowCount($sql);

        $nbPages = ceil($max / $parPage);

        // Détermination de la page courante
        if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {

          $currentPage = $_GET['page'];
        } else {
          $currentPage = 1;
        }

        // Détermination du début de chaque page ainsi que celle de la fin
        if ($currentPage == 1) {
          $debut = $currentPage;
        } else {
          $debut = ($parPage * ($currentPage - 1) + 1);
        }

        if ($currentPage == $nbPages) {
          $fin = $max;
        } else {
          $fin = ($debut + $parPage - 1);
        }

        //Numéro page
        if (!isset($_GET['page']))
          $page = 1;
        else
          $page = $_GET['page'];

        $sql .= " LIMIT " . (($currentPage - 1) * $parPage) . "," . $parPage;
      }

      try {
        $query = $db->query($sql);
        
        $datas = array();
        $datas['sql'] = $sql;
        $datas['max'] = $max;
        $datas['nbPages'] = $nbPages;
        $datas['currentPage'] = $currentPage;
        $datas['count'] = $query->rowCount($sql);
        $datas['datas'] = array();

        while ($data = $query->fetch($pdo)) {
          $datas['datas'][] = $data;
        }

        return $datas;
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }

    public function delete($infos) {
      $sql = "DELETE FROM ".$this->_table." WHERE `".$this->_id."` = '$infos'";

      try {
        $query = $this->_db->query($sql);
        return true;
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }

    public function deleteByCriteria($datas) {
      $sql = "DELETE FROM ".$this->_table;

                if (count($datas) > 0) {
                    $sql .= " WHERE ";
                    foreach ($datas as $k => $v) {
                        $sql .= "`" . $k . "` = '" . $v . "' AND ";
                    }

                    $sql = substr($sql, 0, -5);
                }

      try {
        $query = $this->_db->query($sql);

        return true;
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }

    public function get_db(){
      return $this->_db;
    }

    public function get_table(){
      return $this->_table;
    }

    public function get_id(){
      return $this->_id;
    }

    public function get_img(){
      return $this->_img;
    }

    public function get_title(){
      return $this->_title;
    }

    public function set_db($db){
      $this->_db = $db;
    }

    public function set_table($table){
      $this->_table = $table;
    }

    public function set_id($id){
      $this->_id = $id;
    }

    public function set_img($img){
      $this->_img = $img;
    }

    public function set_title($title){
      $this->_title = $title;
    }
  }
?> 