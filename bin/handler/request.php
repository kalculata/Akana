<?php
namespace Akana\Handler;


require_once __DIR__.'/../response.php';
require_once __DIR__.'/../endpoint.php';
require_once __DIR__.'/../status.php';
require_once __DIR__.'/../request.php';


use Akana\RequestBody;
use Akana\Response;
use Akana\Request;
use Akana\Endpoint;


class RequestHandler {
  private string $_http_verb;
  private string $_resource;
  private string $_endpoint;
  private string $_uri;
  private string $_controller;
  private array $_args;
  private Request $request;

  public function __construct() {	
    $this->_uri = $this->getUri();
    $this->_resource = $this->extractResourceFromUri();
    $this->_endpoint = $this->extractEndpointFromUri();
    $this->_http_verb = strtolower($_SERVER['REQUEST_METHOD']);

    // check if resource exist
    if(!in_array($this->_resource, utils->getResources())) {
      echo (utils->dev_mod == 'debug')?
        new Response(['message' => 'resource '.$this->_resource.' not found'], HTTP_404_NOT_FOUND):
        new Response(['message' => $this->_uri.' not found.'], HTTP_404_NOT_FOUND);
      return false;
    }

    // get endpoint info and check if it exists
    $endpoint_info = new Endpoint($this->_resource, $this->_endpoint);
    if(!$endpoint_info->isExist()) {
      echo (utils->dev_mod == 'debug')?
        new Response(['message' => 'endpoint '.$this->_endpoint.' not found on resource '.$this->_resource], HTTP_404_NOT_FOUND):
        new Response(['message' => $this->_uri.' not found'], HTTP_404_NOT_FOUND);
      return;
    }

    $this->_controller = $endpoint_info->getController();
    $this->_args = $endpoint_info->getArgs();
    $controller_file = __DIR__.'/../../app/'.$this->_resource.'/controller.php';

    // check if controller file exists
    if(!file_exists($controller_file)) {
      echo (utils->dev_mod == 'debug')?
        new Response(['message' => "file $controller_file not found"], HTTP_501_NOT_IMPLEMENTED):
        new Response(['message' => 'internal server error'], HTTP_500_INTERNAL_SERVER_ERROR);
      return;
    }

    require_once $controller_file;

    $this->_args = array_merge($this->_args, array($this->_request));

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

  private function validate() {
    
    return true;
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


//   private function prepare() {    

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
