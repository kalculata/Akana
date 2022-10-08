<?php
class Endpoint {
  public function __construct($resource, $endpoint) {
    $settings = spyc_load_file(__DIR__."/../config/settings.yaml");

      if($settings["global_routers"] == false) {
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
}