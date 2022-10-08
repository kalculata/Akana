<?php
namespace Akana;

use Akana\Response;

class Request {
  public array $data = [];
  public array $query_params;
  public $error = NULL;

  public function __construct() {
    $this->data = $this->getData();
    $this->query_params = $_GET;
  }

  private function getData() {
    if(!bridge) {
      $json = file_get_contents('php://input');

      if(self::json_valid($json) == false) {
        $this->error = new response(['message' => 'body request contain errors'], HTTP_400_BAD_REQUEST);
        return [];
      }

      $body = json_decode($json, true);
      $body = ($body != null)? $body : [];

      return $body;
    }
    else {
      return $_POST;
    }
  }

  private function json_valid($data): bool{
    if(!empty($data)) {
      json_decode($data);
      return (json_last_error() === JSON_ERROR_NONE);
    }
    return true;
  }
}