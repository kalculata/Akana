<?php
  require_once __DIR__.'/../ORM/Column.php';
  require_once __DIR__.'/../ORM/Migration.php';
  require_once __DIR__.'/Utils.php';

  use Akana\Utils;
  use Akana\Shell\Utils as ShellUtils;
  use Akana\ORM\ORM;
  use Akana\ORM\Migration;

  function migrate($args) {
    $resource_config_file = __DIR__.'/../../config/resources.yaml';
    if(!file_exists($resource_config_file)) {
      echo "[ERROR] config/resources.yaml file not found\n";
      return;
    } 

    $resources = spyc_load_file($resource_config_file);

    // check if resource option has been provided
    if(!key_exists("resource", $args)) {
      echo "[ERROR] resource option is required\n";
      return;
    }
    
    // check if the given resource exist
    if(!in_array($args["resource"], $resources)) {
      echo "[ERRO] resource '".$args["resource"]."' not found in settings";
      return;
    }

    // check db connection
    try {
      ShellUtils::check_db_connectivity();
    } catch(PDOException $e) {
      echo "[ERROR] Failed to do migrations due to: ". $e->getMessage().".\n";
      return;
    }

    $tables = Utils::get_classes_in_file(__DIR__.'/../../App/'.$args["resource"].'/tables.php');

    foreach($tables as $table_class) {      
      $tmp = explode("\\", $table_class);
      $table_name = strtolower($tmp[count($tmp) - 1]);
      $resource = $args['resource'];
      $table_name = $resource."__$table_name";
      $resource_tables = Migration::get_tables($resource);

      if(!in_array($table_name, $resource_tables)) {
        Migration::create_table($table_name, $table_class);
      } 

      else {
        echo "Table '$table_name' already exist\n";
      }
    }
  }