<?php
  namespace Akana;

  use UnexpectedValueException;
  use Akana\Router;

  class Request { 

    
    static public function get_args(string $endpoint, string $endpoint_rx) {
      $pattern = "/\[\]/";

      echo preg_match_all($pattern, $endpoint_rx, $data);
      var_dump($data);
      
      return [];
    }

    // return: controller - args
    static function endpoint_detail($resource, $endpoint) {
      
    }

    


    static public function is_authorized($http_verb, $class) {
      $authorized_verbs = get_class_methods($class);
      return in_array($http_verb, $authorized_verbs);
    }
  }