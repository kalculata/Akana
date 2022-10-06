<?php
namespace Akana\Handler;


const commands_desc = [
  'help' => 'Display help menu'
];


class Command {
  private $_name;

  public function __construct($args) {
    $this->_name = $args[1];
  } 
  
  public function run() {
    if($this->_name == "help") { $this->help(); }

    // switch($this->_name) {
 
    //   case "runserver":
    //     require_once __DIR__.'/commands/runserver.php';
    //     runserver($args);
    //     break;

    //   case "migrate":
    //     require_once __DIR__.'/commands/migrate.php';
    //     setup($args);
    //     break;
      
    //   case "export_db":
    //     require_once __DIR__.'/commands/export_db.php';
    //     break;

    //   case "add_resource":
    //     require_once __DIR__.'/commmands/add_resource.php';
    //     addResource($args);
    //     break;

    //   default:
    //     echo "command $command not found";
    //     break;
    // }
  }

  public function help() {
    foreach(commands_desc as $command => $desc) {
      echo "$command      $desc";
    }
  }
}