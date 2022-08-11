<?php
  require_once __DIR__.'/Request.php';
  require_once __DIR__.'/Response.php';
  require_once __DIR__.'/Utils.php';
  require_once __DIR__.'/ORM/Model.php';
  require_once __DIR__.'/Router.php';
  require_once __DIR__.'/spyc.php';

  use Akana\Request;
  use Akana\Response;
  use Akana\Utils;


  class Kernel {
    private $_settings;
    private $_uri;
    private $_http_verb;
    private $_request;

    public function __construct($request, $http_verb, $uri) {
      $this->_settings = spyc_load_file(__DIR__.'/../settings.yaml');
      $this->_uri = $uri;
      $this->_http_verb = $http_verb;
      $this->_request = $request;
    }

    public function start() {
      echo $this->prepare();
    }

    private function prepare() {
      $resource = Request::get_resource($this->_uri);
      $endpoint = Request::get_endpoint($this->_uri);

      if(!in_array($resource, $this->_settings['resources'])) {
        return new Response(["message" => "Resource ".$this->_uri." not found."], 404);
      }

      $tmp = Request::endpoint_detail($resource, $endpoint);

      if(count($tmp) == 0){
        return new Response(["message" => "Endpoint '$endpoint' not found on resource '$resource'"], 404);
      } else {
        include __DIR__."/../src/$resource/controller.php";

        $controller = $tmp[0];
        $args = array_merge(array($this->_request), $tmp[1]);
        
        if(!Request::is_authorized($this->_http_verb, $controller)) {
          return new Response(["message" => "method '".$this->_http_verb."' is not authorized."], 400);
        }
        $this->handler($controller, $this->_http_verb, $args);
      }
    }

    private function handler($class, $func, $args) {
      try{
        echo call_user_func_array(array($class, $func), $args);
      } catch(UnexpectedValueException $e) {
        echo new Response(["message" => $e->getMessage()], 400); 
      } catch(InvalidArgumentException $e) {
        echo new Response(["message" => $e->getMessage()], 500);
      } catch(PDOException $e) {
        echo new Response(["message" => $e->getMessage()], 500);
      }
    }
  }

  