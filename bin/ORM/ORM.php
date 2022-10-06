<?php
  namespace Akana\ORM;

  use PDO;
  use PDOException;
  use ReflectionClass;
  use ReflectionProperty;

  class ORM {
    const DESC = "DESC";
    const ASC = "ASC";

    protected $_columns;

    public function getTableColumns() {
      return $this->_columns;
    }

    public function get_public_vars() {
      $names = [];
      $reflection = new ReflectionClass($this);
      $vars = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

      foreach ($vars as $public_var) {
        array_push($names, $public_var->getName());
      }  
      return $names;
    }

    protected static function typing($val) {
      $type = is_numeric($val)? "NaS" : "string";

      if($type == "string") {
        $val = "'$val'";
      }

      return $val;
    }

    protected static function get_cols($array) {
      $cols = "";
      $array_length = array_key_exists("id", $array)? count($array) - 1 : count($array);

      $counter = 0;
      foreach($array as $k=>$v) {
        if($k == "id") { continue; }
        $cols .= ($counter != $array_length-1)? "$k," : "$k";
        $counter++;
      }
      return $cols;
    }

    protected static function get_vals($array) {
      $vals = "";
      $array_length = array_key_exists("id", $array)? count($array) - 1 : count($array);

      $counter = 0;
      foreach($array as $k=>$v) {
        if($k == "id") { continue; }

        $v = ORM::typing($v);
        $vals .= ($counter != $array_length-1)? $v."," : $v;
        $counter++;
      }
      return $vals;
    }


    public static function query($query) {
      $results = [];
      $dbcon = self::get_dbcon();

      try{
      $stmt = $dbcon->query($query);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);

      return $stmt->fetchAll();
      }
      catch(PDOException $e) {
        echo $e->getMessage()." ".$query;
      }
    }

    public static function exec($query) {
      $results = [];
      $dbcon = self::get_dbcon();
      $dbcon->exec($query);
    }
  }