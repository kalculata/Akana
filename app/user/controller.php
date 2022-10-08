<?php
  namespace App\user;

  use Akana\Request;
  use Akana\Response;
  
  class Main {
    public function get(int $id, Request $request) {
      return new Response([555]);
    }  
  }