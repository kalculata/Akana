<?php
namespace Akana\Handler;

require_once __DIR__.'/../response.php';
require_once __DIR__.'/../endpoint.php';
require_once __DIR__.'/../request.php';
require_once __DIR__.'/../status.php';

use Akana\Response;
use Akana\Endpoint;
use Akana\Request;


class RequestHandler {
  private string $_controller_class;
  private string $_http_verb;
  private Request $_request;
  private string $_resource;
  private string $_endpoint;
  private string $_uri;
  private array $_args;

  public function __construct() {	
    $this->_uri = $this->getUri();
    $this->_request = new Request();
    $this->_resource = $this->extractResourceFromUri();
    $this->_endpoint = $this->extractEndpointFromUri();
    $this->_http_verb = strtolower($_SERVER['REQUEST_METHOD']);

    $this->validate();
  }

  public function run() {
    $controller_obj = new $this->_controller_class();
    echo call_user_func_array(array($controller_obj, $this->_http_verb), $this->_args);
  }

  private function validate() {
    // check if json body is valid
    if($this->_request->error != NULL) {
      echo $this->_request->error;
      return;
    }

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

    $this->_controller_class = $endpoint_info->getController();
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

    // check if controller class exist
    if(!class_exists($this->_controller_class)) {
      echo (utils->dev_mod == 'debug')?
        new Response(['message' => 'controller '.$this->_controller_class.' not found'], HTTP_501_NOT_IMPLEMENTED):
        new Response(['message' => 'internal server error'], HTTP_500_INTERNAL_SERVER_ERROR);
      return;
    }

    // verify if used http verb is authorized
    if(!self::httpIsAuthorized($this->_http_verb, $this->_controller_class)) {
      echo new Response(['message' => 'method '.strtoupper($this->_http_verb).' is not authorized on '.$this->_uri], HTTP_406_NOT_ACCEPTABLE);
      return;
    }

    $this->_args = array_merge($this->_args, array($this->_request));

    // execute request
    $this->run();
  }

  private function getUri() {
    if(isset($_GET["uri"]) && !empty($_GET["uri"])) {
      $uri = explode('?', $_SERVER['REQUEST_URI'])[0];

      if($uri == '/public/') { 
        define('bridge', true);
        return $_GET["uri"]; 
      }
    } 
    
    define('bridge', false);
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

  private function httpIsAuthorized($http_verb, $class) {
    $authorized_verbs = get_class_methods($class);
    return in_array($http_verb, $authorized_verbs);
  }
}