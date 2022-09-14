<?php
  namespace Akana\ORM;

  use Akana\ORM\ORM;

  class Migration {
    static public function get_tables($resource) {
      $all_tables = array();
      $resource_tables = array();
      $dbcon = ORM::get_dbcon();
      $q = $dbcon->query("show tables");

      while($data = $q->fetch()) {
        array_push($all_tables, $data[0]);
      }

      foreach($all_tables as $table) {
        $pattern = "/^".$resource."__(.)+$/";
        if(preg_match($pattern, $table)) {
          array_push($resource_tables, $table);
        }
      }

      return $resource_tables;
    }

    static public function get_object_vars(object &$obj) {
      $tmp = get_object_vars($obj);
      $vars = [];

      foreach($tmp as $k => $v) {
        array_push($vars, $k);
      }

      return $vars;
    }

    static public function get_table_desc($table) {
      $cols = [];

      $query = "DESC $table;";
      $dbcon = ORM::get_dbcon();
      $res = $dbcon->query("DESC $table;");

      while($data = $res->fetch()) {
        array_push($cols, $data["Field"]);
      }

      return $cols;
    }

    static public function create_table($table_name, $table_class) {
      $table_obj = new $table_class();

      $query = "CREATE TABLE $table_name (`id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY";
      foreach($table_obj->getTableColumns() as $col) { $query .= ", ".$col->get_sql(); }
      $query .= ");";

      $dbcon = ORM::get_dbcon();
      $dbcon->query($query);

      echo "Table '$table_name' created\n";
    }

    static public function delete_table($table_name) {
      $query = "DROP TABLE $table_name";
      $dbcon = ORM::get_dbcon();
      $dbcon->exec($query);

      echo "Table '$table_name' deleted\n";
    }

    static public function class_to_tables(array $classes, $resource) {
      $tables = [];

      foreach($classes as $class) {
        $tables = array_merge($tables, [$class => self::class_to_table($class)]);
      }

      return $tables;
    }

    static public function class_to_table($class) {
      // TODO: throw exception if tables file doesn't have namespace file
      
      $tmp = explode("\\", $class);
      $resource = strtolower($tmp[1]);
      $table_name = $resource."__".strtolower($tmp[count($tmp) - 1]);

      return $table_name;
    }
  }