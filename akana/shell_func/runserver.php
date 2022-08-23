<?php
  require __DIR__.'/Utils.php';

  use Akana\ORM\ORM;
  use Akana\Shell\Utils;

  function runserver($args) {
    echo "\nStarting the server\n";

    try {
      Utils::check_db_connectivity();
    } catch(PDOException $e) {
      echo "[ERROR] Failed to start the server due to: ". $e->getMessage().".\n";
      return;
    }

    $host = "127.0.0.1:8000";    
    if(key_exists("host", $args)) 
      $host = $args["host"];
    $command = "php -S $host -t public/";

    system($command);
  }