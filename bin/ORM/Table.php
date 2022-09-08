<?php
  namespace Akana\ORM;

  require_once __DIR__."/ORM.php";

  use PDOException;
  use PDO;
  use Akana\ORM\ORM;


  class Table extends ORM{
    private $_table_name;
    private $_dbcon;

    public function __construct($table_name) {
      $this->_table_name = $table_name;
      $this->_dbcon = self::get_dbcon();
    }

    public function insert($array) {
      $cols = self::get_cols($array);
      $vals = self::get_vals($array);
      
      $query = "INSERT INTO $this->_table_name($cols) VALUES($vals)";

      $this->_dbcon->exec($query);
    }

    public function all($order_by=Table::DESC) {
      $query = "SELECT * FROM $this->_table_name";

      $query .= " ORDER BY id $order_by";
      $stmt = $this->_dbcon->query($query);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);

      return $stmt->fetchAll();
    }

    public function get($id) {
      $query = "SELECT * FROM $this->_table_name WHERE id=$id";

      $stmt = $this->_dbcon->query($query);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);

      return $stmt->fetch();
    }

    public function filter($array, $order_by=Table::DESC) {
      $query = "SELECT * FROM $this->_table_name";

      $counter = 0;
      foreach($array as $k=>$v){
        $v = Table::typing($v);

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

    public function update($id, $array) {
      if(empty($array)){ return; }
      $query = "UPDATE $this->_table_name SET";

      $counter = 0;
      foreach($array as $k=>$v){
        if($k == "id") { continue; }

        $v = ORM::typing($v);

        if($counter == 0) {
          $query .= " $k = $v"; 
        } else {
          $query .= ", $k = $v";
        }
        $counter++;
      }

      $query .= " WHERE id=$id";
      $this->_dbcon->exec($query);
      return $this->get($id);
    }

    public function delete($id) {
      $query = "DELETE FROM $this->_table_name WHERE id=$id";
      $this->_dbcon->exec($query);
    }
  }