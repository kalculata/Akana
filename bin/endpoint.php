<?php
namespace Akana;
class Endpoint {
  private string $_controller;
  private array $_args;
  private bool $_is_exist = true;

  public function __construct($resource, $endpoint) {
    $settings = utils->getSettings();

    if($settings['global_routers'] == false) {
      $endpoints = spyc_load_file(__DIR__."/../app/$resource/routers.yaml");
    }
    else {
      $resource_endpoints = self::getResourceEndpoints($resource);
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
        $this->_controller = "App\\$resource\\$v";
        $this->_args = $args;
        return;
      }
    }

    $this->_is_exist = false;
  }

  public function getController() { return $this->_controller; }

  public function getArgs() { return $this->_args; }

  public function isExist() { return $this->_is_exist; }

  private static function getResourceEndpoints(string $resource) {
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