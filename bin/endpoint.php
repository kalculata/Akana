<?php
class Endpoint {
  public function __construct($resource, $endpoint) {
    $settings = utils->getSettings();

    if($settings["global_routers"] == false) {
      $endpoints = spyc_load_file(__DIR__."/../app/$resource/routers.yaml");
    }
    else {
      $resource_endpoints = self::get_endpoints($resource);
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

  private static function getResourceEndpoint(string $resource) {
    $all_endpoints = spyc_load_file(__DIR__."/../config/routers.yaml");
    $endpoints = [];

    if(!array_key_exists($resource, $all_endpoints)) { return []; }
    $resource_endpoints = $all_endpoints[$resource];
    if(empty($resource_endpoints)) { return []; }

    foreach($resource_endpoints as $endpoint => $controller) {
      $endpoint = $endpoint;
      $tmp = [$endpoint => $controller];
      $endpoints = array_merge($endpoints, $tmp);
    }
    return $endpoints;
  }
}