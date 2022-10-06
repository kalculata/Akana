<?php
const commands_desc = [
  'help' => 'Display help menu',
  'runserver' => 'Start server'
];

foreach(commands_desc as $command => $desc) {
  echo "$command : $desc\n";
}