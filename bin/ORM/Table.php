<?php
  namespace Akana\ORM;

  use PDO;
  use PDOException;
  use Exception;
  use Akana\ORM\ORM;
  use Akana\Utils;


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
      $id = $this->_dbcon->lastInsertId();

      return self::get($id);
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
      return and_filter($array, $order_by);
    }

    public function and_filter($array, $order_by=Table::DESC) {
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

      $stmt = $this->_dbcon->query($query);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);

      return $stmt->fetchAll();
    }

    public function or_filter($array, $order_by=Table::DESC) {
      $query = "SELECT * FROM $this->_table_name";

      $counter = 0;
      foreach($array as $k=>$v){
        $v = Table::typing($v);

        if($counter == 0) {
          $query .= " WHERE $k = $v"; 
        } else {
          $query .= " OR $k = $v";
        }
        $counter++;
      }
      $query .= " ORDER BY id $order_by";

      $stmt = $this->_dbcon->query($query);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);

      return $stmt->fetchAll();
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

    public function validate(array $data) {
      $resource = null;
      $model = null;
      $required_fields = [];
      $model_class = null;
      $clean_data = [];
      $errors = [];

      $tmp = explode("__", $this->_table_name);
      $resource = $tmp[0];
      $model = $tmp[1];

      $model_file = __DIR__."/../../app/$resource/tables.php";
      $resource_models = Utils::get_classes_in_file($model_file);
      $pattern = "#^App\\\\$resource\\\\$model$#i";

      foreach($resource_models as $mod) {
        if(preg_match($pattern, $mod)){
          $model_class = $mod;
          break;
        }
      }
      // todo: if $model_class is empty throw exception;
      $model_obj = new $model_class();
      $model_cols = $model_obj->getTableColumns();

      
      # Remove uncessary fields
      $model_cols_name = Column::getColsName($model_cols);
      foreach($data as $k => $v) {
        if(in_array($k, $model_cols_name)) {
          $clean_data = array_merge($clean_data, [$k => $v]);
        }
      }

      # Check if required fields are providen
      $errors = ["errors" => []];
      $required_fields = Column::getRequiredCols($model_cols);
      foreach($required_fields as $field) {
        if(!array_key_exists($field, $clean_data)) {
          array_push($errors["errors"], "Field '$field' is required");
        }
      }

      # Check if typing is respected
      foreach($clean_data as $k => $v) {
        $res = Column::check_type($k, $v, $model_cols);
        if($res) {
          array_push($errors["errors"], $res);
        }
      }

      if(!empty($errors["errors"])) {
        throw new Exception(json_encode($errors));
      }

      return $clean_data;
    }
  }