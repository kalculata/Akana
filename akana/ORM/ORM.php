<?php
  namespace Akana\ORM;


  class ORM {
    const DESC = "DESC";
    const ASC = "ASC";

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
  }