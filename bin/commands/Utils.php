<?php
namespace Akana\Shell;

use Akana\ORM\ORM;

class Utils {
  static function check_db_connectivity($strict=false) {
    $db_config_file = __DIR__.'/../../config/db.yaml';

    if(!file_exists($db_config_file)) {
      echo "[WARNING] config/database.yaml file not found. (if you don't need a database, it doesn't affect your api)\n";
    } else {
      $vars = spyc_load_file($db_config_file);

      if(!empty($vars)) {
        if(key_exists("type", $vars) && key_exists("host", $vars) && key_exists("port", $vars) && key_exists("name", $vars) && key_exists("login", $vars) && key_exists("password", $vars)) {
            ORM::get_dbcon($vars);
        } else {
          echo "[WARNING] A connection with database may fail because some database variables are missed in env.yaml.\n";
        }
      }
    }
  }
}