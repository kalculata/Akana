<?php
  use Akana\ORM\ORM;

  function runserver($args) {
    echo "\nStarting the server\n";

    if(!file_exists(__DIR__.'/../../env.yaml')) {
      echo "[WARNING] env.yaml file for environment variables doesn't exist. (if you don't need a database it doesn't affect your api\n";
    } else {
      $envs = spyc_load_file(__DIR__.'/../../env.yaml');

      if(isset($envs["database"])) {
        if(key_exists("type", $envs["database"]) && key_exists("host", $envs["database"]) && key_exists("port", $envs["database"]) && 
        key_exists("name", $envs["database"]) && key_exists("login", $envs["database"]) && key_exists("password", $envs["database"])) {
          try {
            ORM::get_dbcon($envs["database"]);
          } catch(PDOException $e) {
            echo "[ERROR] Failed to start the server due to: ". $e->getMessage().".\n";
          }
        } else {
          echo "[WARNING] A connection with database may fail because some database variables are missed in env.yaml.\n";
        }
      }
    }

    $host = "127.0.0.1:8000";    
    if(key_exists("host", $args)) 
      $host = $args["host"];
    $command = "php -S $host -t public/";

    system($command);
  }