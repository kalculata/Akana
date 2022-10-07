<?php
namespace Akana\Handler;


require_once __DIR__.'/../database/connectivity.php';


use Akana\Database\Connectivity;


class Command {
  private $_name;
  private $_subcommand;
  private $_args;

  public function __construct($args) {
    $this->_args = $this->format_args($args);
    $this->_name = $args[1];
  } 

  private function format_args($args) {
    $formated_args = [];

    for($i=2; $i<count($args); $i++) {
      // is a subcommand
      if($i == 2) {
        if(strpos($args[$i], "-") === false && strpos($args[$i], "--") === false) {
          $this->_subcommand = $args[$i];
          continue;
        }
      } 

      if(strpos($args[$i], "-") !== false || strpos($args[$i], "--") !== false) {
        $tmp = [str_replace(['-', '--'], '', $args[$i]) => isset($args[$i+1])? $args[$i+1] : NULL];
        $formated_args = array_merge($formated_args, $tmp);
      }
    } 

    return $formated_args;
  }
  
  public function run() {
    define('subcommand', $this->_subcommand);
    define('args', $this->_args);

    if($this->_name == 'runserver')      { require_once __DIR__.'/../commands/runserver.php'; }
    else if($this->_name == 'export_db') { require_once __DIR__.'/../commands/export_db.php'; }
    else if($this->_name == 'migrate'  ) { require_once __DIR__.'/../commands/migrate.php'; }
    else if($this->_name == 'help'     ) { require_once __DIR__.'/../commands/help.php';  }   
    else                                 { echo "command $command not found"; }
  }

  public function help() {
    foreach(commands_desc as $command => $desc) {
      echo "$command : $desc\n";
    }
  }
}