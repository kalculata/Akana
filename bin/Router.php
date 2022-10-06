<?php
namespace Akana;

use Akana\Utils;

class Router {
  static function is_dynamic(string $endpoint): bool{
    return preg_match('#\([a-zA-Z0-9_]+:int\)|\([a-zA-Z0-9_]+:str\)+#', $endpoint);
  }

  static function to_regex(string $dynamic_endpoint): string{
    $regex = $dynamic_endpoint;
    
    $regex = preg_replace('#\/#', '\/', $regex);
    $regex = preg_replace('#\(([a-zA-Z0-9_]+):int\)#', '(?<$1>[0-9]+)', $regex);
    $regex = preg_replace('#\(([a-zA-Z0-9_]+):str\)#', '(?<$1>[a-zA-Z0-9_-]+)', $regex);

    return $regex;
  }

  static function get_args($ep_vanilla, $ep, $pattern): array{
    $args = [];
    $data_from_ep = [];
    $endpoint_vars = [];

    if(preg_match_all($pattern, $ep, $data_from_ep)) {
      if(preg_match_all("#\([A-Za-z0-9_]+:(int|str)\)#", $ep_vanilla, $endpoint_vars)) {
        $endpoint_vars = $endpoint_vars[0];

        foreach($endpoint_vars as $val) {
          $val = str_replace("(", "", $val);
          $val = str_replace(")", "", $val);
          $val = explode(":", $val);
          $var = $val[0];
          $type =$val[1];
          $val = $data_from_ep[$var][0];

          $val =  ($type == "int")? intval($val): $val;
          array_push($args, $val);
        }
      }
    }
    return $args;
  }

  static function get_endpoints($resource) {
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