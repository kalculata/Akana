<?php
  require_once __DIR__.'/../ORM/Column.php';
  require_once __DIR__.'/Utils.php';

  use Akana\Utils;
  use Akana\Shell\Utils as ShellUtils;
  use Akana\ORM\ORM;
  // use Akana\ORM\ManageMigration;

  function migrate($args) {
    if(!file_exists(__DIR__.'/../../settings.yaml')) {
      echo "[ERROR] settings.yaml file not found\n";
      return;
    } 

    $settings = spyc_load_file(__DIR__.'/../../settings.yaml');
    $resources = $settings["resources"];

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

    $models = Utils::get_classes_in_file(__DIR__.'/../../App/'.$args["resource"].'/models.php');

    foreach($models as $model) {
      $obj = new $model();
      $vars = $obj->get_public_vars();
      $tmp = explode("\\", $model);
      $model_name = strtolower($tmp[count($tmp) - 1]);
      $resource = $args['resource'];
    
      $table = $resource."__$model_name";

      $resource_tables = ManageMigration::get_tables($resource);
      if(in_array($table, $resource_tables)) {
        echo "Table $model_name of resouce $resource already exist";
        $cols_to_add = array();
        $cols_to_remove = array();
        $cols_to_modify = array();
      //    update

      //   // get tables signature
      //   $resource_tables_hash = ORM::get_tables_hash($resource);
      //   $current_table_hash = $obj->gethash();

      //   if(in_array($current_table_hash, $resource_tables_hash)) {
      //     // rename table
      //   }
      } 

      // Creation of the table and rename
      else {
        $hash = ManageMigration::table_hash($table);
        $hashs = ManageMigration::get_hashes($resource);

        if(in_array($hash, $hashs)) {
          
        } else {
          $query = "CREATE TABLE $table (id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY";
          foreach($vars as $var) { $query .= ", $var ".$obj->$var->get_sql(); }
          $query .= ");";

          $dbcon = ORM::get_dbcon();
          // $dbcon->query($query);

          echo $query ."\n";
          echo "Table '$model_name' created";
          // echo $query;
        }
      }

      // ACTIONS ON MIGRATE
      // - update table
      // - delete table
      // - rename table

    }
  }