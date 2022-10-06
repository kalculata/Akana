<?php
namespace Akana\Handler;


class Command {
  private $_name;

  public function __construct($args) {
    $this->_name = $args[1];
  } 
  
  public function run() {
    switch($this->_name) {
      case "help":
        require_once __DIR__.'/commands/help.php';
        help();
        break;

      case "runserver":
        require_once __DIR__.'/commands/runserver.php';
        runserver($args);
        break;

      case "migrate":
        require_once __DIR__.'/commands/migrate.php';
        setup($args);
        break;
      
      case "export_db":
        require_once __DIR__.'/commands/export_db.php';
        break;

      case "add_resource":
        require_once __DIR__.'/commmands/add_resource.php';
        addResource($args);
        break;

      default:
        echo "command $command not found";
        break;
    }
  }
}