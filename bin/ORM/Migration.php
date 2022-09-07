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
  }