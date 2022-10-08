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

    static private function json_valid($data=NULL): bool{
      if(!empty($data)) {
          json_decode($data);
          return (json_last_error() === JSON_ERROR_NONE);
      }
      return true;
    }

    static public function get_request_body(): array{
        $json_body = file_get_contents('php://input');

        if(self::json_valid($json_body) == false) {
          throw new UnexpectedValueException("body request contain errors");
        }
    
        $body = json_decode($json_body, true);

        if(empty($body) && !empty($_POST)) {
          $body = $_POST;
        }

        $body = ($body != null)? $body : [];
        $query_params = $_GET;

        return [
          "body" => $body,
          "query_params" => $query_params
        ];
    }

    static public function is_authorized($http_verb, $class) {
      $authorized_verbs = get_class_methods($class);
      return in_array($http_verb, $authorized_verbs);
    }
  }