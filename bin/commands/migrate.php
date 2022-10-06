<?php
  require_once __DIR__.'/../ORM/Column.php';
  require_once __DIR__.'/../ORM/Migration.php';
  require_once __DIR__.'/Utils.php';

  use Akana\Utils;
  use Akana\Shell\Utils as ShellUtils;
  use Akana\ORM\ORM;
  use Akana\ORM\Migration;

  function setup($args) {
    $resources = RESOURCES;
    
    // check db connection
    try {
      ShellUtils::check_db_connectivity();
    } catch(PDOException $e) {
      echo "[ERROR] Failed to do migrations due to: ". $e->getMessage().".\n";
      return;
    }
    // check if resource option has been provided
    if(key_exists("resource", $args)) {
      // check if the given resource exist
      if(!in_array($args["resource"], $resources)) {
        echo "[ERRO] resource '".$args["resource"]."' not found in settings";
        return;
      }

      migrate($args['resource']);
    } else {
      foreach($resources as $resource) {
        migrate($resource);
      }
    } 
  }

  function migrate($resource) {
    $changes = false;
    $tables_class = Utils::get_classes_in_file(__DIR__.'/../../App/'.$resource.'/tables.php');
    $local_tables = Migration::class_to_tables($tables_class, $resource);
    $remote_tables = Migration::get_tables($resource);

    foreach($local_tables as $class => $table_name) {      
      if(!in_array($table_name, $remote_tables)) {
        Migration::create_table($table_name, $class);
        $changes = true;
      }
    }

    foreach($remote_tables as $table_name) {
      if(!in_array($table_name, $local_tables)) {
        Migration::delete_table($table_name);
        $changes = true;
      }
    }

    if(!$changes) {
      echo "0 change in resource '$resource'\n";
    }
  }