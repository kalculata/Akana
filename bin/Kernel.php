<?php 
require_once __DIR__.'/database/connectivity.php';
require_once __DIR__.'/handler/command.php';
require_once __DIR__.'/Utils.php';
require_once __DIR__.'/spyc.php';

// require_once __DIR__.'/Request.php';
// require_once __DIR__.'/Response.php';
// require_once __DIR__.'/Router.php';
// require_once __DIR__."/ORM/ORM.php";
// require_once __DIR__.'/ORM/Table.php';
// require_once __DIR__.'/ORM/Column.php';
// require_once __DIR__.'/ORM/Migration.php';


use Akana\Handler\Command;
use Akana\Utils;

// use Akana\Request;
// use Akana\Response;


define('utils', new Utils());


class Kernel {
  static public function request() {

  }

  static public function command(Command $command) {
    $command->run();
  }
}

// class Kernel {
//   private $_settings;
//   private $_resources;
//   private $_uri;
//   private $_http_verb;
//   private $_request;

//   public function __construct($request, $http_verb, $uri) {
//     $this->_settings = spyc_load_file(__DIR__.'/../config/settings.yaml');
//     $this->_resources = spyc_load_file(__DIR__.'/../config/resources.yaml');
//     $this->_uri = $uri;
//     $this->_http_verb = $http_verb;
//     $this->_request = $request;
//   }

//   public function start() {
//     echo $this->prepare();
//   }

//   private function prepare() {
//     $resource = Request::extract_resource($this->_uri);
//     $endpoint = Request::extract_endpoint($this->_uri);

//     if(!in_array($resource, $this->_resources)) {
//       return new Response(["message" => "Resource ".$this->_uri." not found."], 404);
//     }

//     $tmp = Request::endpoint_detail($resource, $endpoint);

//     if(count($tmp) == 0){
//       return new Response(["message" => "Endpoint '$endpoint' not found on resource '$resource'"], 404);
//     } else {
//       include __DIR__."/../app/$resource/controller.php";

//       $controller = $tmp[0];
//       $args = array_merge($tmp[1], array($this->_request));
      
//       if(!Request::is_authorized($this->_http_verb, $controller)) {
//         return new Response(["message" => "method '".$this->_http_verb."' is not authorized."], 400);
//       }
//       $this->request_handler($controller, $this->_http_verb, $args);
//     }
//   }

//   private function request_handler($class, $func, $args) {
//     try{
//       $controller_instance = new $class();
//       echo call_user_func_array(array($controller_instance, $func), $args);
//     } catch(InvalidArgumentException $e) {
//       echo new Response(["message" => $e->getMessage()], 500);
//     } catch(PDOException $e) {
//       echo new Response(["message" => $e->getMessage()], 500);
//     } catch(Exception $e) {
//       echo new Response(get_object_vars(json_decode($e->getMessage())), 400);
//     }
//   }
// }
