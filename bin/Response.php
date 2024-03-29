<?php
namespace Akana;


class Response {
  private $_data;
  private $_code;

  public function __construct(array $data, int $code=200){
    $this->_data = $data;
    $this->_code = $code;
  }

  public function __toString() : string{
    http_response_code($this->_code);

    header('Content-Type: application/json');

    return json_encode($this->_data);
  }
}