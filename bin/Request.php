<?php
  namespace Akana;

  use UnexpectedValueException;
  use Akana\Router;

  class Request {
    static function get_resource(string $uri): string{
      return explode('/', $uri)[1];
    }
    
    static function get_endpoint(string $uri): string{
      $tmp = explode('/', $uri);
      $resource = $tmp[1];
      $endpoint = '';
            
      foreach($tmp as $value){
        if($value != $resource AND !empty($value)) {
          $endpoint .= '/' . $value;
        }
      }

      return $endpoint . '/';
    }
    
    static public function get_args(string $endpoint, string $endpoint_rx) {
      $pattern = "/\[\]/";

      echo preg_match_all($pattern, $endpoint_rx, $data);
      var_dump($data);
      
      return [];
    }

    // return: controller - args
    static function endpoint_detail($resource, $endpoint) {
      $settings = spyc_load_file(__DIR__."/../config/settings.yaml");

      if($settings["single_router"] == false) {
        $resource_endpoints = spyc_load_file(__DIR__."/../app/$resource/routers.yaml");
      }
      else {
        $resource_endpoints = Router::get_endpoints($resource);
      }

      foreach($resource_endpoints as $k => $v) {
        $args = [];
        $k_cp = $k;

        $pattern = '#^'.$k.'$#';
        if(Router::is_dynamic($k)) {
          $k = Router::to_regex($k);
          $pattern = "#^$k$#";
          $args = Router::get_args($k_cp, $endpoint, $pattern);
        }
      
        if(preg_match($pattern, $endpoint)) {
          return array("App\\$resource\\$v", $args);
        }
      }

      return [];
    }

    static private function json_valid($data=NULL): bool{
      if(!empty($data)) {
          json_decode($data);
          return (json_last_error() === JSON_ERROR_NONE);
      }
      return true;
    }

    static public function get_request_body(): array{
        $json_data = file_get_contents('php://input');

        if(self::json_valid($json_data) == false) {
          throw new UnexpectedValueException("body request contain errors");
        }
    
        $request_data = json_decode($json_data, true);

        if(empty($request_data) && !empty($_POST))
            $request_data = $_POST;  

        return ($request_data != null)? $request_data : [];
    }

    static public function is_authorized($http_verb, $class) {
      $authorized_verbs = get_class_methods($class);
      return in_array($http_verb, $authorized_verbs);
    }
  }