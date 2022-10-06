<?php
require __DIR__.'/Utils.php';


use Akana\ORM\ORM;
use Akana\Shell\Utils;


echo "\nStarting the server\n";

// default parameters
$host = "127.0.0.1:5000";
$checkdbcon = true;


// if(key_exists("host", $args)) 
//   $host = $args["host"];

// if(key_exists("checkdbcon", $args))
//   $checkdbcon = ($args["checkdbcon"] == "true")? true : false;

// if($checkdbcon) {
//   try {
//     Utils::check_db_connectivity();
//   } catch(PDOException $e) {
//     echo "[ERROR] Failed to start the server due to: ". $e->getMessage().".\n";
//     return;
//   }
// }

// $command = "php -S $host -t public/";

// system($command);