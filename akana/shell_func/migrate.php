<?php
  use Akana\Utils;

  function migrate($args) {
    if(!file_exists(__DIR__.'/../../settings.yaml')) {
      echo "[ERROR] settings.yaml file not found\n";
      return;
    } 

    $settings = spyc_load_file(__DIR__.'/../../settings.yaml');
    $resources = $settings["resources"];

    if(!key_exists("resource", $args)) {
      echo "[ERROR] resource option is required\n";
      return;
    }
    
    if(!in_array($args["resource"], $resources)) {
      echo "[ERRO] ".$args["resource"]." not found in settings";
      return;
    }
    
    require_once __DIR__.'/../ORM/Column.php';
    #require_once __DIR__.'/../../src/'.$args["resource"].'/models.php';

    $models = Utils::get_classes_in_file(__DIR__.'/../../src/'.$args["resource"].'/models.php');

    foreach($models as $model) {
      $obj = new $model();
      $vars = $obj->get_public_vars();

      foreach($vars as $var) {
        echo $obj->$var->getType() . " - ";
      }
    }
  }