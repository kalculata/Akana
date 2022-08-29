<?php
  namespace Akana\ORM;

  use PDO;
  use ReflectionClass;
  use ReflectionProperty;

  class ORM {
    const DESC = "DESC";
    const ASC = "ASC";

    public function get_public_vars() {
      $names = [];
      $reflection = new ReflectionClass($this);
      $vars = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

      foreach ($vars as $public_var) {
        array_push($names, $public_var->getName());
      }  
      return $names;
    }

    protected static function typing($val) {
      $type = is_numeric($val)? "NaS" : "string";

      if($type == "string") {
        $val = "\"$val\"";
      }

      return $val;
    }

    protected static function get_cols($array) {
      $cols = "";

      $counter = 0;
      foreach($array as $k=>$v) {
        $cols .= ($counter != count($array)-1)? "$k," : "$k";
        $counter++;
      }
      return $cols;
    }

    protected static function get_vals($array) {
      $vals = "";

      $counter = 0;
      foreach($array as $k=>$v) {
        $v = ORM::typing($v);
        $vals .= ($counter != count($array)-1)? $v."," : $v;
        $counter++;
      }
      return $vals;
    }

    public static function get_dbcon($_envs=NULL){
      $env_file_path = __DIR__.'/../../env.yaml';
      $env_file_exist = file_exists($env_file_path);

      if(!$env_file_exist) {
        throw new PDOException("env.yaml file not found.");
      }
      if(!isset($_envs)) {
        $envs = spyc_load_file($env_file_path);
        $envs = $envs["database"];
      }
      else{
        $envs = $_envs;
      }

      $db_url = $envs['type'].':host='.$envs['host'].''.$envs['port'].'; dbname='.$envs['name'];
      
      try{
        return new PDO($db_url, $envs['login'], $envs['password'], array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
      }
      catch(PDOException $e){
        throw new PDOException($e->getMessage());
      }
    }
  }