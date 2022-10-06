<?php 
require_once __DIR__.'/database/connectivity.php';
require_once __DIR__.'/handler/command.php';
require_once __DIR__.'/handler/request.php';
require_once __DIR__.'/request_body.php';
require_once __DIR__.'/Utils.php';
require_once __DIR__.'/spyc.php';


use Akana\Handler\RequestHandler;
use Akana\Handler\Command;
use Akana\RequestBody;
use Akana\Utils;


define('utils', new Utils());


class Kernel {
  static public function request() {
    new RequestHandler();
  }

  static public function command(Command $command) {
    $command->run();
  }
}