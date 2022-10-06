<?php
namespace Akana\Database;


use PDO;


class Connectivity {
  public static function get() {
    $credintial = utils->getDBCredintial();

    $port       = isset($credintial['port'])? ':'.$credintial['port'] : '';
    $password   = $credintial['password'];
    $login      = $credintial['login'];
    $type       = $credintial['type'];
    $host       = $credintial['host'];
    $name       = $credintial['name'];

    $db_url     = "$type:host=$host".""."$port; dbname=$name";
    
    echo $db_url;

    try{
      return new PDO($db_url, $login, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(PDOException $e){
      throw new PDOException($e->getMessage());
    }
  }
}