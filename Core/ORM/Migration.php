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
        if(preg_match("/^university__(.)+$/", $table)) {
          array_push($resource_tables, $table);
        }
      }

      return $resource_tables;
    }
  }