<?php
  namespace Akana\ORM;

  require_once __DIR__."/ORM.php";

  use PDOException;
  use PDO;
  use Akana\ORM\ORM;


  class Model extends ORM{
    private $_table_name;
    private $_dbcon;
    private $_db_env;

    public function __construct($table_name) {
      $this->_db_env = spyc_load_file(__DIR__."/../../env.yaml")["database"];
      $this->_table_name = $table_name;
      $this->_dbcon = $this->get_dbcon();

      //TODO: check if table exist
    }

    private function get_dbcon(){
      $db_url = $this->_db_env['type'].':host='.$this->_db_env['host'].''.$this->_db_env['port'].'; dbname='.$this->_db_env['name'];
      
      try{
        return new PDO($db_url, $this->_db_env['login'], $this->_db_env['password'], array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
      }
      catch(PDOException $e){
        throw new PDOException($e->getMessage());
      }
    }

    public function insert($array) {
      $cols = self::get_cols($array);
      $vals = self::get_vals($array);
      
      $query = "INSERT INTO $this->_table_name($cols) VALUES($vals)";
      $this->_dbcon->exec($query);
    }

    public function all($order_by=Model::DESC) {
      $query = "SELECT * FROM $this->_table_name";

      $query .= " ORDER BY id $order_by";
      $q = $this->_dbcon->query($query);

      $results = [];
      while($data = $q->fetch()){
        array_push($results, $data);
      }               
      $q->closeCursor();
      return $results;
    }

    public function get($id) {
      $query = "SELECT * FROM $this->_table_name WHERE id=$id";
      $q = $this->_dbcon->query($query);

      while($data = $q->fetch()){
        return $data;
      }       
      $q->closeCursor();
      return $data;
    }

    public function filter($array, $order_by=Model::DESC) {
      $query = "SELECT * FROM $this->_table_name";

      $counter = 0;
      foreach($array as $k=>$v){
        $v = Model::typing($v);

        if($counter == 0) {
          $query .= " WHERE $k = $v"; 
        } else {
          $query .= " AND $k = $v";
        }
        $counter++;
      }

      $query .= " ORDER BY id $order_by";
      $q = $this->_dbcon->query($query);

      $results = [];
      while($data = $q->fetch()){
        array_push($results, $data);
      }               
      $q->closeCursor();
      return $results;
    }

    public function edit($id, $array) {

    }

    public function delete($id) {
      $query = "DELETE FROM $this->_table_name WHERE id=$id";
      $q = $this->_dbcon->exec($query);
    }
  }