<?php

use Akana\ORM\ORM;


echo "\nStarting the server\n";

// default parameters
$host = '127.0.0.1:5000';
$require_dbcon = true;

if(key_exists('host', args)  || key_exists('h', args))
  $host = isset(args["host"])? args['host'] : args['h'];
if(key_exists('force', args) || key_exists("f", args))
   $require_dbcon = false;

if($require_dbcon) {
  try {
    utils->checkDBConnectivity();
  } catch(PDOException $e) {
    echo "[ERROR] Failed to start the server due to: ". $e->getMessage().".\n";
    return;
  }
}

$command = "php -S $host -t public/";
system($command);