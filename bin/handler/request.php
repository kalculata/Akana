<?php
namespace Akana\Handler;


use Akana\RequestBody;


class RequestHandler {
  private $_http_verb;
  private $_resource;
  private $_endpoint;
  private $_uri;

  public function __construct() {	
	// $request = Request::get_request_body();
	// $app = new Kernel($request, $http_verb, $uri);
	// $app->start();

    $this->_uri = $this->getUri();
    $this->_resource = $this->extractResourceFromUri();
    $this->_endpoint = $this->extractEndpointFromUri();
    $this->_http_verb = strtolower($_SERVER['REQUEST_METHOD']);

    echo $this->_uri . ' : uri ';
    echo $this->_resource . ' : resource ';
    echo $this->_endpoint . ' : endpoint';
  }

  private function getUri() {
    if(isset($_GET["uri"]) && !empty($_GET["uri"])) {
      $uri = explode('?', $_SERVER['REQUEST_URI'])[0];

      if($uri == '/public/') ;
        return $_GET["uri"];
    } 
    
    return $_SERVER['REQUEST_URI'];
  }

  private function extractResourceFromUri(): string {
    return explode('/', $this->_uri)[1];
  }

  private function extractEndpointFromUri(): string {
    $uri = explode('?', $this->_uri)[0];
    $tmp = explode('/', $this->_uri);

    $endpoint = '';
    for($i=2; $i<count($tmp); $i++) {
      $endpoint .= '/' . $tmp[$i];
    }

    if(substr($this->_uri, strlen($this->_uri)-1) == '/') {
      return $endpoint;
    }
    else {
      return $endpoint . '/';
    }

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
