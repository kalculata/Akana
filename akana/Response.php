<?php
  namespace Akana;

  use InvalidArgumentException;

  class Response {
    private $_data;
    private $_code;

    public function __construct($data, int $code=200){
        if(!is_array($data)) {
          throw new InvalidArgumentException("Response argument must be of type array");
        }

        $this->_data = $data;
        $this->_code = $code;
    }

    public function __toString() : string{
        $this->prepare_response($this->_code);
        return json_encode($this->_data);
    }

    private function prepare_response(int $status = 200){
        http_response_code($status);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: http://127.0.0.1:3000');
        header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
        header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
        header ("Access-Control-Allow-Headers: Content-Type, Authorization, Accept, Accept-Language, X-Authorization");
        header('Access-Control-Max-Age: 86400');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            return;
        }
    }
  }