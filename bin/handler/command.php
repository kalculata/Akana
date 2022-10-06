<?php
namespace Akana\Handler;


const commands_desc = [
  'help' => 'Display help menu',
  'runserver' => 'Start server'
];


class Command {
  private $_name;

  public function __construct($args) {
    $this->_name = $args[1];
  } 
  
  public function run() {
    if     ($this->_name == "help"     ) { $this->help(); }   
    else if($this->_name == "runserver") { require_once __DIR__.'/commands/runserver.php'; }
    else if($this->_name == "export_db") { require_once __DIR__.'/commands/export_db.php'; }
    else if($this->_name == "migrate"  ) { require_once __DIR__.'/commands/migrate.php'; }
    else                                 { echo "command $command not found"; }
  }

  public function help() {
    foreach(commands_desc as $command => $desc) {
      echo "$command : $desc\n";
    }
  }
}