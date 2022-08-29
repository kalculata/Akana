<?php
  require_once __DIR__.'/../ORM/Column.php';
  require_once __DIR__.'/Utils.php';

  use Akana\Utils;
  use Akana\Shell\Utils as ShellUtils;

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
      
      // TODO:
      // check database connection
      // check if table exist
      // if table exist
      // - get table columns
      // - check if model columns exist on the table
      // - check diffence between model columns and table columns
      // - generate sql code
      foreach($vars as $var) {
        echo $obj->$var->getType() . " - ";
      }
    }
  }